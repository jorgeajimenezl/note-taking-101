<div>
    <div class="container mx-auto my-5 max-w-screen-md">
        <input type="text" wire:model.live="search" class="w-full border border-gray-300 rounded p-2" placeholder="Search tasks...">
    </div>

    <div class="container mx-auto bg-white rounded-lg shadow-lg my-5 max-w-screen-md p-5">
        @if($uncompletedTasks->isEmpty() && $completedTasks->isEmpty() && $sharedTasks->isEmpty())
            <div class="text-gray-500 text-center py-10 flex flex-col items-center">
                <svg class="w-16 h-16 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 4H7m4-8h.01M12 2a10 10 0 100 20 10 10 0 000-20z"></path>
                </svg>
                <p class="text-xl">No tasks to show</p>
            </div>
        @else
            @if(! $uncompletedTasks->isEmpty() || ! $completedTasks->isEmpty())
                <h2 class="text-xl font-semibold mb-4">Tasks</h2>            
                <ul id="uncompleted-tasks">
                    @foreach ($uncompletedTasks as $task)
                        <li id="task-item-{{ $task->id }}" class="border-b border-gray-300 py-2 bg-gray-200 mt-2.5 p-2.5 rounded flex items-center">
                            <input type="checkbox" name="task" value="{{ $task->id }}" class="mr-2 task-checkbox rounded-sm text-blue-600" data-id="{{ $task->id }}">
                            <a href="{{ route('tasks.show', $task) }}" id="task-title-{{ $task->id }}" class="hover:underline">{{ $task->title }}</a>
                        </li>
                    @endforeach
                </ul>
                <ul id="completed-tasks">
                    @foreach ($completedTasks as $task)
                        <li id="task-item-{{ $task->id }}" class="border-b border-gray-300 py-2 bg-gray-200 mt-2.5 p-2.5 rounded flex items-center">
                            <input type="checkbox" name="task" value="{{ $task->id }}" class="mr-2 task-checkbox rounded-sm text-blue-600" data-id="{{ $task->id }}" checked>
                            <a href="{{ route('tasks.show', $task) }}" id="task-title-{{ $task->id }}" class="line-through hover:underline">{{ $task->title }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
            
            @if(! $sharedTasks->isEmpty())
                <h2 class="text-xl font-semibold my-4">Shared Tasks</h2>            
                <ul id="shared-tasks">
                    @foreach ($sharedTasks as $task)
                        <li id="task-item-{{ $task->id }}" class="border-b border-gray-300 py-2 bg-gray-200 mt-2.5 p-2.5 rounded flex items-center justify-between">
                            <a href="{{ route('tasks.show', $task) }}" id="task-title-{{ $task->id }}" class="hover:underline">{{ $task->title }}</a>
                            <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full border {{ $task->user_role === 'editor' ? 'bg-blue-100 text-blue-800 border-blue-300' : 'bg-gray-100 text-gray-800 border-gray-300' }}">
                                {{ ucfirst($task->user_role) }}
                            </span>
                        </li>
                    @endforeach
                </ul> 
            @endif   
        @endif        
    </div>

    <style>
        .animate-move {
            animation: moveTask var(--animation-duration) ease;
        }
        @keyframes moveTask {
            from {
                transform: var(--initial-transform);
            }
            to {
                transform: translate(0, 0);
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = '{{ csrf_token() }}';
            const uncompletedTasks = document.querySelector('#uncompleted-tasks');
            const completedTasks = document.querySelector('#completed-tasks');
            const animationDuration = 300;

            function animateTaskMovement(taskItem, targetList, callback) {
                const initialPosition = taskItem.getBoundingClientRect();
                if (targetList.id === 'completed-tasks') {
                    targetList.insertBefore(taskItem, targetList.firstChild);
                } else {
                    targetList.appendChild(taskItem);
                }
                const finalPosition = taskItem.getBoundingClientRect();
                const deltaX = initialPosition.left - finalPosition.left;
                const deltaY = initialPosition.top - finalPosition.top;
                if (deltaX === 0 && deltaY === 0) {
                    callback?.();
                    return;
                }
                taskItem.style.setProperty('--initial-transform', `translate(${deltaX}px, ${deltaY}px)`);
                taskItem.style.setProperty('--animation-duration', `${animationDuration}ms`);
                taskItem.classList.add('animate-move');
                taskItem.addEventListener('animationend', () => {
                    taskItem.classList.remove('animate-move');
                    callback?.();
                }, { once: true });
            }

            function handleCheckboxChange(event) {
                const checkbox = event.target;
                const taskId = checkbox.dataset.id;
                const isCompleted = checkbox.checked;
                const taskItem = document.querySelector(`#task-item-${taskId}`);
                const targetList = isCompleted ? completedTasks : uncompletedTasks;
                fetch(`/tasks/${taskId}/toggle-complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ completed: isCompleted })
                })
                .then(response => {
                    if (!response.ok) {
                        checkbox.checked = !isCompleted;
                        alert('Failed to update task status');
                        return;
                    }
                    const taskTitle = taskItem.querySelector(`#task-title-${taskId}`);
                    animateTaskMovement(taskItem, targetList, () => {
                        if (isCompleted) {
                            taskTitle.classList.add('line-through');
                        } else {
                            taskTitle.classList.remove('line-through');
                        }
                    });
                });
            }

            document.querySelectorAll('.task-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', handleCheckboxChange);
            });
        });
    </script>
</div>
