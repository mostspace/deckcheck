<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Interval;
use Illuminate\Http\Request;
use App\Services\WorkOrderGenerationService;

class TaskController extends Controller
{

    public function reorder(Request $request, Interval $interval)
    {
        // validate shape
        $data = $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:tasks,id',
            'order.*.display_order' => 'required|integer'
        ]);

        // update each task's display order
        foreach ($data['order'] as $item) {
            Task::where('id', $item['id'])
                ->update(['display_order' => $item['display_order']]);
        }

        // Propagate New Sequence to Existing Work Orders
        app(WorkOrderGenerationService::class)
            ->refreshTasksForInterval($interval->id);

        return response()->json(['status' => 'ok']);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
