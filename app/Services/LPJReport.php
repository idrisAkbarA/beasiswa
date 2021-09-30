<?php

namespace App\Services;

use App\Beasiswa;
use App\LPJ;
use App\PemohonBeasiswa;
use App\PermohonanLPJ;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PDO;

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

        $query = $this->query();
        // reindex array key so it always return as an array
        // not as an object
        $array =  array_values($this->setField($query));
        $result = new Collection($array);
        return $result;
    }
    public function getArray()
    {

        $query = $this->query();
        // reindex array key so it always return as an array
        // not as an object
        $result =  array_values($this->setField($query));
        return $result;
    }
    private function generateStatus($value)
    {
        if ($value->is_lulus === null) {
            if ($value->is_submitted === null) {
                $statusTemp = "Belum Mengisi";
            } else {
                $statusTemp =
                    $value->is_submitted ? 'Menunggu Validasi' : 'Tidak Lengkap';
            }
        } else {
            $statusTemp = $value->is_lulus ? 'Lulus' : 'Tidak Lulus';
        }
        return $statusTemp;
    }
    private function setField(array $permohonans)
    {
        $fields = $this->filter['field_list'];
        $status = $this->filter['status'];
        $arrayDelete = [];
        // dd($fields);
        foreach ($permohonans as $key => $value) {
            $form = json_decode($permohonans[$key]->form);
            //filter status
            if ($status != "All") {
                if ($status != $this->generateStatus($value)) {
                    unset($permohonans[$key]);
                    continue;
                }
            }
            $statusField = "Status";
            $permohonans[$key]->$statusField = $this->generateStatus($value);

            // filter field
            if (!$fields) {
                unset($permohonans[$key]->form);
                unset($permohonans[$key]->is_lulus);
                unset($permohonans[$key]->is_submitted);
                continue;
            };
            foreach ($fields as $keyField => $field) {
                foreach ($form as $formKey => $formItem) {
                    if ($formItem->pertanyaan == $field) {
                        $permohonans[$key]->$field = $formItem->value;
                        break;
                    }
                }
            }
            unset($permohonans[$key]->form);
            unset($permohonans[$key]->is_lulus);
            unset($permohonans[$key]->is_submitted);
        }
        return $permohonans;
    }
    private function query()
    {
        $lpj_id = $this->filter['lpj'];
        $fakultas_id = $this->filter['fakultas'];
        $whereConditions = [];
        if ($lpj_id != "all") {
            $whereConditions[] = ["lpj_id", "=", $lpj_id];
        }
        if ($fakultas_id != "all") {
            $whereConditions[] = ["fakultas.id", "=", $fakultas_id];
        }
        $permohonansQuery = DB::table('permohonan_lpj')
            ->join('users', 'permohonan_lpj.mhs_id', '=', 'users.nim')
            ->join('LPJ', 'permohonan_lpj.lpj_id', '=', 'LPJ.id')
            ->join('beasiswas', 'LPJ.beasiswa_id', '=', 'beasiswas.id')
            ->join('jurusans', 'users.jurusan_id', '=', 'jurusans.id')
            ->join('fakultas', 'jurusans.fakultas_id', '=', 'fakultas.id')
            ->leftJoin('user_petugas', 'permohonan_lpj.verificator_id', '=', 'user_petugas.id')
            ->select(
                'permohonan_lpj.is_lulus',
                'permohonan_lpj.is_submitted',
                'permohonan_lpj.form',
                'users.nim as NIM',
                'users.nama as Nama Mahasiswa',
                // 'jurusans.id as jurusan_id',
                'jurusans.nama as Jurusan',
                // 'fakultas.id as fakultas_id',
                'fakultas.nama as Fakultas',
                // 'beasiswas.id as beasiswa_id',
                'beasiswas.nama as Beasiswa',
                // 'LPJ.id as lpj_id',
                'LPJ.nama as LPJ',
                'user_petugas.nama_lengkap as Verifikator',
                'permohonan_lpj.keterangan as Keterangan',

            );
        if (count($whereConditions) > 0) {
            $permohonansQuery->where($whereConditions);
        }
        $permohonans = ($permohonansQuery->get())->toArray();
        return $permohonans;
    }
}
