<?php

namespace App\Http\Controllers;

use App\LPJ;
use Illuminate\Http\Request;

class LPJController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lpj = LPJ::with('beasiswa')->orderBy('id', 'DESC')->get();
        return response()->json($lpj);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        LPJ::create($request->all());
        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LPJ  $lPJ
     * @return \Illuminate\Http\Response
     */
    public function show(LPJ $lPJ)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LPJ  $lPJ
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LPJ $lpj)
    {
        $lpj->update($request->all());
        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LPJ  $lPJ
     * @return \Illuminate\Http\Response
     */
    public function destroy(LPJ $lpj)
    {
        $lpj->delete();
        return $this->index();
    }
}
