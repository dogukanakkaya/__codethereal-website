<?php

namespace App\View\Components;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use JetBrains\PhpStorm\NoReturn;

class Dropzone extends Component
{
    public object|null $files;

    /**
     * Create a new component instance.
     *
     * @param string $inputName
     * @param string $folder
     * @param string|int $index
     * @param array|int $fileId
     * @param int|null $maxFiles
     * @param bool $sortable
     */
    #[NoReturn]
    public function __construct(
        public string $inputName,
        public string $folder = "/",
        public string|int $index = 1,
        private array|int $fileId = [],
        public int|null $maxFiles = null,
        public bool $sortable = false
    )
    {
        $this->files = DB::table('files')->whereIn('id', (array)$fileId)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.dropzone');
    }
}
