<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the storage directory';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ignoreFiles = ['public/.gitignore'];
        $files = Storage::allFiles('public');
        foreach ($files as $key => $file) {
            if (in_array($file, $ignoreFiles)){
                unset($files[$key]);
            }
        }
        Storage::delete($files);
        $this->info('Storage cleared successfully.');
        return 0;
    }
}
