<?php

namespace App\Http\Controllers;

use App\Models\Vessel;
use App\Models\User;
use App\Models\Deck;
use App\Models\Location;
use App\Models\Category;
use App\Models\Interval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DriverManager;

class VesselController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vessel = currentVessel();

        return view('vessel.index', compact('vessel'));
    }

    // Display users for Vessel.Crew
    public function users()
    {
        $vessel = currentVessel();

        if (! $vessel) {
            abort(404, 'No vessel assigned.');
        }

        // eager‐load if you like: $vessel->load('users');
        $users = $vessel->users;

        return view('vessel.crew', compact('vessel','users'));
    }

    // Display Deck Plan for Vessel
    public function decks()
    {
        $vessel = currentVessel();

        if (! $vessel) {
            abort(404, 'No vessel assigned.');
        }

        // eager-load locations on every deck
        $decks = $vessel
            ->decks()              // make it a query
            ->with('locations')    // eager-load the locations relation
            ->get();

        return view('vessel.deckplan', compact('vessel', 'decks'));
    }

    // Maintenance Index Page
    public function categories()
    {
        $vessel = currentVessel();

        if (! $vessel) {
            abort(404, 'No vessel assigned.');
        }

        $categories = $vessel
            ->categories()
            ->orderByDesc('created_at')
            ->with('intervals')
            ->withCount('equipment') 
            ->get();

            $totalEquipment = $categories->sum('equipment_count');

        return view('maintenance.index', compact('vessel', 'categories', 'totalEquipment'));
    }

    // Maintenance Category Show
    public function showCategory(Category $category)
    {
        // make sure this category really belongs to the logged-in user's vessel
        if ($category->vessel_id !== currentVessel()?->id) {
            abort(404);
        }

        $category->loadCount('equipment')->load([
            'equipment' => function($q) {
                $q->with(['deck', 'location'])
                ->orderBy('name');
            },

            'intervals' => function ($query) {
                $query->withCount('tasks')
                    ->orderByRaw("FIELD(`interval`, 
                        'Daily','Weekly','Bi-Weekly','Monthly',
                        'Quarterly','Bi-Annually','Annual',
                        '2-Yearly','3-Yearly','5-Yearly',
                        '6-Yearly','10-Yearly','12-Yearly'
                    )");
            },
    ]);

        $decks = Deck::where('vessel_id', currentVessel()->id)->orderBy('name')->get();

        return view('maintenance.show', compact('category', 'decks'));
    }

    // Create & Store New Category
    public function createCategory()
    {
        $types = ['LSA', 'FFE', 'FFS', 'Radio & Nav', 'Deck', 'Other'];

        // Get enum definition via SQL
        $enumRaw = DB::selectOne("SHOW COLUMNS FROM categories WHERE Field = 'icon'")->Type;

        // Extract enum values using regex
        preg_match('/^enum\((.*)\)$/', $enumRaw, $matches);

        $icons = isset($matches[1])
            ? array_map(fn($v) => trim($v, "'"), explode(',', $matches[1]))
            : [];

        return view('maintenance.create', compact('types', 'icons'));
    }


    public function storeCategory(Request $request)
{
    // Get ENUM values for 'icon' via raw SQL (reliable fallback)
    $enumRaw = DB::selectOne("SHOW COLUMNS FROM categories WHERE Field = 'icon'")->Type;

    preg_match('/^enum\((.*)\)$/', $enumRaw, $matches);

    $iconOptions = isset($matches[1])
        ? array_map(fn($v) => trim($v, "'"), explode(',', $matches[1]))
        : [];

    // Validate input
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:LSA,FFE,FFS,Radio & Nav,Deck,Other',
        'icon' => ['required', Rule::in($iconOptions)],
    ]);

    // Get the user's assigned vessel
    $vessel = currentVessel();

    if (! $vessel) {
        abort(404, 'No vessel assigned.');
    }

    $data['vessel_id'] = $vessel->id;

    // Create the new category
    $category = Category::create($data);

    return redirect()->route('maintenance.show', $category)->with('success', 'Category created.');
}

    // Edit & Update Category
    public function editCategory(Category $category)
    {
        if ($category->vessel_id !== currentVessel()?->id) {
            abort(404);
        }

        $types = ['LSA', 'FFE', 'FFS', 'Radio & Nav', 'Deck', 'Other'];

        $enumRaw = DB::selectOne("SHOW COLUMNS FROM categories WHERE Field = 'icon'")->Type;
        preg_match('/^enum\((.*)\)$/', $enumRaw, $matches);
        $icons = isset($matches[1])
            ? array_map(fn($v) => trim($v, "'"), explode(',', $matches[1]))
            : [];

        return view('maintenance.edit', compact('category', 'types', 'icons'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        if ($category->vessel_id !== currentVessel()?->id) {
            abort(404);
        }

        $enumRaw = DB::selectOne("SHOW COLUMNS FROM categories WHERE Field = 'icon'")->Type;
        preg_match('/^enum\((.*)\)$/', $enumRaw, $matches);
        $iconOptions = isset($matches[1])
            ? array_map(fn($v) => trim($v, "'"), explode(',', $matches[1]))
            : [];

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:LSA,FFE,FFS,Radio & Nav,Deck,Other',
            'icon' => ['required', Rule::in($iconOptions)],
        ]);

        $category->update($data);

        return redirect()
            ->route('maintenance.show', $category)
            ->with('success', 'Update successful.');
    }

    // Display Deck Details
    public function showDeck(Deck $deck)
    {
        // make sure this deck really belongs to the logged-in user's vessel
        if ($deck->vessel_id !== currentVessel()?->id) {
            abort(404);
        }

        // eager-load locations if you need them on the detail page
        $deck->load('locations');

        return view('vessel.decks.show', compact('deck'));
    }

   // Create & Store New Deck
    public function createDeck()
    {
        return view('vessel.decks.create');
    }

    public function storeDeck(Request $request)
    {
        $data = $request->validate([
        'name' => 'required|string|max:255',
        ]);

        $vessel = currentVessel();

        if (! $vessel) {
            abort(404, 'No vessel assigned.');
        }

        $data['vessel_id'] = $vessel->id;
        $data['display_order'] = $vessel->decks()->max('display_order') + 1 ?? 1;

        Deck::create($data);

        return redirect()->route('vessel.deckplan')->with('success', 'Deck created.');
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
    public function show(Vessel $vessel)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vessel $vessel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vessel $vessel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vessel $vessel)
    {
        //
    }
}
