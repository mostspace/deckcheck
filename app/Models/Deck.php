<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vessel;
use App\Models\Location;
use App\Models\Equipment;

class Deck extends Model
{
    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    protected $fillable = [
    'name',
    'display_order',
    'vessel_id',
    ];

    public function locations()
    {
        return $this->hasMany(Location::class)
                    ->orderBy('display_order');
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }
}
