<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TomSelect extends Component
{
    public $type;
    public $labelField;
    public $valueField;
    public $name;
    public $placeholder;
    public $multiple;
    public $selected;

    public function __construct(
        $type,
        $labelField,
        $valueField,
        $name,
        $placeholder = 'Pilih Data',
        $multiple = false,
        $selected = null
    ) {
        $this->type = $type;
        $this->labelField = $labelField;
        $this->valueField = $valueField;
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->multiple = $multiple;
        $this->selected = $selected;
    }

    public function render()
    {
        return view('components.tom-select');
    }
}
