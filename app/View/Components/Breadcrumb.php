<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public array $navigations;
    /**
     * Create a new component instance.
     *
     * @param $nav
     */
    public function __construct($nav)
    {
        $this->navigations = array(route('admin.home') => __('global.home')) + $nav;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.breadcrumb');
    }
}
