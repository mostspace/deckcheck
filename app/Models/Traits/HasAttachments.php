<?php

namespace App\Models\Traits;

use App\Models\Attachment;
use App\Models\File;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;

trait HasAttachments
{
    /**
     * Get all attachments for this model
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Get attachments by role
     */
    public function attachmentsByRole(string $role): MorphMany
    {
        return $this->attachments()->byRole($role)->ordered();
    }

    /**
     * Get a specific attachment by role
     */
    public function attachmentByRole(string $role): ?Attachment
    {
        return $this->attachmentsByRole($role)->first();
    }

    /**
     * Attach a file to this model
     */
    public function attachFile(
        UploadedFile $uploadedFile,
        ?string $role = null,
        ?string $caption = null,
        string $disk = 's3_private',
        string $visibility = 'private',
        ?string $description = null
    ): Attachment {
        // Create the file record
        $file = File::createFromUpload(
            $uploadedFile,
            $this->vessel_id ?? $this->vessel?->id,
            auth()->id(),
            $disk,
            $visibility,
            $description
        );

        // Get the next ordering number
        $ordering = Attachment::getNextOrdering($this, $role);

        // Create the attachment
        return Attachment::attachFile(
            $file,
            $this,
            $role,
            $caption,
            $ordering,
            auth()->id()
        );
    }

    /**
     * Attach multiple files to this model
     */
    public function attachFiles(
        array $uploadedFiles,
        ?string $role = null,
        string $disk = 's3_private',
        string $visibility = 'private'
    ): array {
        $attachments = [];
        
        foreach ($uploadedFiles as $uploadedFile) {
            $attachments[] = $this->attachFile(
                $uploadedFile,
                $role,
                null,
                $disk,
                $visibility
            );
        }
        
        return $attachments;
    }

    /**
     * Remove an attachment
     */
    public function removeAttachment(int $attachmentId): bool
    {
        $attachment = $this->attachments()->find($attachmentId);
        
        if ($attachment) {
            // Delete the file from storage
            $attachment->file->deleteFile();
            
            // Delete the attachment record
            $attachment->delete();
            
            return true;
        }
        
        return false;
    }

    /**
     * Remove all attachments by role
     */
    public function removeAttachmentsByRole(string $role): int
    {
        $attachments = $this->attachmentsByRole($role)->get();
        $count = 0;
        
        foreach ($attachments as $attachment) {
            if ($this->removeAttachment($attachment->id)) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Remove all attachments
     */
    public function removeAllAttachments(): int
    {
        $attachments = $this->attachments()->get();
        $count = 0;
        
        foreach ($attachments as $attachment) {
            if ($this->removeAttachment($attachment->id)) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Check if model has attachments
     */
    public function hasAttachments(): bool
    {
        return $this->attachments()->exists();
    }

    /**
     * Check if model has attachments by role
     */
    public function hasAttachmentsByRole(string $role): bool
    {
        return $this->attachmentsByRole($role)->exists();
    }

    /**
     * Get attachment count
     */
    public function getAttachmentCountAttribute(): int
    {
        return $this->attachments()->count();
    }

    /**
     * Get attachment count by role
     */
    public function getAttachmentCountByRoleAttribute(string $role): int
    {
        return $this->attachmentsByRole($role)->count();
    }
}
