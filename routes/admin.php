<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\VesselDataController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/test', fn () => view('admin.test'))
        ->name('test');

    Route::get('/vessels', [VesselDataController::class, 'index'])
        ->name('vessels.index');

    Route::get('/vessels/{vessel}', [VesselDataController::class, 'show'])
        ->name('vessels.show');

});


