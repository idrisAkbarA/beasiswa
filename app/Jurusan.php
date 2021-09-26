<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $fillable = ['nama', 'singkatan', 'fakultas_id'];

    // Getters
    public static function getJurusanByFakultas($fakultas_id)
    {
        return self::where('fakultas_id', $fakultas_id)->get();
    }

    // Relations
    public function fakultas()
    {
        return $this->belongsTo('App\Fakultas');
    }

    public function mahasiswa()
    {
        return $this->hasMany('App\User');
    }
}
