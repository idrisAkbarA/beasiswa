<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LPJ extends Model
{
    protected $table = 'lpj';
    protected $guarded = ['id'];

    public function setFieldsAttribute($value)
    {
        $this->attributes['fields'] = json_encode($value);
    }

    public function checkStatus(User $user)
    {
        $beasiswaLulus =  $user->permohonan
            ->where('is_selection_passed', 1)
            ->pluck('beasiswa_id')
            ->toArray();
        $status = in_array($this->beasiswa->id, $beasiswaLulus);
        $message = 'Anda tidak dapat mendaftar';
        if ($status) {
            $permohonanLPJ = $user->permohonanLPJ
                ->pluck('lpj_id')
                ->toArray();
            $status = !in_array($this->id, $permohonanLPJ);
            $message = 'Laporan anda telah terdaftar, apakah anda ingin mengubah laporan?';
            !$status && $this->fields = $user->permohonanLPJ->where('id', $this->id)->form;
        }
        $result = [
            'status' => $status,
            'message' => $message
        ];
        return $result;
    }

    public function beasiswa()
    {
        return $this->belongsTo('App\Beasiswa')->onlyTrashed();
    }

    public function permohonan()
    {
        return $this->hasMany('App\PermohonanLPJ', 'lpj_id')->with('mahasiswa');
    }
}
