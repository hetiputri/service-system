<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectMultiple extends Component
{
    /**
     * Create a new component instance.
     */
    public $options;

    public $label;

    public $required;

    public function __construct($options = [], $label = null, $required = null)
    {
        $this->options = $options;
        $this->label = $label;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-multiple');
    }
}
