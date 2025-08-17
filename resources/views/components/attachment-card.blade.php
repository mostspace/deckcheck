@props(['attachment'])

        <div class="attachment-card bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow" data-attachment-id="{{ $attachment->id }}">
    <!-- File Icon -->
    <div class="flex items-center justify-center h-16 mb-3">
        @if($attachment->isImage())
            <div class="w-full h-full bg-gray-100 rounded flex items-center justify-center">
                <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
            </div>
        @elseif($attachment->isPdf())
            <div class="w-full h-full bg-red-100 rounded flex items-center justify-center">
                <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                </svg>
            @else
            <div class="w-full h-full bg-blue-100 rounded flex items-center justify-center">
                <svg class="h-8 w-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif
    </div>
    
    <!-- File Info -->
    <div class="text-center mb-3">
        <h4 class="text-sm font-medium text-gray-900 truncate" title="{{ $attachment->display_name }}">
            {{ $attachment->display_name }}
        </h4>
        <p class="text-xs text-gray-500">{{ $attachment->file_size }}</p>
        @if($attachment->caption)
            <p class="text-xs text-gray-600 mt-1">{{ $attachment->caption }}</p>
        @endif
    </div>
    
    <!-- Actions -->
    <div class="flex space-x-2">
        <a href="{{ route('files.download', $attachment->file) }}" 
           class="flex-1 bg-indigo-600 text-white text-xs px-3 py-2 rounded-md hover:bg-indigo-700 transition-colors text-center">
            Download
        </a>
        
        <button onclick="deleteAttachment({{ $attachment->id }})" 
                class="bg-red-600 text-white text-xs px-3 py-2 rounded-md hover:bg-red-700 transition-colors">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 112 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    
    <!-- Upload Info -->
    <div class="mt-3 pt-3 border-t border-gray-100">
        <div class="flex items-center justify-between text-xs text-gray-500">
            <span>Uploaded {{ $attachment->created_at->diffForHumans() }}</span>
            @if($attachment->created_by)
                <span>by {{ $attachment->createdBy->name ?? 'Unknown User' }}</span>
            @endif
        </div>
    </div>
</div>

<script>
function deleteAttachment(attachmentId) {
    if (confirm('Are you sure you want to delete this attachment? This action cannot be undone.')) {
        fetch(`/attachments/${attachmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the attachment card from the DOM
                const card = document.querySelector(`[data-attachment-id="${attachmentId}"]`);
                if (card) {
                    card.remove();
                }
                
                // Show success message
                alert('Attachment deleted successfully');
                
                // Optionally reload the page or update the UI
                // location.reload();
            } else {
                alert('Failed to delete attachment: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the attachment');
        });
    }
}
</script>
