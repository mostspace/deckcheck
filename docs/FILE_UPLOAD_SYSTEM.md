# File Upload System Documentation

## Overview

The file upload system provides a comprehensive solution for managing file uploads, storage, and attachments throughout the application. It supports vessel-scoped file storage, polymorphic relationships, and role-based categorization.

## Architecture

### Core Components

1. **File Model** (`app/Models/File.php`) - Manages individual file records
2. **Attachment Model** (`app/Models/Attachment.php`) - Handles polymorphic relationships
3. **HasAttachments Trait** (`app/Models/Traits/HasAttachments.php`) - Provides attachment functionality to models
4. **FileUploadService** (`app/Services/FileUploadService.php`) - Handles file upload logic
5. **FileController** (`app/Http/Controllers/FileController.php`) - Manages file operations
6. **AttachmentController** (`app/Http/Controllers/AttachmentController.php`) - Manages attachment operations

### Database Structure

- **files** table: Stores file metadata and storage information
- **attachments** table: Polymorphic pivot table linking files to models

## Features

- ✅ Vessel-scoped file storage
- ✅ S3 storage support (public and private buckets)
- ✅ File deduplication using SHA256 hashing
- ✅ Polymorphic relationships for any model
- ✅ Role-based categorization (manual, photo, report, etc.)
- ✅ File versioning support
- ✅ Soft deletes for data retention
- ✅ Access control and permissions
- ✅ Drag & drop file uploads
- ✅ Progress tracking and error handling

## Configuration

### Storage Disks

The system uses two S3 storage disks:

- **s3_private**: Default for uploaded files (secure, vessel-scoped)
- **s3_public**: For public files (accessible via URL)

### Environment Variables

```bash
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=your_region
AWS_BUCKET=your_bucket_name
AWS_URL=your_s3_url
AWS_ENDPOINT=your_endpoint
AWS_USE_PATH_STYLE_ENDPOINT=false
```

## Usage

### Adding File Attachments to Models

1. **Use the HasAttachments trait:**

```php
use App\Models\Traits\HasAttachments;

class Equipment extends Model
{
    use HasAttachments;
    
    // ... existing code
}
```

2. **Upload files using the trait methods:**

```php
// Single file upload
$attachment = $equipment->attachFile(
    $request->file('manual'),
    'manual',
    'User Manual v2.1',
    's3_private',
    'private',
    'Equipment operation manual'
);

// Multiple file uploads
$attachments = $equipment->attachFiles(
    $request->file('photos'),
    'photo',
    's3_private',
    'private'
);
```

### File Upload Component

Use the Blade component for easy file uploads:

```blade
<!-- Single file upload -->
<x-file-upload 
    :model="$equipment"
    role="manual"
    accept=".pdf,.doc,.docx"
    label="Upload Manual"
/>

<!-- Multiple file upload -->
<x-file-upload 
    :model="$equipment"
    role="photo"
    :multiple="true"
    accept="image/*"
    label="Upload Photos"
/>
```

### Displaying Attachments

```blade
<!-- Show all attachments -->
@if($equipment->hasAttachments())
    @foreach($equipment->attachments as $attachment)
        <x-attachment-card :attachment="$attachment" />
    @endforeach
@endif

<!-- Show attachments by role -->
@if($equipment->hasAttachmentsByRole('manual'))
    @foreach($equipment->attachmentsByRole('manual') as $attachment)
        <x-attachment-card :attachment="$attachment" />
    @endforeach
@endif
```

## API Endpoints

### File Management

```
POST   /files/upload              # Upload single file
POST   /files/upload-multiple     # Upload multiple files
GET    /files/config              # Get upload configuration
GET    /files/vessel/{id}         # Get vessel files
GET    /files/{file}              # Get file info
PUT    /files/{file}              # Update file
DELETE /files/{file}              # Delete file
GET    /files/{file}/download     # Download file
```

### Attachment Management

```
POST   /attachments               # Create attachment
PUT    /attachments/{id}          # Update attachment
DELETE /attachments/{id}          # Delete attachment
POST   /attachments/reorder       # Reorder attachments
GET    /attachments/{type}/{id}   # Get model attachments
```

## File Types and Limits

### Supported File Types

- **Documents**: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV
- **Images**: JPEG, PNG, GIF, WebP, SVG
- **Archives**: ZIP, RAR, 7Z
- **CAD/Technical**: DXF, DWG, DWF

### File Size Limits

- **Default**: 50MB per file
- **Configurable**: Via FileUploadService

## Security Features

### Access Control

- Vessel-scoped file access
- User permission checks
- Admin override capabilities
- File ownership validation

### File Validation

- MIME type validation
- File size limits
- Extension whitelisting
- SHA256 hash verification

## Best Practices

### File Organization

1. **Use descriptive roles** for categorization (manual, photo, report, certificate)
2. **Provide meaningful captions** for better searchability
3. **Organize by vessel** to maintain data isolation
4. **Use appropriate visibility** settings (private vs public)

### Performance

1. **Lazy load attachments** when displaying lists
2. **Use pagination** for large file collections
3. **Implement caching** for frequently accessed files
4. **Monitor storage usage** and implement cleanup policies

### Error Handling

1. **Validate file types** before upload
2. **Handle upload failures** gracefully
3. **Provide user feedback** for all operations
4. **Log errors** for debugging

## Troubleshooting

### Common Issues

1. **File upload fails**
   - Check file size limits
   - Verify file type is supported
   - Ensure S3 credentials are correct

2. **Files not displaying**
   - Check file permissions
   - Verify vessel access
   - Ensure attachments are properly linked

3. **Storage quota exceeded**
   - Implement file cleanup policies
   - Consider archiving old files
   - Monitor storage usage

### Debugging

1. **Check Laravel logs** for error details
2. **Verify database records** for file/attachment data
3. **Test S3 connectivity** and permissions
4. **Validate file paths** and storage configuration

## Examples

### Equipment Manual Upload

```php
// In EquipmentController
public function uploadManual(Request $request, Equipment $equipment)
{
    $request->validate([
        'manual' => 'required|file|mimes:pdf,doc,docx|max:10240'
    ]);

    $attachment = $equipment->attachFile(
        $request->file('manual'),
        'manual',
        'Equipment Operation Manual',
        's3_private',
        'private',
        'Complete operation and maintenance manual'
    );

    return response()->json([
        'success' => true,
        'attachment' => $attachment
    ]);
}
```

### Photo Gallery

```php
// In EquipmentController
public function uploadPhotos(Request $request, Equipment $equipment)
{
    $request->validate([
        'photos.*' => 'required|image|max:5120'
    ]);

    $attachments = $equipment->attachFiles(
        $request->file('photos'),
        'photo',
        's3_private',
        'private'
    );

    return response()->json([
        'success' => true,
        'count' => count($attachments)
    ]);
}
```

## Future Enhancements

- [ ] Image thumbnail generation
- [ ] File preview capabilities
- [ ] Advanced search and filtering
- [ ] File sharing and collaboration
- [ ] Automated backup and archiving
- [ ] Integration with external storage providers
- [ ] File analytics and usage tracking
