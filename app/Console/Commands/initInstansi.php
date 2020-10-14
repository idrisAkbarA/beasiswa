<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Instansi;
use Faker\Factory as Faker;

class initInstansi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initInstansi';

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
        $faker = Faker::create("id_ID");
        // for ($i=0; $i < 10 ; $i++) { 
        //     $instansi = new Instansi;
        //     $instansi->name = $faker->text($maxNbChars = 20);
        //     $instansi->save();
        // }
        $instansi = new Instansi;
        $instansi->name ="UIN SUSKA RIAU";
        $instansi->save();

        echo "\nInstansi Created\n";
    }
}
