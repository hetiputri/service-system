<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FileButton extends Component
{
    public $url;
    public $theme;

    public function __construct($url = null,$theme = 'blue')
    {
        if (!empty($url)) {
            $this->url = str_starts_with($url, 'http')
                ? $url
                : 'https://' . ltrim($url, '/');
        } else {
            $this->url = null;
        }
        $this->theme = $theme;
    }

    public function render()
    {
        return view('components.file-button');
    }
}