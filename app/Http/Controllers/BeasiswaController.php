<?php

namespace App\Http\Controllers;

use App\Beasiswa;
use App\UserPetugas;
use App\Instansi;
use App\Settings\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\BeasiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;

class BeasiswaController extends Controller
{
    public function setAppSettings(Request $request)
    {
        // return $request['settings'];
        try {
            $file = Settings::set($request['settings']);
            return $file;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function getAppSettings()
    {
        $file = Settings::get();

        return $file;
        return Settings::get();
    }
    public function downloadReport(Request $request)
    {
        // return Excel::download(new BeasiswaExport, 'Beasiswa.xlsx');
        $finalData = $this->report($request);
        return Excel::download(new BeasiswaExport($finalData), 'Beasiswa.xlsx');
        // return array_keys( $finalData[0]);
        // return $finalData;
        // return $beasiswaCol;
        // return [$whereFakultas,$whereTahap];
    }
    public function report(Request $request)
    {
        $beasiswaCol = [];
        $whereFakultas = [];
        $whereTahap = [];
        $finalData = [];
        $tahap = $request['tahap'];
        $status = $request['status'];
        $beasiswa = $request['beasiswa'];
        $fakultas = $request['fakultas'];
        $statusSemua = '';

        if ($fakultas != 'all') $whereFakultas['fakultas_id'] = $fakultas;
        if ($tahap != 'all') {
            if ($tahap == "berkas") {
                if ($status == "lulus") {
                    $whereTahap['is_berkas_passed'] = 1;
                }
                if ($status == "gagal") {
                    $whereTahap['is_berkas_passed'] = 0;
                }
                if ($status == "seleksi") {
                    $whereTahap['is_berkas_passed'] = null;
                }
                if ($status == "all") {
                    $statusSemua = "is_berkas_passed";
                }
            }
            if ($tahap == "wawancara") {
                if ($status == "lulus") {
                    $whereTahap['is_interview_passed'] = 1;
                }
                if ($status == "gagal") {
                    $whereTahap['is_interview_passed'] = 0;
                }
                if ($status == "seleksi") {
                    $whereTahap['is_interview_passed'] = null;
                }
                if ($status == "all") {
                    $statusSemua = "is_interview_passed";
                }
            }
            if ($tahap == "survey") {
                if ($status == "lulus") {
                    $whereTahap['is_survey_passed'] = 1;
                }
                if ($status == "gagal") {
                    $whereTahap['is_survey_passed'] = 0;
                }
                if ($status == "seleksi") {
                    $whereTahap['is_survey_passed'] = null;
                }
                if ($status == "all") {
                    $statusSemua = "is_survey_passed";
                }
            }
            if ($tahap == "seleksi") {
                if ($status == "lulus") {
                    $whereTahap['is_selection_passed'] = 1;
                }
                if ($status == "gagal") {
                    $whereTahap['is_selection_passed'] = 0;
                }
                if ($status == "seleksi") {
                    $whereTahap['is_selection_passed'] = null;
                }
                if ($status == "all") {
                    $statusSemua = "is_selection_passed";
                }
            }
        }
        if ($beasiswa != 'all') {
            // return 'oi';
            $beasiswaCol = Beasiswa::where("id", $beasiswa)
                ->get()
                ->makeVisible(["lulus", "permohonan"]);
        } else {
            $beasiswaCol = Beasiswa::all()->makeVisible(["lulus", "permohonan"]);
        }
        $beasiswaCol->map(function ($item, $key) use ($whereFakultas, $whereTahap) {
            unset($item['instansi_id']);
            unset($item['fields']);
            unset($item['created_at']);
            unset($item['update_at']);
            // return "oi";
            if (isset($whereFakultas['fakultas_id'])) {

                if (count($whereTahap) > 0) {
                    $tahapKey = array_key_first($whereTahap);
                    $tahapValue = $whereTahap[$tahapKey];

                    // filter fakultas
                    foreach ($item['permohonan'] as $key => $value) {
                        if ($value['mahasiswa']['jurusan']['fakultas']['id'] !== $whereFakultas['fakultas_id']) {
                            unset($item['permohonan'][$key]);
                        }
                    }
                    // filter tahap
                    foreach ($item['permohonan'] as $key => $value) {
                        if ($value[$tahapKey] !== $tahapValue) {
                            unset($item['permohonan'][$key]);
                        };
                    }
                }
            } else {
                if (count($whereTahap) > 0) {
                    $tahapKey = array_key_first($whereTahap);
                    $tahapValue = $whereTahap[$tahapKey];
                    // filter tahap
                    foreach ($item['permohonan'] as $key => $value) {
                        if ($value[$tahapKey] !== $tahapValue) {
                            unset($item['permohonan'][$key]);
                        };
                    }
                }
            }
        });

        if (count($whereTahap) < 1) {
            $tahapKey = $statusSemua;
            $namedTahapKey = "Tahap " . explode("_", $tahapKey)[1];
        } else {
            $tahapKey = array_key_first($whereTahap);
            $namedTahapKey = "Tahap " . explode("_", $tahapKey)[1];
        }


        foreach ($beasiswaCol as $keyB => $valueB) {
            foreach ($valueB['permohonan'] as $keyP => $valueP) {
                $temp = [];
                $status = '';
                $temp['Beasiswa'] = $valueB['nama'];
                $temp['Nama'] = $valueP['mahasiswa']['nama'];
                $temp['NIM'] = $valueP['mahasiswa']['nim'];
                $temp['Jurusan'] = $valueP['mahasiswa']['jurusan']['nama'];
                $temp['Fakultas'] = $valueP['mahasiswa']['fakultas']['nama'];
                $temp['IPS'] = $valueP['mahasiswa']['ips'];
                $temp['IPK'] = $valueP['mahasiswa']['ipk'];
                if ($valueP[$tahapKey] === 1) {
                    $status = "Lulus";
                } else if ($valueP[$tahapKey] === 0) {
                    $status = "Gagal";
                } else if ($valueP[$tahapKey] === null) {
                    $status = "Dalam Tahap Seleksi";
                }
                $temp[$namedTahapKey] = $status;
                # code...
                array_push($finalData, $temp);
            }
        }
        // return [$whereFakultas,$whereTahap];
        // return $beasiswaCol;
        return $finalData;
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

    public function responseProdi($input)
    {
        return
            [
                'Kamu lalai sih',
                'solusinya gada solusi'
            ][random_int(0, 1)];
    }

    public function store(Request $request)
    {
        $instansi = new Instansi;
        if (!isset($request['data']['instansi_id'])) {
            $instansi->name = $request['data']['instansi'];
            $instansi->save();
            $request['data'] = array_merge($request['data'], ['instansi_id' => $instansi->id]);
        }
        $beasiswa = Beasiswa::create($request['data']);
        if (optional($request->data)['selesai']) {
            $beasiswa->delete();
        }
        return response()->json(['status' => "Success: Beasiswa Added"]);
    }

    public function edit(Request $request, $id)
    {
        $instansi = new Instansi;
        if (!$request['instansi_id']) {
            $instansi->name = $request['instansi'];
            $instansi->save();
            $request['instansi_id'] = $instansi->id;
        }
        $beasiswa = Beasiswa::find($id);
        $beasiswa->update($request->all());
        return response()->json(['status' => "Success: Beasiswa Updated"]);
    }
    public function delete(Request $request, $id)
    {
        $beasiswa = Beasiswa::find($id);
        $beasiswa->delete();
        return response()->json(['status' => "Success: Beasiswa Selesai"]);
    }

    public function destroy(Request $request, $id)
    {
        $beasiswa = Beasiswa::find($id);
        $beasiswa->forcedelete();
        return response()->json(['status' => "Success: Beasiswa Deleted"]);
    }
    public function selesai()
    {
        $beasiswaOnProgress = Beasiswa::onProgress();
        $beasiswaOnProgress->makeVisible(['berkas', 'interview', 'survey', 'selection', 'lulus', 'permohonan']);
        $data = [
            'selesai' => $beasiswaOnProgress,
        ];
        return response()->json($data);
    }
}
