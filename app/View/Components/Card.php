<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public $title, $toolbar, $link, $footer;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title = null, $toolbar = "", $link = " ", $footer = null)
    {
        $this->title = $title;
        $this->toolbar = $toolbar;
        $this->link = $link;
        $this->footer = $footer;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card');
    }
}
