<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class initAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initialize all test data';

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
        $this->call("migrate:fresh");
        $this->call("initFakultas");
        $this->call("initAdmin");
        $this->call("initInstansi");
        $this->call("initBeasiswa");
        $this->call("initMhs");
    }
}
