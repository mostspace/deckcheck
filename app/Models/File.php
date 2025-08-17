<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'disk',
        'path',
        'original_name',
        'display_name',
        'description',
        'version',
        'is_latest',
        'mime_type',
        'size',
        'sha256',
        'uploaded_by',
        'vessel',
        'visibility',
    ];

    protected $casts = [
        'is_latest' => 'boolean',
        'size' => 'integer',
        'version' => 'integer',
    ];

    // Relationships
    public function vessel(): BelongsTo
    {
        return $this->belongsTo(Vessel::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    // Scopes
    public function scopeForVessel(Builder $query, $vesselId): void
    {
        $query->where('vessel', $vesselId);
    }

    public function scopePublic(Builder $query): void
    {
        $query->where('visibility', 'public');
    }

    public function scopePrivate(Builder $query): void
    {
        $query->where('visibility', 'private');
    }

    public function scopeLatest(Builder $query): void
    {
        $query->where('is_latest', true);
    }

    // File handling methods
    public function getUrlAttribute(): ?string
    {
        if ($this->disk === 's3_public') {
            return Storage::disk($this->disk)->url($this->path);
        }
        
        return null; // Private files don't have public URLs
    }

    public function getFullPathAttribute(): string
    {
        return Storage::disk($this->disk)->path($this->path);
    }

    public function exists(): bool
    {
        return Storage::disk($this->disk)->exists($this->path);
    }

    public function deleteFile(): bool
    {
        if ($this->exists()) {
            return Storage::disk($this->disk)->delete($this->path);
        }
        
        return true;
    }

    public function getContents(): ?string
    {
        if ($this->exists()) {
            return Storage::disk($this->disk)->get($this->path);
        }
        
        return null;
    }

    public function downloadResponse()
    {
        if ($this->exists()) {
            return Storage::disk($this->disk)->download($this->path, $this->original_name);
        }
        
        abort(404);
    }

    // Static methods for file creation
    public static function createFromUpload(
        UploadedFile $uploadedFile,
        int $vesselId,
        int $uploadedById,
        string $disk = 's3_private',
        string $visibility = 'private',
        ?string $description = null
    ): self {
        $path = $uploadedFile->store('vessels/' . $vesselId . '/uploads', $disk);
        $sha256 = hash_file('sha256', $uploadedFile->getRealPath());
        
        return static::create([
            'disk' => $disk,
            'path' => $path,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'display_name' => $uploadedFile->getClientOriginalName(),
            'description' => $description,
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'sha256' => $sha256,
            'uploaded_by' => $uploadedById,
            'vessel' => $vesselId,
            'visibility' => $visibility,
            'version' => 1,
            'is_latest' => true,
        ]);
    }

    // Check if file is an image
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    // Check if file is a PDF
    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    // Get file extension
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }

    // Get human readable file size
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
