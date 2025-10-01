@props([
    'model' => null,
    'role' => null,
    'multiple' => false,
    'accept' => null,
    'maxSize' => '50MB',
    'vesselId' => null,
    'class' => '',
    'label' => 'Upload Files',
    'modelClass' => null
])

@php
    $vesselId = $vesselId ?? ($model?->vessel_id ?? auth()->user()?->vessel_id);
    $uploadUrl = $multiple ? route('files.upload-multiple') : route('files.upload');
    $inputName = $multiple ? 'files[]' : 'file';

@endphp

<div class="file-upload-component {{ $class }}" x-data="fileUpload()">
    <div class="mb-4">
        <label class="mb-2 block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>

        <div class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center transition-colors hover:border-gray-400"
            @dragover.prevent="dragover = true" @dragleave.prevent="dragover = false" @drop.prevent="handleDrop($event)">

            <div x-show="!dragover" class="space-y-2">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path
                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

                <div class="text-sm text-gray-600">
                    <label for="file-upload-{{ $role ?? 'default' }}"
                        class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                        <span>Upload files</span>
                        <input id="file-upload-{{ $role ?? 'default' }}" name="{{ $inputName }}" type="file"
                            class="sr-only" {{ $multiple ? 'multiple' : '' }} {{ $accept ? "accept={$accept}" : '' }}
                            @change="handleFileSelect($event)">
                    </label>
                    <span class="ml-1">or drag and drop</span>
                </div>

                <p class="text-xs text-gray-500">
                    {{ $maxSize }} max file size. Supported formats: PDF, DOC, DOCX, XLS, XLSX, images, and more.
                </p>
            </div>

            <div x-show="dragover" class="space-y-2">
                <svg class="mx-auto h-12 w-12 text-indigo-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path
                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p class="text-sm text-indigo-600">Drop files here to upload</p>
            </div>
        </div>
    </div>

    <!-- Upload Progress -->
    <div x-show="uploads.length > 0" class="space-y-3">
        <template x-for="(upload, index) in uploads" :key="index">
            <div class="flex items-center space-x-3 rounded-lg bg-gray-50 p-3">
                <div class="flex-shrink-0">
                    <div x-show="upload.status === 'uploading'"
                        class="h-5 w-5 animate-spin rounded-full border-b-2 border-indigo-600"></div>
                    <div x-show="upload.status === 'success'"
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-green-500">
                        <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div x-show="upload.status === 'error'"
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500">
                        <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-gray-900" x-text="upload.file.name"></p>
                    <p class="text-sm text-gray-500"
                        x-text="upload.status === 'uploading' ? 'Uploading...' : upload.status === 'success' ? 'Upload complete' : upload.error">
                    </p>
                </div>

                <div class="flex-shrink-0">
                    <button @click="removeUpload(index)" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Upload Button -->
    <div x-show="uploads.length > 0" class="mt-4">
        <button @click="uploadFiles()" :disabled="!canUpload"
            class="w-full rounded-md bg-indigo-600 px-4 py-2 text-white transition-colors hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">
            <span x-show="!isUploading">Upload <span x-text="uploads.length"></span> file(s)</span>
            <span x-show="isUploading">Uploading...</span>
        </button>
    </div>

    <!-- Success Message -->
    <div x-show="uploadSuccess" class="mt-4 rounded-md border border-green-200 bg-green-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800" x-text="successMessage"></p>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <div x-show="uploadError" class="mt-4 rounded-md border border-red-200 bg-red-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800" x-text="errorMessage"></p>
            </div>
        </div>
    </div>
</div>

<script>
    function fileUpload() {
        return {
            uploads: [],
            isUploading: false,
            uploadSuccess: false,
            uploadError: false,
            successMessage: '',
            errorMessage: '',
            dragover: false,

            get canUpload() {
                return this.uploads.length > 0 && !this.isUploading;
            },

            handleFileSelect(event) {
                const files = Array.from(event.target.files);
                this.addFiles(files);
            },

            handleDrop(event) {
                this.dragover = false;
                const files = Array.from(event.dataTransfer.files);
                this.addFiles(files);
            },

            addFiles(files) {
                files.forEach(file => {
                    this.uploads.push({
                        file: file,
                        status: 'pending',
                        error: null
                    });
                });
            },

            removeUpload(index) {
                this.uploads.splice(index, 1);
            },

            async uploadFiles() {
                if (this.uploads.length === 0) return;

                this.isUploading = true;
                this.uploadSuccess = false;
                this.uploadError = false;

                try {
                    const formData = new FormData();
                    formData.append('vessel_id', '{{ $vesselId }}');

                    // Add attachable information if model is provided
                    @if ($model && $modelClass)
                        formData.append('attachable_id', '{{ $model->id }}');
                        formData.append('attachable_type', '{{ $modelClass }}');
                        formData.append('role', '{{ $role ?? 'file' }}');
                    @endif

                    if ({{ $multiple ? 'true' : 'false' }}) {
                        this.uploads.forEach(upload => {
                            formData.append('files[]', upload.file);
                        });
                    } else {
                        formData.append('file', this.uploads[0].file);
                    }



                    // Update status to uploading
                    this.uploads.forEach(upload => {
                        upload.status = 'uploading';
                    });

                    const response = await fetch('{{ $uploadUrl }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: formData
                    });

                    let result;
                    const contentType = response.headers.get('content-type');

                    if (contentType && contentType.includes('application/json')) {
                        try {
                            result = await response.json();
                        } catch (jsonError) {
                            console.error('Failed to parse JSON response:', jsonError);
                            throw new Error('Server returned invalid JSON response');
                        }
                    } else {
                        // Response is not JSON (likely HTML error page)
                        const responseText = await response.text();
                        console.error('Server returned non-JSON response:', responseText);
                        throw new Error('Server error: Received HTML instead of JSON response. Check server logs.');
                    }

                    if (result.success) {
                        this.uploads.forEach(upload => {
                            upload.status = 'success';
                        });

                        this.uploadSuccess = true;
                        this.successMessage = result.message;

                        // Clear uploads after successful upload
                        setTimeout(() => {
                            this.uploads = [];
                            this.uploadSuccess = false;
                        }, 3000);

                        // Emit event for parent components
                        this.$dispatch('files-uploaded', result);

                    } else {
                        throw new Error(result.message || 'Upload failed');
                    }

                } catch (error) {
                    this.uploads.forEach(upload => {
                        upload.status = 'error';
                        upload.error = error.message;
                    });

                    this.uploadError = true;
                    this.errorMessage = error.message;
                } finally {
                    this.isUploading = false;
                }
            }
        }
    }
</script>
