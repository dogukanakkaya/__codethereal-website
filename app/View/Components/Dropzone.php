<?php

namespace App\View\Components;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class Dropzone extends Component
{
    public $index;
    private int $fileId;
    public int $maxFiles;
    public $file;
    public string $folder;
    public string $inputName;

    /**
     * Create a new component instance.
     *
     * @param $folder
     * @param $inputName
     * @param int $index
     * @param int $fileId
     * @param int $maxFiles
     */
    public function __construct($folder, $inputName, $index = 1, $fileId = 0, $maxFiles = 1)
    {
        $this->index = $index;
        $this->fileId = $fileId;
        $this->folder = $folder;
        $this->maxFiles = $maxFiles;
        $this->inputName = $inputName;
        $this->file = DB::table('files')->where('id', $fileId)->first();
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
