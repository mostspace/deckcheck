<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interval extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function equipmentIntervals()
    {
        return $this->hasMany(EquipmentInterval::class);
    }

    protected $fillable = [
        'description',
        'category_id',
        'interval',
        'facilitator',
    ];
}
