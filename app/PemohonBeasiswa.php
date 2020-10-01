<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PemohonBeasiswa extends Model
{
    protected $hidden = [
        'verificator_id',
        'interviewer_id',
        'surveyor_id',
        'selector_id',
    ];

    protected $appends = [
        'verificator',
        'interviewer',
        'surveyor',
        'selector',
    ];

    public function getVerificatorAttribute()
    {
        $petugas = UserPetugas::find($this->verificator_id);
        if (!$petugas){
            return '-';
        }
        return $petugas->nama_lengkap;
    }

    public function getInterviewerAttribute()
    {
        $petugas = UserPetugas::find($this->interviewer_id);
        if (!$petugas){
            return '-';
        }
        return $petugas->nama_lengkap;
    }

    public function getSurveyorAttribute()
    {
        $petugas = UserPetugas::find($this->surveyor_id);
        if (!$petugas){
            return '-';
        }
        return $petugas->nama_lengkap;
    }

    public function getSelectorAttribute()
    {
        $petugas = UserPetugas::find($this->selector_id);
        if (!$petugas){
            return '-';
        }
        return $petugas->nama_lengkap;
    }

    public function setIsSelectionPassedAttribute($value)
    {
        if ($value){
            if ($this->beasiswa->quota > count($this->beasiswa->lulus)){
                $this->attributes['is_selection_passed'] = $value;
            }else {
                return;
            }
        }
        $this->attributes['is_selection_passed'] = $value;
    }

    public function beasiswa()
    {
        return $this->belongsTo('App\Beasiswa')->withTrashed();
    }

    public function mahasiswa()
    {
        return $this->belongsTo('App\User', 'mhs_id');
    }
}
