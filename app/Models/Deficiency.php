<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deficiency extends Model
{
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function updates()
    {
        return $this->hasMany(DeficiencyUpdate::class)->latest();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($deficiency) {
            if (empty($deficiency->display_id)) {
                // Get the vessel from the equipment
                $vessel = $deficiency->equipment->vessel;

                // Find the highest display_id for this vessel and increment
                $lastDeficiency = static::whereHas('equipment', function ($query) use ($vessel) {
                    $query->where('vessel_id', $vessel->id);
                })->orderByRaw('CAST(display_id AS UNSIGNED) DESC')->first();

                if ($lastDeficiency && is_numeric($lastDeficiency->display_id)) {
                    $deficiency->display_id = (int) $lastDeficiency->display_id + 1;
                } else {
                    $deficiency->display_id = 1;
                }
            }
        });
    }

    protected $fillable = [
        'display_id',
        'equipment_id',
        'work_order_id',
        'opened_by',
        'resolved_by',
        'assigned_to',
        'subject',
        'description',
        'priority',
        'status',
        'resolved_at',
        'detail',
    ];
}
