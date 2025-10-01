<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    protected $fillable = [
        'name',
        'type',
        'flag',
        'registry_port',
        'build_year',
        'vessel_make',
        'vessel_size',
        'vessel_loa',
        'vessel_lwl',
        'vessel_beam',
        'vessel_draft',
        'vessel_gt',
        'official_number',
        'mmsi_number',
        'imo_number',
        'callsign',
        'hero_photo',
        'dpa_name',
        'dpa_phone',
        'dpa_email',
        'vessel_phone',
        'vessel_email',
        'account_owner',
    ];

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
                'status', 'is_primary', 'is_crew', 'access_level', 'department', 'role', 'crew_number', 'joined_at', 'terminated_at',
            ])
            ->withTimestamps();
    }

    public function activeUsers()
    {
        return $this->belongsToMany(User::class, 'boardings')
            ->where('status', 'active');
    }
}
