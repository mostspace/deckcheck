<?php

namespace App\Services;

use App\Models\File;
use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FileUploadService
{
    /**
     * Allowed file types
     */
    protected array $allowedMimeTypes = [
        // Documents
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'text/csv',
        
        // Images
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
        
        // Archives
        'application/zip',
        'application/x-rar-compressed',
        'application/x-7z-compressed',
        
        // CAD/Technical
        'application/dxf',
        'application/dwg',
        'model/vnd.dwf',
    ];

    /**
     * Maximum file size in bytes (default: 50MB)
     */
    protected int $maxFileSize = 50 * 1024 * 1024; // 50MB in bytes

    /**
     * Upload a file and create File record
     */
    public function uploadFile(
        UploadedFile $uploadedFile,
        int $vesselId,
        int $uploadedById,
        string $disk = 's3_private',
        string $visibility = 'private',
        ?string $description = null,
        ?string $customPath = null
    ): File {
        // File validation is now handled in the controller

        // Generate storage path
        $path = $customPath ?: $this->generateStoragePath($uploadedFile, $vesselId);

        // Store the file
        try {
            $storedPath = $uploadedFile->store($path, $disk);
        } catch (\Exception $e) {
            // If S3 fails, fall back to local storage for testing
            if (config('app.debug') && $disk !== 'local') {
                \Log::warning('S3 storage failed, falling back to local storage', [
                    'disk' => $disk,
                    'error' => $e->getMessage(),
                    'falling_back_to' => 'local'
                ]);
                $storedPath = $uploadedFile->store($path, 'local');
            } else {
                throw $e;
            }
        }

        // Calculate SHA256 hash for deduplication
        $sha256 = hash_file('sha256', $uploadedFile->getRealPath());

        // Check for existing file with same hash and size
        $existingFile = File::where('sha256', $sha256)
            ->where('size', $uploadedFile->getSize())
            ->where('vessel', $vesselId)
            ->first();

        if ($existingFile) {
            // File already exists, return existing record
            return $existingFile;
        }

        // Create new file record
        return File::create([
            'disk' => $disk,
            'path' => $storedPath,
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

    /**
     * Upload multiple files
     */
    public function uploadFiles(
        array $uploadedFiles,
        int $vesselId,
        int $uploadedById,
        string $disk = 's3_private',
        string $visibility = 'private'
    ): array {
        $files = [];
        
        foreach ($uploadedFiles as $uploadedFile) {
            $files[] = $this->uploadFile(
                $uploadedFile,
                $vesselId,
                $uploadedById,
                $disk,
                $visibility
            );
        }
        
        return $files;
    }

    /**
     * Validate uploaded file
     */
    protected function validateFile(UploadedFile $uploadedFile): void
    {
        // Debug logging
        if (config('app.debug')) {
            \Log::info('File validation', [
                'file_size' => $uploadedFile->getSize(),
                'max_size_bytes' => $this->maxFileSize,
                'max_size_mb' => (int) ($this->maxFileSize / (1024 * 1024)),
                'file_name' => $uploadedFile->getClientOriginalName(),
                'php_upload_max_filesize' => ini_get('upload_max_filesize'),
                'php_post_max_size' => ini_get('post_max_size'),
                'php_max_file_uploads' => ini_get('max_file_uploads')
            ]);
        }
        
        // Check file size directly first
        if ($uploadedFile->getSize() > $this->maxFileSize) {
            throw new ValidationException(
                Validator::make([], [])->errors()->add(
                    'file', 
                    "File size ({$this->formatFileSize($uploadedFile->getSize())}) exceeds maximum allowed size ({$this->formatFileSize($this->maxFileSize)})"
                )
            );
        }
        
        $validator = Validator::make(['file' => $uploadedFile], [
            'file' => [
                'required',
                'file',
                'mimes:' . implode(',', $this->getAllowedExtensions()),
            ],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Generate storage path for file
     */
    protected function generateStoragePath(UploadedFile $uploadedFile, int $vesselId): string
    {
        $date = now()->format('Y/m/d');
        $extension = $uploadedFile->getClientOriginalExtension();
        
        return "vessels/{$vesselId}/uploads/{$date}";
    }

    /**
     * Get allowed file extensions from MIME types
     */
    protected function getAllowedExtensions(): array
    {
        $extensions = [];
        
        foreach ($this->allowedMimeTypes as $mimeType) {
            $extension = $this->getExtensionFromMimeType($mimeType);
            if ($extension) {
                $extensions[] = $extension;
            }
        }
        
        return array_unique($extensions);
    }

    /**
     * Get file extension from MIME type
     */
    protected function getExtensionFromMimeType(string $mimeType): ?string
    {
        $mimeToExtension = [
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'text/plain' => 'txt',
            'text/csv' => 'csv',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
            'application/zip' => 'zip',
            'application/x-rar-compressed' => 'rar',
            'application/x-7z-compressed' => '7z',
            'application/dxf' => 'dxf',
            'application/dwg' => 'dwg',
            'model/vnd.dwf' => 'dwf',
        ];
        
        return $mimeToExtension[$mimeType] ?? null;
    }

    /**
     * Set allowed MIME types
     */
    public function setAllowedMimeTypes(array $mimeTypes): self
    {
        $this->allowedMimeTypes = $mimeTypes;
        return $this;
    }

    /**
     * Set maximum file size
     */
    public function setMaxFileSize(int $bytes): self
    {
        $this->maxFileSize = $bytes;
        return $this;
    }

    /**
     * Format file size in human readable format
     */
    protected function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get maximum file size in human readable format
     */
    public function getMaxFileSizeHuman(): string
    {
        return $this->formatFileSize($this->maxFileSize);
    }
}
