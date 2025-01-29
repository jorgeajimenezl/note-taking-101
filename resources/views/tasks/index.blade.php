<x-app-layout title="Tasks">
    <div class="flex justify-end my-5 max-w-screen-md mx-auto">
        <a href="{{ route('import.google-tasks') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">Import</a>
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Create</a>
    </div>

    @session('status')
        @php
            $alertClass = 'bg-green-100 border border-green-400 text-green-700';
            if (strpos($value, 'error') !== false) {
                $alertClass = 'bg-red-100 border border-red-400 text-red-700';
            }
        @endphp
        <div class="container mx-auto my-5 max-w-screen-md">
            <div class="{{ $alertClass }} px-6 py-4 border-l-4 rounded shadow-md mb-4" role="alert">
                <span class="block sm:inline font-bold">
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
    
    <livewire:tasks-list />
</x-app-layout>
