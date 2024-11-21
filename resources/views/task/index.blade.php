<x-site-layout title="Tasks">
    <div class="container mx-auto flex justify-center">
        <ul class="w-3/4">
            @foreach ($tasks as $task)
                <li class="border-b border-gray-300 py-2">
                    <input type="checkbox" name="task" value="{{ $task->id }}" class="mr-2">
                    <a href="{{ route('task.show', $task) }}" class="hover:underline">{{ $task->title }}</a>
                </li>
            @endforeach
            <li class="border-b border-gray-300 py-2">
                <a href="#" class="flex items-center text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add task
                </a>
            </li>
        </ul>
    </div>
</x-site-layout>
