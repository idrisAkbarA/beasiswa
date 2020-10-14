<?php

namespace App\Http\Controllers;

use App\Beasiswa;
use App\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\BeasiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
class BeasiswaController extends Controller
{
    public function downloadReport(Request $request){
        $beasiswaCol;
        $whereFakultas = [];
        $whereTahap = [];

        $tahap = $request['tahap'];
        $status = $request['status'];
        $beasiswa = $request['beasiswa'];
        $fakultas = $request['fakultas'];

        if($fakultas != 'all') $whereFakultas['fakultas_id'] = $fakultas; 
        if($tahap != 'all'){
            if($tahap == "berkas"){
                if($status == "lulus"){
                    $whereTahap['is_berkas_passed'] = 1;
                }
                if($status == "gagal"){
                    $whereTahap['is_berkas_passed'] = 0;
                }
                if($status == "seleksi"){
                    $whereTahap['is_berkas_passed'] = null;
                }
            }
            if($tahap == "wawancara"){
                if($status == "lulus"){
                    $whereTahap['is_interview_passed'] = 1;
                }
                if($status == "gagal"){
                    $whereTahap['is_interview_passed'] = 0;
                }
                if($status == "seleksi"){
                    $whereTahap['is_interview_passed'] = null;
                }
            }
            if($tahap == "survey"){
                if($status == "lulus"){
                    $whereTahap['is_survey_passed'] = 1;
                }
                if($status == "gagal"){
                    $whereTahap['is_survey_passed'] = 0;
                }
                if($status == "seleksi"){
                    $whereTahap['is_survey_passed'] = null;
                }
            }
            if($tahap == "seleksi"){
                if($status == "lulus"){
                    $whereTahap['is_selection_passed'] = 1;
                }
                if($status == "gagal"){
                    $whereTahap['is_selection_passed'] = 0;
                }
                if($status == "seleksi"){
                    $whereTahap['is_selection_passed'] = null;
                }
            }
        }
        if($beasiswa != 'all'){
            // return 'oi';
            $beasiswaCol = Beasiswa::where("id",$beasiswa)
            ->get()
            ->makeVisible(["lulus","permohonan"]);
        }else{
            $beasiswaCol = Beasiswa::all()->makeVisible(["lulus","permohonan"]);
        }
        $beasiswaCol->map(function ($item, $key) use($whereFakultas,$whereTahap){
            unset($item['instansi_id']);
            unset($item['fields']);
            unset($item['created_at']);
            unset($item['update_at']);
            // return "oi";
            if($whereFakultas['fakultas_id']){
                
                if(count($whereTahap)>0){
                    $tahapKey = array_key_first($whereTahap);
                    $tahapValue = $whereTahap[$tahapKey];

                    // filter fakultas
                    foreach ($item['permohonan'] as $key => $value) {
                        if($value['mahasiswa']['jurusan']['fakultas']['id'] != $whereFakultas['fakultas_id']){
                            unset($item['permohonan'][$key]);
                        }
                    }
                    // filter tahap
                    foreach ($item['permohonan'] as $key => $value) {
                        if($value[$tahapKey] != $tahapValue){
                            unset($item['permohonan'][$key]);
                        };
                    }
                }
            }
        });
        // return Beasiswa::with("beasiswa")->get();
        // $columns = Schema::getColumnListing('beasiswas');
        // return $columns;
        return $beasiswaCol;
        return [$whereFakultas,$whereTahap];
        // return Excel::download(new BeasiswaExport, 'Beasiswa.xlsx');
    }
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

    public function responseProdi($input){
        return 
        [
            'Kamu lalai sih',
            'solusinya gada solusi'
        ]
        [random_int(0,1)];
    }

    public function store(Request $request)
    {
        // return $request;
        $instansi = new Instansi;
        if(!isset($request['data']['instansi_id'])){
            $instansi->name = $request['data']['instansi'];
            $instansi->save();
            $request['data'] = array_merge($request['data'],['instansi_id' => $instansi->id]);
            // return $request['data']['instansi_id'];
        }
        // return $request;
        $beasiswa = Beasiswa::create($request['data']);
        return response()->json(['status' => "Success: Beasiswa Added"]);
    }
    
    public function edit(Request $request, $id)
    {
        $instansi = new Instansi;
        if(!$request['instansi_id']){
            $instansi->name = $request['instansi'];
            $instansi->save();
            $request['instansi_id'] = $instansi->id;
            // return $request['data']['instansi_id'];
        }
        $beasiswa = Beasiswa::find($id);
        // $Beasiswa->nama             = $request['nama'];
        // $Beasiswa->deskripsi        = $request['deskripsi'];
        // $Beasiswa->quota            = $request['kuota'];
        // $Beasiswa->instansi_id      = $request['instansi'];
        // $Beasiswa->is_interview     = $request['is_wawancara'];
        // $Beasiswa->is_survey        = $request['is_survey'];
        // $Beasiswa->awal_interview   = $request['awal_wawancara'];
        // $Beasiswa->akhir_interview  = $request['akhir_wawancara'];
        // $Beasiswa->awal_berkas      = $request['awal_berkas'];
        // $Beasiswa->akhir_berkas     = $request['akhir_berkas'];
        // $Beasiswa->awal_survey      = $request['awal_survey'];
        // $Beasiswa->akhir_survey     = $request['akhir_survey'];
        // $Beasiswa->ipk              = $request['ipk'];
        // // $Beasiswa->total_sks        = $request['total_sks'];
        // $Beasiswa->ukt              = $request['ukt'];
        // $Beasiswa->semester         = $request['semester'];
        // $Beasiswa->is_first         = $request['is_first'];
        // $Beasiswa->fields           = json_encode($request['fields']);
        // $Beasiswa->save();
        $beasiswa->update($request->all());
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
        $data = [
            'selesai' => $beasiswaSelesai->merge($beasiswaOnProgress),
        ];
        return response()->json($data);
    }
}
