# File Upload System Documentation

## Overview

The file upload system provides a comprehensive, production-ready solution for managing file uploads, storage, and attachments throughout the application. It supports vessel-scoped file storage, polymorphic relationships, and provides an intuitive user interface for file management.

## Architecture

### Core Components

1. **File Model** (`app/Models/File.php`) - Manages individual file records with S3 storage
2. **Attachment Model** (`app/Models/Attachment.php`) - Handles polymorphic relationships between files and models
3. **HasAttachments Trait** (`app/Models/Traits/HasAttachments.php`) - Provides reusable attachment functionality to any model
4. **FileUploadService** (`app/Services/FileUploadService.php`) - Handles file upload logic and validation
5. **FileController** (`app/Http/Controllers/FileController.php`) - Manages file operations and streaming
6. **AttachmentController** (`app/Http/Controllers/AttachmentController.php`) - Manages attachment operations

### Database Structure

- **files** table: Stores file metadata, S3 paths, and vessel scoping
- **attachments** table: Polymorphic pivot table linking files to any model with role-based categorization

## Features

### ✅ **Core Functionality**

- Vessel-scoped file storage with S3 integration
- File deduplication using SHA256 hashing
- Polymorphic relationships for any model
- Role-based categorization (resource, manual, photo, report, etc.)
- File versioning support with soft deletes
- Comprehensive access control and permissions

### ✅ **User Experience**

- Drag & drop file uploads with progress tracking
- Real-time validation and error handling
- Custom display names for uploaded files
- Professional lightbox for media viewing
- Responsive design with Tailwind CSS
- Loading animations and smooth transitions

### ✅ **File Management**

- Automatic file type detection and validation
- Support for all major file formats
- 50MB file size limit (configurable)
- Secure S3 private storage by default
- Public file sharing capabilities

## Configuration

### Storage Disks

The system uses two S3 storage disks configured in `config/filesystems.php`:

- **s3_private**: Default for uploaded files (secure, vessel-scoped, requires authentication)
- **s3_public**: For public files (accessible via direct URL)

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

## Implementation

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

2. **Available trait methods:**

```php
// Single file attachment
$equipment->attachFile($file, $role, $caption, $ordering, $createdById);

// Multiple file attachments
$equipment->attachFiles($files, $role, $caption, $ordering, $createdById);

// Check for attachments
$equipment->hasAttachments();
$equipment->hasAttachmentsByRole('resource');

// Get attachments
$equipment->attachments; // All attachments
$equipment->attachmentsByRole('resource'); // By specific role

// Remove attachments
$equipment->removeAttachment($attachmentId);
$equipment->removeAttachmentsByRole('resource');
$equipment->removeAllAttachments();
```

### File Upload Component

The system includes a comprehensive Blade component for file uploads:

```blade
<x-file-upload
    :model="$equipment"
    modelClass="Equipment"
    role="resource"
    :multiple="false"
    accept="*/*"
    maxFileSize="52428800"
    label="Upload Resource"
/>
```

**Component Props:**

- `model`: The model instance to attach files to
- `modelClass`: The class name for the model (used in API calls)
- `role`: The role/category for the uploaded files
- `multiple`: Whether to allow multiple file selection
- `accept`: File type restrictions
- `maxFileSize`: Maximum file size in bytes
- `label`: Display label for the upload area

### Displaying Attachments

The system provides a dedicated component for displaying attachments:

```blade
<x-attachment-card
    :attachment="$attachment"
    :showActions="true"
/>
```

**Features:**

- File type icons based on MIME type
- Display name and file size
- Upload date and uploader information
- Action buttons (view, download, delete)
- Responsive design with hover effects

## API Endpoints

### File Management

```
POST   /files/upload              # Upload single file with optional attachment creation
POST   /files/upload-multiple     # Upload multiple files
GET    /files/{file}              # Get file metadata
GET    /files/{file}/view         # Stream file content for inline viewing
GET    /files/{file}/download     # Download file
PUT    /files/{file}              # Update file metadata
DELETE /files/{file}              # Delete file and all attachments
GET    /files/config              # Get upload configuration (limits, types)
GET    /files/vessel/{id}         # Get vessel files
```

### Attachment Management

```
POST   /attachments               # Create attachment
PUT    /attachments/{id}          # Update attachment
DELETE /attachments/{id}          # Delete attachment
POST   /attachments/reorder       # Reorder attachments
GET    /attachments/{type}/{id}   # Get model attachments
```

## File Viewing System

### **🎯 Lightbox Implementation**

The system includes a professional lightbox for viewing media files:

**Features:**

- **Instant Loading**: Shows loading spinner immediately while content loads
- **Responsive Design**: Content constrained to `max-w-4xl max-h-[70vh]`
- **Multiple Close Methods**: ESC key, click outside, X button
- **Loading States**: Progressive loading with smooth transitions
- **Error Handling**: Graceful fallbacks for failed loads
- **Metadata Display**: File name, upload date, uploader, file size

**Supported Media Types:**

- **Images**: JPEG, PNG, GIF, WebP, SVG (with onload detection)
- **Videos**: MP4, WebM, AVI (with onloadeddata detection)
- **Audio**: MP3, WAV, OGG (with oncanplay detection)

### **📄 Document Viewing**

- **PDFs**: Open in new tab for optimal viewing
- **Other Documents**: Streamed content with appropriate headers
- **Private Files**: Secure streaming via Laravel with authentication

## Equipment Show Page Integration

### **🔧 Complete Implementation**

The file upload system is fully integrated into the equipment show page:

**Resources Section:**

- Dynamic display of existing attachments
- "Add Resource" button with side-out modal
- File upload with drag & drop support
- Custom display names and descriptions
- Real-time validation and progress tracking

**Modal Features:**

- Clean, professional design
- File input with type restrictions
- Display name field (saves to database)
- Description field for additional context
- Automatic role assignment ("resource")
- Error handling and success feedback

**Attachment Display:**

- File type icons and metadata
- Action buttons for each attachment
- Responsive grid layout
- Hover effects and animations

## File Types and Limits

### Supported File Types

- **Documents**: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV
- **Images**: JPEG, PNG, GIF, WebP, SVG, BMP, TIFF
- **Videos**: MP4, WebM, AVI, MOV, WMV, FLV
- **Audio**: MP3, WAV, OGG, AAC, FLAC
- **Archives**: ZIP, RAR, 7Z, TAR, GZ
- **CAD/Technical**: DXF, DWG, DWF, STL, OBJ

### File Size Limits

- **Default**: 50MB per file
- **Configurable**: Via FileUploadService `$maxFileSize` property
- **Validation**: Both frontend and backend validation

## Security Features

### Access Control

- **Vessel Scoping**: All files are scoped to specific vessels
- **User Permissions**: Upload and delete permissions checked
- **Admin Override**: System administrators have full access
- **File Ownership**: Users can only manage their own uploads

### File Validation

- **MIME Type Validation**: Strict file type checking
- **File Size Limits**: Configurable maximum file sizes
- **Extension Whitelisting**: Only allowed file extensions
- **SHA256 Verification**: File integrity checking
- **Virus Scanning**: Ready for integration with security services

## Best Practices

### File Organization

1. **Use Descriptive Roles**: `resource`, `manual`, `photo`, `report`, `certificate`
2. **Meaningful Display Names**: Help users identify files quickly
3. **Vessel Isolation**: Maintain data separation between vessels
4. **Appropriate Visibility**: Private for sensitive files, public for sharing

### Performance

1. **Lazy Loading**: Attachments loaded only when needed
2. **Eager Loading**: Use `with('attachments.file')` for lists
3. **Pagination**: Implement for large file collections
4. **Caching**: Cache frequently accessed file metadata

### Error Handling

1. **User Feedback**: Clear error messages and success confirmations
2. **Graceful Degradation**: Fallbacks for failed operations
3. **Logging**: Comprehensive error logging for debugging
4. **Validation**: Frontend and backend validation consistency

## Troubleshooting

### Common Issues

1. **File Upload Fails**
   - Check file size limits (50MB default)
   - Verify file type is supported
   - Ensure S3 credentials are correct
   - Check vessel access permissions

2. **Files Not Displaying**
   - Verify `attachable_type` is full class name (not alias)
   - Check vessel scoping and permissions
   - Ensure attachments are properly linked
   - Verify file exists in S3 storage

3. **Lightbox Issues**
   - Check browser console for JavaScript errors
   - Verify file streaming endpoints are working
   - Check S3 file permissions and accessibility
   - Ensure proper MIME type headers

### Debugging

1. **Laravel Logs**: Check `storage/logs/laravel.log` for backend errors
2. **Browser Console**: Look for JavaScript errors and network issues
3. **Database Records**: Verify file and attachment records exist
4. **S3 Connectivity**: Test bucket access and file permissions

## Examples

### Equipment Resource Upload

```php
// In EquipmentController
public function show(Equipment $equipment)
{
    $equipment->load('attachments.file');
    return view('inventory.equipment.show', compact('equipment'));
}
```

```blade
<!-- In equipment show view -->
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Resources</h3>
        <button onclick="openResourceModal()" class="btn-primary">
            Add Resource
        </button>
    </div>

    @if($equipment->hasAttachments())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($equipment->attachments as $attachment)
                <x-attachment-card :attachment="$attachment" />
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center py-8">No resources yet</p>
    @endif
</div>
```

### File Upload Modal

```blade
<!-- Resource creation modal -->
<div id="resourceCreateModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Add Resource</h3>

                <x-file-upload
                    :model="$equipment"
                    modelClass="Equipment"
                    role="resource"
                    :multiple="false"
                    accept="*/*"
                    label="Choose File"
                />

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Display Name</label>
                    <input type="text" id="resourceDisplayName" class="form-input w-full mt-1" placeholder="Enter a display name">
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="resourceDescription" class="form-textarea w-full mt-1" rows="3" placeholder="Optional description"></textarea>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closeResourceModal()" class="btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
```

## Future Enhancements

### **🚀 Planned Features**

- [ ] **Image Thumbnails**: Automatic thumbnail generation for images
- [ ] **File Previews**: In-browser preview for documents
- [ ] **Advanced Search**: Full-text search across file contents
- [ ] **File Sharing**: Secure sharing with expiration dates
- [ ] **Version Control**: Track file changes and rollbacks
- [ ] **Bulk Operations**: Mass upload, download, and deletion
- [ ] **Storage Analytics**: Usage tracking and cost monitoring

### **🔧 Technical Improvements**

- [ ] **CDN Integration**: Global file distribution
- [ ] **Image Optimization**: Automatic compression and resizing
- [ ] **Video Transcoding**: Multiple format support
- [ ] **Backup Automation**: Scheduled backups to secondary storage
- [ ] **API Rate Limiting**: Prevent abuse and ensure performance

## Conclusion

The file upload system provides a robust, user-friendly foundation for file management throughout the application. With its comprehensive feature set, security measures, and professional user interface, it's ready for production use and future enhancements.

The system successfully addresses all MVP requirements:

- ✅ File uploads with S3 storage
- ✅ Vessel-scoped file management
- ✅ Polymorphic relationships
- ✅ Professional lightbox for media viewing
- ✅ Complete equipment page integration
- ✅ Responsive design and smooth UX
- ✅ Comprehensive error handling and validation
