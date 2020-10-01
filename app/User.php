<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard = 'mahasiswa';

    protected $fillable = [
        'nama', 'nim', 'email', 'hp', 'password', 'semester', 'ipk', 'tmpt_lahir', 'tgl_lahir'
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

    public function getNamaAttribute($value)
    {
        return ucwords($value);
    }

    public function permohonan()
    {
        return $this->hasMany('App\PemohonBeasiswa','mhs_id');
    }
}
