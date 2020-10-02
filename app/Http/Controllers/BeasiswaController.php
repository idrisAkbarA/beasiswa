<?php

namespace App\Http\Controllers;

use App\Beasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeasiswaController extends Controller
{

    public function getAll()
    {
        $beasiswa = Beasiswa::orderBy('id', 'DESC')->get();
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
        $beasiswa = Beasiswa::active();
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
        $beasiswa = Beasiswa::findOrFail($id);
        $beasiswa->makeHidden(['berkas', 'interview', 'survey', 'selection', 'lulus']);
        return response()->json($beasiswa);
    }
    public function store(Beasiswa $Beasiswa, Request $request)
    {
        $Beasiswa->nama             = $request['data']['nama'];
        $Beasiswa->deskripsi        = $request['data']['deskripsi'];
        $Beasiswa->quota            = $request['data']['kuota'];
        $Beasiswa->instansi_id       = $request['data']['instansi'];
        $Beasiswa->is_interview    = $request['data']['is_wawancara'];
        $Beasiswa->is_survey        = $request['data']['is_survey'];
        $Beasiswa->awal_interview   = $request['data']['awal_wawancara'];
        $Beasiswa->akhir_interview  = $request['data']['akhir_wawancara'];
        $Beasiswa->awal_berkas      = $request['data']['awal_berkas'];
        $Beasiswa->akhir_berkas     = $request['data']['akhir_berkas'];
        $Beasiswa->awal_survey      = $request['data']['awal_survey'];
        $Beasiswa->akhir_survey     = $request['data']['akhir_survey'];
        $Beasiswa->fields           = json_encode($request['data']['fields']);
        $Beasiswa->save();
        return response()->json(['status' => "Success: Beasiswa Added"]);
    }
    public function edit(Request $request, $id)
    {
        $Beasiswa = Beasiswa::find($id);
        $Beasiswa->nama             = $request['nama'];
        $Beasiswa->deskripsi        = $request['deskripsi'];
        $Beasiswa->quota            = $request['kuota'];
        $Beasiswa->instansi_id      = $request['instansi'];
        $Beasiswa->is_interview     = $request['is_wawancara'];
        $Beasiswa->is_survey        = $request['is_survey'];
        $Beasiswa->awal_interview   = $request['awal_wawancara'];
        $Beasiswa->akhir_interview  = $request['akhir_wawancara'];
        $Beasiswa->awal_berkas      = $request['awal_berkas'];
        $Beasiswa->akhir_berkas     = $request['akhir_berkas'];
        $Beasiswa->awal_survey      = $request['awal_survey'];
        $Beasiswa->akhir_survey     = $request['akhir_survey'];
        $Beasiswa->fields           = json_encode($request['fields']);
        $Beasiswa->save();
        return response()->json(['status' => "Success: Beasiswa Updated"]);
    }
    public function delete(Request $request, $id)
    {
        $Beasiswa = Beasiswa::find($id);
        $Beasiswa->delete();
        return response()->json(['status' => "Success: Beasiswa Deleted"]);
    }
    public function selesai()
    {
        $beasiswaSelesai = Beasiswa::onlyTrashed()->get();
        $beasiswaSelesai->makeVisible(['berkas', 'interview', 'survey', 'selection', 'lulus']);
        $beasiswaOnProgress = Beasiswa::active();
        $beasiswaOnProgress->makeVisible(['berkas', 'interview', 'survey', 'selection', 'lulus']);
        $temp = [];
        foreach ($beasiswaOnProgress as $row) {
            array_push($temp, $row);
        }
        $beasiswa = [
            'selesai' => $beasiswaSelesai,
            'aktif' => $temp
        ];
        return response()->json($beasiswa);
    }
}
