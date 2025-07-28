<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WorkOrder;
use Carbon\Carbon;

class MarkOverdueWorkOrders extends Command
{
    protected $signature = 'work-orders:mark-overdue';
    protected $description = 'Mark open work orders as overdue if their due date has passed';

    public function handle(): int
    {
        $now = \Carbon\Carbon::now()->startOfDay();

        $count = \App\Models\WorkOrder::whereIn('status', ['open', 'in_progress', 'flagged'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', $now)
            ->update(['status' => 'overdue']);

        $this->info("Marked {$count} work order(s) as overdue.");

        return 0;
    }

}
