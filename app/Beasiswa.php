<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beasiswa extends Model
{
    use SoftDeletes;

    protected $appends = [
        'selection'
    ];

    public function getSelectionAttribute()
    {
        return $this->pemohon
            ->where('is_berkas_passed', 1)
            ->when($this->is_interview == 1, function ($q) {
                return $q->where('is_interview_passed', 1);
            })
            ->when($this->is_survey == 1, function ($q) {
                return $q->where('is_survey_passed', 1);
            });
    }

    public function instansi()
    {
        return $this->belongsTo('App\Instansi');
    }

    public function pemohon()
    {
        return $this->hasMany('App\PemohonBeasiswa','beasiswa_id');
    }
}
