<?php

namespace App\Exports;

use App\PemohonBeasiswa;
use Maatwebsite\Excel\Concerns\FromCollection;

class PemohonExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PemohonBeasiswa::with("beasiswa")->get();
    }
}
