<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TagSelector extends Component
{
    public function __construct(
        public string $name,
        public string $label,
        public array $tags,
        public array $allTags,
        public ?string $id = 'tagsInput',
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.tag-selector');
    }
}
