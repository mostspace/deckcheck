<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    protected $fillable = [
        'file_id',
        'attachable_id',
        'attachable_type',
        'role',
        'caption',
        'ordering',
        'created_by',
    ];

    protected $casts = [
        'ordering' => 'integer',
    ];

    // Relationships
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordering');
    }

    // Utility methods
    public function getUrlAttribute(): ?string
    {
        return $this->file?->url;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->caption ?: $this->file?->display_name ?: $this->file?->original_name ?? 'Unknown File';
    }

    public function getFileSizeAttribute(): string
    {
        return $this->file?->human_size ?? 'Unknown';
    }

    public function getFileTypeAttribute(): string
    {
        return $this->file?->mime_type ?? 'Unknown';
    }

    public function isImage(): bool
    {
        return $this->file?->isImage() ?? false;
    }

    public function isPdf(): bool
    {
        return $this->file?->isPdf() ?? false;
    }

    // Static methods for easy attachment creation
    public static function attachFile(
        File $file,
        Model $attachable,
        string $role = null,
        string $caption = null,
        int $ordering = 0,
        int $createdById = null
    ): self {
        return static::create([
            'file_id' => $file->id,
            'attachable_id' => $attachable->id,
            'attachable_type' => get_class($attachable),
            'role' => $role,
            'caption' => $caption,
            'ordering' => $ordering,
            'created_by' => $createdById,
        ]);
    }

    // Get the next ordering number for a specific attachable and role
    public static function getNextOrdering($attachable, string $role = null): int
    {
        $query = static::where('attachable_id', $attachable->id)
            ->where('attachable_type', get_class($attachable));
        
        if ($role) {
            $query->where('role', $role);
        }
        
        return $query->max('ordering') + 1;
    }
}
