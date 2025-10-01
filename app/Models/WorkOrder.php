<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $fillable = [
        'equipment_interval_id',
        'due_date',
        'status',
        'assigned_to',
        'completed_at',
        'completed_by',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function equipmentInterval()
    {
        return $this->belongsTo(EquipmentInterval::class);
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function tasks()
    {
        return $this->hasMany(WorkOrderTask::class);
    }

    public function deficiencies()
    {
        return $this->hasMany(Deficiency::class);
    }

    public function deficiency()
    {
        return $this->hasOne(Deficiency::class)->latestOfMany();
    }
}
