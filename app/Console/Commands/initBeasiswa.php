<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Beasiswa;
use Faker\Factory as Faker;
use Carbon\CarbonImmutable;

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
        for ($i = 0; $i < 15; $i++) {

            $date = CarbonImmutable::createFromTimeStamp($faker->dateTimeBetween('-7 days', '+7 days')->getTimestamp());

            $awal_berkas = $date;
            $akhir_berkas = $awal_berkas->add(2, 'day');

            $awal_interview = $akhir_berkas->add(2, 'day');
            $akhir_interview = $awal_interview->add(2, 'day');

            $awal_survey = $akhir_interview->add(2, 'day');
            $akhir_survey = $awal_survey->add(2, 'day');

            $beasiswa = new Beasiswa;
            $beasiswa->nama = $faker->sentence($nbWords = random_int(1, 10), $variableNbWords = true);
            $beasiswa->deskripsi = $faker->realText($maxNbChars = 200, $indexSize = 2);
            $beasiswa->instansi_id = random_int(1, 8);
            $beasiswa->is_interview = $faker->boolean($chanceOfGettingTrue = 50);
            $beasiswa->is_survey = $faker->boolean($chanceOfGettingTrue = 50);
            $beasiswa->quota = $faker->randomDigit;
            $beasiswa->awal_berkas = $awal_berkas;
            $beasiswa->akhir_berkas = $akhir_berkas;
            $beasiswa->awal_interview = $awal_interview;
            $beasiswa->akhir_interview = $akhir_interview;
            $beasiswa->awal_survey = $awal_survey;
            $beasiswa->akhir_survey = $akhir_survey;
            $beasiswa->fields = "[{\"date\": false, \"type\": \"Jawaban Pendek\", \"index\": 1, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": true}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"Test\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Jawaban Angka\", \"index\": 2, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"Test123\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Paragraf\", \"index\": 3, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"Par123\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Upload File\", \"index\": 4, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"UP\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Multiple Upload\", \"index\": 5, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"UpnUp\", \"multiUpload\": {\"items\": [{\"label\": \"Up\", \"value\": null, \"isSelected\": false}, {\"label\": \"UPUPUp\", \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Pilihan\", \"index\": 6, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": \"asdasd\"}, {\"label\": \"asdas\"}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"pil123\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Checkboxes\", \"index\": 7, \"value\": [], \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": \"asdasd\"}, {\"label\": \"asdasd\"}]}, \"pertanyaan\": \"are you\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Tanggal\", \"index\": 8, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"tanggal;l\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}]";
            $beasiswa->semester = ["1","1,3,5","7","9",null][random_int(0,4)];
            $beasiswa->is_first = random_int(0, 1);
            $beasiswa->jenjang = [[0],[0,1]][random_int(0, 1)];
            $beasiswa->ukt = [1, 2, 3][random_int(0, 2)];
            $beasiswa->save();
        }
        for ($i = 0; $i < 20; $i++) {

            $date = CarbonImmutable::createFromTimeStamp($faker->dateTimeBetween('-14 days', '+14 days')->getTimestamp());
            $awal_berkas = $date;
            $akhir_berkas = $awal_berkas->add(2, 'day');

            $awal_interview = $akhir_berkas->add(2, 'day');
            $akhir_interview = $awal_interview->add(2, 'day');

            $awal_survey = $akhir_interview->add(2, 'day');
            $akhir_survey = $awal_survey->add(2, 'day');

            $beasiswa = new Beasiswa;
            $beasiswa->nama = $faker->sentence($nbWords = random_int(1, 10), $variableNbWords = true);
            $beasiswa->deskripsi = $faker->realText($maxNbChars = 200, $indexSize = 2);
            $beasiswa->instansi_id = random_int(1, 8);
            $beasiswa->is_interview = 1;
            $beasiswa->is_survey = 1;
            $beasiswa->quota = $faker->randomDigit;
            $beasiswa->awal_berkas = $awal_berkas;
            $beasiswa->akhir_berkas = $akhir_berkas;
            $beasiswa->awal_interview = $awal_interview;
            $beasiswa->akhir_interview = $akhir_interview;
            $beasiswa->awal_survey = $awal_survey;
            $beasiswa->akhir_survey = $akhir_survey;
            $beasiswa->fields = "[{\"date\": false, \"type\": \"Jawaban Pendek\", \"index\": 1, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": true}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"Test\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Jawaban Angka\", \"index\": 2, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"Test123\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Paragraf\", \"index\": 3, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"Par123\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Upload File\", \"index\": 4, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"UP\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Multiple Upload\", \"index\": 5, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"UpnUp\", \"multiUpload\": {\"items\": [{\"label\": \"Up\", \"value\": null, \"isSelected\": false}, {\"label\": \"UPUPUp\", \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Pilihan\", \"index\": 6, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": \"asdasd\"}, {\"label\": \"asdas\"}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"pil123\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Checkboxes\", \"index\": 7, \"value\": [], \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": \"asdasd\"}, {\"label\": \"asdasd\"}]}, \"pertanyaan\": \"are you\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}, {\"date\": false, \"type\": \"Tanggal\", \"index\": 8, \"value\": null, \"isLulus\": null, \"pilihan\": {\"items\": [{\"label\": null}], \"required\": false}, \"required\": true, \"checkboxes\": {\"items\": [{\"label\": null}]}, \"pertanyaan\": \"tanggal;l\", \"multiUpload\": {\"items\": [{\"label\": null, \"value\": null, \"isSelected\": false}]}}]";

            $beasiswa->semester = null;
            $beasiswa->is_first = 0;
            $beasiswa->jenjang = [1];
            $beasiswa->ukt = 5;
            $beasiswa->save();
        }

        echo "\nBeasiswa Created\n";
    }
}
