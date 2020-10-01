<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Beasiswa;
use Faker\Factory as Faker;
class initBeasiswa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initBeasiswa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initialize beasiswa mock data';

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
        for ($i=0; $i < 10 ; $i++) {
            $beasiswa = new Beasiswa;
            $beasiswa->nama = $faker->text($maxNbChars = 10);
            $beasiswa->deskripsi = $faker->sentence;
            $beasiswa->instansi_id = random_int(1, 10);
            $beasiswa->is_interview = $faker->boolean($chanceOfGettingTrue = 50);
            $beasiswa->is_survey = $faker->boolean($chanceOfGettingTrue = 50);
            $beasiswa->quota = $faker->randomDigit;
            $beasiswa->fields = "[]";
            $beasiswa->save();
        }

        echo "\nBeasiswa Created\n";

    }
}
