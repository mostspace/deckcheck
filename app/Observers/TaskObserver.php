<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Task;
use App\Services\WorkOrderGenerationService;

class TaskObserver
{
    // Fire on New Task Creation
    public function created(Task $task): void
    {
        app(WorkOrderGenerationService::class)
            ->propagateNewTask($task);
    }

    public function updated(Task $task): void
    {
        // on any change to description, order, applicability, or even a deletion
        // we just refresh the whole batch for that interval
        app(WorkOrderGenerationService::class)
            ->refreshTasksForInterval($task->interval_id);
    }

    public function deleted(Task $task): void
    {
        // same behavior when you delete the template task
        app(WorkOrderGenerationService::class)
            ->refreshTasksForInterval($task->interval_id);
    }
}
