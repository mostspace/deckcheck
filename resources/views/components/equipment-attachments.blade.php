@props(['equipment'])

<div class="equipment-attachments">
    <div class="mb-6">
        <h3 class="mb-4 text-lg font-medium text-gray-900">Equipment Attachments</h3>

        <!-- File Upload Section -->
        <div class="mb-6">
            <x-file-upload :model="$equipment" role="manual" :multiple="true" accept=".pdf,.doc,.docx,.txt"
                label="Upload Manuals & Documentation" maxSize="50MB" class="mb-4" modelClass="Equipment" />

            <x-file-upload :model="$equipment" role="photo" :multiple="true" accept="image/*" label="Upload Photos"
                maxSize="50MB" class="mb-4" modelClass="Equipment" />

            <x-file-upload :model="$equipment" role="file" :multiple="true" accept=".pdf,.doc,.docx,.xls,.xlsx"
                label="Upload Reports & Certificates" maxSize="50MB" modelClass="Equipment" />
        </div>

        <!-- Existing Attachments -->
        <div class="space-y-4">
            <!-- Manuals -->
            @if ($equipment->hasAttachmentsByRole('manual'))
                <div>
                    <h4 class="text-md mb-2 font-medium text-gray-700">Manuals & Documentation</h4>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($equipment->attachmentsByRole('manual')->get() as $attachment)
                            <x-attachment-card :attachment="$attachment" />
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Photos -->
            @if ($equipment->hasAttachmentsByRole('photo'))
                <div>
                    <h4 class="text-md mb-2 font-medium text-gray-700">Photos</h4>
                    <div class="grid grid-cols-2 gap-3 md:grid-cols-4 lg:grid-cols-6">
                        @foreach ($equipment->attachmentsByRole('photo')->get() as $attachment)
                            <x-attachment-card :attachment="$attachment" />
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Reports -->
            @if ($equipment->hasAttachmentsByRole('file'))
                <div>
                    <h4 class="text-md mb-2 font-medium text-gray-700">Reports & Certificates</h4>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($equipment->attachmentsByRole('file')->get() as $attachment)
                            <x-attachment-card :attachment="$attachment" />
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- No Attachments Message -->
            @if (!$equipment->hasAttachments())
                <div class="py-8 text-center text-gray-500">
                    <svg class="mx-auto mb-4 h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                        viewBox="0 0 48 48">
                        <path
                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <p class="text-sm">No attachments uploaded yet.</p>
                    <p class="mt-1 text-xs text-gray-400">Upload manuals, photos, and reports above.</p>
                </div>
            @endif
        </div>
    </div>
</div>
