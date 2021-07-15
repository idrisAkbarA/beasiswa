<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermohonanLPJ extends Model
{
    protected $table = 'permohonan_lpj';
    protected $guarded = ['id'];

    protected $appends = [
        'status'
    ];

    public function setFormAttribute($value)
    {
        $this->attributes['form'] = json_encode($value);
    }

    public function setIsLulusAttribute($value)
    {
        $beasiswa = $this->lpj->beasiswa;
        $permohonan = $this->mahasiswa->permohonan->where('beasiswa_id', $beasiswa->id)->first();
        $permohonan->update(['is_selection_passed' => $value]);
        $this->attributes['is_lulus'] = $value;
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
