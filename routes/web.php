<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IntervalController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentIntervalController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\WorkOrderTaskController;
use App\Http\Controllers\DeficiencyController;
use App\Http\Controllers\VesselSwitchController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\WorkOrderFlowController;
use App\Http\Controllers\InviteUserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AttachmentController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('v1.welcome');
});

Route::get('/dashboard', function () {
    return view('v2.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Switch Between Vessels
    Route::post('/switch-vessel', [VesselSwitchController::class, 'switch'])
        ->name('vessel.switch');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Update Personal Information
    Route::put('/profile/info', [ProfileController::class, 'updateInfo'])
        ->name('profile.info.update');
    
    // Update Profile Picture 
    Route::put('/profile/picture', [ProfileController::class, 'updatePicture'])
        ->name('profile.picture.update');

    // File Management
    Route::prefix('files')->name('files.')->group(function () {
        Route::post('/upload', [FileController::class, 'upload'])->name('upload');
        Route::post('/upload-multiple', [FileController::class, 'uploadMultiple'])->name('upload-multiple');
        Route::get('/config', [FileController::class, 'config'])->name('config');
        Route::get('/vessel/{vesselId}', [FileController::class, 'vesselFiles'])->name('vessel');
        Route::get('/{file}', [FileController::class, 'show'])->name('show');
        Route::get('/{file}/view', [FileController::class, 'view'])->name('view');
        Route::put('/{file}', [FileController::class, 'update'])->name('update');
        Route::delete('/{file}', [FileController::class, 'destroy'])->name('destroy');
        Route::get('/{file}/download', [FileController::class, 'download'])->name('download');
    });

    // Attachment Management
    Route::prefix('attachments')->name('attachments.')->group(function () {
        Route::post('/', [AttachmentController::class, 'store'])->name('store');
        Route::put('/{attachment}', [AttachmentController::class, 'update'])->name('update');
        Route::delete('/{attachment}', [AttachmentController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [AttachmentController::class, 'reorder'])->name('reorder');
        Route::get('/{modelType}/{modelId}', [AttachmentController::class, 'forModel'])->name('for-model');
    });
});

Route::get('/vessel', [\App\Http\Controllers\VesselController::class, 'index'])->name('vessel.index')->middleware('auth');

Route::get('/v2/vessel', [\App\Http\Controllers\VesselController::class, 'index'])->name('vessel.v2.index')->middleware('auth');

// Crew Pages
    Route::middleware('auth')->group(function(){
    // existing…
    Route::get('/vessel/crew', [VesselController::class, 'users'])
         ->name('vessel.crew');
});

Route::get('vessel/crew/{user}', [UserController::class, 'show'])
    ->name('vessel.crew.show')
    ->middleware('auth');

Route::middleware('auth')->group(function(){
    // existing…
    Route::get('/vessel/deckplan', [VesselController::class, 'decks'])
         ->name('vessel.deckplan');
});

// Invitation & User Management
Route::middleware('auth')->group(function () { 

    // Invite User to Vessel
    Route::post('/vessel/invitations', [InviteUserController::class, 'store'])
    ->name('vessel.invitations.store');

    

});
   
    // Invitation Accept Flow
    Route::get('/invitations/accept', [InviteUserController::class, 'showAcceptForm'])
        ->name('invitations.accept');
    
    Route::post('/invitations/accept/password', [InviteUserController::class, 'storePassword'])
        ->name('invitations.accept.password');

    Route::get('/invitations/accept/avatar/{token}', [InviteUserController::class, 'showAvatarForm'])
        ->name('invitations.accept.avatar');

    Route::post('/invitations/accept/avatar', [InviteUserController::class, 'storeAvatar'])
        ->name('invitations.accept.avatar.store');

    Route::get('/invitations/accept/terms/{token}', [InviteUserController::class, 'showTermsForm'])
        ->name('invitations.accept.terms');

    Route::post('/invitations/accept/terms', [InviteUserController::class, 'storeTerms'])
        ->name('invitations.accept.terms.store');





// Deck & Locations
Route::middleware('auth')->group(function () {


    // Deck Create & Store
    Route::get('/vessel/decks/create', [VesselController::class, 'createDeck'])
        ->name('vessel.decks.create')
        ->middleware('vessel.access');

    Route::post('/vessel/decks', [VesselController::class, 'storeDeck'])
        ->name('vessel.decks.store')
        ->middleware('vessel.access');

    // Deck Delete
    Route::delete('/vessel/decks/{deck}', [DeckController::class, 'destroy'])
        ->name('vessel.decks.destroy')
        ->middleware('vessel.access');

    // Deck Edit & Update
    Route::get('/vessel/decks/{deck}/edit', [DeckController::class, 'edit'])
        ->name('vessel.decks.edit')
        ->middleware('vessel.access');

    Route::put('/vessel/decks/{deck}', [DeckController::class, 'update'])
        ->name('vessel.decks.update')
        ->middleware('vessel.access');

    // Show one deck
    Route::get('/vessel/decks/{deck}', [VesselController::class, 'showDeck'])
        ->name('vessel.decks.show')
        ->middleware('vessel.access');

    // Re-Order Locations         
    Route::post('/decks/{deck}/locations/reorder', [App\Http\Controllers\LocationController::class, 'reorder'])
        ->name('locations.reorder')
        ->middleware('vessel.access');

    // Location Create & Store
    Route::get('vessel/decks/{deck}/locations/create', [LocationController::class, 'create'])
         ->name('vessel.decks.locations.create')
         ->middleware('vessel.access');

    Route::post('decks/{deck}/locations', [LocationController::class, 'store'])
         ->name('decks.locations.store')
         ->middleware('vessel.access');

    // Location Delete
    Route::delete('locations/{location}', [LocationController::class, 'destroy'])
        ->name('locations.destroy')
        ->middleware('vessel.access');

    // Location Edit & Update
    Route::get('vessel/decks/locations/{location}/edit', [LocationController::class, 'edit'])
        ->name('locations.edit')
        ->middleware('vessel.access');

    Route::put('locations/{location}', [LocationController::class, 'update'])
        ->name('locations.update')
        ->middleware('vessel.access');

    // AJAX: create a new location without redirect
    Route::post('/inventory/decks/{deck}/locations/ajax', [LocationController::class, 'ajaxStore'])
        ->name('decks.locations.ajax-store')
        ->middleware('vessel.access');

    // For AJAX location lookup
    Route::get('/inventory/decks/{deck}/locations', [DeckController::class, 'locations'])
        ->name('decks.locations')
        ->middleware('vessel.access');

});

// Schedule
Route::middleware('auth')->group(function () { 

    // Schedule Index Page 
    Route::get('/maintenance/schedule', [ScheduleController::class, 'index'])
        ->name('schedule.index');

});

// Schedule Flow
Route::middleware('auth')->prefix('maintenance/schedule/flow')->group(function () {

    Route::get('/', [WorkOrderFlowController::class, 'start'])
        ->name('flow.start');

    Route::get('/load/{workOrder}', [WorkOrderFlowController::class, 'load'])
        ->name('flow.load');

});


// Maintenance
Route::middleware('auth')->group(function () {

    // Category Create & Store
    Route::get('/maintenance/create', [VesselController::class, 'createCategory'])
        ->name('maintenance.create');

    Route::post('/maintenance/categories', [VesselController::class, 'storeCategory'])
        ->name('maintenance.store');

    // Category Edit & Update
    Route::get('/maintenance/category/{category}/edit', [VesselController::class, 'editCategory'])
        ->name('maintenance.edit')
        ->middleware('vessel.access');

    Route::put('/maintenance/category/{category}', [VesselController::class, 'updateCategory'])
        ->name('maintenance.update')
        ->middleware('vessel.access');

    // Category Index Page 
    Route::get('/maintenance', [VesselController::class, 'categories'])->name('maintenance.index');

    Route::get('/v2/maintenance', [VesselController::class, 'categories'])->name('v2.maintenance.index');

    // Category Detail Page
    Route::get('/maintenance/{category}', [VesselController::class, 'showCategory'])
        ->name('maintenance.show')
        ->middleware('vessel.access');

    // Interval Create & Store
    Route::get('/maintenance/categories/{category}/intervals/create', [CategoryController::class, 'createInterval'])
        ->name('maintenance.intervals.create')
        ->middleware('vessel.access');

    Route::post('/maintenance/categories/{category}/intervals', [CategoryController::class, 'storeInterval'])
        ->name('maintenance.intervals.store')
        ->middleware('vessel.access');
    
    // Show Interval
    Route::get('/maintenance/categories/{category}/intervals/{interval}', [CategoryController::class, 'showInterval'])
        ->name('maintenance.intervals.show')
        ->middleware('vessel.access');

    // Delete Interval
    Route::delete('/maintenance/categories/{category}/intervals/{interval}', [CategoryController::class, 'destroyInterval'])
        ->name('maintenance.intervals.destroy')
        ->middleware('vessel.access');

    // Edit & Update Interval
    Route::get('/maintenance/categories/{category}/intervals/{interval}/edit', [CategoryController::class, 'editInterval'])
        ->name('intervals.edit')
        ->middleware('vessel.access');

    Route::put('/maintenance/categories/{category}/intervals/{interval}', [CategoryController::class, 'updateInterval'])
        ->name('intervals.update')
        ->middleware('vessel.access');

    // Task Create & Store
    Route::get('/intervals/{interval}/tasks/create', [IntervalController::class, 'createTask'])
        ->name('maintenance.intervals.tasks.create')
        ->middleware('vessel.access');

    Route::post('/maintenance/categories/{category}/intervals/{interval}/tasks', [IntervalController::class, 'storeTask'])
        ->name('maintenance.intervals.tasks.store')
        ->middleware('vessel.access');

    // Show Task
    Route::get('/maintenance/categories/{category}/intervals/{interval}/tasks/{task}', [IntervalController::class, 'showTask'])
        ->name('maintenance.intervals.tasks.show')
        ->middleware('vessel.access');

    // Delete Task
    Route::delete('/maintenance/categories/{category}/intervals/{interval}/tasks/{task}', [IntervalController::class, 'destroyTask'])
    ->name('maintenance.intervals.tasks.destroy')
    ->middleware('vessel.access');

    // Re-Order Tasks         
    Route::post('/intervals/{interval}/tasks/reorder', [App\Http\Controllers\TaskController::class, 'reorder'])
        ->name('tasks.reorder')
        ->middleware('vessel.access');

    // Edit & Update Task
    Route::get('/maintenance/categories/{category}/intervals/{interval}/tasks/{task}/edit', [IntervalController::class, 'editTask'])
        ->name('maintenance.intervals.tasks.edit')
        ->middleware('vessel.access');

    Route::put('/maintenance/categories/{category}/intervals/{interval}/tasks/{task}', [IntervalController::class, 'updateTask'])
        ->name('maintenance.intervals.tasks.update')
        ->middleware('vessel.access');
});


// ------------------------------------  Inventory ----------------------------------------
Route::get('/inventory', [\App\Http\Controllers\InventoryController::class, 'index'])->name('inventory.index')->middleware('auth');

// Equipment
Route::middleware('auth')->group(function () {

    // Equipment Create & Store
    Route::get('/inventory/equipment/create', [EquipmentController::class, 'create'])
        ->name('equipment.create')
        ->middleware('vessel.access');

    Route::post('/inventory/equipment', [EquipmentController::class, 'store'])
        ->name('equipment.store')
        ->middleware('vessel.access');

    // For AJAX location lookup
    Route::get('/inventory/decks/{deck}/locations', [DeckController::class, 'locations'])
        ->name('decks.locations');

    // Show Equipment
    Route::get('/inventory/equipment/{equipment}', [EquipmentController::class, 'show'])
        ->name('equipment.show')
        ->middleware('vessel.access');

    // Equipment Index
    Route::get('/inventory/equipment', [EquipmentController::class, 'index'])
        ->name('equipment.index');

    // Update Equipment Basic Info
    Route::put('/equipment/{equipment}/basic', [EquipmentController::class, 'updateBasic'])
        ->name('equipment.updateBasic')
        ->middleware('vessel.access');

    // Update Equipment Attributes
    Route::put('/equipment/{equipment}/attributes', [EquipmentController::class, 'updateAttributes'])
        ->name('equipment.attributes.update')
        ->middleware('vessel.access');

    // Update Equipment Data
    Route::put('/equipment/{equipment}/data',[EquipmentController::class, 'updateData'])
        ->name('equipment.updateData')
        ->middleware('vessel.access');
    
    // Edit Index Table Columns
    Route::post('/equipment/columns', [EquipmentController::class, 'updateVisibleColumns'])
        ->name('equipment.columns.update')
        ->middleware('vessel.access');

    // Show Equipment Interval Detail
    Route::get('/equipment-intervals/{interval}', [EquipmentIntervalController::class, 'show'])
        ->name('equipment-intervals.show')
        ->middleware('vessel.access');

    // Show Work Order Detail
    Route::get('/equipment/intervals/work-orders/{workOrder}', [WorkOrderController::class, 'show'])
        ->name('work-orders.show')
        ->middleware('vessel.access');

    
    // Bulk Create & Store
    Route::post('/equipment/bulk-store', [EquipmentController::class, 'bulkStore'])
        ->name('equipment.bulk-store')
        ->middleware('vessel.access');

    Route::get('/equipment/bulk-row', [EquipmentController::class, 'getBulkRow'])
        ->name('equipment.bulk-row.partial')
        ->middleware('vessel.access');


});


// Work Orders & Work Order Tasks
Route::middleware('auth')->group(function () {

    // Assign Work Order
    Route::post('/work-orders/{workOrder}/assign', [WorkOrderController::class, 'assign'])
        ->name('work-orders.assign')
        ->middleware('vessel.access');
    
    // Open Work Order
    Route::post('/work-orders/{workOrder}/open', [WorkOrderController::class, 'open'])
        ->name('work-orders.open')
        ->middleware('vessel.access');
    
    // Complete Work Order
    Route::post('/work-orders/{workOrder}/complete', [WorkOrderController::class, 'complete'])
        ->name('work-orders.complete')
        ->middleware('vessel.access');

    // Update Work Order Task Status
    Route::put('/work-orders/tasks/{task}', [WorkOrderTaskController::class, 'updateStatus'])
        ->name('work-orders.tasks.update-status')
        ->middleware('vessel.access');
});

// Deficiencies
Route::middleware('auth')->group(function () { 

    Route::resource('deficiencies', \App\Http\Controllers\DeficiencyController::class);

    // Deficiency Index
    Route::get('/deficiencies', [DeficiencyController::class, 'index'])
        ->name('deficiencies.index');

    // Show Deficiency
    Route::get('/deficiencies/{deficiency}', [DeficiencyController::class, 'show'])
        ->name('deficiencies.show');

    // Update Assignee
    Route::put('/deficiencies/{deficiency}/assign', [DeficiencyController::class, 'assign'])
        ->name('deficiencies.assign');

    // Post Deficiency Update
    Route::post('/deficiencies/{deficiency}/updates', [DeficiencyController::class, 'storeUpdate'])
    ->name('deficiencies.updates.store');

Route::post('/deficiencies/{deficiency}/update-description', [DeficiencyController::class, 'updateDescription'])
    ->name('deficiencies.update-description');


});


require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
