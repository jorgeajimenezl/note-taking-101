<!-- resources/views/tags/create.blade.php -->
<x-app-layout title="Create Tag">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <form action="{{ route('tags.store') }}" method="POST">
            @csrf
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Create a New Tag</h3>
                    <div class="mt-2 max-w-xl text-sm text-gray-500">
                        <p>Enter the name of the new tag below.</p>
                    </div>
                    <div class="mt-5">
                        <x-input-label for="name" :value="__('Tag Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="mt-5">
                        <x-primary-button>{{ __('Create') }}</x-primary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>