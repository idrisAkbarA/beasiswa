<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Beasiswa extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'permohonan',
        'berkas',
        'interview',
        'survey',
        'selection',
        'lulus'
    ];

    protected $appends = [
        'berkas',
        'interview',
        'survey',
        'selection',
        'lulus'
    ];

    public function getBerkasAttribute()
    {
        $petugas = Auth::guard('petugas')->user();
        return $this->permohonan
            ->whereNull('is_berkas_passed')
            ->where('mahasiswa.fakultas.id', $petugas->fakultas_id);
    }

    public function getInterviewAttribute()
    {
        if ($this->is_interview) {
            return $this->permohonan
                ->where('is_berkas_passed', 1)
                ->whereNull('is_interview_passed');
        }
        return [];
    }

    public function getSurveyAttribute()
    {
        if ($this->is_survey) {
            return $this->permohonan
                ->where('is_berkas_passed', 1)
                ->when($this->is_interview == 1, function ($q) {
                    return $q->where('is_interview_passed', 1);
                })
                ->whereNull('is_survey_passed');
        }
        return [];
    }

    public function getSelectionAttribute()
    {
        return $this->permohonan
            ->where('is_berkas_passed', 1)
            ->when($this->is_interview == 1, function ($q) {
                return $q->where('is_interview_passed', 1);
            })
            ->when($this->is_survey == 1, function ($q) {
                return $q->where('is_survey_passed', 1);
            })
            ->where('is_selection_passed', '!=', 1);
    }

    public function getLulusAttribute()
    {
        return $this->permohonan
            ->where('is_berkas_passed', 1)
            ->when($this->is_interview == 1, function ($q) {
                return $q->where('is_interview_passed', 1);
            })
            ->when($this->is_survey == 1, function ($q) {
                return $q->where('is_survey_passed', 1);
            })
            ->where('is_selection_passed', 1);
    }

    public static function active()
    {
        $today = Carbon::today();
        $beasiswa =  self::whereDate('akhir_berkas', '>=', $today)
            ->whereDate('awal_berkas', '<=', $today)
            ->with('instansi')
            ->get();
        return $beasiswa;
    }

    public function isActive(Beasiswa $beasiswa)
    {
        $today = Carbon::today();

        if ($beasiswa->awal_berkas <= $today && $today <= $beasiswa->akhir_berkas) {
            return true;
        }
        return false;
    }

    public static function cekSemester(Beasiswa $beasiswa, User $user)
    {
        $semester = explode(',', $beasiswa->semester);
        return is_null($beasiswa->semester) ||  in_array($user->semester, $semester);
    }

    public static function cekAllPersyaratan(User $user)
    {
        $beasiswa = self::active();

        foreach ($beasiswa as $row) {
            $row->cekPersyaratan($user);
        }
        return $beasiswa;
    }

    public function cekPersyaratan(User $user)
    {
        $jenjang = $this->jenjang == substr($user->nim, 0, 1);
        $ukt = $this->ukt && $user->ukt > $this->ukt;
        $first = $this->is_first && $user->permohonan->count() > 0;
        $syarat = [
            'jenjang' => $jenjang,
            'ukt' => !$ukt,
            'first' => !$first,
            'semester' => self::cekSemester($this, $user)
        ];
        $this->syarat = $syarat;
        if (in_array(false, $syarat)) {
        }
    }

    public function instansi()
    {
        return $this->belongsTo('App\Instansi');
    }

    public function permohonan()
    {
        return $this->hasMany('App\PemohonBeasiswa', 'beasiswa_id')->with('mahasiswa');
    }
}
