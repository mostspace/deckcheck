<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Interval;
use App\Services\IntervalInheritanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showInterval(Category $category, Interval $interval)
    {
        if ($interval->category_id !== $category->id) {
            abort(404);
        }

        if (! auth()->user()->hasSystemAccessToVessel($category->vessel)) {
            abort(403, 'Access denied to this category');
        }

        $interval->load(['tasks' => function ($query) {
            $query->orderBy('display_order');
        }]);

        return view('v2.pages.maintenance.intervals.show', compact('interval', 'category'));
    }

    public function createInterval(Category $category)
    {
        if (! auth()->user()->hasSystemAccessToVessel($category->vessel)) {
            abort(403, 'Access denied to this category');
        }

        return view('v2.pages.maintenance.intervals.create', compact('category'));
    }

    public function storeInterval(Request $request, Category $category)
    {
        if (! auth()->user()->hasSystemAccessToVessel($category->vessel)) {
            abort(403, 'Access denied to this category');
        }

        $data = $request->validate([
            'interval' => 'required|in:Daily,Bi-Weekly,Weekly,Monthly,Quarterly,Bi-Annually,Annual,2-Yearly,3-Yearly,5-Yearly,6-Yearly,10-Yearly,12-Yearly',
            'facilitator' => 'required|in:Crew,Service Provider',
            'description' => 'nullable|string',
        ]);

        $data['category_id'] = $category->id;

        Interval::create($data);

        return redirect()
            ->route('maintenance.show', $category)
            ->with('success', 'Interval created.');
    }

    public function destroyInterval(Category $category, Interval $interval): RedirectResponse
    {
        if ($interval->category_id !== $category->id) {
            abort(404);
        }

        if (! auth()->user()->hasSystemAccessToVessel($category->vessel)) {
            abort(403, 'Access denied to this category');
        }

        $affectedCount = app(IntervalInheritanceService::class)
            ->deactivateInterval($interval);

        $interval->delete();

        return redirect()
            ->route('maintenance.show', $category)
            ->with('warning', "Interval removed from {$affectedCount} equipment items; their future work orders have been deleted.");
    }

    public function editInterval(Category $category, Interval $interval)
    {
        if ($interval->category_id !== $category->id) {
            abort(404);
        }

        if (! auth()->user()->hasSystemAccessToVessel($category->vessel)) {
            abort(403, 'Access denied to this category');
        }

        return view('v2.pages.maintenance.intervals.edit', compact('interval', 'category'));
    }

    public function updateInterval(Request $request, Category $category, Interval $interval)
    {
        if ($interval->category_id !== $category->id) {
            abort(404);
        }

        if (! auth()->user()->hasSystemAccessToVessel($category->vessel)) {
            abort(403, 'Access denied to this category');
        }

        $data = $request->validate([
            'interval' => 'required|in:Daily,Bi-Weekly,Weekly,Monthly,Quarterly,Bi-Annually,Annual,2-Yearly,3-Yearly,5-Yearly,6-Yearly,10-Yearly,12-Yearly',
            'facilitator' => 'required|in:Crew,Service Provider',
            'description' => 'nullable|string',
        ]);

        $interval->update($data);

        return redirect()
            ->route('maintenance.intervals.show', [$category, $interval])
            ->with('success', 'Interval updated.');
    }

    // Pre-Boarding Scope:
    /*public function showInterval(Category $category, Interval $interval)
    {
        // Make sure the interval actually belongs to the category
        if ($interval->category_id !== $category->id) {
            abort(404);
        }

        // Confirm the user owns the vessel
        if ($category->vessel_id !== auth()->user()->vessel_id) {
            abort(404);
        }

        $interval->load(['tasks' => function ($query) {
            $query->orderBy('display_order');
        }]);

        return view('v2.pages.maintenance.intervals.show', compact('interval', 'category'));
    }

    // Create & Store Interval
    public function createInterval(Category $category)
    {
        // Optional: make sure user owns this category
        if ($category->vessel_id !== auth()->user()->vessel_id) {
            abort(404);
        }

        return view('maintenance.intervals.create', compact('category'));
    }

    public function storeInterval(Request $request, Category $category)
    {
        if ($category->vessel_id !== auth()->user()->vessel_id) {
            abort(404);
        }

        $data = $request->validate([
            'interval'    => 'required|in:Daily,Weekly,Monthly,Quarterly,Annual,Bi-Weekly,Bi-Annually,2-Yearly,3-Yearly,5-Yearly,6-Yearly,10-Yearly,12-Yearly',
            'facilitator' => 'required|in:Crew,Service Provider',
            'description'  => 'nullable|string',
        ]);

        $data['category_id'] = $category->id;

        Interval::create($data);

        return redirect()
            ->route('maintenance.show', $category)
            ->with('success', 'Interval created.');
    }

    // Delete Interval
    public function destroyInterval(Category $category, Interval $interval): RedirectResponse
    {
        // 1. Security: ensure interval belongs to this category
        if ($interval->category_id !== $category->id) {
            abort(404);
        }

        // 2. Security: ensure category belongs to the user's vessel
        if ($category->vessel_id !== auth()->user()->vessel_id) {
            abort(404);
        }

        // 3. Deactivate all EquipmentIntervals & delete pending WOs
        $affectedCount = app(IntervalInheritanceService::class)
            ->deactivateInterval($interval);

        // 4. Delete the interval template
        $interval->delete();

        // 5. Redirect with warning about affected equipment
        return redirect()
            ->route('maintenance.show', $category)
            ->with('warning', "Interval removed from {$affectedCount} equipment items; their future work orders have been deleted.");
    }

    // Edit & Update Interval
    public function editInterval(Category $category, Interval $interval)
    {
        if ($interval->category_id !== $category->id || $category->vessel_id !== auth()->user()->vessel_id) {
            abort(404);
        }

        return view('v2.pages.maintenance.intervals.edit', compact('interval', 'category'));
    }

    public function updateInterval(Request $request, Category $category, Interval $interval)
    {
        if ($interval->category_id !== $category->id || $category->vessel_id !== auth()->user()->vessel_id) {
            abort(404);
        }

        $data = $request->validate([
            'interval'    => 'required|in:Daily,Weekly,Monthly,Quarterly,Annual,Bi-Weekly,Bi-Annually,2-Yearly,3-Yearly,5-Yearly,6-Yearly,10-Yearly,12-Yearly',
            'facilitator' => 'required|in:Crew,Service Provider',
            'description'  => 'nullable|string',
        ]);

        $interval->update($data);

        return redirect()
            ->route('maintenance.intervals.show', [$category, $interval])
            ->with('success', 'Interval updated.');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
