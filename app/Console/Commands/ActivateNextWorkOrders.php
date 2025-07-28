<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WorkOrder;
use Illuminate\Support\Carbon;

class ActivateNextWorkOrders extends Command
{
    protected $signature = 'work-orders:activate-next';
    protected $description = 'Activate the next scheduled work order when the current one reaches its due date';

    public function handle(): int
    {
        $today = Carbon::today();

        // Get all work orders due today, with their interval's work orders loaded chronologically
        $eligibleWorkOrders = WorkOrder::whereDate('due_date', $today)
            ->with('equipmentInterval.workOrdersChronological')
            ->get();

        $activatedCount = 0;

        foreach ($eligibleWorkOrders as $workOrder) {
            $interval = $workOrder->equipmentInterval;

            if (! $interval) {
                continue;
            }

            $next = $interval->workOrdersChronological
                ->where('due_date', '>', $today)
                ->first();

            if ($next && $next->status === 'scheduled') {
                $next->status = 'open';
                $next->save();
                $activatedCount++;
            }
        }

        $this->info("Activated {$activatedCount} next work order(s).");
        return 0;
    }
}
