<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\VesselDataController;
use App\Http\Controllers\Admin\UserManagementController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/test', fn () => view('admin.test'))
        ->name('test');

    Route::get('/vessels', [VesselDataController::class, 'index'])
        ->name('vessels.index');

    Route::get('/vessels/create', [VesselDataController::class, 'create'])
        ->name('vessels.create');

    Route::post('/vessels', [VesselDataController::class, 'store'])
        ->name('vessels.store');

    Route::get('/vessels/{vessel}', [VesselDataController::class, 'show'])
        ->name('vessels.show');

    Route::get('/users', [UserManagementController::class, 'index'])
        ->name('users.index');
    
    Route::get('/users/{user}', [UserManagementController::class, 'show'])
        ->name('users.show');

});


