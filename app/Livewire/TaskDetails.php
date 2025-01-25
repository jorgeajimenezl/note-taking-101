<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TaskDetails extends Component
{
    public Task $task;

    public string $role;

    #[Validate('required|string|min:5|max:255', message: 'The title must be at least 5 characters long and at most 255 characters long.')]
    public string $title;

    #[Validate('required|string', message: 'The description field is required.')]
    public string $description;

    public function mount(Task $task)
    {
        $this->task = $task;

        $this->title = $task->title;
        $this->description = $task->description;
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

        $this->task->update([$name => $value]);
    }

    #[On('tags-updated')]
    public function updateTags($tags)
    {
        $this->checkRole('editor', 'owner');

        // TODO: Improve this to avoid multiple queries
        $this->task->tags()->sync(array_map(function ($tag) {
            return $tag['id'];
        }, $tags));
    }

    public function deleteTask()
    {
        $this->checkRole('owner');
        $this->task->delete();

        return redirect()->route('tasks.index');
    }

    public function deleteAttachment(Media $attachment)
    {
        ray($attachment);
        $this->checkRole('editor', 'owner');
        $attachment->delete();
    }
}
