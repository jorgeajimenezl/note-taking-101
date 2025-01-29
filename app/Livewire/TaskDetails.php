<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TaskDetails extends Component
{
    use WithFileUploads;

    public Task $task;

    public string $role;

    #[Validate('required|string|min:5|max:255', message: [
        'required' => 'The title cannot be empty.',
        'string' => 'The title must be a string.',
        'min' => 'The title must be at least 5 characters.',
        'max' => 'The title must not exceed 255 characters.',
    ])]
    public string $title;

    #[Validate('nullable|string')]
    public ?string $description;

    #[Validate('nullable|file|max:10240', message: [
        'file' => 'The attachment must be a valid file.',
        'max' => 'The attachment must not exceed 10MB.',
    ])]
    public $attachment;

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
        $this->checkRole('editor', 'owner');
        $attachment->delete();
    }

    public function addAttachment(string $filename)
    {
        $this->checkRole('editor', 'owner');
        $this->validate();

        try {
            $this->task->addMedia($this->attachment->getRealPath())
                ->usingFileName($filename)
                ->toMediaCollection('attachments');
        } catch (\Exception $e) {
            $this->addError('attachment', 'The attachment could not be uploaded.');
            ray($e->getMessage())->error();
        }

        $this->attachment = null;
    }

    public function downloadAttachment(Request $request, Media $attachment)
    {
        $this->checkRole('editor', 'owner', 'viewer');

        return $attachment->toInlineResponse($request);
    }
}
