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
        $this->attributes['is_lulus'] = $value;
        if (!$value) {
            $beasiswa = $this->lpj->beasiswa;
        }
    }

    public function getStatusAttribute()
    {
        if ($this->is_submitted === 0) {
            $status = [
                'color' => 'orange',
                'text' => 'Tidak Lengkap'
            ];
        } else {
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
