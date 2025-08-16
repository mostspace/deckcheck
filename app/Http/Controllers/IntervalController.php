<?php

namespace App\Http\Controllers;

use App\Models\Interval;
use App\Models\Category;
use App\Models\Task;
use App\Models\Deck;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\ApplicableEquipment;


class IntervalController extends Controller
{
    // Show Task
    public function showTask(Interval $interval, Task $task)
    {
        // Make sure the task actually belongs to the interval
        if ($task->interval_id !== $interval->id) {
            abort(404);
        }

        // Confirm the user has access to this vessel
        if (!auth()->user()->hasSystemAccessToVessel($interval->category->vessel)) {
            abort(403, 'Access denied to this interval');
        }

        //$interval->load('instructions'); // or whatever relations you want

        return view('maintenance.intervals.tasks.show', compact('task', 'interval'));
    }

    public function createTask(Interval $interval)
    {
        if (!auth()->user()->hasSystemAccessToVessel($interval->category->vessel)) {
            abort(403, 'Access denied to this interval');
        }

        $equipmentInCategory = $interval->category->equipment;

        // Static fields to be conditionally matched
        $staticConditions = [
            'manufacturer' => $equipmentInCategory->pluck('manufacturer')->unique()->filter()->map(fn($val) => ['label' => $val, 'value' => $val])->values(),
            'model' => $equipmentInCategory->pluck('model')->unique()->filter()->map(fn($val) => ['label' => $val, 'value' => $val])->values(),
        ];

        // Dynamic select fields using related models
        $staticConditions['deck_id'] = Deck::whereIn('id', $equipmentInCategory->pluck('deck_id')->filter()->unique())
            ->get()->map(fn($deck) => ['label' => $deck->name, 'value' => $deck->id])->values();

        $staticConditions['location_id'] = Location::whereIn('id', $equipmentInCategory->pluck('location_id')->filter()->unique())
            ->get()->map(fn($loc) => ['label' => $loc->name, 'value' => $loc->id])->values();

        // Dynamic user-defined attributes
        $dynamicRaw = [];
        foreach ($equipmentInCategory as $equipment) {
            foreach ($equipment->attributes_json ?? [] as $key => $value) {
                $dynamicRaw[$key][] = $value;
            }
        }

        $dynamicConditions = collect($dynamicRaw)->map(function ($values) {
            return collect($values)->unique()->filter()->map(fn($val) => ['label' => $val, 'value' => $val])->values();
        });

        // JSON-friendly format for dynamic JS injection
        $staticConditionsJson = collect($staticConditions)->mapWithKeys(function ($options, $key) {
            return [$key => collect($options)->pluck('label', 'value')];
        });

        $dynamicConditionsJson = collect($dynamicConditions)->mapWithKeys(function ($options, $key) {
            return [$key => collect($options)->pluck('label', 'value')];
        });

        return view('maintenance.intervals.tasks.create', compact(
            'interval',
            'staticConditions',
            'dynamicConditions',
            'staticConditionsJson',
            'dynamicConditionsJson'
        ));
    }

    public function storeTask(Request $request, Category $category, Interval $interval)
    {
        if ($interval->category_id !== $category->id) {
            abort(404);
        }

        if (!auth()->user()->hasSystemAccessToVessel($interval->category->vessel)) {
            abort(403, 'Access denied to this interval');
        }

        // Validation rules
        $rules = [
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'applicable_to' => 'required|in:All Equipment,Specific Equipment,Conditional',
            'specific_equipment' => 'nullable|array',
            'specific_equipment.*' => 'exists:equipment,id',
            'applicability_conditions' => 'nullable|array',
        ];

        // Only validate condition rows if conditional mode is selected
        if ($request->input('applicable_to') === 'Conditional') {
            $conditionRows = $request->input('applicability_conditions', []);
            foreach ($conditionRows as $index => $row) {
                $rules["applicability_conditions.$index.key"] = 'required|string';
                $rules["applicability_conditions.$index.value"] = 'required|string';
            }
        }

        $data = $request->validate($rules);

        $data['interval_id'] = $interval->id;
        $data['display_order'] = ($interval->tasks()->max('display_order') ?? 0) + 1;

        // Only save fully valid conditions
        if ($data['applicable_to'] === 'Conditional') {
            $conditions = collect($data['applicability_conditions'] ?? [])
                ->filter(fn($row) => isset($row['key'], $row['value']) && $row['key'] !== '' && $row['value'] !== '')
                ->values()
                ->all();
            $data['applicability_conditions'] = json_encode($conditions);
        } else {
            $data['applicability_conditions'] = null;
        }

        // Create the task
        $task = Task::create($data);

        // Save specific equipment (if applicable)
        if ($data['applicable_to'] === 'Specific Equipment' && !empty($data['specific_equipment'])) {
            foreach ($data['specific_equipment'] as $equipmentId) {
                $task->applicableEquipment()->create([
                    'equipment_id' => $equipmentId,
                ]);
            }
        }

        return redirect()
            ->route('maintenance.intervals.show', ['category' => $category, 'interval' => $interval])
            ->with('success', 'Task created.');
    }


    // Delete Task
    public function destroyTask(Category $category, Interval $interval, Task $task)
    {
        if (
            $interval->category_id !== $category->id ||
            $interval->category->vessel_id !== currentVessel()?->id ||
            $task->interval_id !== $interval->id
        ) {
            abort(404);
        }

        $task->delete();

        return redirect()
            ->route('maintenance.intervals.show', ['category' => $category, 'interval' => $interval])
            ->with('success', 'Task deleted.');
    }

    public function editTask(Category $category, Interval $interval, Task $task)
    {
        if (
            $interval->category_id !== $category->id ||
            $interval->category->vessel_id !== currentVessel()?->id ||
            $task->interval_id !== $interval->id
        ) {
            abort(404);
        }

        $equipmentInCategory = $interval->category->equipment;

        // Static fields to be conditionally matched
        $staticConditions = [
            'manufacturer' => $equipmentInCategory->pluck('manufacturer')->unique()->filter()->map(fn($val) => ['label' => $val, 'value' => $val])->values(),
            'model' => $equipmentInCategory->pluck('model')->unique()->filter()->map(fn($val) => ['label' => $val, 'value' => $val])->values(),
        ];

        // Dynamic select fields using related models
        $staticConditions['deck_id'] = Deck::whereIn('id', $equipmentInCategory->pluck('deck_id')->filter()->unique())
            ->get()->map(fn($deck) => ['label' => $deck->name, 'value' => $deck->id])->values();

        $staticConditions['location_id'] = Location::whereIn('id', $equipmentInCategory->pluck('location_id')->filter()->unique())
            ->get()->map(fn($loc) => ['label' => $loc->name, 'value' => $loc->id])->values();

        // Dynamic user-defined attributes
        $dynamicRaw = [];
        foreach ($equipmentInCategory as $equipment) {
            foreach ($equipment->attributes_json ?? [] as $key => $value) {
                $dynamicRaw[$key][] = $value;
            }
        }

        $dynamicConditions = collect($dynamicRaw)->map(function ($values) {
            return collect($values)->unique()->filter()->map(fn($val) => ['label' => $val, 'value' => $val])->values();
        });

        $staticConditionsJson = collect($staticConditions)->mapWithKeys(function ($options, $key) {
            return [$key => collect($options)->pluck('label', 'value')];
        });

        $dynamicConditionsJson = collect($dynamicConditions)->mapWithKeys(function ($options, $key) {
            return [$key => collect($options)->pluck('label', 'value')];
        });

        // --- Clean, direct query for applicable equipment IDs ---
        $selectedSpecific = ApplicableEquipment::where('task_id', $task->id)->pluck('equipment_id')->toArray();

        // --- Decode conditions for Blade ---
        $conditions = old('applicability_conditions');
        if (is_null($conditions)) {
            if (is_array($task->applicability_conditions)) {
                $conditions = $task->applicability_conditions;
            } elseif (is_string($task->applicability_conditions) && !empty($task->applicability_conditions)) {
                $decoded = json_decode($task->applicability_conditions, true);
                $conditions = is_array($decoded) ? $decoded : [];
            } else {
                $conditions = [];
            }
        }
        if (!is_array($conditions)) $conditions = [];

        return view('maintenance.intervals.tasks.edit', compact(
            'category',
            'interval',
            'task',
            'staticConditions',
            'dynamicConditions',
            'staticConditionsJson',
            'dynamicConditionsJson',
            'conditions',
            'selectedSpecific'
        ));
    }


    public function updateTask(Request $request, Category $category, Interval $interval, Task $task)
    {
        if (
            $interval->category_id !== $category->id ||
            $task->interval_id !== $interval->id
        ) {
            abort(404);
        }

        if (!auth()->user()->hasSystemAccessToVessel($interval->category->vessel)) {
            abort(403, 'Access denied to this interval');
        }

        $rules = [
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'applicable_to' => 'required|in:All Equipment,Specific Equipment,Conditional',
            'specific_equipment' => 'nullable|array',
            'specific_equipment.*' => 'exists:equipment,id',
            'applicability_conditions' => 'nullable|array',
        ];

        if ($request->input('applicable_to') === 'Conditional') {
            $conditionRows = $request->input('applicability_conditions', []);
            foreach ($conditionRows as $index => $row) {
                $rules["applicability_conditions.$index.key"] = 'required|string';
                $rules["applicability_conditions.$index.value"] = 'required|string';
            }
        }

        $data = $request->validate($rules);

        // Only save fully valid conditions
        if ($data['applicable_to'] === 'Conditional') {
            $conditions = collect($data['applicability_conditions'] ?? [])
                ->filter(fn($row) => isset($row['key'], $row['value']) && $row['key'] !== '' && $row['value'] !== '')
                ->values()
                ->all();
            $data['applicability_conditions'] = json_encode($conditions);
        } else {
            $data['applicability_conditions'] = null;
        }

        $task->update($data);

        // Sync specific equipment (delete then re-create)
        if ($data['applicable_to'] === 'Specific Equipment') {
            $task->applicableEquipment()->whereNotIn('equipment_id', $data['specific_equipment'] ?? [])->delete();
            $existing = $task->applicableEquipment()->pluck('equipment_id')->toArray();
            $toAdd = array_diff($data['specific_equipment'] ?? [], $existing);
            foreach ($toAdd as $equipmentId) {
                $task->applicableEquipment()->create(['equipment_id' => $equipmentId]);
            }
        } else {
            $task->applicableEquipment()->delete();
        }

        return redirect()
            ->route('maintenance.intervals.show', ['category' => $category, 'interval' => $interval])
            ->with('success', 'Task updated.');
    }




    /* 
    public function editTask(Category $category, Interval $interval, Task $task)
    {
        if ($interval->category_id !== $category->id || $interval->category->vessel_id !== auth()->user()->vessel_id || $task->interval_id !== $interval->id) {
            abort(404);
        }

        return view('maintenance.intervals.tasks.edit', compact('category', 'interval', 'task'));
    }

    public function updateTask(Request $request, Category $category, Interval $interval, Task $task)
    {
        if ($interval->category_id !== $category->id || $interval->category->vessel_id !== auth()->user()->vessel_id || $task->interval_id !== $interval->id) {
            abort(404);
        }

        $data = $request->validate([
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'applicable_to' => 'required|in:All Equipment,Specific Equipment,Conditional',
        ]);

        $task->update($data);

        return redirect()
            ->route('maintenance.intervals.show', ['category' => $category, 'interval' => $interval])
            ->with('success', 'Task updated.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Interval $interval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Interval $interval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Interval $interval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interval $interval)
    {
        //
    }
}
