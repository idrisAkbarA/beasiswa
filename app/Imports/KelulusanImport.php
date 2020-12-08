<?php

namespace App\Imports;

use App\Beasiswa;
use App\PemohonBeasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KelulusanImport implements ToCollection, WithHeadingRow

{
    use Importable;

    protected $_beasiswa = null;

    public function __construct(Beasiswa $beasiswa)
    {
        $this->_beasiswa = $beasiswa;
    }

    /**
     * @param collection $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $listPermohonan = [];
        foreach ($rows as $row) {
            $nim = $row['nim'];

            if (!$nim)
                return;

            $permohonan = PemohonBeasiswa::create(
                [
                    'mhs_id'  => $nim,
                    'beasiswa_id' => $this->_beasiswa->id,
                    'is_submitted' => true,
                    'form' => '[]'
                ]
            );
            array_push($listPermohonan, $permohonan);
        }
        $update = [
            'is_berkas_passed' => true,
            'is_selection_passed' => true,
            'is_interview_passed' => $this->_beasiswa->is_interview ? 1 : null,
            'is_survey_passed' => $this->_beasiswa->is_survey ? 1 : null,
        ];
        foreach ($listPermohonan as $permohonan) {
            $permohonan->update($update);
        }
    }
}
