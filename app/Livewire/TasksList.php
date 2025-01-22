<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;

class TasksList extends Component
{
    public $uncompletedTasks;

    public $completedTasks;

    public $sharedTasks;

    public string $search = '';

    public function render()
    {
        $ownTasks = Task::where('author_id', auth()->id())
            ->where('title', 'like', "%$this->search%")
            ->get()
            ->sortBy('created_at')
            ->partition(function ($task) {
                return ! $task->isCompleted();
            });

        $sharedTasks = Task::select('tasks.id', 'tasks.title', 'contributors.role as user_role')
            ->join('contributors', 'tasks.id', '=', 'contributors.task_id')
            ->where('contributors.user_id', auth()->id())
            ->where('tasks.title', 'like', "%$this->search%")
            ->get()
            ->sortBy('created_at');

        $this->uncompletedTasks = $ownTasks[0];
        $this->completedTasks = $ownTasks[1];
        $this->sharedTasks = $sharedTasks;

        return view('livewire.tasks-list');
    }
}
