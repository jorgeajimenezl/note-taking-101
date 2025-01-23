<form id="task-form" action="{{ route('tasks.store') }}" method="POST">
    @csrf
    @method("POST")
    <div class="container mx-auto bg-white rounded-lg shadow-lg my-5 max-w-screen-md p-5">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="title-section flex items-center justify-between">
                <x-text-input id="task-title" data-mark="input-field" class="text-xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow editable" placeholder="Title" name="title" required/>
            </div>
            <x-input-error :messages="$errors->get('title')" class="mt-2" />

            <x-input-label class="mt-4 mb-2" for="task-tags[]" :value="__('Tags')"/>
            <livewire:tag-selector id="task-tags" name="tags" :readonly="false"/>
            <div class="mt-4"></div>
            <x-textarea-input id="task-description" name="description" placeholder="Description" value=""/>
            <div class="flex justify-end mt-4">
                <x-primary-button id="save-button" type="submit">Create</x-primary-button>
            </div>
        </div>
    </div>
</form>