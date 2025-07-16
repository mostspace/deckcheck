<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vessel;
use App\Models\Interval;
use App\Models\Equipment;

class Category extends Model
{
    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    public function intervals()
    {
        return $this->hasMany(Interval::class);
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

    protected $fillable = [
    'name',
    'vessel_id',
    'type',
    'icon',
    ];

}
