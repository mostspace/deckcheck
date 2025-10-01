<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasAttachments;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasAttachments;

    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function intervals()
    {
        return $this->hasMany(EquipmentInterval::class);
    }

    public function workOrders()
    {
        return $this->hasManyThrough(WorkOrder::class, EquipmentInterval::class);
    }

    public function deficiencies()
    {
        return $this->hasMany(Deficiency::class);
    }

    protected $fillable = [
        'vessel_id',
        'category_id',
        'deck_id',
        'location_id',
        'internal_id',
        'name',
        'manufacturer',
        'model',
        'serial_number',
        'preferred_vendor',
        'hero_photo',
        'comments',
        'attributes_json',
        'manufacturing_date',
        'purchase_date',
        'expiry_date',
        'in_service',
        'removed_from_service',
        'status',
    ];

    protected $casts = [
        'manufacturing_date' => 'date',
        'purchase_date' => 'date',
        'expiry_date' => 'date',
        'in_service' => 'date',
        'removed_from_service' => 'date',
        'attributes_json' => 'array',
    ];
}
