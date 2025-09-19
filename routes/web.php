<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    UserController,
    VesselController,
    DeckController,
    LocationController,
    CategoryController,
    IntervalController,
    TaskController,
    EquipmentController,
    EquipmentIntervalController,
    WorkOrderController,
    WorkOrderTaskController,
    DeficiencyController,
    VesselSwitchController,
    ScheduleController,
    WorkOrderFlowController,
    InviteUserController,
    FileController,
    AttachmentController,
    ReportController,
    InventoryController
};

// ------------------------------------  Public ----------------------------------------
Route::view('/', 'v1.welcome');
Route::view('/dashboard', 'v2.dashboard')
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
        Route::get('/create', [VesselController::class, 'createCategory'])->name('create');
        Route::post('/categories', [VesselController::class, 'storeCategory'])->name('store');

        Route::middleware('vessel.access')->group(function () {
            Route::get('/category/{category}/edit', [VesselController::class, 'editCategory'])->name('edit');
            Route::put('/category/{category}', [VesselController::class, 'updateCategory'])->name('update');
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

    // Schedule
    Route::prefix('maintenance/schedule')->name('schedule.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::prefix('flow')->name('flow.')->controller(WorkOrderFlowController::class)->group(function () {
            Route::get('/', 'start')->name('start');
            Route::get('/load/{workOrder}', 'load')->name('load');
        });
    });

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