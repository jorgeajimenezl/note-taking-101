<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session()->has('success'))
        <div class="bg-green-100 text-green-500 p-2">
            {!! session()->get('success') !!}
        </div>
        @endif

        <form id="task-form" action="{{ route('tasks.store') }}" method="POST">
            @csrf
            @method("POST")
            <div class="bg-white shadow sm:rounded-lg">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <div class="title-section flex items-center justify-between">
                        <x-text-input id="task-title" data-mark="input-field" class="text-xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow editable" placeholder="Title" name="title" required/>
                        <x-primary-button id="save-button" class="ml-4" type="submit">Create</x-primary-button>
                    </div>
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <main>
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        <x-tag-selector id="task-tags" name="tags" label="Tags" :tags="[]" :allTags="$allTags->all()" />
                        <div class="mt-2"></div>
                        <x-textarea-input id="task-description" label="Description" name="description" placeholder="Description" />
                    </div>
                </main>
            </div>
        </form>
    </div>
</x-app-layout>
<style>
    .editable {
        border: 1px solid #ccc;
    }
</style>