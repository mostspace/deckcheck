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
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Switch Between Vessels
    Route::post('/switch-vessel', [VesselSwitchController::class, 'switch'])
        ->name('vessel.switch');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Update Personal Information
    Route::put('/profile/info', [ProfileController::class, 'updateInfo'])
        ->name('profile.info.update');
    
    // Update Profile Picture 
    Route::put('/profile/picture', [ProfileController::class, 'updatePicture'])
        ->name('profile.picture.update');

});

Route::get('/vessel', [\App\Http\Controllers\VesselController::class, 'index'])
    ->name('vessel.index')->middleware('auth');


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





// Deck & Locations
Route::middleware('auth')->group(function () {


    // Deck Create & Store
    Route::get('/vessel/decks/create', [VesselController::class, 'createDeck'])
        ->name('vessel.decks.create');

    Route::post('/vessel/decks', [VesselController::class, 'storeDeck'])
        ->name('vessel.decks.store');

    // Deck Delete
    Route::delete('/vessel/decks/{deck}', [DeckController::class, 'destroy'])
        ->name('vessel.decks.destroy');

    // Deck Edit & Update
    Route::get('/vessel/decks/{deck}/edit', [DeckController::class, 'edit'])
        ->name('vessel.decks.edit');

    Route::put('/vessel/decks/{deck}', [DeckController::class, 'update'])
        ->name('vessel.decks.update');

    // Show one deck
    Route::get('/vessel/decks/{deck}', [VesselController::class, 'showDeck'])
        ->name('vessel.decks.show');

    // Re-Order Locations         
    Route::post('/decks/{deck}/locations/reorder', [App\Http\Controllers\LocationController::class, 'reorder'])
        ->name('locations.reorder');

    // Location Create & Store
    Route::get('vessel/decks/{deck}/locations/create', [LocationController::class, 'create'])
         ->name('vessel.decks.locations.create');

    Route::post('decks/{deck}/locations', [LocationController::class, 'store'])
         ->name('decks.locations.store');

    // Location Delete
    Route::delete('locations/{location}', [LocationController::class, 'destroy'])
        ->name('locations.destroy');

    // Location Edit & Update
    Route::get('vessel/decks/locations/{location}/edit', [LocationController::class, 'edit'])
        ->name('locations.edit');

    Route::put('locations/{location}', [LocationController::class, 'update'])
        ->name('locations.update');

    // AJAX: create a new location without redirect
    Route::post('/inventory/decks/{deck}/locations/ajax', [LocationController::class, 'ajaxStore'])
        ->name('decks.locations.ajax-store');

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
        ->name('maintenance.edit');

    Route::put('/maintenance/category/{category}', [VesselController::class, 'updateCategory'])
        ->name('maintenance.update');

    // Category Index Page 
    Route::get('/maintenance', [VesselController::class, 'categories'])
        ->name('maintenance.index');

    // Category Detail Page
    Route::get('/maintenance/{category}', [VesselController::class, 'showCategory'])
        ->name('maintenance.show');

    // Interval Create & Store
    Route::get('/maintenance/categories/{category}/intervals/create', [CategoryController::class, 'createInterval'])
        ->name('maintenance.intervals.create');

    Route::post('/maintenance/categories/{category}/intervals', [CategoryController::class, 'storeInterval'])
        ->name('maintenance.intervals.store');
    
    // Show Interval
    Route::get('/maintenance/categories/{category}/intervals/{interval}', [CategoryController::class, 'showInterval'])
        ->name('maintenance.intervals.show');

    // Delete Interval
    Route::delete('/maintenance/categories/{category}/intervals/{interval}', [CategoryController::class, 'destroyInterval'])
        ->name('maintenance.intervals.destroy');

    // Edit & Update Interval
    Route::get('/maintenance/categories/{category}/intervals/{interval}/edit', [CategoryController::class, 'editInterval'])
        ->name('intervals.edit');

    Route::put('/maintenance/categories/{category}/intervals/{interval}', [CategoryController::class, 'updateInterval'])
        ->name('intervals.update');

    // Task Create & Store
    Route::get('/intervals/{interval}/tasks/create', [IntervalController::class, 'createTask'])
        ->name('maintenance.intervals.tasks.create');

    Route::post('/maintenance/categories/{category}/intervals/{interval}/tasks', [IntervalController::class, 'storeTask'])
        ->name('maintenance.intervals.tasks.store');

    // Show Task
    Route::get('/maintenance/categories/{category}/intervals/{interval}/tasks/{task}', [IntervalController::class, 'showTask'])
        ->name('maintenance.intervals.tasks.show');

    // Delete Task
    Route::delete('/maintenance/categories/{category}/intervals/{interval}/tasks/{task}', [IntervalController::class, 'destroyTask'])
    ->name('maintenance.intervals.tasks.destroy');

    // Re-Order Tasks         
    Route::post('/intervals/{interval}/tasks/reorder', [App\Http\Controllers\TaskController::class, 'reorder'])
        ->name('tasks.reorder');

    // Edit & Update Task
    Route::get('/maintenance/categories/{category}/intervals/{interval}/tasks/{task}/edit', [IntervalController::class, 'editTask'])
        ->name('maintenance.intervals.tasks.edit');

    Route::put('/maintenance/categories/{category}/intervals/{interval}/tasks/{task}', [IntervalController::class, 'updateTask'])
        ->name('maintenance.intervals.tasks.update');
});

// Equipment
Route::middleware('auth')->group(function () {

    // Equipment Create & Store
    Route::get('/inventory/equipment/create', [EquipmentController::class, 'create'])
        ->name('equipment.create');

    Route::post('/inventory/equipment', [EquipmentController::class, 'store'])
        ->name('equipment.store');

    // For AJAX location lookup
    Route::get('/inventory/decks/{deck}/locations', [DeckController::class, 'locations'])
        ->name('decks.locations');

    // Show Equipment
    Route::get('/inventory/equipment/{equipment}', [EquipmentController::class, 'show'])
        ->name('equipment.show');

    // Equipment Index
    Route::get('/inventory/equipment', [EquipmentController::class, 'index'])
        ->name('equipment.index');

    // Update Equipment Basic Info
    Route::put('/equipment/{equipment}/basic', [EquipmentController::class, 'updateBasic'])
        ->name('equipment.updateBasic');

    // Update Equipment Attributes
    Route::put('/equipment/{equipment}/attributes', [EquipmentController::class, 'updateAttributes'])
        ->name('equipment.attributes.update');

    // Update Equipment Data
    Route::put('/equipment/{equipment}/data',[EquipmentController::class, 'updateData'])
        ->name('equipment.updateData');
    
    // Edit Index Table Columns
    Route::post('/equipment/columns', [EquipmentController::class, 'updateVisibleColumns'])
        ->name('equipment.columns.update');

    // Show Equipment Interval Detail
    Route::get('/equipment-intervals/{interval}', [EquipmentIntervalController::class, 'show'])
        ->name('equipment-intervals.show');

    // Show Work Order Detail
    Route::get('/equipment/intervals/work-orders/{workOrder}', [WorkOrderController::class, 'show'])
        ->name('work-orders.show');

    
    // Bulk Create & Store
    Route::post('/equipment/bulk-store', [EquipmentController::class, 'bulkStore'])
        ->name('equipment.bulk-store');

    Route::get('/equipment/bulk-row', [EquipmentController::class, 'getBulkRow'])
        ->name('equipment.bulk-row.partial');


});


// Work Orders & Work Order Tasks
Route::middleware('auth')->group(function () {

    // Show Equipment Interval Detail
    Route::get('/equipment-intervals/{interval}', [EquipmentIntervalController::class, 'show'])
        ->name('equipment-intervals.show');

    // Show Work Order Detail
    Route::get('/equipment/intervals/work-orders/{workOrder}', [WorkOrderController::class, 'show'])
        ->name('work-orders.show');

    // Assign Work Order
    Route::post('/work-orders/{workOrder}/assign', [WorkOrderController::class, 'assign'])
        ->name('work-orders.assign');
    
    // Update Work Order Task
    Route::put('/work-orders/tasks/{task}', [WorkOrderTaskController::class, 'updateStatus'])
        ->name('work-orders.tasks.update');

    // Complete Work Order
    Route::post('/work-orders/{workOrder}/complete', [WorkOrderController::class, 'complete'])
        ->name('work-orders.complete');

    // Open Scheduled Work Order
    Route::post('/work-orders/{workOrder}/open', [WorkOrderController::class, 'open'])
        ->name('work-orders.open');


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


});



require __DIR__.'/auth.php';
