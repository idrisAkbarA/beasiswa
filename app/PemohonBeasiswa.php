<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PemohonBeasiswa extends Model
{
    //
    public function beasiswa()
    {
        return $this->belongsTo('App\Beasiswa');
    }

    public function mahasiswa()
    {
        return $this->belongsTo('App\User');
    }
}
