<x-site-layout title="Tasks">
    <div class="container mx-auto flex justify-center">
        <ul class="w-3/4">
            <ul id="uncompleted-tasks">
                @foreach ($uncompletedTasks as $task)
                <li id="task-item-{{ $task->id }}" class="task-item border-b border-gray-300 py-2">
                    <input type="checkbox" name="task" value="{{ $task->id }}" class="mr-2 task-checkbox" data-id="{{ $task->id }}">
                    <a href="{{ route('task.show', $task) }}" id="task-title-{{ $task->id }}" class="hover:underline">{{ $task->title }}</a>
                </li>
                @endforeach
            </ul>
            <ul id="completed-tasks">
                @foreach ($completedTasks as $task)
                <li id="task-item-{{ $task->id }}" class="task-item border-b border-gray-300 py-2">
                    <input type="checkbox" name="task" value="{{ $task->id }}" class="mr-2 task-checkbox" data-id="{{ $task->id }}" checked>
                    <a href="{{ route('task.show', $task) }}" id="task-title-{{ $task->id }}" class="line-through hover:underline">{{ $task->title }}</a>
                </li>
                @endforeach
            </ul>
            <li class="border-b border-gray-300 py-2">
                <a href="#" class="flex items-center text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="red" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add task
                </a>
            </li>
        </ul>
    </div>

    <style>
        .task-item {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .task-item.animate {
            opacity: 0;
            transform: translateY(-20px);
        }
    </style>

    <script>
        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const taskId = this.dataset.id;
                const isCompleted = this.checked;

                fetch(`/task/${taskId}/toggle-complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        completed: isCompleted
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        this.checked = !isCompleted;
                        alert('Failed to update task status');
                    } else {
                        // Update the DOM to reflect the new task status
                        const taskItem = document.querySelector(`#task-item-${taskId}`);
                        const taskElement = document.querySelector(`#task-title-${taskId}`);

                        taskItem.classList.add('animate');

                        setTimeout(() => {
                            // Update the DOM after animation
                            taskItem.classList.remove('animate');
                            if (isCompleted) {
                                taskElement.classList.add('line-through');
                                document.querySelector('#completed-tasks').appendChild(taskItem);
                            } else {
                                taskElement.classList.remove('line-through');
                                document.querySelector('#uncompleted-tasks').appendChild(taskItem);
                            }
                        }, 300); // Match transition duration
                    }
                });
            });
        });
    </script>
</x-site-layout>