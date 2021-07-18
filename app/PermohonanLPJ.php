<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PermohonanLPJ extends Model
{
    protected $table = 'permohonan_lpj';
    protected $guarded = ['id'];

    protected $appends = [
        'status'
    ];
    protected static function booted()
    {
        // static::created(function ($permohonanLPJ) {
        //     Cache::forget('lpj-', $permohonanLPJ->lpj_id);
        // });
        // static::updated(function ($permohonanLPJ) {
        //     Cache::forget('lpj-', $permohonanLPJ->lpj_id);
        // });
        // static::saved(function ($permohonanLPJ) {
        //     Cache::forget('lpj-', $permohonanLPJ->lpj_id);
        // });
    }

    public function setFormAttribute($value)
    {
        $this->attributes['form'] = json_encode($value);
    }

    public function setIsLulusAttribute($value)
    {
        $beasiswa = $this->lpj->beasiswa;
        $permohonan = $beasiswa->permohonan->where('mhs_id', $this->mhs_id)->first();
        try {
            $permohonan->update(['is_selection_passed' => $value]);
            $this->attributes['is_lulus'] = $value;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getStatusAttribute()
    {
        if ($this->is_submitted === 0) {
            $status = [
                'color' => 'orange',
                'text' => 'Tidak Lengkap'
            ];
        } elseif ($this->is_submitted === 1) {
            if ($this->is_lulus === null) {
                $status = [
                    'color' => 'blue',
                    'text' => 'Proses'
                ];
            } else {
                $status = [
                    'color' => $this->is_lulus ? 'green' : 'red',
                    'text' => $this->is_lulus ? 'Lulus' : 'Tidak Lulus'
                ];
            }
        } else {
            $status = [
                'color' => 'purple',
                'text' => 'Belum Mengisi'
            ];
        }
        return $status;
    }

    public function mahasiswa()
    {
        return $this->belongsTo('App\User', 'mhs_id');
    }
    public function lpj()
    {
        return $this->belongsTo('App\LPJ', 'lpj_id');
    }
}
