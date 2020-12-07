<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetDev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:dev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets everything like storage, db for development';

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
        $this->call('ce:clear', ['--storage' => 1]);
        $this->call('migrate:fresh');
        $this->call('db:seed');
        return 0;
    }
}
