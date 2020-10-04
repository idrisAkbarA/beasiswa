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
        $fakName = [
            "Sains dan Teknologi",
            "Tarbiyah dan Keguruan",
            "Syariah dan Hukum",
            "Ushuluddin",
            "Dakwah dan Komunikasi",
            "Psikologi",
            "Ekonomi dan Ilmu Sosial",
            "Pertanian dan Peternakan",
        ];
        $fak_name_uppercase =  [];

        foreach ($fakName as $key => $value) {
            array_push($fak_name_uppercase, strtoupper($value));
        }

        foreach ($fak_name_uppercase as $key => $value) {
            $words = explode(" DAN ",$value);
            // var_dump($words);
            if(count($words)>2){
                $first_letter = substr($words[0],0,1);
                $second_letter = substr($words[2],0,1);
                Fakultas::create([
                    'nama' => $value,
                    'singkatan'=> "F".$first_letter.$second_letter
                ]);
            }else if(count($words) >1){
                $first_letter = substr($words[0],0,1);
                $second_letter = substr($words[1],0,1);
                Fakultas::create([
                    'nama' => $value,
                    'singkatan'=> "F".$first_letter.$second_letter
                ]);
            }
            else{
                $first_letter = substr($words[0],0,2);
                // $second_letter = substr($words[0],1,2);
                Fakultas::create([
                    'nama' => $value,
                    'singkatan'=> "F".$first_letter
                ]);
            }
        }
        

        // $faker = Faker::create("id_ID");
        // for ($i=0; $i < 8 ; $i++) {
        //     Fakultas::create([
        //         'nama' => $faker->text(20),
        //         'singkatan' => $faker->text(5)
        //     ]);
        // }

        echo "\nFakultas Created\n";
    }
}
