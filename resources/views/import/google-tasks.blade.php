<x-app-layout title="Import Google Tasks">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-screen-md py-6">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-4 sm:p-6 lg:p-8">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6">Select Google Tasks to Import</h2>
                
                <form action="{{ route('import.google-tasks') }}" method="POST">
                    @csrf
                    <div class="mb-4 sm:mb-6 flex items-center">
                        <input type="checkbox" 
                               id="select-all" 
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="select-all" 
                               class="ml-2 text-sm sm:text-base font-medium text-gray-700">
                            Select All
                        </label>
                    </div>

                    <div class="space-y-3 max-h-[calc(100vh-24rem)] overflow-y-auto border border-gray-200 rounded-md p-4">
                        @foreach($tasks as $task)
                            <div class="flex items-center group">
                                <input type="checkbox" 
                                       name="tasks[]" 
                                       value="{{ $task->id }}" 
                                       id="task-{{ $task->id }}" 
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="task-{{ $task->id }}" 
                                       class="ml-2 flex-grow text-sm sm:text-base text-gray-700">
                                    {{ $task->title }}
                                </label>
                                <a href="{{ $task->link }}" 
                                   target="_blank" 
                                   class="ml-2 p-2 text-blue-500 hover:text-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 sm:mt-8 flex justify-start">
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm sm:text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            Import Selected Tasks
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('input[name="tasks[]"]');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
        });
    </script>
</x-app-layout>