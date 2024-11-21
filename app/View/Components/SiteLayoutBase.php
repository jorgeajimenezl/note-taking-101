<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SiteLayoutBase extends Component
{
    public $title;

    public function __construct(?string $title = null)
    {
        $this->title = ucfirst($title);
    }

    public function render(): View|Closure|string
    {
        return view('layouts.site.base');
    }
}
