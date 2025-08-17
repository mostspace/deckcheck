<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Attachment;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Upload a file
     */
    public function upload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:51200', // 50MB in KB (50 * 1024)
            'vessel_id' => 'required|exists:vessels,id',
            'description' => 'nullable|string|max:500',
            'visibility' => 'nullable|in:private,public',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            
            
            // Debug logging for display name
            if (config('app.debug')) {
                \Log::info('File upload request data', [
                    'display_name' => $request->get('display_name'),
                    'description' => $request->description,
                    'has_display_name' => $request->has('display_name'),
                    'all_request_data' => $request->all()
                ]);
            }
            
            $file = $this->fileUploadService->uploadFile(
                $request->file('file'),
                $request->vessel_id,
                Auth::id(),
                's3_private',
                $request->visibility ?? 'private',
                $request->description,
                null, // customPath
                $request->get('display_name') // displayName
            );

            // Create attachment if attachable information is provided
            $attachment = null;
            if ($request->has('attachable_id') && $request->has('attachable_type') && $request->has('role')) {
                try {
                    // Resolve the class from the string - handle both full class names and aliases
                    $attachableType = $request->attachable_type;
                    $attachableClass = null;
                    
                    // Map common aliases to full class names
                    $classMap = [
                        'Equipment' => 'App\Models\Equipment',
                        'Vessel' => 'App\Models\Vessel',
                        'Task' => 'App\Models\Task',
                        'WorkOrder' => 'App\Models\WorkOrder',
                        'Deficiency' => 'App\Models\Deficiency',
                    ];
                    
                    if (isset($classMap[$attachableType])) {
                        $attachableClass = $classMap[$attachableType];
                    } else {
                        // Try to use the string directly (for full class names)
                        $attachableClass = $attachableType;
                    }
                    
                    if (!class_exists($attachableClass)) {
                        throw new \Exception("Class '{$attachableClass}' not found");
                    }
                    
                    $attachableModel = $attachableClass::find($request->attachable_id);
                    
                    $attachment = \App\Models\Attachment::create([
                        'file_id' => $file->id,
                        'attachable_id' => $request->attachable_id,
                        'attachable_type' => $attachableClass, // Use the resolved class name, not the alias
                        'role' => $request->role,
                        'caption' => $request->caption,
                        'ordering' => \App\Models\Attachment::getNextOrdering(
                            $attachableModel,
                            $request->role
                        ),
                        'created_by' => Auth::id(),
                    ]);
                    

                } catch (\Exception $e) {
                    // Log error silently in production
                }
            }

            return response()->json([
                'success' => true,
                'file' => $file->load('uploadedBy'),
                'attachment' => $attachment,
                'message' => 'File uploaded successfully' . ($attachment ? ' and attached' : '')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload multiple files
     */
    public function uploadMultiple(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|max:51200', // 50MB in KB (50 * 1024)
            'vessel_id' => 'required|exists:vessels,id',
            'visibility' => 'nullable|in:private,public',
        ]);



        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $files = $this->fileUploadService->uploadFiles(
                $request->file('files'),
                $request->vessel_id,
                Auth::id(),
                's3_private',
                $request->visibility ?? 'private'
            );

            // Create attachments if attachable information is provided
            $attachments = [];
            if ($request->has('attachable_id') && $request->has('attachable_type') && $request->has('role')) {
                
                foreach ($files as $file) {
                    try {
                        // Resolve the class from the string - handle both full class names and aliases
                        $attachableType = $request->attachable_type;
                        $attachableClass = null;
                        
                        // Map common aliases to full class names
                        $classMap = [
                            'Equipment' => 'App\Models\Equipment',
                            'Vessel' => 'App\Models\Vessel',
                            'Task' => 'App\Models\Task',
                            'WorkOrder' => 'App\Models\WorkOrder',
                            'Deficiency' => 'App\Models\Deficiency',
                        ];
                        
                        if (isset($classMap[$attachableType])) {
                            $attachableClass = $classMap[$attachableType];
                        } else {
                            // Try to use the string directly (for full class names)
                            $attachableClass = $attachableType;
                        }
                        
                        if (!class_exists($attachableClass)) {
                            throw new \Exception("Class '{$attachableClass}' not found");
                        }
                        
                        $attachableModel = $attachableClass::find($request->attachable_id);
                        
                        $attachment = \App\Models\Attachment::create([
                            'file_id' => $file->id,
                            'attachable_id' => $request->attachable_id,
                            'attachable_type' => $attachableClass, // Use the resolved class name, not the alias
                            'role' => $request->role,
                            'caption' => $request->caption,
                            'ordering' => \App\Models\Attachment::getNextOrdering(
                                $attachableModel,
                                $request->role
                            ),
                            'created_by' => Auth::id(),
                        ]);
                        
                        $attachments[] = $attachment;
                        

                    } catch (\Exception $e) {
                        // Log error silently in production
                    }
                }
            }

            // Load relationships on each file model
            $filesWithRelations = collect($files)->map(function($file) {
                return $file->load('uploadedBy');
            });

            return response()->json([
                'success' => true,
                'files' => $filesWithRelations,
                'attachments' => $attachments,
                'message' => count($files) . ' files uploaded successfully' . (count($attachments) > 0 ? ' and attached' : '')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View a file in the browser
     */
    public function view(File $file)
    {
        // Check if user has access to this file
        if (!$this->canAccessFile($file)) {
            abort(403, 'Access denied');
        }

        if (!$file->exists()) {
            abort(404, 'File not found');
        }

        // For S3 files, we need to stream them properly
        if ($file->disk === 's3_private') {
            // Stream the file from S3 with proper headers
            $stream = Storage::disk($file->disk)->readStream($file->path);
            
            return response()->stream(
                function () use ($stream) {
                    fpassthru($stream);
                },
                200,
                [
                    'Content-Type' => $file->mime_type,
                    'Content-Disposition' => 'inline; filename="' . $file->display_name . '"',
                    'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0'
                ]
            );
        }

        // For local files, get contents and serve
        $contents = $file->getContents();
        if ($contents === null) {
            abort(404, 'File not found');
        }

        return response($contents)
            ->header('Content-Type', $file->mime_type)
            ->header('Content-Disposition', 'inline; filename="' . $file->display_name . '"');
    }

    /**
     * Download a file
     */
    public function download(File $file)
    {
        // Check if user has access to this file
        if (!$this->canAccessFile($file)) {
            abort(403, 'Access denied');
        }

        if (!$file->exists()) {
            abort(404, 'File not found');
        }

        return $file->downloadResponse();
    }

    /**
     * Get file information
     */
    public function show(File $file): JsonResponse
    {
        if (!$this->canAccessFile($file)) {
            abort(403, 'Access denied');
        }

        return response()->json([
            'success' => true,
            'file' => $file->load(['uploadedBy', 'vessel', 'attachments'])
        ]);
    }

    /**
     * Update file information
     */
    public function update(Request $request, File $file): JsonResponse
    {
        if (!$this->canAccessFile($file)) {
            abort(403, 'Access denied');
        }

        $validator = Validator::make($request->all(), [
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'visibility' => 'nullable|in:private,public',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $file->update($request->only(['display_name', 'description', 'visibility']));

        return response()->json([
            'success' => true,
            'file' => $file->fresh(),
            'message' => 'File updated successfully'
        ]);
    }

    /**
     * Delete a file
     */
    public function destroy(File $file): JsonResponse
    {
        if (!$this->canAccessFile($file)) {
            abort(403, 'Access denied');
        }

        try {
            // Delete from storage
            $file->deleteFile();
            
            // Delete the record
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get files for a vessel
     */
    public function vesselFiles(Request $request, int $vesselId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'type' => 'nullable|string',
            'visibility' => 'nullable|in:private,public',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $query = File::forVessel($vesselId)
            ->with(['uploadedBy', 'vessel'])
            ->latest();

        // Apply filters
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('original_name', 'like', '%' . $request->search . '%')
                  ->orWhere('display_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->type) {
            $query->where('mime_type', 'like', $request->type . '%');
        }

        if ($request->visibility) {
            $query->where('visibility', $request->visibility);
        }

        $files = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'files' => $files
        ]);
    }

    /**
     * Check if user can access a file
     */
    protected function canAccessFile(File $file): bool
    {
        $user = Auth::user();
        
        // Admin users can access all files
        if ($user->is_admin) {
            return true;
        }
        
        // Users can access files from their vessel
        if ($user->vessel_id === $file->vessel) {
            return true;
        }
        
        // Users can access their own uploaded files
        if ($user->id === $file->uploaded_by) {
            return true;
        }
        
        return false;
    }

    /**
     * Get upload configuration
     */
    public function config(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'config' => [
                'max_file_size' => $this->fileUploadService->getMaxFileSizeHuman(),
                'max_file_size_bytes' => $this->fileUploadService->getMaxFileSizeHuman(),
                'allowed_types' => $this->fileUploadService->getAllowedExtensions(),
                'storage_disks' => ['s3_private', 's3_public'],
            ]
        ]);
    }
}
