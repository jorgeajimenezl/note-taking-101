<x-app-layout title="Create Task">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-screen-md">
        @if(session()->has('success'))
        <div class="mb-4 sm:mb-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 sm:px-6 py-3 sm:py-4 rounded shadow-md" role="alert">
                <span class="block text-sm sm:text-base">
                    {!! session()->get('success') !!}
                </span>
            </div>
        </div>
        @endif

        <livewire:create-task />
    </div>
</x-app-layout>
<style>
    .editable {
        border: 1px solid #ccc;
    }
</style>