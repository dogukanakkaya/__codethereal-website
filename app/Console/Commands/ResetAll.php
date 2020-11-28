<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets everything and build again from scratch';

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
        $this->call('ce:clear');
        $this->call('migrate:fresh');
        $this->call('db:seed');
        return 0;
    }
}
