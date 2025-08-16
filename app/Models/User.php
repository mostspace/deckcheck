<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Vessel;
use App\Models\WorkOrder;
use App\Models\Deficiency;
use App\Models\DeficiencyUpdate;
use App\Models\Invitation;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'profile_pic',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'date_of_birth',
        'nationality',
        'is_beta_user',
        'is_test_user',
        'has_completed_onboarding',
        'accepts_marketing',
        'accepts_updates',
        'agreed_at',
        'agreed_ip',
        'agreed_user_agent',
        'terms_version',
    
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth'     => 'date',
            'password'          => 'hashed',
        ];
    }

    /*
    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }
    */

    public function workOrders()
    {
        return $this-> hasMany(WorkOrder::class);
    }

    public function assignedWorkOrders()
    {
        return $this->hasMany(WorkOrder::class, 'assigned_to');
    }

    public function deficiencies()
    {
        return $this->hasMany(Deficiency::class);
    }

    public function resolvedDeficiencies()
    {
        return $this->hasMany(Deficiency::class, 'resolved_by');
    }

    public function deficiencyUpdates()
    {
        return $this->hasMany(DeficiencyUpdate::class);
    }

    public function boardings()
    {
        return $this->hasMany(Boarding::class);
    }

    public function vessels()
    {
        return $this->belongsToMany(Vessel::class, 'boardings')
            ->withPivot([
                'id',
                'status', 'is_primary', 'is_crew', 'access_level', 'department', 'role', 'crew_number', 'joined_at', 'terminated_at'
            ])
            ->withTimestamps();
    }

    public function activeBoarding()
    {
        return $this->boardings()->where('is_primary', true)->first();
    }

    public function allActiveBoardings()
    {
        return $this->boardings()->where('status', 'active');
    }

    public function activeVessel()
    {
        return $this->activeBoarding()?->vessel;
    }

    public function hasSystemAccess(string $level): bool
    {
        return $this->system_role === $level;
    }

    public function hasSystemAccessToVessel(Vessel $vessel): bool
    {
        // System admins/staff have access to all vessels
        if (in_array($this->system_role, ['superadmin', 'staff', 'dev'])) {
            \Log::info('System user vessel access granted', [
                'user_id' => $this->id,
                'user_role' => $this->system_role,
                'vessel_id' => $vessel->id
            ]);
            return true;
        }
        
        // Regular users need explicit boarding records
        $hasAccess = $this->boardings()
            ->where('vessel_id', $vessel->id)
            ->where('status', 'active')
            ->exists();
            
        \Log::info('Regular user vessel access check', [
            'user_id' => $this->id,
            'user_role' => $this->system_role,
            'vessel_id' => $vessel->id,
            'has_access' => $hasAccess
        ]);
        
        return $hasAccess;
    }

    public function getAccessibleVessels()
    {
        if (in_array($this->system_role, ['superadmin', 'staff', 'dev'])) {
            // System users can access all vessels
            $vessels = Vessel::all();
            \Log::info('System user accessible vessels', [
                'user_id' => $this->id,
                'user_role' => $this->system_role,
                'vessels_count' => $vessels->count()
            ]);
            return $vessels;
        }
        
        // Regular users only get vessels they're boarded on
        $vessels = $this->vessels()->where('status', 'active')->get();
        \Log::info('Regular user accessible vessels', [
            'user_id' => $this->id,
            'user_role' => $this->system_role,
            'vessels_count' => $vessels->count()
        ]);
        return $vessels;
    }

    public function sentInvitations()
    {
        return $this->hasMany(Invitation::class, 'invited_by');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

}

 