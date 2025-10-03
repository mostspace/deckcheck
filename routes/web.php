<?php

declare(strict_types=1);

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\DeficiencyController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentIntervalController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\IntervalController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InviteUserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\VesselSwitchController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\WorkOrderFlowController;
use App\Http\Controllers\WorkOrderTaskController;
use Illuminate\Support\Facades\Route;

// ------------------------------------  Public ----------------------------------------
Route::view('/', 'welcome');
Route::view('/dashboard', 'v2.pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ------------------------------------  Authenticated ----------------------------------------
Route::middleware('auth')->group(function () {

    // Vessel Switch
    Route::post('/switch-vessel', [VesselSwitchController::class, 'switch'])->name('vessel.switch');

    // Profile
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
        Route::put('/info', 'updateInfo')->name('info.update');
        Route::put('/picture', 'updatePicture')->name('picture.update');
    });

    // Vessel
    Route::prefix('vessel')->name('vessel.')->group(function () {
        Route::get('/', [VesselController::class, 'index'])->name('index');
        Route::get('/crew', [VesselController::class, 'users'])->name('crew');
        Route::get('/crew/{user}', [UserController::class, 'show'])->name('crew.show');
        Route::get('/deckplan', [VesselController::class, 'decks'])->name('deckplan');

        // Invitations
        Route::post('/invitations', [InviteUserController::class, 'store'])->name('invitations.store');
    });

    // Maintenance (Categories → Intervals → Tasks)
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [VesselController::class, 'categories'])->name('index');
        Route::get('/summary', [VesselController::class, 'categories'])->name('summary');
        Route::get('/create', [VesselController::class, 'createCategory'])->name('create');
        Route::post('/categories', [VesselController::class, 'storeCategory'])->name('store');

        Route::middleware('vessel.access')->group(function () {
            Route::get('/category/{category}/edit', [VesselController::class, 'editCategory'])->name('edit');
            Route::put('/category/{category}', [VesselController::class, 'updateCategory'])->name('update');

            // Schedule route must come before the catch-all {category} route
            Route::prefix('schedule')->name('schedule.')->group(function () {
                Route::get('/', [ScheduleController::class, 'index'])->name('index');
                Route::prefix('flow')->name('flow.')->controller(WorkOrderFlowController::class)->group(function () {
                    Route::get('/', 'start')->name('start');
                    Route::get('/load/{workOrder}', 'load')->name('load');
                });
            });

            // Maintenance Manifest (Equipment in maintenance context)
            Route::prefix('manifest')->name('manifest.')->controller(EquipmentController::class)->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/{equipment}', 'show')->name('show');
                Route::put('/{equipment}/basic', 'updateBasic')->name('updateBasic');
                Route::put('/{equipment}/attributes', 'updateAttributes')->name('attributes.update');
                Route::put('/{equipment}/data', 'updateData')->name('updateData');
                Route::post('/columns', 'updateVisibleColumns')->name('columns.update');

                // Manifest Equipment Intervals (matching equipment route structure)
                Route::get('/intervals/{interval}', [EquipmentIntervalController::class, 'show'])
                    ->name('intervals.show');

                // Manifest Work Orders (matching equipment route structure)
                Route::get('/intervals/work-orders/{workOrder}', [WorkOrderController::class, 'show'])
                    ->name('work-orders.show');

                // Manifest Deck Locations AJAX
                Route::get('/decks/{deck}/locations', [DeckController::class, 'locations'])
                    ->name('decks.locations');
            });

            Route::get('/{category}', [VesselController::class, 'showCategory'])->name('show');

            Route::prefix('categories/{category}/intervals')->name('intervals.')->group(function () {
                Route::get('/create', [CategoryController::class, 'createInterval'])->name('create');
                Route::post('/', [CategoryController::class, 'storeInterval'])->name('store');
                Route::get('/{interval}', [CategoryController::class, 'showInterval'])->name('show');
                Route::get('/{interval}/edit', [CategoryController::class, 'editInterval'])->name('edit');
                Route::put('/{interval}', [CategoryController::class, 'updateInterval'])->name('update');
                Route::delete('/{interval}', [CategoryController::class, 'destroyInterval'])->name('destroy');

                Route::prefix('{interval}/tasks')->name('tasks.')->controller(IntervalController::class)->group(function () {
                    Route::get('/create', 'createTask')->name('create');
                    Route::post('/', 'storeTask')->name('store');
                    Route::get('/{task}', 'showTask')->name('show');
                    Route::get('/{task}/edit', 'editTask')->name('edit');
                    Route::put('/{task}', 'updateTask')->name('update');
                    Route::delete('/{task}', 'destroyTask')->name('destroy');
                });
            });
        });
    });

    // ------------------------------------  Inventory ----------------------------------------
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index')->middleware('auth');

    // ------------------------------------  Equipment ----------------------------------------
    Route::middleware('auth')->prefix('inventory')->group(function () {

        // AJAX: Deck → Locations (kept separate, not under EquipmentController)
        Route::get('/decks/{deck}/locations', [DeckController::class, 'locations'])
            ->name('decks.locations');

        // Equipment
        Route::prefix('equipment')->name('equipment.')->controller(EquipmentController::class)->group(function () {

            // Routes that require vessel.access
            Route::middleware('vessel.access')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{equipment}', 'show')->name('show');

                // Updates
                Route::put('/{equipment}/basic', 'updateBasic')->name('updateBasic');
                Route::put('/{equipment}/attributes', 'updateAttributes')->name('attributes.update');
                Route::put('/{equipment}/data', 'updateData')->name('updateData');

                // Columns
                Route::post('/columns', 'updateVisibleColumns')->name('columns.update');

                // Bulk
                Route::post('/bulk-store', 'bulkStore')->name('bulk-store');
                Route::get('/bulk-row', 'getBulkRow')->name('bulk-row.partial');
            });

            // Equipment index (does not require vessel.access)
            Route::get('/', 'index')->name('index');
        });

        // Equipment Intervals
        Route::get('/equipment-intervals/{interval}', [EquipmentIntervalController::class, 'show'])
            ->name('equipment-intervals.show')
            ->middleware('vessel.access');

        // Work Orders
        Route::get('/equipment/intervals/work-orders/{workOrder}', [WorkOrderController::class, 'show'])
            ->name('work-orders.show')
            ->middleware('vessel.access');
    });

    // ------------------------------------  Inventory Navigation Routes ----------------------------------------
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index')->middleware('auth');
    Route::get('/inventory/equipment', [InventoryController::class, 'equipment'])->name('inventory.equipment')->middleware('auth');
    Route::get('/inventory/consumables', [InventoryController::class, 'consumables'])->name('inventory.consumables')->middleware('auth');

    // Files
    Route::prefix('files')->name('files.')->controller(ReportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/analytics', 'analytics')->name('analytics');
        Route::get('/exports', 'exports')->name('exports');
        Route::get('/my-reports', 'myReports')->name('my-reports');
        Route::get('/all-reports', 'allReports')->name('all-reports');
        Route::post('/generate/{reportType}', 'generate')->name('generate');
    });

    // Invitations Accept Flow (public, so outside auth group if needed)
    Route::prefix('invitations/accept')->name('invitations.accept')->controller(InviteUserController::class)->group(function () {
        Route::get('/', 'showAcceptForm');
        Route::post('/password', 'storePassword')->name('.password');
        Route::get('/avatar/{token}', 'showAvatarForm')->name('.avatar');
        Route::post('/avatar', 'storeAvatar')->name('.avatar.store');
        Route::get('/terms/{token}', 'showTermsForm')->name('.terms');
        Route::post('/terms', 'storeTerms')->name('.terms.store');
    });

    // Decks & Locations
    Route::prefix('vessel/decks')->name('vessel.decks.')->middleware('vessel.access')->group(function () {
        Route::get('/create', [VesselController::class, 'createDeck'])->name('create');
        Route::post('/', [VesselController::class, 'storeDeck'])->name('store');
        Route::get('/{deck}/edit', [DeckController::class, 'edit'])->name('edit');
        Route::put('/{deck}', [DeckController::class, 'update'])->name('update');
        Route::get('/{deck}', [VesselController::class, 'showDeck'])->name('show');
        Route::delete('/{deck}', [DeckController::class, 'destroy'])->name('destroy');

        // Locations
        Route::prefix('{deck}/locations')->name('locations.')->controller(LocationController::class)->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::post('/ajax', 'ajaxStore')->name('ajax-store');
        });
    });

    Route::prefix('locations')->name('locations.')->middleware('vessel.access')->controller(LocationController::class)->group(function () {
        Route::delete('/{location}', 'destroy')->name('destroy');
        Route::get('/{location}/edit', 'edit')->name('edit');
        Route::put('/{location}', 'update')->name('update');
    });

    Route::post('/decks/{deck}/locations/reorder', [LocationController::class, 'reorder'])
        ->name('locations.reorder')
        ->middleware('vessel.access');

    Route::get('/inventory/decks/{deck}/locations', [DeckController::class, 'locations'])
        ->name('decks.locations')
        ->middleware('vessel.access');

    // Task reorder
    Route::post('/intervals/{interval}/tasks/reorder', [TaskController::class, 'reorder'])
        ->name('tasks.reorder')
        ->middleware('vessel.access');

    // Work Orders & Tasks
    Route::prefix('work-orders')->name('work-orders.')->middleware('vessel.access')->group(function () {
        Route::post('/{workOrder}/assign', [WorkOrderController::class, 'assign'])->name('assign');
        Route::post('/{workOrder}/open', [WorkOrderController::class, 'open'])->name('open');
        Route::post('/{workOrder}/complete', [WorkOrderController::class, 'complete'])->name('complete');
        Route::put('/tasks/{task}', [WorkOrderTaskController::class, 'updateStatus'])->name('tasks.update-status');
    });

    // Deficiencies
    Route::resource('deficiencies', DeficiencyController::class)->middleware('auth');
    Route::put('/deficiencies/{deficiency}/assign', [DeficiencyController::class, 'assign'])->name('deficiencies.assign');
    Route::post('/deficiencies/{deficiency}/updates', [DeficiencyController::class, 'storeUpdate'])->name('deficiencies.updates.store');
    Route::post('/deficiencies/{deficiency}/update-description', [DeficiencyController::class, 'updateDescription'])->name('deficiencies.update-description');

    // File Management
    Route::prefix('files')->name('files.')->controller(FileController::class)->group(function () {
        Route::post('/upload', 'upload')->name('upload');
        Route::post('/upload-multiple', 'uploadMultiple')->name('upload-multiple');
        Route::get('/config', 'config')->name('config');
        Route::get('/vessel/{vesselId}', 'vesselFiles')->name('vessel');
        Route::get('/{file}', 'show')->name('show');
        Route::get('/{file}/view', 'view')->name('view');
        Route::put('/{file}', 'update')->name('update');
        Route::delete('/{file}', 'destroy')->name('destroy');
        Route::get('/{file}/download', 'download')->name('download');
    });

    // Attachment Management
    Route::prefix('attachments')->name('attachments.')->controller(AttachmentController::class)->group(function () {
        Route::post('/', 'store')->name('store');
        Route::put('/{attachment}', 'update')->name('update');
        Route::delete('/{attachment}', 'destroy')->name('destroy');
        Route::post('/reorder', 'reorder')->name('reorder');
        Route::get('/{modelType}/{modelId}', 'forModel')->name('for-model');
    });
});

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
