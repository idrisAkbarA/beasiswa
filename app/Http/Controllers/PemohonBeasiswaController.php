<?php

namespace App\Http\Controllers;

use App\PemohonBeasiswa;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
        //
    }
    public function storeFile(Request $request)
    {
        $fileName = $request->file->getClientOriginalName();
        $request->file->move(public_path('files/'.$request['id']), $fileName);
         
    	return response()->json(['success'=>'You have successfully upload file.']);    }

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
