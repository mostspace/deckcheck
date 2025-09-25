<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Category;
use App\Models\Deck;
use App\Models\Location;
use Illuminate\Http\Request;

use App\Services\IntervalInheritanceService;

class EquipmentController extends Controller
{

    public function index()
    {
        $vessel = currentVessel();

        // Load equipment for this vessel with relationships
        $equipment = Equipment::with(['category', 'deck', 'location'])
            ->where('vessel_id', $vessel->id)
            ->orderByDesc('created_at')
            ->get();

        // Count operational status
        $operationalCount = $equipment
            ->where('status', 'In Service')
            ->count();

        $inoperableCount = $equipment
            ->where('status', 'Inoperable')
            ->count();

        // Static DB columns (key => label)
        $staticFields = [
            'category'             => 'Category',
            'deck'                 => 'Deck',
            'location'             => 'Location',
            'internal_id'          => 'Internal ID',
            'name'                 => 'Name',
            'manufacturer'         => 'Manufacturer',
            'model'                => 'Model',
            'serial_number'        => 'Serial Number',
            'preferred_vendor'     => 'Preferred Vendor',
            'comments'             => 'Comments',
            'in_service'           => 'In Service',
            'manufacturing_date'   => 'Manufacturing Date',
            'purchase_date'        => 'Purchase Date',
            'expiry_date'          => 'Expiry Date',
            'status'               => 'Status',
            'removed_from_service' => 'Removed From Service',
        ];

        // Default columns to display
        $defaultColumns = ['category', 'name', 'serial_number', 'deck', 'location', 'status'];

        // Extract JSON attribute keys across all equipment
        $attributeKeys = $equipment->pluck('attributes_json')
            ->filter()
            ->flatMap(function ($attr) {
                // Only decode if it's a string
                if (is_string($attr)) {
                    $decoded = json_decode($attr, true);
                } elseif (is_array($attr)) {
                    $decoded = $attr;
                } else {
                    return [];
                }

                return is_array($decoded) ? array_keys($decoded) : [];
            })
            ->unique()
            ->values();

        // return view('v1.inventory.equipment.index', compact(
        //     'equipment',
        //     'operationalCount',
        //     'inoperableCount',
        //     'staticFields',
        //     'attributeKeys',
        //     'defaultColumns'
        // ));

        return view('v2.pages.maintenance.manifest', compact(
            'equipment',
            'operationalCount',
            'inoperableCount',
            'staticFields',
            'attributeKeys',
            'defaultColumns'
        ));
    }

    // Update index table columns
    public function updateVisibleColumns(Request $request)
    {
        // If "Restore Defaults" was clicked
        if ($request->has('reset')) {
            session()->forget('visible_columns');
            return redirect()->route('equipment.index')->with('success', 'Column preferences reset to default.');
        }

        // Otherwise validate and store selected columns
        $validated = $request->validate([
            'columns' => 'nullable|array',
            'columns.*' => 'string',
        ]);

        session(['visible_columns' => $validated['columns'] ?? []]);

        return redirect()->route('equipment.index')->with('success', 'Visible columns updated.');
    }


    // Create & Store Equipment
    public function create()
    {
        $vessel = currentVessel();

        $categories = Category::where('vessel_id', $vessel->id)->orderBy('name')->get();
        $decks = Deck::where('vessel_id', $vessel->id)->orderBy('name')->get();

        return view('inventory.equipment.create', compact('categories', 'decks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'deck_id'     => 'required|exists:decks,id',
            'location_id' => 'required|exists:locations,id',
            'name'        => 'required|string',
            'internal_id' => 'nullable|string',
        ]);

        $validated['vessel_id'] = currentVessel()?->id;

        // Create the new equipment
        $equipment = Equipment::create($validated);

        // Trigger interval inheritance logic
        app(IntervalInheritanceService::class)->handle($equipment);

        return redirect()
            ->route('equipment.show', $equipment)
            ->with('success', 'Equipment added and maintenance intervals inherited.');
    }


    // Equipment Detail Page
    public function show(Equipment $equipment)
    {
        // Check if user has access to this equipment's vessel
        if (!auth()->user()->hasSystemAccessToVessel($equipment->vessel)) {
            abort(403, 'Access denied to this equipment');
        }

        $categories = Category::where('vessel_id', $equipment->vessel_id)
                            ->orderBy('name')
                            ->get();

        $decks = Deck::where('vessel_id', $equipment->vessel_id)
                    ->orderBy('name')
                    ->get();

        // Only load locations for the equipment's current deck
        $locations = $equipment->deck
            ? $equipment->deck->locations()->orderBy('name')->get()
            : collect();

        // Load intervals and their work orders ordered by due date
        $equipment->load([
            'vessel',
            'intervals.workOrders' => function ($query) {
                $query->with('tasks');
            },
            'attachments.file'
        ]);

        // Load deficiencies for this equipment, ordered by created_at (newest first)
        $deficiencies = $equipment->deficiencies()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('inventory.equipment.show', compact(
            'equipment',
            'categories',
            'decks',
            'locations',
            'deficiencies'
        ));
    }

    // Equipment Edit Methods - Basic, Data & Attributes
    // Update Equipment Basic Info
    public function updateBasic(Request $request, Equipment $equipment)
    {
        // Check if user has access to this equipment's vessel
        if (!auth()->user()->hasSystemAccessToVessel($equipment->vessel)) {
            abort(403, 'Access denied to this equipment');
        }

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'deck_id'     => 'nullable|exists:decks,id',
            'location_id' => 'nullable|exists:locations,id',
            'status'      => 'nullable|string|max:50',
            'hero_photo'  => 'nullable|image|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('hero_photo')) {
            $path = $request->file('hero_photo')->store('hero_photos', 's3_public');
            $data['hero_photo'] = $path;
        }

        $equipment->update($data);

        return redirect()
            ->route('equipment.show', $equipment)
            ->with('success', 'Equipment record updated.');
    }

    // Update Equipment Data
    public function updateData(Request $request, Equipment $equipment)
    {
        // Check if user has access to this equipment's vessel
        if (!auth()->user()->hasSystemAccessToVessel($equipment->vessel)) {
            abort(403, 'Access denied to this equipment');
        }

        // 1. Validate only the fields in your modal
        $validated = $request->validate([
            'manufacturer'       => 'nullable|string|max:255',
            'model'              => 'nullable|string|max:255',
            'serial_number'      => 'nullable|string|max:255',
            'internal_id'        => 'nullable|string|max:255',
            'purchase_date'      => 'nullable|date',
            'manufacturing_date' => 'nullable|date',
            'in_service'         => 'nullable|date',
            'expiry_date'        => 'nullable|date',
        ]);

        // 2. Mass‐assign the validated data
        //    Make sure you have these keys in your $fillable on the model
        $equipment->update($validated);

        // 3. Redirect back to the show page
        return redirect()
            ->route('equipment.show', $equipment)
            ->with('success', 'Equipment updated successfully.');
    }

    // Update Equipment Attributes
    public function updateAttributes(Request $request, Equipment $equipment)
    {
        // Check if user has access to this equipment's vessel
        if (!auth()->user()->hasSystemAccessToVessel($equipment->vessel)) {
            abort(403, 'Access denied to this equipment');
        }

        $data = $request->validate([
            'attributes_json'   => 'nullable|array',
            'attributes_json.*' => 'nullable|string|max:255',
        ]);

        $equipment->update([
            'attributes_json' => $data['attributes_json'] ?? [],
        ]);

        return redirect()
            ->route('equipment.show', $equipment)
            ->with('success', 'Attributes updated successfully.');
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'equipment' => 'required|array|min:1',
            'equipment.*.name' => 'required|string|max:255',
            'equipment.*.deck_id' => 'required|exists:decks,id',
            'equipment.*.location_id' => 'required|exists:locations,id',
            'equipment.*.internal_id' => 'nullable|string|max:255',
            'equipment.*.serial_number' => 'nullable|string|max:255',
            'equipment.*.manufacturer' => 'nullable|string|max:255',
            'equipment.*.model' => 'nullable|string|max:255',
            'equipment.*.status' => 'required|string|in:In Service,Out of Service,Inoperable',
            'category_id' => 'required|exists:categories,id',
        ]);

        foreach ($validated['equipment'] as $entry) {
            $entry['category_id'] = $validated['category_id'];
            $entry['vessel_id'] = currentVessel()?->id;
            $equipment = Equipment::create($entry);
            app(IntervalInheritanceService::class)->handle($equipment);
        }

        return redirect()->route('maintenance.show', $validated['category_id'])
            ->with('success', 'Equipment records added successfully.');
    }

    public function getBulkRow(Request $request)
    {
        $index = $request->input('index', 0);
        $decks = Deck::where('vessel_id', currentVessel()->id)->orderBy('name')->get();

        return view('partials.maintenance.category.bulk-row', compact('index', 'decks'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipment $equipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        //
    }
}
