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

    public function beasiswa()
    {
        return $this->belongsTo('App\Beasiswa')->onlyTrashed();
    }
}
