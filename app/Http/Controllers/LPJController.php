<?php

namespace App\Http\Controllers;

use App\LPJ;
use App\PermohonanLPJ;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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
     * Display a listing of the resource in mahasiswa.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexMahasiswa()
    {
        $user = Auth::guard('mahasiswa')->user();
        $today = Carbon::today();
        $beasiswaLulus = $user->permohonan
            ->where('is_selection_passed', 1)
            ->pluck('beasiswa_id');
        $lpj = LPJ::with('beasiswa')
            ->whereHas('beasiswa', function ($q) use ($beasiswaLulus) {
                return $q->whereIn('beasiswas.id', $beasiswaLulus);
            })
            ->whereDate('awal', '<=', $today)
            ->whereDate('akhir', '>=', $today)
            ->orderBy('id', 'DESC')
            ->get();
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
    public function show(LPJ $lpj)
    {
        $lpj->beasiswa = $lpj->beasiswa;
        $lpj->permohonan = $lpj->permohonan;

        $lpj->beasiswa->permohonan->each(function ($item) use ($lpj) {
            if (in_array($item->mhs_id, $lpj->permohonan->pluck('mhs_id')->toArray())) {
                return false;
            }
            $new = new PermohonanLPJ();
            $new->mahasiswa = $item->mahasiswa;
            $lpj->permohonan->push($new);
        });
        return response()->json($lpj);
    }

    /**
     * Show the register form to the specified resource.
     *
     * @param  \App\LPJ  $lPJ
     * @return \Illuminate\Http\Response
     */
    public function daftar(LPJ $lpj)
    {
        $user = Auth::guard('mahasiswa')->user();
        $result = $lpj->checkStatus($user);
        $reply = [
            'status' => $result['status'],
            'message' => $result['message'],
            'data' => $lpj
        ];
        return response()->json($reply);
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
