<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Clear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ce:clear {--cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all the cached files';

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
        $cache = $this->option('cache');

        // Clear everything laravel provides us
        $this->call('optimize:clear');
        $this->call('event:clear');

        // Clear 3rd party libraries caches
        $this->call('route:trans:clear');

        if ($cache){
            // Cache everything laravel provides us
            $this->call('optimize');
            $this->call('event:cache');

            // Cache 3rd party libraries
            $this->call('route:trans:cache');
        }

        return 0;
    }
}
