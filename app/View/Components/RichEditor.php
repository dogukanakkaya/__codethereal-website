<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RichEditor extends Component
{
    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param bool $basic
     */
    public function __construct(public string $name, public bool $basic = false)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.rich-editor');
    }
}
