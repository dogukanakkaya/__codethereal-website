<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RichEditor extends Component
{
    /**
     * Create a new component instance.
     *
     * @param string $name
     */
    public function __construct(public string $name)
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
