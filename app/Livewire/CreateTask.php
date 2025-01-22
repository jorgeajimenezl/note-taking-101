<?php

namespace App\Livewire;

use App\Models\Tag;
use Livewire\Component;

class CreateTask extends Component
{
    public function render()
    {
        $allTags = Tag::where('user_id', auth()->id())->get();

        return view('livewire.create-task', compact('allTags'));
    }
}
