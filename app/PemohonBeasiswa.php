<?php

namespace App;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class PemohonBeasiswa extends Model
{
    protected $guarded = ['id'];

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
        if (!$this->is_submitted && $this->beasiswa->status != 'Tahap Berkas') {
            return 'Berkas tdk lengkap';
        } else if (!$petugas) {
            return '-';
        }
        return $petugas->nama_lengkap;
    }

    public function getInterviewerAttribute()
    {
        $petugas = UserPetugas::find($this->interviewer_id);
        if (!$petugas) {
            return '-';
        }
        return $petugas->nama_lengkap;
    }

    public function getSurveyorAttribute()
    {
        $petugas = UserPetugas::find($this->surveyor_id);
        if (!$petugas) {
            return '-';
        }
        return $petugas->nama_lengkap;
    }

    public function getSelectorAttribute()
    {
        $petugas = UserPetugas::find($this->selector_id);
        if (!$petugas) {
            return '-';
        }
        return $petugas->nama_lengkap;
    }

    public function setFormAttribute($value)
    {
        $this->attributes['form'] = json_encode($value);
    }

    public function setIsBerkasPassedAttribute($value)
    {
        $petugas = Auth::guard('petugas')->user();
        if ($this->is_berkas_passed === null || $this->verificator_id == $petugas->id || $petugas->role == 0) {
            $this->attributes['is_berkas_passed'] = $value;
            $this->attributes['verificator_id'] = Auth::guard('petugas')->id();
            $this->attributes['verified_at'] = now();
        }
    }

    public function setIsInterviewPassedAttribute($value)
    {
        $petugas = Auth::guard('petugas')->user();
        if ($this->is_interview_passed === null || $petugas->role == 0) {
            $this->attributes['is_interview_passed'] = $value;
            $this->attributes['interviewer_id'] = Auth::guard('petugas')->id();
            $this->attributes['interviewed_at'] = now();
        }
    }

    public function setIsSurveyPassedAttribute($value)
    {
        $petugas = Auth::guard('petugas')->user();
        if ($this->is_survey_passed === null || $petugas->role == 0) {
            $this->attributes['is_survey_passed'] = $value;
            $this->attributes['surveyor_id'] = Auth::guard('petugas')->id();
            $this->attributes['surveyed_at'] = now();
        }
    }

    public function setIsSelectionPassedAttribute($value)
    {
        $petugas = Auth::guard('petugas')->user();
        try {
            if ($this->is_selection_passed !== null && $value == $this->is_selection_passed) {
                return;
            }
            if ($petugas->role !== 1 && $petugas->role !== 4) {
                throw new Exception('Anda tidak memiliki hak akses');
            }
            if ($value && $this->beasiswa->quota <= count($this->beasiswa->lulus)) {
                throw new Exception('Kuota beasiswa telah terpenuhi');
            }
            $this->attributes['is_selection_passed'] = $value;
            $this->attributes['selector_id'] = Auth::guard('petugas')->id();
            $this->attributes['selected_at'] = now();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
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
