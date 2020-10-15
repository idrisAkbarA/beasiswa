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
        'status',
        'berkas',
        'interview',
        'survey',
        'selection',
        'lulus'
    ];

    public function setFieldsAttribute($value)
    {
        $this->attributes['fields'] = json_encode($value);
    }

    public function setJenjangAttribute($value)
    {
        $this->attributes['jenjang'] = json_encode($value);
    }

    public function getJenjangAttribute($value)
    {
        return json_decode($value);
    }

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
                ->whereNull('is_interview_passed')
                ->values();
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
            ->where('is_selection_passed', '!=', 1)
            ->values();
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
            ->where('is_selection_passed', 1)
            ->values();
    }

    public function getStatusAttribute()
    {
        $today = Carbon::today()->format('Y-m-d');
        $status = 'Tahap Akhir';
        if ($this->deleted_at) {
            $status = 'Selesai';
        } else {
            if ($this->is_survey && ($today <= $this->akhir_survey)) {
                $status = 'Tahap Survey';
            }
            if ($this->is_interview && ($today <= $this->akhir_interview)) {
                $status = 'Tahap Interview';
            }
            if ($today <= $this->akhir_berkas) {
                $status = 'Tahap Berkas';
            }
        }
        return $status;
    }

    public static function onProgress()
    {
        $today = Carbon::today();
        $beasiswa =  self::withTrashed()
            ->whereDate('awal_berkas', '<=', $today)
            ->with('instansi')
            ->get();
        return $beasiswa;
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

    public function isActive()
    {
        $today = Carbon::today();
        $startDate = Carbon::createFromFormat('Y-m-d', $this->berkas_awal);
        $endDate = Carbon::createFromFormat('Y-m-d', $this->berkas_akhir);
        if ($today->between($startDate, $endDate)) {
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
        $jenjang = in_array(substr($user->nim, 0, 1), $this->jenjang);
        $ukt = $this->ukt && $user->ukt > $this->ukt;
        $first = $this->is_first && $user->permohonan->where('is_selection_passed', 1)->count() > 0;
        $syarat = [
            'Jenjang' => $jenjang,
            'UKT' => !$ukt,
            'Tidak mengikuti beasiswa lain' => !$first,
            'Semester' => self::cekSemester($this, $user)
        ];
        $this->syarat = $syarat;
        // if (in_array(false, $syarat)) {
        //     //
        // }
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
