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
            $beasiswa->fields = "[{\"date\": false, \"type\": \"Jawaban Pendek\", \"index\": 1, \"value\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": true}, \"required\": true, \"pertanyaan\": \"Alamat\"}, {\"date\": false, \"type\": \"Jawaban Angka\", \"index\": 2, \"value\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"pertanyaan\": \"No. HP\"}, {\"date\": false, \"type\": \"Paragraf\", \"index\": 3, \"value\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"pertanyaan\": \"Motivasi mengikuti beasiswa ini\"}, {\"date\": false, \"type\": \"Upload File\", \"index\": 4, \"value\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"pertanyaan\": \"Scan KTP\"}, {\"date\": false, \"type\": \"Pilihan\", \"index\": 5, \"value\": null, \"pilihan\": {\"items\": [{\"label\": \"Bersama Orangtua\"}, {\"label\": \"Dalam Perantauan (Kost,kontrakan, dll)\"}], \"required\": true}, \"required\": true, \"pertanyaan\": \"Status tempat tinggal sekarang\"}, {\"date\": false, \"type\": \"Tanggal\", \"index\": 6, \"value\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"pertanyaan\": \"Tanggal Lahir\"}]";
            $beasiswa->save();
        }{
}

        echo "\nBeasiswa Created\n";

    }
}
