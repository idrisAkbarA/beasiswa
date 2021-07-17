<?php

namespace App\Http\Controllers;

use App\LPJ;
use App\User;
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

        $lpj->beasiswa->getLulusAttribute()->each(function ($item) use ($lpj) {
            if (!in_array($item->mhs_id, $lpj->permohonan->pluck('mhs_id')->toArray())) {
                $permohonan = new PermohonanLPJ();
                $permohonan->mahasiswa = $item->mahasiswa ?? new User(['nim' => $item->mhs_id]);
                $lpj->permohonan->push($permohonan);
            }
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
     * Update all permohonan LPJ to lulus if is_submitted == 1.
     *
     * @param  \App\LPJ $lpj
     * @return \Illuminate\Http\Response
     */
    public function lulusAll(LPJ $lpj)
    {
        $permohonan = $lpj->permohonan->where('is_submitted', 1);
        try {
            $count = 0;
            foreach ($permohonan as $row) {
                if ($row->is_submitted) {
                    $row->update(['is_lulus' => 1]);
                    $count += 1;
                }
            }
            $reply = [
                'status' => true,
                'message' => "Berhasil meluluskan {$count} LPJ",
            ];
        } catch (Exception $e) {
            $reply = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($reply);
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
