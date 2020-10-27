<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Beasiswa;
use App\PemohonBeasiswa;
class movePermohonanBySemester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movePermohonanBySemester { semester : Semester mahasiswa yang akan dipindahkan } { from_id : Asal beasiswa } { to_id : Target beasiswa}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pindahkan mahasiswa semester x dari beasiswa_id awal ke beasiswa_id target';

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
        $from_id = $this->argument('from_id');
        $to_id = $this->argument('to_id');
        $semester = $this->argument('semester');

        // get all permohonan by beasiswa id
        $beasiswa_to = Beasiswa::find($to_id);
        $beasiswa_from = Beasiswa::where("id", $from_id)
            ->get()
            ->makeVisible(["lulus", "permohonan"]);


        echo "\n";
        echo "Moving from \033[01;31m" . $beasiswa_from[0]['nama'] . "\033[0m to \033[01;31m" . $beasiswa_to['nama'] . "\033[0m\n";

        echo "getting all records..\n\n";
        // process moving records
        $beasiswa_from->map(function ($item, $key) use ($from_id, $to_id, $semester) {
            $total_occurence = 0;
            // loop all permohonan inside beasiswa
            foreach ($item['permohonan'] as $key => $value) {
                $nim_in_loop = $value['mahasiswa']['nim'];
                $current_iteration_semester = $value['mahasiswa']['semester'];
                $is_same_as_target_semester = $semester == $current_iteration_semester ? 0:1;

                if ($is_same_as_target_semester == 0) { // if matched 
                    $total_occurence += 1;
                    echo "\n Moving " . $value['mahasiswa']['nim'];
                    $matched_pemohon = PemohonBeasiswa::where(["beasiswa_id" => $from_id, "mhs_id" => $value['mahasiswa']['nim']])
                        ->first();
                    $matched_pemohon['beasiswa_id'] = $to_id;
                    $new_pemohon = PemohonBeasiswa::create(json_decode(json_encode($matched_pemohon), true));
                    $temp = PemohonBeasiswa::find($matched_pemohon['id']);
                    $temp->delete();
                    echo " | Done";
                    echo "\n";
                }
            }
            echo "Total moved " . $total_occurence;
        });
        return 0;
    }
}
