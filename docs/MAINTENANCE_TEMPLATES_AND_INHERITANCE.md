## Maintenance Templates and Inheritance

This document explains how maintenance templates — built from categories, intervals, and tasks — are inherited by operational entities — equipment intervals, work orders, and work order tasks. It covers data relationships, lifecycle flows, observer-driven automation, and how updates propagate across the system.

### High-level Concepts
- **Category**: Groups equipment on a vessel into a logical maintenance domain (e.g., "Engines"). Holds interval templates and is linked to a `Vessel`.
- **Interval (Template)**: Defines a recurring maintenance cadence for a category (e.g., Monthly). Contains a sequenced list of `Task` templates.
- **Task (Template)**: A single maintenance step tied to an interval template. Tasks can be applicable to all equipment, a specific subset, or conditionally based on equipment attributes.
- **EquipmentInterval (Instance)**: Per-equipment instantiation of an interval template. Inherits frequency/facilitator/description for a specific `Equipment` item.
- **WorkOrder (Instance)**: An actionable order tied to an `EquipmentInterval`. Holds due date, status, assignee, completion, and user notes.
- **WorkOrderTask (Instance)**: Concrete tasks created for each `WorkOrder` from the interval’s task templates, filtered by applicability to the specific equipment.

---

### Core Data Relationships

- **Category** (`app/Models/Category.php`)
  - belongsTo `Vessel`
  - hasMany `Interval`
  - hasMany `Equipment`
  - Fillable: `name`, `vessel_id`, `type`, `icon`

- **Interval** (`app/Models/Interval.php`)
  - belongsTo `Category`
  - hasMany `Task`
  - hasMany `EquipmentInterval`
  - Fillable: `description`, `category_id`, `interval`, `facilitator`
  - The `interval` string drives frequency semantics (e.g., Daily, Weekly, Monthly...).

- **Task** (`app/Models/Task.php`)
  - belongsTo `Interval`
  - hasMany `ApplicableEquipment` (explicit allow-list for Specific Equipment mode)
  - belongsToMany `Equipment` via `applicable_equipment` (convenience pivot)
  - Fillable: `interval_id`, `description`, `instructions`, `applicable_to`, `display_order`, `applicability_conditions`
  - `applicable_to`: one of "All Equipment", "Specific Equipment", "Conditional".
  - `applicability_conditions`: JSON array of { key, value } matches (for Conditional mode).

- **ApplicableEquipment** (`app/Models/ApplicableEquipment.php`)
  - belongsTo `Task`
  - belongsTo `Equipment`
  - Fillable: `task_id`, `equipment_id`

- **Equipment** (`app/Models/Equipment.php`)
  - belongsTo `Vessel`, `Category`, optional `Deck`, `Location`
  - hasMany `EquipmentInterval`
  - hasManyThrough `WorkOrder` via `EquipmentInterval`
  - hasMany `Deficiency`
  - Fillable includes identity, vendor, dates, status, and `attributes_json` (dynamic attributes)
  - Casts: several dates and `attributes_json` as array

- **EquipmentInterval** (`app/Models/EquipmentInterval.php`)
  - belongsTo `Equipment`
  - belongsTo `Interval` (source template)
  - hasMany `WorkOrder`
  - Fillable: `equipment_id`, `interval_id`, `description`, `facilitator`, `frequency`, `frequency_interval`, `first_completed_at`, `last_completed_at`, `next_due_date`, `is_active`
  - Casts: dates and `is_active`

- **WorkOrder** (`app/Models/WorkOrder.php`)
  - belongsTo `EquipmentInterval`
  - belongsTo `User` as `completedBy` (via `completed_by`)
  - belongsTo `User` as `assignee` (via `assigned_to`)
  - hasMany `WorkOrderTask`
  - hasMany `Deficiency`; `deficiency()` returns latestOfMany
  - Fillable: `equipment_interval_id`, `due_date`, `status`, `assigned_to`, `completed_at`, `completed_by`, `notes`
  - Casts: `due_date` (date), `completed_at` (datetime)

- **WorkOrderTask** (`app/Models/WorkOrderTask.php`)
  - belongsTo `WorkOrder`
  - belongsTo `User` as `completedBy`
  - Fillable: `work_order_id`, `name`, `instructions`, `sequence_position`, `completed_at`, `completed_by`, `status`, `is_flagged`, `notes`
  - Casts: `is_flagged` (boolean), `completed_at` (datetime)

---

### Lifecycle and Inheritance Flows

#### 1) Seeding EquipmentIntervals from Interval Templates
- Observer: `IntervalObserver@created` (`app/Observers/IntervalObserver.php`)
  - On new `Interval` creation, invokes `IntervalInheritanceService::handleNewInterval($interval)`.
- Service: `IntervalInheritanceService` (`app/Services/IntervalInheritanceService.php`)
  - `handle(Equipment $equipment)`: Used when new equipment is created (called elsewhere) to instantiate all relevant intervals for the equipment’s category.
  - `handleNewInterval(Interval $interval)`: For each `Equipment` matching the interval’s `category_id`, creates an `EquipmentInterval` via `createEquipmentInterval`.
  - `createEquipmentInterval(Equipment $equipment, Interval $interval)`:
    - Creates `EquipmentInterval` inheriting description, facilitator, and frequency from the template.
    - Maps the textual `interval` (e.g., "Monthly") into a `frequency_interval` string (e.g., "1 month") using `mapToFrequencyString` for scheduling math.
    - Immediately creates the initial `WorkOrder` by calling `WorkOrderGenerationService::createInitialWorkOrder($ei)`.

Result: Every time an interval template is added or when equipment is created, per-equipment `EquipmentInterval` instances are created, and each gets an initial `WorkOrder` scaffolded.

#### 2) Initial WorkOrder and Task Instantiation
- Service: `WorkOrderGenerationService::createInitialWorkOrder(EquipmentInterval $interval)`
  - Creates a `WorkOrder` with `due_date = null`, `status = 'Open'` (case varies), and no notes.
  - Populates `WorkOrderTask` items by calling `createWorkOrderTasks($workOrder)`.
- Task Instantiation Logic: `createWorkOrderTasks(WorkOrder $workOrder)`
  - Loads the linked `Equipment` and `Interval` template tasks.
  - For each template `Task`, applies applicability filtering:
    - **All Equipment**: always included.
    - **Specific Equipment**: included only if the `Equipment` id is in the task’s applicable equipment list.
    - **Conditional**: decodes `applicability_conditions` and matches against:
      - Static keys: `manufacturer`, `model`, `deck_id`, `location_id` (checks equality against the equipment fields)
      - Dynamic keys: any key in `equipment.attributes_json`
      - If any condition is invalid or mismatched, the task is excluded.
  - For included template tasks, creates a `WorkOrderTask` with `name`, `instructions`, and `sequence_position` (from `display_order`).

Result: The work order has a filtered, ordered set of tasks tailored to the specific equipment’s properties.

#### 3) Completing the First WorkOrder and Scheduling Future Ones
- Controller: `WorkOrderController@complete`
  - Guards: user must have access to the vessel; all tasks must be `completed` or `flagged`; if any are `flagged`, `notes` are required.
  - Updates the `WorkOrder` with `status = completed`, `completed_at`, `completed_by`, and `notes`. If the initial order had `due_date = null`, it stamps it with now.
  - Updates the parent `EquipmentInterval` (e.g., sets `next_due_date` to now initially).
  - If any tasks were flagged, attempts to create a `Deficiency` linking back to the `WorkOrder` and `Equipment`.
  - Detects if this `WorkOrder` is the first for the `EquipmentInterval`; if yes, calls `WorkOrderGenerationService::handleFirstCompletion($interval)`.
- Service: `WorkOrderGenerationService::handleFirstCompletion`
  - Sets `first_completed_at`, `last_completed_at`, and computes `next_due_date = now + frequency_interval`.
  - Calls `generateFutureWorkOrders($interval, first_completed_at, 2)` to add upcoming orders.
- Service: `generateFutureWorkOrders`
  - For i from 1..count (default 2):
    - Computes `due_date = start + (i * frequency_interval)`.
    - Creates a `WorkOrder` with `status = 'open'` for i=1, else `'scheduled'`.
    - Populates tasks via `createWorkOrderTasks`.
  - Updates the `EquipmentInterval.next_due_date` to the first generated due date.

Result: After the first completion, the system schedules the immediate next and an additional future work order, both with tasks re-derived from the current template state and equipment applicability.

#### 4) Propagating Template Changes to Pending WorkOrders
- Observer: `TaskObserver` (`app/Observers/TaskObserver.php`)
  - `created(Task $task)`: calls `WorkOrderGenerationService::propagateNewTask($task)` to add the new task to all pending work orders derived from that interval template (subject to applicability filtering inside task creation logic).
  - `updated` and `deleted`: calls `refreshTasksForInterval($task->interval_id)` to fully refresh pending orders.
- Service: `WorkOrderGenerationService`
  - `propagateNewTask(Task $task)`:
    - Finds all `EquipmentInterval` ids for the task’s `interval_id`.
    - Loads all non-completed, non-deferred `WorkOrder`s for those equipment intervals.
    - For each such `WorkOrder`, temporarily overrides the template’s in-memory `tasks` collection to just the new task, then calls `createWorkOrderTasks` to append the instance task as applicable.
  - `refreshTasksForInterval(int $intervalId)`:
    - Finds all non-completed, non-deferred, non-in-progress `WorkOrder`s linked to the interval via their `EquipmentInterval`.
    - Deletes all `WorkOrderTask`s for that set of work orders in bulk.
    - Re-populates each work order’s tasks by re-running `createWorkOrderTasks` against the latest template state.

Result: Template edits propagate forward to pending operational work without altering historical, completed work. This preserves auditability while keeping upcoming work current.

#### 5) Deactivating an Interval Template
- Controller: `CategoryController@destroyInterval`
  - Uses `IntervalInheritanceService::deactivateInterval($interval)` to:
    - Deactivate all `EquipmentInterval`s bound to this template (`is_active = false`).
    - Delete all non-completed, non-deferred `WorkOrder`s for those intervals.
  - Deletes the interval template itself.

Result: The template and its unstarted operational instances are removed while completed/deferred work remains untouched.

---

### Access Control Touchpoints
- Many controller actions gate by `auth()->user()->hasSystemAccessToVessel(...)` to ensure users only operate on work for vessels they are authorized to access.
- Additional gates are registered in `AuthServiceProvider` (e.g., `is-superadmin`, `is-staff`).

---

### Controller Entry Points of Interest
- `EquipmentIntervalController@show`: Loads an interval instance with its work orders and tasks (ordered), plus vessel users for assignment.
- `WorkOrderController@show`: Displays a single work order, eager-loading relationships used in the UI.
- `WorkOrderController@assign`: Validates and assigns a work order to a crew member on the same vessel.
- `WorkOrderController@open`: Transitions a scheduled work order to open.
- `WorkOrderController@complete`: Validates task resolution, handles flagged tasks and deficiencies, stamps completion, and, when applicable, triggers scheduling of future orders.
- `TaskController@reorder`: Updates `display_order` for tasks in a template and then refreshes tasks on pending work orders.
- `IntervalController@createTask/storeTask/editTask/updateTask`: CRUD flows for template tasks, including capturing applicability modes and conditions and managing specific-equipment associations.
- `CategoryController@createInterval/storeInterval/editInterval/updateInterval/destroyInterval/showInterval`: CRUD flows for interval templates at the category level, with vessel access checks.

---

### Frequency Semantics
- `IntervalInheritanceService::mapToFrequencyString` maps template strings to human-readable durations used by Carbon/CarbonInterval, e.g.:
  - "daily" → "1 day"
  - "weekly" → "1 week"
  - "monthly" → "1 month"
  - "quarterly" → "3 months"
  - "bi-annually" → "6 months"
  - "annual" → "1 year"
  - And multi-year variants up to "12-yearly".

These strings drive schedule math for due dates in `WorkOrderGenerationService`.

---

### Status Model Overview
- WorkOrder statuses in practice: `open`, `scheduled`, `in_progress`, `flagged`, `completed`, `deferred`.
  - Creation: initial is often `open` (or `Open` prior to normalization).
  - Opening a scheduled order sets it to `open`.
  - Task updates can set order to `in_progress` or `flagged`.
  - Completion sets to `completed`; deferred orders are out of scope for refresh/deletion logic.

Note: Ensure consistent casing for statuses across the codebase (normalize to lowercase where possible).

---

### Update Propagation Rules (Practical Guide)
- Adding a new task to an interval template:
  - Observer triggers `propagateNewTask` to append the task to all pending work orders where it is applicable.
- Editing/deleting tasks or reordering tasks:
  - Observer or controller triggers `refreshTasksForInterval`, which:
    - Deletes all instance tasks for pending orders, then rebuilds from the current template, preserving order and applicability.
- Deleting an interval:
  - Deactivates equipment intervals and removes pending orders, keeping completed/deferred orders untouched.
- Completing the first work order on an equipment interval:
  - Schedules the next due order and another future order; tasks are freshly derived from template state and equipment applicability at generation time.

---

### Operational Tips and Gotchas
- Template changes do not affect completed work orders; they only apply to future or pending ones.
- Conditional tasks rely on exact matches for both static and dynamic keys; typos or invalid keys cause exclusion.
- `attributes_json` is free-form; ensure UI-assisted condition-building uses the key/value dictionaries built in `IntervalController` to avoid invalid keys.
- When creating new intervals or equipment, the observer/service chain ensures initial work orders exist; if you add pre-existing equipment en masse, consider batch invoking `IntervalInheritanceService::handle` per equipment.
- When normalizing status strings, check all creation/update sites to avoid mixed-casing.

---

### Extensibility Notes
- To add new static condition keys (e.g., `power_rating`), extend both the template task condition builder (controller/UI) and the match logic in `createWorkOrderTasks`.
- To change the number of future work orders scheduled after first completion, adjust the `count` parameter in `handleFirstCompletion` or `generateFutureWorkOrders`.
- To pause all maintenance for an equipment item, set `EquipmentInterval.is_active = false` and remove/close pending work orders for that interval.
- To add additional status transitions or validations, centralize in controllers and consider policy-based authorization complementing `hasSystemAccessToVessel`.

---

### Initialization and Wiring
- `AppServiceProvider@boot` registers observers:
  - `Interval::observe(IntervalObserver::class)`
  - `Task::observe(TaskObserver::class)`
- Observers live in `app/Observers` and invoke services in `app/Services` to perform bulk operations.

---

### Reference: Key Methods
- `IntervalInheritanceService::handle(Equipment)` — seed all category intervals for new equipment.
- `IntervalInheritanceService::handleNewInterval(Interval)` — seed new interval across existing category equipment.
- `IntervalInheritanceService::deactivateInterval(Interval)` — deactivate and clean up pending work for an interval template.
- `WorkOrderGenerationService::createInitialWorkOrder(EquipmentInterval)` — create first WO and tasks.
- `WorkOrderGenerationService::handleFirstCompletion(EquipmentInterval)` — record first completion and schedule future WOs.
- `WorkOrderGenerationService::generateFutureWorkOrders(EquipmentInterval, Carbon, int)` — create upcoming WOs with tasks.
- `WorkOrderGenerationService::createWorkOrderTasks(WorkOrder)` — materialize instance tasks with applicability filtering.
- `WorkOrderGenerationService::propagateNewTask(Task)` — add a new template task across pending WOs.
- `WorkOrderGenerationService::refreshTasksForInterval(int)` — rebuild tasks on pending WOs after template edits.
