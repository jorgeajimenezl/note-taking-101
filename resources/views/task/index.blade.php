<x-app-layout title="Tasks">
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #e9ecef;
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            display: flex;
            align-items: center;
        }

        li.completed {
            background-color: #d4edda;
            text-decoration: line-through;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
        
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

                // Calculate the delta and set CSS variables
                const deltaX = initialPosition.left - finalPosition.left;
                const deltaY = initialPosition.top - finalPosition.top;

                if (deltaX === 0 && deltaY === 0) {
                    callback?.();
                    return;
                }

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
</x-app-layout>
