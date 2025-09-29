<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Category;
use App\Models\Deck;
use App\Models\Location;
use Illuminate\Http\Request;

class InventoryController extends Controller
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

        return view('v2.pages.inventory.equipment.index', compact(
            'equipment',
            'operationalCount',
            'inoperableCount',
            'staticFields',
            'attributeKeys',
            'defaultColumns'
        ));
    }

    public function equipment()
    {
        // Redirect to index() method following RESTful convention
        return redirect()->route('inventory.index');
    }

    public function consumables()
    {
        $vessel = currentVessel();

        // TODO: Load consumables data when consumables model is implemented
        // For now, return empty data
        $consumables = collect([]);
        $categories = collect([]);
        $decks = collect([]);

        return view('v2.pages.inventory.consumables', compact(
            'consumables',
            'categories',
            'decks'
        ));
    }
}
