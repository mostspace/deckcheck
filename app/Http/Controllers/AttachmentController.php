<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttachmentController extends Controller
{
    /**
     * Create an attachment
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file_id' => 'required|exists:files,id',
            'attachable_id' => 'required|integer',
            'attachable_type' => 'required|string',
            'role' => 'nullable|string|max:50',
            'caption' => 'nullable|string|max:255',
            'ordering' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get the next ordering number if not provided
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
            
            $ordering = $request->ordering ?? Attachment::getNextOrdering(
                $attachableModel,
                $request->role
            );

            $attachment = Attachment::create([
                'file_id' => $request->file_id,
                'attachable_id' => $request->attachable_id,
                'attachable_type' => $request->attachable_type,
                'role' => $request->role,
                'caption' => $request->caption,
                'ordering' => $ordering,
                'created_by' => Auth::id(),
            ]);

            $attachment->load(['file', 'createdBy']);

            return response()->json([
                'success' => true,
                'attachment' => $attachment,
                'message' => 'Attachment created successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create attachment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an attachment
     */
    public function update(Request $request, Attachment $attachment): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role' => 'nullable|string|max:50',
            'caption' => 'nullable|string|max:255',
            'ordering' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $attachment->update($request->only(['role', 'caption', 'ordering']));

            return response()->json([
                'success' => true,
                'attachment' => $attachment->fresh()->load(['file', 'createdBy']),
                'message' => 'Attachment updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update attachment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an attachment
     */
    public function destroy(Attachment $attachment): JsonResponse
    {
        try {
            // Check if user can delete this attachment
            if (!$this->canDeleteAttachment($attachment)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            // Delete the attachment record
            $attachment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attachment deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attachment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder attachments
     */
    public function reorder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'attachments' => 'required|array',
            'attachments.*.id' => 'required|exists:attachments,id',
            'attachments.*.ordering' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($request->attachments as $item) {
                Attachment::where('id', $item['id'])->update(['ordering' => $item['ordering']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Attachments reordered successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder attachments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attachments for a specific model
     */
    public function forModel(Request $request, string $modelType, int $modelId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = Attachment::where('attachable_type', $modelType)
                ->where('attachable_id', $modelId)
                ->with(['file', 'createdBy'])
                ->orderBy('ordering');

            if ($request->role) {
                $query->where('role', $request->role);
            }

            $attachments = $query->get();

            return response()->json([
                'success' => true,
                'attachments' => $attachments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch attachments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user can delete an attachment
     */
    protected function canDeleteAttachment(Attachment $attachment): bool
    {
        $user = Auth::user();
        
        // Admin users can delete any attachment
        if ($user->is_admin) {
            return true;
        }
        
        // Users can delete attachments they created
        if ($user->id === $attachment->created_by) {
            return true;
        }
        
        // Users can delete attachments from their vessel
        if ($attachment->file && $user->vessel_id === $attachment->file->vessel) {
            return true;
        }
        
        return false;
    }
}
