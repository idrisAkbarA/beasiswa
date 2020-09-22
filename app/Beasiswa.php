<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    //
    public function instansi()
    {
        return $this->belongsTo('App\Instansi');
    }

    public function pemohon()
    {
        return $this->hasMany('App\PemohonMahasiswa','beasiswa_id');
    }
}
