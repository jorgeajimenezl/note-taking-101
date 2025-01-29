<!-- resources/views/tags/create.blade.php -->
<x-app-layout title="Create Tag">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-screen-md py-6">
        <form action="{{ route('tags.store') }}" method="POST">
            @csrf
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-4 sm:p-6 lg:p-8">
                    <h3 class="text-lg sm:text-xl leading-6 font-medium text-gray-900">Create a Tag</h3>
                    
                    <div class="mt-2 max-w-xl text-sm sm:text-base text-gray-500">
                        <p>Ready to unleash your creativity? Give your new tag a name that will make it unforgettable!</p>
                    </div>
                    
                    <div class="mt-6">
                        <x-input-label for="name" :value="__('Name')" class="text-sm font-medium" />
                        <x-text-input 
                            id="name" 
                            name="name" 
                            type="text" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            required 
                            autofocus 
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    
                    <div class="mt-6 flex justify-start">
                        <x-primary-button class="w-full sm:w-auto">
                            {{ __('Create') }}
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>