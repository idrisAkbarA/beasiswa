<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermohonanLPJ extends Model
{
    protected $table = 'permohonan_lpj';
    protected $guarded = ['id'];

    public function mahasiswa()
    {
        return $this->belongsTo('App\User', 'mhs_id');
    }
    public function lpj()
    {
        return $this->belongsTo('App\LPJ', 'lpj_id');
    }
}
