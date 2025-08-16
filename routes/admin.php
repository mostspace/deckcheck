<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\VesselDataController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\StaffManagementController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/test', fn () => view('admin.test'))
        ->name('test');

    Route::get('/vessels', [VesselDataController::class, 'index'])
        ->name('vessels.index');

    Route::get('/vessels/create', [VesselDataController::class, 'create'])
        ->name('vessels.create');

    Route::post('/vessels', [VesselDataController::class, 'store'])
        ->name('vessels.store');

    Route::get('/vessels/{vessel}/edit', [VesselDataController::class, 'edit'])
        ->name('vessels.edit');

    Route::put('/vessels/{vessel}', [VesselDataController::class, 'update'])
        ->name('vessels.update');

    Route::get('/vessels/{vessel}', [VesselDataController::class, 'show'])
        ->name('vessels.show');

    Route::get('/vessels/{vessel}/add-user', [VesselDataController::class, 'addUser'])
        ->name('vessels.add-user');

    Route::post('/vessels/{vessel}/add-user', [VesselDataController::class, 'storeUser'])
        ->name('vessels.store-user');

    Route::get('/vessels/{vessel}/transfer-ownership', [VesselDataController::class, 'transferOwnership'])
        ->name('vessels.transfer-ownership');

    Route::post('/vessels/{vessel}/transfer-ownership', [VesselDataController::class, 'processOwnershipTransfer'])
        ->name('vessels.process-ownership-transfer');

    Route::get('/users', [UserManagementController::class, 'index'])
        ->name('users.index');
    
    Route::get('/users/{user}', [UserManagementController::class, 'show'])
        ->name('users.show');

    // Staff Management Routes
    Route::get('/staff', [StaffManagementController::class, 'index'])
        ->name('staff.index');
    
    Route::get('/staff/{user}', [StaffManagementController::class, 'show'])
        ->name('staff.show');
    
    Route::get('/staff/{user}/edit', [StaffManagementController::class, 'edit'])
        ->name('staff.edit');
    
    Route::put('/staff/{user}', [StaffManagementController::class, 'update'])
        ->name('staff.update');

});


