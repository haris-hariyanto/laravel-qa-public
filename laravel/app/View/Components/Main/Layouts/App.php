<?php

namespace App\View\Components\Main\Layouts;

use Illuminate\View\Component;

class App extends Component
{
    public $useRecaptcha;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($useRecaptcha = false)
    {
        $this->useRecaptcha = $useRecaptcha;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('main.layouts.app');
    }
}
