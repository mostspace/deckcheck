<?php


namespace App\Observers;

use App\Models\Interval;
use App\Models\Equipment;
use App\Services\IntervalInheritanceService;

class IntervalObserver
{
    public function created(Interval $interval): void
    {
        // only seed the one new template
        app(IntervalInheritanceService::class)
            ->handleNewInterval($interval);
    }
}
