<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class initServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initServer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'init db server (lakukan hanya untuk pertama kali, db akan di migrate ulang soalnya)';

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
        $this->call("initInstansi");
        $this->call("initAdmin");
    }
}
