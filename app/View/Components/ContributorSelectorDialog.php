<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContributorSelectorDialog extends Component
{
    public function __construct(
        public array $contributors,
        public int $taskId,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.contributor-selector-dialog');
    }
}
