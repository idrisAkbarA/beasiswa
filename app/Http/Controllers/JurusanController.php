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
}
