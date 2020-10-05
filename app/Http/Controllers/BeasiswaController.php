<?php

namespace App\Http\Controllers;

use App\Beasiswa;
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
        $beasiswa = Beasiswa::cekPersyaratan($user);
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
        $Beasiswa->total_sks        = $request['data']['total_sks'];
        $Beasiswa->ukt              = $request['data']['ukt'];
        $Beasiswa->semester         = $request['data']['semester'];
        $Beasiswa->is_first         = $request['data']['is_first'];
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
        $Beasiswa->total_sks        = $request['data']['total_sks'];
        $Beasiswa->ukt              = $request['data']['ukt'];
        $Beasiswa->semester         = $request['data']['semester'];
        $Beasiswa->is_first         = $request['data']['is_first'];
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
        $beasiswaSelesai = Beasiswa::with('instansi')->onlyTrashed()->get();
        $beasiswaSelesai->makeVisible(['berkas', 'interview', 'survey', 'selection', 'lulus', 'permohonan']);
        $beasiswaOnProgress = Beasiswa::active();
        $beasiswaOnProgress->makeVisible(['berkas', 'interview', 'survey', 'selection', 'lulus', 'permohonan']);
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

    public function cekPersyaratan(Request $request, $id)
    {
        $beasiswa = Beasiswa::find($id);
        $user = Auth::guard('mahasiswa')->user();
        $reply['status'] = true;
        $reply['message'] = [];
        // beasiswa ada
        if (is_null($beasiswa)) {
            $reply['status'] = false;
            $reply['message'] = 'Beasiswa tidak ditemukan!';
            return response()->json($reply);
        }
        // sks cukup
        if (!is_null($beasiswa->total_sks) && $user->total_sks < $beasiswa->total_sks) {
            $reply['status'] = false;
            array_push($reply['message'], 'Total sks tidak mencukupi');
        }
        // ukt
        if (!is_null($beasiswa->ukt) && $user->jml_bayar > $beasiswa->ukt) {
            $reply['status'] = false;
            array_push($reply['message'], 'UKT tidak memenuhi syarat');
        }
        // first
        if ($beasiswa->is_first && $user->permohonan->count() > 0) {
            $reply['status'] = false;
            array_push($reply['message'], 'Sudah pernah menerima beasiswa');
        }
        return response()->json($reply);
    }
}
