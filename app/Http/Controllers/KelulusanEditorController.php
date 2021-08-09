<?php

namespace App\Http\Controllers;

use App\Beasiswa;
use App\Services\KelulusanEditor;
use Illuminate\Http\Request;

class KelulusanEditorController extends Controller
{
    public function index($beasiswaID, Request $request)
    {
        $beasiswa = Beasiswa::withTrashed()->find($beasiswaID);
        $deletedMHSArray = $request->deletedMHSArray;
        $insertedMHSArray = $request->insertedMHSArray;
        $kelulusanEditor = new KelulusanEditor($beasiswa);
        $kelulusanEditor->removeMahasiswa($deletedMHSArray);
        $kelulusanEditor->addMahasiswa($insertedMHSArray);
        return response()->json([
            'message' => "Success! Perubahan berhasil disimpan.",
            'status' => true,
        ]);
    }
}
