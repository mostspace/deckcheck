<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Deck;
use App\Models\Equipment;


class Location extends Model
{
    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

    protected $fillable = [
    'name',
    'description',
    'display_order',
    'deck_id',
];
}
 