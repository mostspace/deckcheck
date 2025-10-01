<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkOverdueWorkOrders extends Command
{
    protected $signature = 'work-orders:mark-overdue';

    protected $description = 'Mark open work orders as overdue if their due date has passed';

    public function handle(): int
    {
        $now = Carbon::now()->startOfDay();

        $count = WorkOrder::whereIn('status', ['open', 'in_progress', 'flagged'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', $now)
            ->update(['status' => 'overdue']);

        $this->info("Marked {$count} work order(s) as overdue.");

        return 0;
    }
}
