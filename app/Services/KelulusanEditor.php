<?php

namespace App\Services;

use App\PemohonBeasiswa;
use Illuminate\Support\Facades\DB;

class KelulusanEditor
{
    private $beasiswa;
    private $quota;
    public function __construct($beasiswaCollection)
    {
        $this->beasiswa = $beasiswaCollection;
        $this->quota = $beasiswaCollection->quota;
    }
    public function addMahasiswa($mahasiswaArray)
    {
        if ($mahasiswaArray == null || count($mahasiswaArray) == 0) {
            return;
        }
        DB::transaction(function () use ($mahasiswaArray) {
            $listPermohonan = [];
            $this->setQuota($mahasiswaArray);
            foreach ($mahasiswaArray as $key => $value) {
                $permohonan = PemohonBeasiswa::create(
                    [
                        'mhs_id'  => $value,
                        'beasiswa_id' => $this->beasiswa->id,
                        'is_submitted' => true,
                        'form' => '[]'
                    ]
                );
                array_push($listPermohonan, $permohonan);
            }
            $update = [
                'is_berkas_passed' => true,
                'is_selection_passed' => true,
                'is_interview_passed' => $this->beasiswa->is_interview ? 1 : null,
                'is_survey_passed' => $this->beasiswa->is_survey ? 1 : null,
            ];
            foreach ($listPermohonan as $permohonan) {
                $permohonan->update($update);
            }
        });
    }
    public function removeMahasiswa($mahasiswaArray)
    {
        if ($mahasiswaArray == null || count($mahasiswaArray) == 0) {
            return;
        }
        PemohonBeasiswa::whereIn('id', $mahasiswaArray)->delete();
    }
    private function setQuota($mahasiswaArray)
    {

        $permohonanInDBCount = count(PemohonBeasiswa::where('beasiswa_id', $this->beasiswa->id)->get());
        $permohonanNewCount = count($mahasiswaArray);
        if ($permohonanInDBCount + $permohonanNewCount > $this->quota) {
            $this->beasiswa->quota = $permohonanInDBCount + $permohonanNewCount;
            $this->beasiswa->save();
        }
    }
}
