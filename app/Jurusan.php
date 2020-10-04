<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $fillable = ['nama', 'singkatan', 'fakultas_id'];

    public function fakultas()
    {
        return $this->belongsTo('App\Fakultas');
    }

    public function mahasiswa()
    {
        return $this->hasMany('App\User');
    }

    public function ukt()
    {
        return $this->belongsToMany('App\GolonganUKT', 'ukt', 'jurusan_id', 'golongan_ukt_id');
    }
}
