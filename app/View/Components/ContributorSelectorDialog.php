<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContributorSelectorDialog extends Component
{
    public function __construct(
        public string $name,
        public string $label,
        public array $contributors,
        public array $allContributors,
        public ?string $id = 'contributorsInput',
        public ?bool $readonly = false,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.contributor-selector-dialog');
    }
}
