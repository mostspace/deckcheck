<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('work-orders:mark-overdue')->dailyAt('00:00');
Schedule::command('work-orders:activate-next')->dailyAt('00:00');
