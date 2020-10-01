<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Fakultas;
use Faker\Factory as Faker;

class initFakultas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initFakultas';

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
        for ($i=0; $i < 8 ; $i++) {
            Fakultas::create([
                'nama' => $faker->text(20),
                'singkatan' => $faker->text(5)
            ]);
        }

        echo "\nFakultas Created\n";
    }
}
