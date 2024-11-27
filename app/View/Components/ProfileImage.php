<?php

namespace App\View\Components;

use Illuminate\View\Component;
use LasseRafn\Initials\Initials;

class ProfileImage extends Component
{
    public $backgroundColor;

    public $initials;

    public function __construct(
        public string $name,
        public ?string $profilePicture = null,
    ) {
        $this->initials = (new Initials)->name($name)->generate();
    }

    public function render()
    {
        return view('components.profile-image');
    }
}
