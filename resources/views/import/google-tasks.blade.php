<x-app-layout title="Import Google Tasks">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Select Google Tasks to Import</h2>
            <form action="{{ route('import.google-tasks') }}" method="POST">
                @csrf
                <div class="mb-4 flex items-center">
                    <input type="checkbox" id="select-all" class="mr-2 h-4 w-4 text-blue-600">
                    <label for="select-all" class="font-medium text-gray-700">Select All</label>
                </div>
                <div class="space-y-2 max-h-96 overflow-y-auto border border-gray-200 rounded p-4">
                    @foreach($tasks as $task)
                        <div class="flex items-center">
                            <input type="checkbox" name="tasks[]" value="{{ $task->id }}" id="task-{{ $task->id }}" class="mr-2 h-4 w-4 text-blue-600">
                            <label for="task-{{ $task->id }}" class="text-gray-700">{{ $task->title }}</label>
                            <a href="{{ $task->link }}" target="_blank" class="ml-2 text-blue-500">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Import Selected Tasks
                </button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('select-all').addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('input[name="tasks[]"]');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
        });
    </script>
</x-app-layout>