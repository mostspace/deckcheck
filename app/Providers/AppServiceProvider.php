<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Interval;
use App\Models\Task;
use App\Observers\IntervalObserver;
use App\Observers\TaskObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Interval::observe(IntervalObserver::class);
        Task::observe(TaskObserver::class);

        // Register anonymous view component alias for v2 UI button
        Blade::component('v2.components.ui.button', 'v2.components.ui.button');
    }
}
