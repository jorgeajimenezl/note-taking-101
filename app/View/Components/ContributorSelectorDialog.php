<?php

namespace App\View\Components;

use App\Models\Task;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContributorSelectorDialog extends Component
{
    public array $contributors;

    public function __construct(
        public int $taskId,
    ) {
        $this->contributors = Task::find($this->taskId)->contributors->all();
    }

    public function render(): View|Closure|string
    {
        return view('components.contributor-selector-dialog');
    }
}
