<?php

namespace App\Http\Controllers;

use App\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function getAll()
    {
        $jurusan = Jurusan::all();
        return response()->json($jurusan);
    }

    public function getJurusanByFakultas($fakultas_id)
    {
        $jurusan = Jurusan::getJurusanByFakultas($fakultas_id);
        return response()->json($jurusan);
    }
}
