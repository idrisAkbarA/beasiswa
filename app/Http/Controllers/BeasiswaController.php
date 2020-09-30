<?php

namespace App\Http\Controllers;

use App\Beasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class BeasiswaController extends Controller
{ 

    public function getAll(){
        return response()->json( Beasiswa::all());
    }
    public function countBeasiswa(){
        return count(Beasiswa::all());
    }
    public function get($id){
        return response()->json( Beasiswa::find($id));
    }
    public function store(Beasiswa $Beasiswa, Request $request){
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
        return response()->json(['status'=>"Success: Beasiswa Added"]);
    }
    public function edit(Request $request, $id){
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
        return response()->json(['status'=>"Success: Beasiswa Updated"]);
    }
    public function delete(Request $request, $id){
        $Beasiswa = Beasiswa::find($id);
        $Beasiswa->delete();
        return response()->json(['status'=>"Success: Beasiswa Deleted"]);
    }
    public function selesai(){
        return response()->json( Beasiswa::onlyTrashed()->get() );
    }
}
