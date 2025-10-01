@props(['attachment'])

<div class="attachment-card rounded-lg border border-gray-200 bg-white p-3 transition-shadow hover:shadow-md"
    data-attachment-id="{{ $attachment->id }}">
    <!-- File Icon -->
    <div class="mb-3 flex h-16 items-center justify-center">
        @if ($attachment->isImage())
            <div class="flex h-full w-full items-center justify-center rounded bg-gray-100">
                <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        @elseif($attachment->isPdf())
            <div class="flex h-full w-full items-center justify-center rounded bg-red-100">
                <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                        clip-rule="evenodd" />
                </svg>
            @else
                <div class="flex h-full w-full items-center justify-center rounded bg-blue-100">
                    <svg class="h-8 w-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
        @endif
    </div>

    <!-- File Info -->
    <div class="mb-3 text-center">
        <h4 class="truncate text-sm font-medium text-gray-900" title="{{ $attachment->display_name }}">
            {{ $attachment->display_name }}
        </h4>
        <p class="text-xs text-gray-500">{{ $attachment->file_size }}</p>
        @if ($attachment->caption)
            <p class="mt-1 text-xs text-gray-600">{{ $attachment->caption }}</p>
        @endif
    </div>

    <!-- Actions -->
    <div class="flex space-x-2">
        <a href="{{ route('files.download', $attachment->file) }}"
            class="flex-1 rounded-md bg-indigo-600 px-3 py-2 text-center text-xs text-white transition-colors hover:bg-indigo-700">
            Download
        </a>

        <button onclick="deleteAttachment({{ $attachment->id }})"
            class="rounded-md bg-red-600 px-3 py-2 text-xs text-white transition-colors hover:bg-red-700">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 112 0V8a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <!-- Upload Info -->
    <div class="mt-3 border-t border-gray-100 pt-3">
        <div class="flex items-center justify-between text-xs text-gray-500">
            <span>Uploaded {{ $attachment->created_at->diffForHumans() }}</span>
            @if ($attachment->created_by)
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
