<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boarding extends Model
{
    protected $fillable = [
        'user_id',
        'vessel_id',
        'status',
        'is_primary',
        'is_crew',
        'access_level',
        'department',
        'role',
        'crew_number',
        'joined_at',
        'terminated_at',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

}
