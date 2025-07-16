<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Equipment;
use App\Models\Interval;
use App\Models\WorkOrder;

class EquipmentInterval extends Model
{
    protected $fillable = [
        'equipment_id',
        'interval_id',
        'description',
        'facilitator',
        'frequency',
        'frequency_interval',
        'first_completed_at',
        'last_completed_at',
        'next_due_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'first_completed_at' => 'date',
        'last_completed_at' => 'date',
        'next_due_date' => 'date',
    ];

    // Relationships
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function interval()
    {
        return $this->belongsTo(Interval::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}
