<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PemohonBeasiswa extends Model
{
    protected $appends = [
        'mahasiswa'
    ];

    public function getMahasiswaAttribute()
    {
        return User::where('nim', $this->mhs_id)->first();
    }

    public function beasiswa()
    {
        return $this->belongsTo('App\Beasiswa');
    }

    public function mahasiswa()
    {
        return $this->belongsTo('App\User');
    }
}
