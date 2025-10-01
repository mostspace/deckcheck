<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderTask extends Model
{
    protected $fillable = [
        'work_order_id',
        'name',
        'instructions',
        'sequence_position',
        'completed_at',
        'completed_by',
        'status',
        'is_flagged',
        'notes',
    ];

    protected $casts = [
        'is_flagged' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
