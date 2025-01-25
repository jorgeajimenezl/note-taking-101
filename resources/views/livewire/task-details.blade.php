<form id="task-form" wire:submit>
    <div class="container mx-auto bg-white rounded-lg shadow-lg my-5 max-w-screen-md p-5">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="title-section flex items-center justify-between">
                <x-text-input id="task-title" data-mark="input-field" class="text-xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow editable" name="title" wire:model.blur="title" wire:dirty.class="focus:ring-yellow-500 focus:border-yellow-500" :readonly="$role === 'viewer'" />
            </div>
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
            <x-contributor-selector-dialog :task="$task"/>            
            <x-input-label class="mt-4 mb-2" :value="__('Tags')"/>
            <livewire:tag-selector name="tags" :tags="$task->tags->all()" :readonly="$role === 'viewer'" />
            <div class="mt-4"></div>
            <x-textarea-input id="task-description" name="description" placeholder="Description" class="tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow editable" wire:model.blur="description" wire:dirty.class="focus:ring-yellow-500 focus:border-yellow-500" :readonly="$role === 'viewer'" />
            <!-- Attachments Section -->
            <div class="flex items-center justify-between mt-4 mb-2">
                <x-input-label :value="__('Attachments')"/>
                <button type="button" id="add-attachment-button" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-sm" onclick="addAttchment()">
                    <i class="fas fa-plus mr-1"></i> Add
                </button>
            </div>
            <div class="attachments-section mt-2">
                @foreach($task->getMedia('attachments') as $attachment)
                <div class="attachment-placeholder bg-gray-100 p-2 rounded mb-2 flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-paperclip mr-2"></i> {{ $attachment->file_name }}
                    </div>
                    <button type="button" class="text-red-500 hover:text-red-700" wire:click="deleteAttachment({{ $attachment->id }})">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                @endforeach
            </div>
            <div class="mt-5">
                <p>Author: <a href="mailto:{{ $task->author->email }}">{{ $task->author->name }}</a></p>
            </div>
            <div class="flex justify-end mt-4">
                @if($role === 'owner')
                    <x-primary-button id="share-button" class="ml-1" type="button" onclick="toggleContributorDialog(true)">
                        <i class="fas fa-share-alt"></i> 
                        <span class="ml-1">Share</span>
                    </x-primary-button>
                    <x-danger-button id="delete-button" class="ml-2" wire:click="deleteTask" wire:confirm="Are you sure you want to delete this task?">
                        <i class="fas fa-trash-alt"></i> 
                        <span class="ml-1">Delete</span>
                    </x-danger-button>                    
                @endif
            </div>
        </div>
    </div>
    @script
    <script>
        document.getElementById('add-attachment-button').addEventListener('click', function() {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = 'attachment';
            fileInput.classList.add('hidden');
            fileInput.click();

            fileInput.addEventListener('change', function(e) {
                console.log(e.target.files);
                if (e.target.files.length !== 1)
                    return;
                
                $wire.upload('attachment', e.target.files[0], (uploadedFilename) => {
                    // Success callback...
                    console.log(uploadedFilename);
                    $wire.call('addAttachment', uploadedFilename);
                }, () => {
                    // Error callback...
                    console.log('Error uploading file');
                }, (event) => {
                    // Progress callback...
                    // event.detail.progress contains a number between 1 and 100 as the upload progresses
                    console.log(event.detail.progress);
                }, () => {
                    // Cancelled callback...
                    console.log('Upload cancelled');
                });
            });
            fileInput.addEventListener('cancel', function() {
                fileInput.remove();
            });
        });
    </script>
    @endscript
</form>