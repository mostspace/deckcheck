<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeficiencyUpdate extends Model
{
    public function deficiency()
    {
        return $this->belongsTo(Deficiency::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected $fillable = [
        'display_id',
        'deficiency_id',
        'created_by',
        'comment',
        'previous_status',
        'new_status',
        'previous_priority',
        'new_priority',
        'new_assignee',
    ];
}
