<?php

namespace App\Http\Controllers;

use App\LPJ;
use App\User;
use App\PermohonanLPJ;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LPJController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lpj = LPJ::with('beasiswa')
            ->when($request->verificator, function ($q) {
                return $q->whereDate('awal', '<=', date('Y-m-d'))
                    ->whereDate('akhir', '>=', date('Y-m-d'))
                    ->with(['permohonan' => function ($que) {
                        $que->whereNull('is_lulus');
                    }]);
            })
            ->orderBy('id', 'DESC')->get();
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
        return $this->index($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LPJ  $lPJ
     * @return \Illuminate\Http\Response
     */
    public function show(LPJ $lpj)
    {
        $result = Cache::rememberForever('lpj-' . $lpj->id, function () use ($lpj) {
            Log::info("creating cache with key: lpj-" . $lpj->id);
            $lpj->beasiswa = $lpj->beasiswa;
            $lpj->permohonan = $lpj->permohonan;

            $lpj->beasiswa->getLulusAttribute()->each(function ($item) use ($lpj) {
                if (!in_array($item->mhs_id, $lpj->permohonan->pluck('mhs_id')->toArray())) {
                    $permohonan = new PermohonanLPJ(['mhs_id' => $item->mhs_id, 'form' => [], 'is_lulus' => null]);
                    $permohonan->mahasiswa = $item->mahasiswa ?? new User(['nim' => $item->mhs_id]);
                    $lpj->permohonan->push($permohonan);
                }
            });
            $indexes = [];
            $lpj->permohonan->each(function ($item, $key) use (&$indexes) {
                // $item->nomor = $key;
                if ($item->is_submitted) {
                    //Continue/skip the iteration
                    return true;
                }
                if (!$item->form) {
                    return true;
                }

                $form = json_decode($item->form, true);
                $is_submitted = false;

                foreach ($form as $value) {
                    if (!$value['required']) {
                        continue;
                    }
                    if ($value['value'] === null) {
                        $is_submitted = false;
                        break;
                    }
                    $is_submitted = true;
                }
                if ($is_submitted) {
                    $permohonan_lpj = PermohonanLPJ::find($item->id);
                    $permohonan_lpj->is_submitted = true;
                    $permohonan_lpj->save();

                    $indexes[] = $key;
                }
            });
            $lpjArr = $lpj->toArray();
            foreach ($indexes as $key => $value) {
                $lpjArr['permohonan'][$value]['status'] = [
                    'color' => 'blue',
                    'text' => 'Proses'
                ];
            }

            return $lpjArr;
        });

        return response()->json($result);
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
        return $this->index($request);
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
                $row->update(['is_lulus' => 1]);
                $count += 1;
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
        return $this->index(new Request);
    }

    public function report(Request $request)
    {
        return $request;
    }
}
