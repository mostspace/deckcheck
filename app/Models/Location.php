<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
