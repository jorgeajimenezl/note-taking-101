<form id="task-form" wire:submit>
    <div class="container mx-auto bg-white rounded-lg shadow-lg my-5 max-w-screen-md p-5">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="title-section flex items-center justify-between">
                <x-text-input id="task-title" data-mark="input-field" class="text-xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow editable" name="title" wire:model.blur="title" wire:dirty.class="focus:border-yellow-500" />
            </div>
            <x-input-error :messages="$errors->get('title')" class="mt-2" />

            <x-contributor-selector-dialog :task="$task"/>        
            
            <x-input-label class="mt-4 mb-2" for="task-tags[]" :value="__('Tags')"/>
            <livewire:tag-selector id="task-tags" name="tags" :tags="$task->tags->all()" :readonly="$role === 'viewer'" wire:model.live="tags" />
            <div class="mt-4"></div>
            <x-textarea-input id="task-description" name="description" placeholder="Description" class="tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow editable" wire:model.blur="description" wire:dirty.class="focus:border-yellow-500"/>
            <div class="mt-5">
                <p>Author: <a href="mailto:{{ $task->author->email }}">{{ $task->author->name }}</a></p>
            </div>
            <div class="flex justify-end mt-4">
                @if($role === 'owner')
                    <x-primary-button id="share-button" class="ml-1" type="button" onclick="toggleContributorDialog(true)">
                        <i class="fas fa-share-alt"></i> 
                        <span class="ml-1">Share</span>
                    </x-primary-button>
                    <x-danger-button id="delete-button" class="ml-2" wire:click="deleteTask">
                        <i class="fas fa-trash-alt"></i> 
                        <span class="ml-1">Delete</span>
                    </x-danger-button>                    
                @endif
            </div>
        </div>
    </div>
</form>