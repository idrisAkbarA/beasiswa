<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $fillable = ['nama', 'singkatan'];

    public function jurusan()
    {
        return $this->hasMany('App\Jurusan');
    }
}
