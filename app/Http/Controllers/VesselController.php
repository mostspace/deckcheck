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
        
        // If no vessel is selected and user is a system user, redirect to dashboard
        if (!$vessel && auth()->user() && in_array(auth()->user()->system_role, ['superadmin', 'staff', 'dev'])) {
            return redirect()->route('dashboard')->with('info', 'No vessel selected. Use the user modal or admin vessel list to select a vessel to view.');
        }
        
        // If no vessel is selected for regular users, show an error
        if (!$vessel) {
            abort(404, 'No vessel assigned.');
        }

        return view('v1.vessel.index', compact('vessel'));
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

        return view('v1.vessel.crew', compact('vessel','users'));
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

        return view('v1.vessel.deckplan', compact('vessel', 'decks'));
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

        // Get deficiencies data for the deficiencies tab
        $deficiencies = \App\Models\Deficiency::with(['equipment', 'workOrder'])
            ->whereHas('equipment', fn ($q) => $q->where('vessel_id', $vessel->id))
            ->latest()
            ->get();

        // Calculate deficiency age distribution for open deficiencies
        $openDeficiencies = $deficiencies->where('status', 'open');
        
        $ageDistribution = [
            'under_30_days' => 0,
            '30_to_90_days' => 0,
            'over_90_days' => 0
        ];

        foreach ($openDeficiencies as $deficiency) {
            $daysOpen = $deficiency->created_at->diffInDays(now());
            
            if ($daysOpen < 30) {
                $ageDistribution['under_30_days']++;
            } elseif ($daysOpen >= 30 && $daysOpen <= 90) {
                $ageDistribution['30_to_90_days']++;
            } else {
                $ageDistribution['over_90_days']++;
            }
        }

        // Prepare chart data
        $chartData = [
            'labels' => ['< 30 Days', '30-90 Days', '> 90 Days'],
            'data' => [
                $ageDistribution['under_30_days'],
                $ageDistribution['30_to_90_days'],
                $ageDistribution['over_90_days']
            ],
            'colors' => ['#12b76a', '#f79009', '#f04438']
        ];

        // Get schedule data for the schedule tab
        $request = request();
        
        // Loop Work Orders, Pass Used Frequencies into Array for Dynamic Display
        $rawFrequencies = \App\Models\WorkOrder::with('equipmentInterval')
            ->whereHas('equipmentInterval.equipment', fn($q) => $q->where('vessel_id', $vessel->id))
            ->get()
            ->map(fn($wo) => strtolower($wo->equipmentInterval?->frequency))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Set Frequency Tab Display Order
        $order = [
            'daily' => 1, 'weekly' => 2, 'bi-weekly' => 3, 'monthly' => 4,
            'quarterly' => 5, 'bi-annually' => 6, 'annual' => 7,
            '2-yearly' => 8, '3-yearly' => 9, '5-yearly' => 10,
            '6-yearly' => 11, '10-yearly' => 12, '12-yearly' => 13,
        ];

        // Update Array with Display Order
        $visibleFrequencies = collect($rawFrequencies)
            ->filter(fn($f) => isset($order[$f]))
            ->sortBy(fn($f) => $order[$f])
            ->values()
            ->toArray();

        // Set Date Anchors for Each Respective Frequency
        $frequency = $request->input('frequency', $visibleFrequencies[0] ?? 'daily');
        $date = \Illuminate\Support\Carbon::parse($request->input('date', now()));

        // Calculate Interval Date Range for Each Respective Frequency
        $start = match ($frequency) {
            'daily' => $date->copy()->startOfDay(),
            'weekly', 'bi-weekly' => $date->copy()->startOfWeek(),
            'monthly', 'quarterly', 'bi-annually' => $date->copy()->startOfMonth(),
            default => $date->copy()->startOfYear(),
        };

        $end = match ($frequency) {
            'daily' => $date->copy()->endOfDay(),
            'weekly', 'bi-weekly' => $date->copy()->endOfWeek(),
            'monthly', 'quarterly', 'bi-annually' => $date->copy()->endOfMonth(),
            default => $date->copy()->endOfYear(),
        };

        // Query Work Orders for Selected Frequency & Date Range
        $query = \App\Models\WorkOrder::with(['equipmentInterval.equipment.location.deck', 'assignee'])
            ->whereHas('equipmentInterval', fn($q) => $q->where('frequency', $frequency))
            ->whereHas('equipmentInterval.equipment', fn($q) => $q->where('vessel_id', $vessel->id))
            ->where(function (\Illuminate\Database\Eloquent\Builder $q) use ($start, $end) {
                $q->where(function (\Illuminate\Database\Eloquent\Builder $sub) use ($start, $end) {
                    $sub->whereNotIn('status', ['completed', 'deferred'])
                        ->where(function (\Illuminate\Database\Eloquent\Builder $inner) use ($start, $end) {
                            $inner->whereBetween('due_date', [$start, $end])
                                ->orWhereNull('due_date'); 
                        });
                })
                ->orWhere(function (\Illuminate\Database\Eloquent\Builder $sub) use ($start, $end) {
                    $sub->whereIn('status', ['completed', 'deferred'])
                        ->whereBetween('due_date', [$start, $end]);
                });
            });

        // If Toggled: Filter by Assignee = Current User
        if ($request->boolean('assigned')) {
            $query->where('assigned_to', auth()->id());
        }

        // Full Query
        $allWorkOrders = $query->get();

        // Separate into Arrays for Active & Resolved
        $activeWorkOrders = $allWorkOrders->filter(fn($wo) => !in_array($wo->status, ['completed', 'deferred']));
        $resolvedWorkOrders = $allWorkOrders->filter(fn($wo) => in_array($wo->status, ['completed', 'deferred']));

        // Default Grouping by Date
        $group = $request->input('group', 'date');
        $groups = [];

        // Group by Category
        if ($group === 'category') {
            $activeWorkOrders = $activeWorkOrders->groupBy(function ($wo) {
                return $wo->equipmentInterval->equipment->category->name ?? 'Uncategorized';
            });
            
            $resolvedWorkOrders = $resolvedWorkOrders->groupBy(function ($wo) {
                return $wo->equipmentInterval->equipment->category->name ?? 'Uncategorized';
            });

            $allCategories = $activeWorkOrders->keys()->merge($resolvedWorkOrders->keys())->unique();

            foreach ($allCategories as $category) {
                $groups[$category] = [
                    'active' => $activeWorkOrders->get($category, collect()),
                    'resolved' => $resolvedWorkOrders->get($category, collect()),
                ];
            }
        }
        // Group by Location
        elseif ($group === 'location') {
            $activeWorkOrders = $activeWorkOrders->groupBy(function ($wo) {
                $deck = $wo->equipmentInterval->equipment->location->deck->name ?? 'Unknown Deck';
                $location = $wo->equipmentInterval->equipment->location->name ?? 'Unknown Location';
                return "{$deck} > {$location}";
            });
            
            $resolvedWorkOrders = $resolvedWorkOrders->groupBy(function ($wo) {
                $deck = $wo->equipmentInterval->equipment->location->deck->name ?? 'Unknown Deck';
                $location = $wo->equipmentInterval->equipment->location->name ?? 'Unknown Location';
                return "{$deck} > {$location}";
            });

            // Group by deck first, then location
            $act = $activeWorkOrders->groupBy(function ($wo, $key) {
                return explode(' > ', $key)[0];
            });
            
            $res = $resolvedWorkOrders->groupBy(function ($wo, $key) {
                return explode(' > ', $key)[0];
            });

            $allDecks = $act->keys()->merge($res->keys())->unique();

            foreach ($allDecks as $deck) {
                $activeLocs = $act->get($deck, collect());
                $resolvedLocs = $res->get($deck, collect());

                $allLocs = $activeLocs->keys()->merge($resolvedLocs->keys())->unique();

                $groups[$deck] = [];

                foreach ($allLocs as $locationName) {
                    $groups[$deck][$locationName] = [
                        'active' => $activeLocs->get($locationName, collect()),
                        'resolved' => $resolvedLocs->get($locationName, collect()),
                    ];
                }
            }
        }

        // Pass Vessel Crew into View for Assignee Drop-Down
        $availableUsers = $vessel->users()->orderBy('first_name')->get();

        // return view('v1.maintenance.index', compact('vessel', 'categories', 'totalEquipment'));
        return view('v2.crew.maintenance.index', compact(
            'vessel', 'categories', 'totalEquipment', 
            'deficiencies', 'ageDistribution', 'chartData',
            'frequency', 'date', 'visibleFrequencies', 'group', 'groups',
            'activeWorkOrders', 'resolvedWorkOrders', 'availableUsers'
        ));
    }

    // Maintenance Category Show
    public function showCategory(Category $category)
    {
        // Check if user has access to this category's vessel
        if (!auth()->user()->hasSystemAccessToVessel($category->vessel)) {
            abort(403, 'Access denied to this category');
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

        $decks = Deck::where('vessel_id', $category->vessel_id)->orderBy('name')->get();

        return view('v1.maintenance.show', compact('category', 'decks'));
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

        $vessel = currentVessel();
        if (!$vessel) {
            abort(404, 'No vessel assigned.');
        }

        return view('v1.maintenance.create', compact('types', 'icons', 'vessel'));
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
        'vessel_id' => 'required|exists:vessels,id',
    ]);

    // Check if user has access to the specified vessel
    $vessel = Vessel::findOrFail($data['vessel_id']);
    if (!auth()->user()->hasSystemAccessToVessel($vessel)) {
        abort(403, 'Access denied to this vessel');
    }

    // Create the new category
    $category = Category::create($data);

    return redirect()->route('maintenance.show', $category)->with('success', 'Category created.');
}

    // Edit & Update Category
    public function editCategory(Category $category)
    {
        if (!auth()->user()->hasSystemAccessToVessel($category->vessel)) {
            abort(403, 'Access denied to this category');
        }

        $types = ['LSA', 'FFE', 'FFS', 'Radio & Nav', 'Deck', 'Other'];

        $enumRaw = DB::selectOne("SHOW COLUMNS FROM categories WHERE Field = 'icon'")->Type;
        preg_match('/^enum\((.*)\)$/', $enumRaw, $matches);
        $icons = isset($matches[1])
            ? array_map(fn($v) => trim($v, "'"), explode(',', $matches[1]))
            : [];

        return view('v1.maintenance.edit', compact('category', 'types', 'icons'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        if (!auth()->user()->hasSystemAccessToVessel($category->vessel)) {
            abort(403, 'Access denied to this category');
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
        // Check if user has access to this deck's vessel
        if (!auth()->user()->hasSystemAccessToVessel($deck->vessel)) {
            abort(403, 'Access denied to this deck');
        }

        // eager-load locations if you need them on the detail page
        $deck->load('locations');

        return view('v1.vessel.decks.show', compact('deck'));
    }

   // Create & Store New Deck
    public function createDeck()
    {
        $vessel = currentVessel();
        if (!$vessel) {
            abort(404, 'No vessel assigned.');
        }

        // Check if user has access to this vessel
        if (!auth()->user()->hasSystemAccessToVessel($vessel)) {
            abort(403, 'Access denied to this vessel');
        }

        return view('v1.vessel.decks.create', compact('vessel'));
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

        // Check if user has access to this vessel
        if (!auth()->user()->hasSystemAccessToVessel($vessel)) {
            abort(403, 'Access denied to this vessel');
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
