<x-site-layout title="Tasks">
    <div class="container mx-auto flex justify-center">
        <ul class="w-3/4">
            <ul id="uncompleted-tasks">
                @foreach ($uncompletedTasks as $task)
                <li id="task-item-{{ $task->id }}" class="border-b border-gray-300 py-2">
                    <input type="checkbox" name="task" value="{{ $task->id }}" class="mr-2 task-checkbox" data-id="{{ $task->id }}">
                    <a href="{{ route('task.show', $task) }}" id="task-title-{{ $task->id }}" class="hover:underline">{{ $task->title }}</a>
                </li>
                @endforeach
            </ul>
            <ul id="completed-tasks">
                @foreach ($completedTasks as $task)
                <li id="task-item-{{ $task->id }}" class="border-b border-gray-300 py-2">
                    <input type="checkbox" name="task" value="{{ $task->id }}" class="mr-2 task-checkbox" data-id="{{ $task->id }}" checked>
                    <a href="{{ route('task.show', $task) }}" id="task-title-{{ $task->id }}" class="line-through hover:underline">{{ $task->title }}</a>
                </li>
                @endforeach
            </ul>
        </ul>
    </div>

    <style>
        @keyframes moveTask {
            from {
                transform: var(--initial-transform);
            }
            to {
                transform: translate(0, 0);
            }
        }
        .animate-move {
            animation: moveTask var(--animation-duration) ease;
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

                // Calculate the delta and set CSS variables
                const deltaX = initialPosition.left - finalPosition.left;
                const deltaY = initialPosition.top - finalPosition.top;

                taskItem.style.setProperty('--initial-transform', `translate(${deltaX}px, ${deltaY}px)`);
                taskItem.style.setProperty('--animation-duration', `${animationDuration}ms`);

                // Add the animation class
                taskItem.classList.add('animate-move');

                // Listen for the end of the animation
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

                fetch(`/task/${taskId}/toggle-complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ completed: isCompleted })
                })
                .then(response => {
                    if (!response.ok) {
                        checkbox.checked = !isCompleted; // Revert state on failure
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

            // Attach event listeners to all checkboxes
            document.querySelectorAll('.task-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', handleCheckboxChange);
            });
        });
    </script>
</x-site-layout>
