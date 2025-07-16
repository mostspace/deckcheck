<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Interval;
use App\Models\Task;
use App\Observers\IntervalObserver;
use App\Observers\TaskObserver;

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
    }
}
