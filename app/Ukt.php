<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ukt extends Model
{
    protected $table = 'ukt';

    // public function jurusan()
    // {
    //     return  $this->hasMany('App\Jurusan', 'jurusan_id');
    // }

    // public function golongan()
    // {
    //     return $this->hasMany('App\GolonganUKT', 'golongan_ukt_id');
    // }
}
