<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\PemohonBeasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class PemohonBeasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function countBerkas(){
        $berkas = $this->cekBerkas();
        return count($berkas);
    }
    public function cekBerkas(){
        
        $permohonan = PemohonBeasiswa::where("is_berkas_passed",null)
                                    ->where("akhir_berkas",">=",Carbon::now())
                                    ->join("beasiswas","beasiswas.id","=","pemohon_beasiswas.beasiswa_id")
                                    ->join("users","users.nim","=","pemohon_beasiswas.mhs_id")
                                    ->select(["pemohon_beasiswas.*","beasiswas.nama AS nama_beasiswa","beasiswas.akhir_berkas","users.nama"])
                                    ->get();
        
                                    
        foreach ($permohonan as $key => $value) {
            $value['form'] = json_decode($value['form']);
        }
        return $permohonan;
    }
    public function countInterview(){
        $interview = $this->cekInterview();
        return count($interview);
    }
    public function countLulus(){
        $permohonan = PemohonBeasiswa::where("is_selection_passed",1)->get();
        return count($permohonan);
    }
    public function lulus(){
        $permohonan = PemohonBeasiswa::where("is_selection_passed",1)->get();
        return response()->json($permohonan);
    }
    public function cekInterview(){
        
        $permohonan = PemohonBeasiswa::where("beasiswas.is_interview","=",1)
                                    ->where("is_interview_passed",null)
                                    ->where("akhir_interview",">=",Carbon::now())
                                    ->join("beasiswas","beasiswas.id","=","pemohon_beasiswas.beasiswa_id")
                                    ->join("users","users.nim","=","pemohon_beasiswas.mhs_id")
                                    ->select(["pemohon_beasiswas.*","beasiswas.nama AS nama_beasiswa","beasiswas.akhir_interview","users.nama"])
                                    ->get();
        
                                    
        foreach ($permohonan as $key => $value) {
            $value['form'] = json_decode($value['form']);
        }
        return $permohonan;
    }
    public function cekSurvey(){
        
        $permohonan = PemohonBeasiswa::where("beasiswas.is_survey","=",1)
                                    ->where("is_survey_passed",null)
                                    ->where("akhir_survey",">=",Carbon::now())
                                    ->join("beasiswas","beasiswas.id","=","pemohon_beasiswas.beasiswa_id")
                                    ->join("users","users.nim","=","pemohon_beasiswas.mhs_id")
                                    ->select(["pemohon_beasiswas.*","beasiswas.nama AS nama_beasiswa","beasiswas.akhir_survey","users.nama"])
                                    ->get();
        
                                    
        foreach ($permohonan as $key => $value) {
            $value['form'] = json_decode($value['form']);
        }
        return $permohonan;
    }
    public function cekSelection(){
        
        $permohonan = PemohonBeasiswa::where("is_selection_passed",null)
                                    ->join("beasiswas","beasiswas.id","=","pemohon_beasiswas.beasiswa_id")
                                    ->join("users","users.nim","=","pemohon_beasiswas.mhs_id")
                                    ->select(["pemohon_beasiswas.*","beasiswas.nama AS nama_beasiswa","beasiswas.akhir_selection","users.nama"])
                                    ->get();
        
                                    
        foreach ($permohonan as $key => $value) {
            $value['form'] = json_decode($value['form']);
        }
        return $permohonan;
    }
    public function setBerkas(Request $request){
        $permohonan = PemohonBeasiswa::find($request['id']);
        $permohonan->is_berkas_passed = $request['bool'];
        $permohonan->save();
        return response()->json([
            'status' => 'Success: berkas set'
        ]);
    }
    public function setSurvey(Request $request){
        $permohonan = PemohonBeasiswa::find($request['id']);
        $permohonan->is_Survey_passed = $request['bool'];
        $permohonan->save();
        return response()->json([
            'status' => 'Success: Survey set'
        ]);
    }
    public function setInterview(Request $request){
        $permohonan = PemohonBeasiswa::find($request['id']);
        $permohonan->is_interview_passed = $request['bool'];
        $permohonan->save();
        return response()->json([
            'status' => 'Success: interview set'
        ]);
    }
    public function setSelection(Request $request){
        $permohonan = PemohonBeasiswa::find($request['id']);
        $permohonan->is_selection_passed = $request['bool'];
        $permohonan->save();
        return response()->json([
            'status' => 'Success: selection set'
        ]);
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $permohonan = new PemohonBeasiswa;
        $permohonan->mhs_id         = $user->nim;
        $permohonan->beasiswa_id    = $request['beasiswa_id'];
        $permohonan->form           = json_encode($request['form']);
        $permohonan->save();

        return response()->json([
                'status' => 'Success: Permohonan added'
            ]);
    }
    public function storeFile(Request $request)
    {   
        $user = Auth::user();
        $fileName = "files/".$request['id'].'/'.$user['nim']."/".Carbon::now()->format("Y-m-d-H-i-s").$request->file->getClientOriginalName();
        $request->file->move(public_path('files/'.$request['id'].'/'.$user['nim']), $fileName);
         
    	return response()->json(['success'=>'You have successfully upload file.','file_name'=>$fileName]);    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PemohonBeasiswa  $pemohonBeasiswa
     * @return \Illuminate\Http\Response
     */
    public function show(PemohonBeasiswa $pemohonBeasiswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PemohonBeasiswa  $pemohonBeasiswa
     * @return \Illuminate\Http\Response
     */
    public function edit(PemohonBeasiswa $pemohonBeasiswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PemohonBeasiswa  $pemohonBeasiswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PemohonBeasiswa $pemohonBeasiswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PemohonBeasiswa  $pemohonBeasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(PemohonBeasiswa $pemohonBeasiswa)
    {
        //
    }
}
