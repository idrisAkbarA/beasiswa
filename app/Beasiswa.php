<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beasiswa extends Model
{
    use SoftDeletes;

    protected $appends = [
        'interview',
        'survey',
        'selection',
        'lulus'
    ];

    public function getInterviewAttribute()
    {
        if ($this->is_interview){
            return $this->pemohon
                ->where('is_berkas_passed', 1)
                ->whereNull('is_interview_passed');
        }
        return [];
    }

    public function getSurveyAttribute()
    {
        if ($this->is_survey){
            return $this->pemohon
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
        return $this->pemohon
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
        return $this->pemohon
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
        $beasiswa =  self::whereDate('akhir_berkas', '>=', $today)->get();
        $beasiswa = $beasiswa->reject(function ($value, $key) use ($today) {
            if ($value->awal_berkas == NULL){
                return false;
            }
            return $value->awal_berkas.'+1 day' < $today;
        });
        return $beasiswa;
    }

    public function instansi()
    {
        return $this->belongsTo('App\Instansi');
    }

    public function pemohon()
    {
        return $this->hasMany('App\PemohonBeasiswa','beasiswa_id')->whereNull('is_berkas_passed');
    }
}
