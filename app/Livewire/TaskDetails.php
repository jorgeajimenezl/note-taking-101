<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TaskDetails extends Component
{
    public Task $task;

    public string $role;

    #[Validate('required|string|min:5|max:255', message: 'The title must be at least 5 characters long and at most 255 characters long.')]
    public string $title;

    #[Validate('required|string', message: 'The description field is required.')]
    public string $description;

    #[Validate('array')]
    public array $tags = [];

    public function mount(Task $task)
    {
        $this->task = $task;

        $this->title = $task->title;
        $this->description = $task->description;
        $this->tags = $task->tags->pluck('id')->toArray();
        $this->role = $task->getUserRole(auth()->user());
    }

    public function checkRole(string ...$role)
    {
        if (! empty($role) && ! in_array($this->role, $role)) {
            abort(403);
        }
    }

    public function render()
    {
        $this->checkRole('editor', 'owner', 'viewer');

        return view('livewire.task-details');
    }

    public function updated($name, $value)
    {
        $this->checkRole('editor', 'owner');
        $this->validate();

        if ($name === 'tags') {
            $this->task->tags()->sync($value);
        } else {
            $this->task->update([$name => $value]);
        }
    }

    public function deleteTask()
    {
        $this->checkRole('owner');
        $this->task->delete();

        return redirect()->route('tasks.index');
    }
}
