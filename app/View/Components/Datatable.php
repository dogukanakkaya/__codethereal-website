<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Datatable extends Component
{

    /**
     * Create a new component instance.
     *
     * @param $url
     * @param $columns
     */
    public function __construct(public string $url, public array $columns) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.datatable');
    }
}
