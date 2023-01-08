<?php

namespace App\View\Components\Admin\Layouts;

use Illuminate\View\Component;

class App extends Component
{
    public $breadcrumb;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($breadcrumb = false)
    {
        $this->breadcrumb = $breadcrumb;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('admin.layouts.app');
    }
}
