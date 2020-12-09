<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'nim';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard = 'mahasiswa';

    protected $fillable = [
        'nama', 'nim', 'email', 'hp', 'password', 'semester', 'ipk', 'ips', 'ukt', 'tmpt_lahir', 'tgl_lahir', 'jurusan_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'fakultas',
        // 'ukt'
    ];

    public function getNamaAttribute($value)
    {
        return ucwords($value);
    }

    public function getFakultasAttribute()
    {
        return $this->jurusan->fakultas;
    }

    public function permohonan()
    {
        return $this->hasMany('App\PemohonBeasiswa', 'mhs_id');
    }

    public function permohonanLPJ()
    {
        return $this->hasMany('App\PermohonanLPJ', 'mhs_id');
    }

    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan');
    }
}
