<?php

namespace App\Livewire;

use App\Models\Tag;
use Livewire\Component;

class TagSelector extends Component
{
    public string $name;

    public ?string $id;

    public array $tags = [];

    public ?string $searchTag = '';

    public ?bool $readonly = false;

    public bool $showDialog = false;

    public function render()
    {
        if ($this->searchTag === '') {
            $filteredTags = [];
        } else {
            $filteredTags = Tag::where('user_id', auth()->id())
                ->where('name', 'like', "%$this->searchTag%")->get();
        }

        return view('livewire.tag-selector', compact('filteredTags'));
    }

    public function deleteTag(Tag $tag)
    {
        $this->tags = array_diff($this->tags, [$tag]);
        $this->dispatch('tags-updated', $this->tags);
    }

    public function addTag(Tag $tag)
    {
        if (! in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
            $this->dispatch('tags-updated', $this->tags);
        }
        $this->showDialog = false;
    }

    public function updateDialogVisibility(bool $showDialog)
    {
        $this->showDialog = $showDialog;
    }
}
