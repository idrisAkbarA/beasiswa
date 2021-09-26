<?php

namespace App\Services;

use App\Beasiswa;
use App\LPJ;
use App\PemohonBeasiswa;
use App\PermohonanLPJ;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LPJReport
{
    protected $filter;
    protected $beasiswa;
    protected $lpj;
    public function __construct($filter)
    {
        $this->filter = $filter;
    }
    public function getCollection()
    {
        $lpj_id = $this->filter['lpj'];
        $fakultas_id = $this->filter['fakultas'];
        $whereConditions = [];
        $withConditions = ["lpj"];
        if ($lpj_id != "semua") {
            $whereConditions["LPJ.id"] = $lpj_id;
            // dd($whereConditions);
        }
        if ($fakultas_id != "semua") {
            $whereConditions["fakultas.id"] = $lpj_id;
        }
        // dd($whereConditions);
        $permohonans = DB::table('permohonan_lpj')
            ->join('users', 'permohonan_lpj.mhs_id', '=', 'users.nim')
            ->join('LPJ', 'permohonan_lpj.lpj_id', '=', 'LPJ.id')
            ->join('beasiswas', 'LPJ.beasiswa_id', '=', 'beasiswas.id')
            ->join('jurusans', 'users.jurusan_id', '=', 'jurusans.id')
            ->join('fakultas', 'jurusans.id', '=', 'fakultas.id')
            ->select(
                'permohonan_lpj.*',
                'users.nim',
                'users.nama as mhs_nama',
                'jurusans.id as jurusan_id',
                'jurusans.nama as jurusan_nama',
                'fakultas.id as fakultas_id',
                'fakultas.nama as fakultas_nama',
                'beasiswas.id as beasiswa_id',
                'beasiswas.nama as beasiswa_nama',
                'LPJ.id as lpj_id',
                'LPJ.nama as lpj_nama',

            )
            ->where($whereConditions)
            ->get();
        // $permohonan = (PermohonanLPJ::with($withConditions)->where($whereConditions)->get())->toArray();
        // $permohonan = (PermohonanLPJ::with(["mahasiswa", "lpj"])->where($conditions)->get());
        dd($permohonans);
        $result = [];
        return $permohonans;
        return $this->filter;
    }
}
