<x-app-layout title="New Task">
    @if(session()->has('success'))
    <div class="bg-green-100 text-green-500 p-2">
        {!! session()->get('success') !!}
    </div>
    @endif

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <form id="task-form" action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="bg-white shadow">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <div class="title-section flex items-center justify-between">
                        <input id="task-title" data-mark="input-field" class="text-3xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow editable" placeholder="Title" name="title">
                        <button id="save-button" class="btn btn-primary ml-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out" type="submit">Create</button>
                    </div>
                </div>
                <main>
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        <x-tag-selector id="task-tags" name="tags" label="Tags" :tags="[]" :allTags="$allTags->all()" />
                        <div class="mt-2" />
                        <x-form-text-area id="task-description" label="Description" name="description" placeholder="Description" />
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