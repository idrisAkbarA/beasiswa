<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\GolonganUKT;

class initGolUkt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initGolUkt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        for ($i=0; $i < 7 ; $i++) { 
            GolonganUKT::create(['nama'=>$i+1]);
        }
        echo "Golongan UKT Created";
    }
}
