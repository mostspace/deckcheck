<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Equipment;
use App\Models\Task;

class ApplicableEquipment extends Model
{
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    protected $fillable = [
    'task_id',
    'equipment_id',
    ];

}
