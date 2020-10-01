<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class UserPetugas extends Authenticatable
{
    use Notifiable;

    protected $guard = 'petugas';

    protected $fillable = [
        'name', 'nama_lengkap', 'email', 'password', 'fakultas_id', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token', 'fakultas_id'
    ];

    protected $appends = [
        'fakultas'
    ];

    public function getFakultasAttribute()
    {
        return Fakultas::find($this->fakultas_id);
    }
}
