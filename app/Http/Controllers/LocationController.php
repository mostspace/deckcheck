<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Deck;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function reorder(Request $request, Deck $deck)
    {
    // validate shape
    $data = $request->validate([
        'order' => 'required|array',
        'order.*.id' => 'required|integer|exists:locations,id',
        'order.*.display_order' => 'required|integer'
    ]);

    // update each record
    foreach ($data['order'] as $item) {
        Location::where('id', $item['id'])
        ->update(['display_order' => $item['display_order']]);
    }

    return response()->json(['status' => 'ok']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    // Create & Store Location
    public function create(Deck $deck)
    {
        $this->authorizeDeck($deck);

        return view('vessel.decks.locations.create', compact('deck'));
    }

    public function store(Request $request, Deck $deck)
    {
        $this->authorizeDeck($deck);

        $data = $request->validate([
        'name'          => 'required|string|max:255',
        'description'   => 'nullable|string',
        ]);

        $data['deck_id'] = $deck->id; 
        $data['display_order'] = $deck->locations()->max('display_order') + 1 ?? 1;
        Location::create($data);

        return redirect()
        ->route('vessel.decks.show', $deck->id)
        ->with('success', 'Location added!');
    }

    // Store for Inline New Location on Equipment Create
    public function ajaxStore(Request $request, Deck $deck)
    {
        $this->authorizeDeck($deck);

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // preserve your existing ordering logic
        $data['deck_id'] = $deck->id;
        $data['display_order'] = ($deck->locations()->max('display_order') ?? 0) + 1;

        $location = Location::create($data);

        // return the new record as JSON
        return response()->json($location, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
    }

    // Edit & Update Location
    public function edit(Location $location)
    {
        $this->authorizeLocation($location);

        return view('vessel.decks.locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $this->authorizeLocation($location);

        $data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        ]);

        $location->update($data);

        return redirect()
        ->route('vessel.decks.show', $location->deck_id)
        ->with('success', 'Location updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $this->authorizeLocation($location);
        
            $deckId = $location->deck_id;
            $location->delete();

            return redirect()
                ->route('vessel.decks.show', $deckId)
                ->with('success', 'Location deleted.');
    }

    private function authorizeDeck(Deck $deck)
    {
        if ($deck->vessel_id !== currentVessel()?->id) {
            abort(403);
        }
    }

    private function authorizeLocation(Location $location)
    {
        if ($location->deck->vessel_id !== currentVessel()?->id) {
            abort(403);
        }
    }

}
