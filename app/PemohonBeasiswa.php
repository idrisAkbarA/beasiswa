<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PemohonBeasiswa extends Model
{
    protected $appends = [
        'mahasiswa'
    ];

    public function setIsSelectionPassedAttribute($value)
    {
        if ($value){
            if ($this->beasiswa->quota > count($this->beasiswa->lulus)){
                $this->attributes['is_selection_passed'] = $value;
            }else {
                return;
            }
        }
        $this->attributes['is_selection_passed'] = $value;
    }

    public function getMahasiswaAttribute()
    {
        return User::where('nim', $this->mhs_id)->first();
    }

    public function beasiswa()
    {
        return $this->belongsTo('App\Beasiswa');
    }

    public function mahasiswa()
    {
        return $this->belongsTo('App\User');
    }
}
