<x-app-layout title="Tasks">
    <div class="flex justify-end my-5 max-w-screen-md mx-auto">
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Create</a>
    </div>

    <livewire:tasks-list />
</x-app-layout>
