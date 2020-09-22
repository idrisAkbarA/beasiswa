<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    //
    public function beasiswa()
    {
        return $this->hasMany('App\Beasiswa','instansi_id');
    }
}
