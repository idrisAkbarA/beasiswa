<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GolonganUKT extends Model
{
    protected $table = 'golongan_ukt';

    public function ukt()
    {
        return $this->belongsToMany('App\Jurusan', 'ukt', 'golongan_ukt_id', 'jurusan_id');
    }
}
