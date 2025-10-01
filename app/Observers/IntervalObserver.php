<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Interval;
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
