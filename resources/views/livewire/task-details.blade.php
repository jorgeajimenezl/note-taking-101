<div>
    <div class="container mx-auto bg-white rounded-lg shadow-lg my-4 sm:my-5 max-w-screen-md p-4 sm:p-5">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 py-4 sm:py-6">
            <div class="title-section flex items-center justify-between">
                <x-text-input id="task-title" data-mark="input-field" 
                    class="text-lg sm:text-xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow editable" 
                    name="title" 
                    wire:model.blur="title" 
                    wire:dirty.class="focus:ring-yellow-500 focus:border-yellow-500" 
                    :readonly="$role === 'viewer'" />
            </div>
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
            
            @if($role === 'owner')
                <livewire:contributor-selector :task="$task"/>
            @endif
            
            <x-input-label class="mt-4 mb-2" :value="__('Tags')"/>
            <livewire:tag-selector name="tags" :tags="$task->tags->all()" :readonly="$role === 'viewer'" />
            
            <div class="mt-4"></div>
            <x-textarea-input id="task-description" 
                name="description" 
                placeholder="Description" 
                class="w-full min-h-[100px] text-sm sm:text-base tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow editable" 
                wire:model.blur="description" 
                wire:dirty.class="focus:ring-yellow-500 focus:border-yellow-500" 
                :readonly="$role === 'viewer'" />
            
            <!-- Attachments Section -->
            <div class="flex items-center justify-between mt-4 mb-2">
                <x-input-label :value="__('Attachments')" class="text-sm sm:text-base"/>
                @if($role !== 'viewer')                    
                    <button type="button" id="add-attachment-button" 
                        class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-sm transition-colors duration-200">
                        <i class="fas fa-plus mr-1"></i> Add
                    </button>
                @endif
            </div>
            
            <div class="attachments-section mt-2 space-y-2">
                @foreach($task->getMedia('attachments') as $attachment)
                <div class="attachment-placeholder bg-gray-100 p-2 sm:p-3 rounded mb-2 flex items-center justify-between">
                    <div class="flex items-center overflow-hidden flex-grow">
                        <i class="fas fa-paperclip mr-2"></i> 
                        <span class="truncate text-sm sm:text-base">{{ $attachment->file_name }}</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                            class="text-gray-600 hover:text-gray-800 transition-colors duration-200" 
                            wire:click.prevent="downloadAttachment({{ $attachment->id }})">
                            <i class="fas fa-download"></i>
                        </button>
                        @if($role !== 'viewer')
                            <button type="button" 
                                class="text-red-500 hover:text-red-700 transition-colors duration-200" 
                                wire:click.prevent="deleteAttachment({{ $attachment->id }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        @endif                    
                    </div>
                </div>
                @endforeach
                <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
            </div>
            
            <!-- Author Info -->
            <div class="mt-5 text-sm sm:text-base">
                <p>Author: <a href="mailto:{{ $task->author->email }}" class="text-blue-600 hover:text-blue-800">{{ $task->author->name }}</a></p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end mt-6 space-y-2 sm:space-y-0 sm:space-x-2">
                @if($role === 'owner')
                    <x-primary-button id="share-button" 
                        type="button"
                        class="w-full sm:w-auto"
                        onclick="window.dispatchEvent(new CustomEvent('open-dialog'))">
                        <i class="fas fa-share-alt"></i> 
                        <span class="ml-2">Share</span>
                    </x-primary-button>
                    <x-danger-button id="delete-button"
                        class="w-full sm:w-auto" 
                        wire:click="deleteTask" 
                        wire:confirm="Are you sure you want to delete this task?">
                        <i class="fas fa-trash-alt"></i> 
                        <span class="ml-2">Delete</span>
                    </x-danger-button>                    
                @endif
            </div>
        </div>
    </div>

    <style>
        .attachment-placeholder .truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    
    @script
    <script>
        document.getElementById('add-attachment-button').addEventListener('click', function() {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = 'attachment';
            fileInput.classList.add('hidden');
            fileInput.click();

            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length !== 1)
                    return;

                const file = e.target.files[0];

                const attachmentPlaceholder = document.createElement('div');
                attachmentPlaceholder.classList.add('attachment-placeholder', 'bg-gray-100', 'p-2', 'rounded', 'mb-2', 'flex', 'items-center', 'justify-between');
                attachmentPlaceholder.innerHTML = `
                    <div class="flex items center overflow-hidden">
                        <i class="fas fa-paperclip mr-2"></i>
                        <span class="truncate">${file.name}</span>
                    </div>
                    <div class="grow"></div>
                    <div class="relative w-5 h-5 mr-2">
                        <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="18" cy="18" r="14" fill="none" class="stroke-current text-gray-200 dark:text-neutral-700" stroke-width="7"></circle>
                            <circle id="progress-circle" cx="18" cy="18" r="14" fill="none" class="stroke-current text-blue-600 dark:text-blue-500" stroke-width="7" stroke-dasharray="100" stroke-dashoffset="100" stroke-linecap="round"></circle>
                        </svg>
                    </div>
                `;
                document.querySelector('.attachments-section').appendChild(attachmentPlaceholder);
                const progressCircle = attachmentPlaceholder.querySelector('#progress-circle');
                
                $wire.upload('attachment', file, (uploadedFilename) => {
                    attachmentPlaceholder.remove();
                    $wire.call('addAttachment', file.name);
                }, () => {
                    console.log('Error uploading file');
                    attachmentPlaceholder.remove();
                }, (event) => {
                    progressCircle.style.strokeDashoffset = 100 - event.detail.progress;
                }, () => {
                    console.log('Upload cancelled');
                    attachmentPlaceholder.remove();
                });
            });
            fileInput.addEventListener('cancel', function() {
                fileInput.remove();
            });
        });
    </script>
    @endscript
</div>