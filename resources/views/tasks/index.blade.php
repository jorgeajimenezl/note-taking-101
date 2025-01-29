<x-app-layout title="Tasks">
    <div class="container mx-auto px-4 max-w-screen-md">
        <!-- Button Group -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end my-4 sm:my-5 space-y-2 sm:space-y-0 sm:space-x-2">
            <div x-data="{ open: false }" class="relative inline-flex w-full sm:w-auto">
                <a href="{{ route('tasks.create') }}"
                   class="flex-grow sm:flex-grow-0 inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-l-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Create
                </a>
                <button @click="open = !open" type="button"
                        class="inline-flex items-center justify-center px-2 py-2 bg-gray-800 border-l border-gray-700 rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        aria-haspopup="true">
                    <i class="fa-solid fa-caret-down"></i>
                </button>
                <div x-show="open" @click.away="open = false"
                     class="origin-top-right absolute right-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1">
                        <a href="{{ route('import.google-tasks') }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full">
                            <i class="fa-brands fa-google mr-2"></i>
                            Import
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @session('status')
            @php
                $alertClass = 'bg-green-100 border border-green-400 text-green-700';
                if (strpos($value, 'error') !== false) {
                    $alertClass = 'bg-red-100 border border-red-400 text-red-700';
                }
            @endphp
            <div class="mb-4 sm:mb-6">
                <div class="{{ $alertClass }} px-4 sm:px-6 py-3 sm:py-4 border-l-4 rounded shadow-md" role="alert">
                    <span class="block text-sm sm:text-base font-medium">
                        @if($value == 'tasks-imported')
                            Tasks imported successfully.
                        @elseif($value == 'error:tasks-fetch-failed')
                            Failed to fetch tasks from Google Tasks.
                        @elseif($value == 'error:tasks-fetch-expired')
                            Failed to fetch tasks from Google Tasks. The access token has expired.
                        @elseif($value == 'error:tasks-import-failed')
                            Failed to import tasks from Google Tasks.
                        @endif
                    </span>
                </div>
            </div>
        @endsession
        
        <!-- Tasks List -->
        <div class="w-full">
            <livewire:tasks-list />
        </div>
    </div>
</x-app-layout>
