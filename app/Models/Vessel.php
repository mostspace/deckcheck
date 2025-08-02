<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Deck;
use App\Models\Category;
use App\Models\Equipment;


class Vessel extends Model
{
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'account_owner');
    }

    public function decks()
    {
        return $this->hasMany(Deck::class)
                    ->orderBy('display_order');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

    /*
    // What was this for? Inaccurate relationship bindings
    public function workOrders()
    {
        return $this->hasManyThrough(
            \App\Models\WorkOrder::class,
            \App\Models\Equipment::class,
            'vessel_id',   // FK on equipment table...
            'equipment_id' // FK on equipment table...
        );
    }
        */

    public function boardings()
    {
        return $this->hasMany(Boarding::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'boardings')
            ->withPivot([
                'status', 'is_primary', 'is_crew', 'access_level', 'department', 'role', 'crew_number', 'joined_at', 'terminated_at'
            ])
            ->withTimestamps();
    }

    public function activeUsers()
    {
        return $this->belongsToMany(User::class, 'boardings')
            ->where('status', 'active');
    }


}

