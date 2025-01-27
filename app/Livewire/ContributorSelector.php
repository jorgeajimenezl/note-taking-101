<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContributorSelector extends Component
{
    public Task $task;

    #[Validate('email|exists:users,email')]
    public string $email = '';

    #[Validate('required_if:action,add|string|in:admin,editor,viewer')]
    public string $role = 'viewer';

    public function mount(
        Task $task,
    ) {
        if (auth()->id() === null || $task->author_id !== auth()->id()) {
            abort(403);
        }

        $this->task = $task;
    }

    public function render()
    {
        return view('livewire.contributor-selector')->with([
            'contributors' => $this->task->contributors()
                ->with('user:id,name,email')
                ->get()
                ->pluck('user'),
        ]);
    }

    public function addContributor()
    {
        $this->validate();
        $contributorUser = User::where('email', $this->email)->first(['id', 'name', 'email']);

        if ($contributorUser->id === auth()->id()) {
            $this->addError('email', 'You cannot add yourself as a contributor');

            return;
        }

        if ($contributorUser === null) {
            $this->addError('email', 'User not found');

            return;
        }

        if ($this->task->contributors()->where('user_id', $contributorUser->id)->exists()) {
            $this->addError('email', 'This user is already a contributor');

            return;
        }

        $this->task->contributors()->create([
            'user_id' => $contributorUser->id,
            'role' => $this->role,
        ]);
    }

    public function removeContributor(string $email)
    {
        $validatedData = Validator::validate(
            ['email' => $email],
            ['email' => 'required|email|exists:users,email'],
        );

        $contributorUser = User::where('email', $validatedData['email'])->first(['id']);

        if ($contributorUser === null) {
            $this->addError('email', 'User not found');

            return;
        }

        $this->task->contributors()->where('user_id', $contributorUser->id)->delete();
    }
}
