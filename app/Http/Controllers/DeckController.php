<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Deck;
use Illuminate\Http\Request;

class DeckController extends Controller
{
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
    public function show(Deck $deck)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deck $deck)
    {
        // Check if user has access to this deck's vessel
        if (! auth()->user()->hasSystemAccessToVessel($deck->vessel)) {
            abort(403, 'Access denied to this deck');
        }

        return view('vessel.decks.edit', compact('deck'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deck $deck)
    {
        // Check if user has access to this deck's vessel
        if (! auth()->user()->hasSystemAccessToVessel($deck->vessel)) {
            abort(403, 'Access denied to this deck');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $deck->update($data);

        return redirect()->route('vessel.deckplan')->with('success', 'Deck updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deck $deck)
    {
        // Check if user has access to this deck's vessel
        if (! auth()->user()->hasSystemAccessToVessel($deck->vessel)) {
            abort(403, 'Access denied to this deck');
        }

        $deck->delete();

        return redirect()->route('vessel.deckplan')->with('success', 'Deck deleted.');
    }

    public function locations(Deck $deck)
    {
        // Check if user has access to this deck's vessel
        if (! auth()->user()->hasSystemAccessToVessel($deck->vessel)) {
            abort(403, 'Access denied to this deck');
        }

        return response()->json(
            $deck->locations()->select('id', 'name')->get()
        );
    }
}
