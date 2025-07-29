<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WorkOrder;
use Illuminate\Support\Carbon;
use App\Services\WorkOrderGenerationService;

class ActivateNextWorkOrders extends Command
{
    protected $signature = 'work-orders:activate-next';
    protected $description = 'Activate the next scheduled work order when the current one reaches its due date';

    protected WorkOrderGenerationService $generator;

    public function __construct(WorkOrderGenerationService $generator)
    {
        parent::__construct();
        $this->generator = $generator;
    }

    public function handle(): int
    {
        $today = Carbon::today();

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

                // ✅ Generate next scheduled work order
                $this->generator->generateFutureWorkOrders(
                    $interval,
                    startDate: $next->due_date,
                    count: 1
                );
            }
        }

        $this->info("Activated {$activatedCount} next work order(s) and generated follow-ups.");
        return 0;
    }
}
