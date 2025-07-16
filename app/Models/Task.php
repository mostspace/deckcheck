<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Interval;
use App\Models\Equipment;
use App\Models\Task;
use App\Models\ApplicableEquipment;

class Task extends Model
{
    public function interval()
    {
        return $this->belongsTo(Interval::class);
    }

    public function applicableEquipment()
    {
        return $this->hasMany(ApplicableEquipment::class, 'task_id');
    }

    public function equipment() {
        return $this->belongsToMany(Equipment::class, 'applicable_equipment', 'task_id', 'equipment_id');
    }


    protected $fillable = [
    'interval_id',
    'description',
    'instructions',
    'applicable_to',
    'display_order',
    'applicability_conditions',
    ];
}
