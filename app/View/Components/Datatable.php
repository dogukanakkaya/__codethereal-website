<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Datatable extends Component
{
    public string $url;
    public array $columns;

    /**
     * Create a new component instance.
     *
     * @param $url
     * @param $columns
     */
    public function __construct($url, $columns)
    {
        $this->url = $url;
        $this->columns = $columns;
    }

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
