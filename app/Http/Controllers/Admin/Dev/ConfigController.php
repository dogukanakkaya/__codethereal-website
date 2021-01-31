<?php

namespace App\Http\Controllers\Admin\Dev;

use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    private array $directories = [
        'config',
        'resources/lang'
    ];

    private array $restrict = [
        'config/app.php',
        'config/auth.php',
        'config/broadcasting.php',
        'config/cache.php',
        'config/cors.php',
        'config/database.php',
        'config/filesystems.php',
        'config/hashing.php',
        'config/laravellocalization.php',
        'config/logging.php',
        'config/mail.php',
        'config/permission.php',
        'config/queue.php',
        'config/services.php',
        'config/session.php',
        'config/view.php',
    ];

    public function index()
    {
        $dirs = [];
        foreach ($this->directories as $folder) {
            $dirs[$folder] = $this->createTree(base_path($folder));
        }
        $dirTree = $this->createHtmlTree($dirs);
        $data = [
            'navigations' => ['Config'],
            'dirTree' => $dirTree
        ];
        return view('admin.config.index', $data);
    }

    public function find()
    {
        $path = request()->get('path');
        if (in_array($path, $this->restrict)){
            return resJsonUnauthorized();
        }
        return resJson(1, ['content' => file_get_contents(base_path($path))]);
    }

    public function update()
    {
        $path = request()->get('path');
        if (in_array($path, $this->restrict)){
            return resJsonUnauthorized();
        }
        return resJson(file_put_contents(base_path($path), request()->get('content')) !== false);
    }

    /**
     * Create folder tree for given paths
     *
     * @param string $path
     * @return array
     */
    private function createTree(string $path): array
    {
        $structure = [];
        $dirItems = scandir($path);
        foreach ($dirItems as $item) {
            if ($item === '.' || $item === '..')
                continue;

            if (is_file($path . '/' . $item)) {
                $structure[] = $item;
            } else if (is_dir($path . '/' . $item)) {
                $structure[$item] = $this->createTree($path . '/' . $item);
            }
        }
        return $structure;
    }

    /**
     * Return html output of given directory structure
     *
     * @param array $dirStructure
     * @param string|null $folder
     * @return string
     */
    private function createHtmlTree(array $dirStructure, string $folder = null): string
    {
        $folder ??= config('app.name');
        // Only open first directories (directories that we defined inside this class)
        $className = in_array($folder, $this->directories) || $folder === config('app.name') ? 'open' : '';

        $explodeSlashes = explode('/', $folder);
        $lastFolderName = end($explodeSlashes);

        $html = '<ul class="directory ' . $className . '"> <span onclick="this.closest(`.directory`).classList.toggle(`open`)"><i class="material-icons">folder</i> ' . $lastFolderName . '</span>';

        foreach ($dirStructure as $folderName => $file) {
            if (is_array($file)) {
                $html .= $this->createHtmlTree($file, $folder . '/' . $folderName);
            } else {
                // Remove config('app.name') variable from value (upper folder)
                $path = str_replace(config('app.name') . '/', '', $folder . '/' . $file);
                if (in_array($path, $this->restrict)){
                    continue;
                }
                $html .= '<li onclick="__find(`' . $path . '`)"><i class="material-icons-outlined md-18">insert_drive_file</i> ' . $file . '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }
}
