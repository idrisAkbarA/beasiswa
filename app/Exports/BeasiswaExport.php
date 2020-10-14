<?php

namespace App\Exports;

use App\Beasiswa;
use App\Instansi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Schema;
class BeasiswaExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private array $keys;
    public function __construct(
            $beasiswa = "all", // all, 0, 1, 2 ...
            $fakultas = "all", // all, 0, 1, 2 ...
            $status = "all", // gagal, lulus
            $tahap = "all" // berkas, wawancara, survey, seleksi
        )
    {
        $this->beasiswa = $beasiswa;
        $this->fakultas = $fakultas;
        $this->status = $status;
        $this->tahap = $tahap;
        $this->keys = [];
    }
    public function collection()
    {
        //beasiswa.test/api/beasiswa/download-report
        $instansi = Instansi::all();
        $beasiswa = Beasiswa::all();
        // $this->keys = Schema::getColumnListing('beasiswas');
        // $this->keys = array_keys((array)$beasiswa[0]);
        $beasiswa->map(function ($item, $key) {
            $instansi = Instansi::find($item['instansi_id']);
            $item['instansi_id'] = $instansi['name'];
        });
        // return $this->keys;
        return $beasiswa;
        
    }
    public function headings(): array
    {
        $key = $this->keys;
        return $key;
    }
}
