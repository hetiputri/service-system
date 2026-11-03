<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectCustom extends Component
{
    public function __construct(
        public array $options = [],
        public string $name = 'select',
        public string $placeholder = 'Pilih...',
        public mixed $selected = null,
    ) {}

    public function render()
    {
        return view('components.select-custom');
    }
}
