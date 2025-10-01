<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    public function boarding()
    {
        return $this->belongsTo(Boarding::class);
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    protected $fillable = [
        'boarding_id',
        'email',
        'token',
        'expires_at',
        'accepted_at',
        'invited_by',
    ];
}
