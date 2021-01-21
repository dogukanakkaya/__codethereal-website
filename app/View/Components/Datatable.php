<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Datatable extends Component
{

    /**
     * Create a new component instance.
     *
     * @param string $url
     * @param array $columns
     * @param bool $deleteChecked
     */
    public function __construct(public string $url, public array $columns, public bool $deleteChecked = true)
    {
        if ($this->deleteChecked)
        {
            array_unshift($this->columns, ['data' => 'check_all', 'name' => 'check_all', 'title' => '<input type="checkbox" onclick="__checkAll()" id="check-all"/>', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']);
        }
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
