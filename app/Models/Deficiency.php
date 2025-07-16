<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Equipment;
use App\Models\WorkOrder;
use App\Models\User;
use App\Models\DeficiencyUpdate;

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
