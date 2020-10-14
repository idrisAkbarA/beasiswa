<?php

namespace App\Http\Controllers;

use App\Beasiswa;
use App\UserPetugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeasiswaController extends Controller
{

    public function getAll()
    {
        $beasiswa = Beasiswa::orderBy('id', 'DESC')
            ->with('instansi')
            ->get();
        return response()->json($beasiswa);
    }
    public function getAllWithPermohonan()
    {
        $beasiswa = Beasiswa::orderBy('id', 'DESC')->get();
        $beasiswa->makeVisible(['berkas', 'interview', 'survey', 'selection', 'lulus']);
        return response()->json($beasiswa);
    }
    public function getActive()
    {
        $user = Auth::guard('mahasiswa')->user();
        $beasiswa = Beasiswa::cekAllPersyaratan($user);
        $beasiswa->makeHidden(['interview', 'survey', 'selection', 'lulus'])
            ->sortByDesc('id');
        return response()->json($beasiswa);
    }
    public function countBeasiswa()
    {
        return count(Beasiswa::all());
    }
    public function get($id)
    {
        $user = Auth::guard('mahasiswa')->user();
        $beasiswa = Beasiswa::findOrFail($id);
        $beasiswa->cekPersyaratan($user);
        $beasiswa->makeHidden(['berkas', 'interview', 'survey', 'selection', 'lulus']);
        return response()->json($beasiswa);
    }
    public function store(Request $request)
    {
        $beasiswa = Beasiswa::create($request['data']);
        return response()->json(['status' => "Success: Beasiswa Added"]);
    }
    public function edit(Request $request, $id)
    {
        $beasiswa = Beasiswa::find($id);
        $beasiswa->update($request->all());
        return response()->json(['status' => "Success: Beasiswa Updated"]);
    }
    public function delete(Request $request, $id)
    {
        $beasiswa = Beasiswa::find($id);
        $beasiswa->delete();
        return response()->json(['status' => "Success: Beasiswa Deleted"]);
    }
    public function selesai()
    {
        $beasiswaOnProgress = Beasiswa::onProgress();
        $beasiswaOnProgress->makeVisible(['berkas', 'interview', 'survey', 'selection', 'lulus', 'permohonan']);
        $data = [
            'selesai' => $beasiswaOnProgress,
        ];
        return response()->json($data);
    }
}
