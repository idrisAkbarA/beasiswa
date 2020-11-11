<?php

namespace App\Http\Controllers;

use Artisan;
use App\Beasiswa;
use App\Instansi;
use Carbon\Carbon;
use App\UserPetugas;
use App\Settings\Backup;
use App\Settings\Settings;
use Illuminate\Http\Request;
use App\Exports\BeasiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class BeasiswaController extends Controller
{
    public function getBackupList()
    {
        return response()->json(Backup::list());
    }
    public function getBackup()
    {
        // $filename = "backup-" . Carbon::now()->format('Y-m-d-H-i-s') . ".sql";

        // $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . storage_path() . "/app/backup/" . $filename;

        // $returnVar = NULL;
        // $output  = NULL;

        // exec($command, $output, $returnVar);
        // Storage::download("/app/backup/".$filename);
        // Artisan::call("backupDB");
    }
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
        function petugas($id)
        {
            // return "pantek";
            // return UserPetugas::find($id);
            $result = UserPetugas::find($id);
            if ($result != null) {
                return $result['nama_lengkap'];
            } else {
                return null;
            }
        }
        $beasiswaCol = [];
        $whereFakultas = [];
        $whereTahap = [];
        $finalData = [];
        $tahap = $request['tahap'];
        $status = $request['status'];
        $beasiswa = $request['beasiswa'];
        $fakultas = $request['fakultas'];
        $fieldList = $request['field_list'];
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
            // unset($item['fields']);
            unset($item['created_at']);
            unset($item['update_at']);
            // return "oi";
            if (isset($whereFakultas['fakultas_id'])) {
                if (count($whereTahap) > 0) {
                    $tahapKey = array_key_first($whereTahap);
                    $tahapValue = $whereTahap[$tahapKey];
                    // filter fakultas
                    foreach ($item['permohonan'] as $key => $value) {
                        if ($value['mahasiswa']['jurusan']['fakultas']['id'] != $whereFakultas['fakultas_id']) {
                            unset($item['permohonan'][$key]);
                        }
                    }
                    // filter tahap
                    foreach ($item['permohonan'] as $key => $value) {
                        if ($value[$tahapKey] !== $tahapValue) {
                            unset($item['permohonan'][$key]);
                        };
                    }
                } else {
                    foreach ($item['permohonan'] as $key => $value) {
                        if ($value['mahasiswa']['jurusan']['fakultas']['id'] != $whereFakultas['fakultas_id']) {
                            unset($item['permohonan'][$key]);
                        }
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

        // get tahap key
        if (count($whereTahap) < 1) {
            $tahapKey = $statusSemua;
            $namedTahapKey = "Tahap " . explode("_", $tahapKey)[1];
        } else {
            $tahapKey = array_key_first($whereTahap);
            $namedTahapKey = "Tahap " . explode("_", $tahapKey)[1];
        }

        // set final data
        foreach ($beasiswaCol as $keyB => $valueB) {
            $semesters = explode(",",$valueB['semester']);
            foreach ($valueB['permohonan'] as $keyP => $valueP) {
                // check if somehow some permohonan that isnt match required semester happened to be
                // included here, if it did, filter out

                $isSemesterMatched = false;
                foreach ($semesters as $keySemester => $valueSemester) {
                    if($valueP['mahasiswa']['semester']==$valueSemester){
                        $isSemesterMatched = true;
                        break;
                    }
                }
                if($isSemesterMatched!=true) continue; // semester filter clear


                $form = json_decode($valueP['form'], true);
                $temp = [];
                $status = '';
                $temp['Beasiswa'] = $valueB['nama'];
                $temp['Nama'] = $valueP['mahasiswa']['nama'];
                $temp['NIM'] = $valueP['mahasiswa']['nim'];
                $temp['Jurusan'] = $valueP['mahasiswa']['jurusan']['nama'];
                $temp['Fakultas'] = $valueP['mahasiswa']['fakultas']['nama'];
                $temp['IPS'] = round($valueP['mahasiswa']['ips'], 2);
                $temp['IPK'] = round($valueP['mahasiswa']['ipk'], 2);
                if ($valueP[$tahapKey] === 1) {
                    $status = "Lulus";
                } else if ($valueP[$tahapKey] === 0) {
                    $status = "Gagal";
                } else if ($valueP[$tahapKey] === null) {
                    $status = "Dalam Tahap Seleksi";
                }
                $temp[$namedTahapKey] = $status;
                // set field list
                if ($fieldList != null) {
                    foreach ($fieldList as $fieldKey => $fieldValue) {
                        foreach ($form as $formKey => $formValue) {
                            if ($formValue['pertanyaan'] == $fieldValue) { // get all matched requested list
                                // check if it is a file upload or a multiple upload
                                // if it is, the value should be it's status
                                // Lulus/Tidak Lulus/Belum di verifikasi
                                if ($formValue['type'] == 'Upload File' || $formValue['type'] == 'Multiple Upload') {
                                    try {
                                        if ($formValue['value'] != null || $formValue['value'] != []) {
                                            $temp[$fieldValue] = "File Diupload";
                                        } else {
                                            $temp[$fieldValue] = "File Tidak Diupload";
                                        }
                                        if ($formValue['isLulus'] === null) {
                                            $temp["STATUS " . $fieldValue] = "Belum verifikasi";
                                        } else if ($formValue['isLulus'] === true) {
                                            $temp["STATUS " . $fieldValue] = "Lulus verifikasi";
                                        } else if ($formValue['isLulus'] === false) {
                                            $temp["STATUS " . $fieldValue] = "Tidak lulus verifikasi";
                                        }
                                    } catch (\Throwable $th) {
                                        $temp[$fieldValue] = "Belum verifikasi";
                                    }
                                } else {
                                    $temp[$fieldValue] = $formValue["value"];
                                    if ($formValue['isLulus'] === null) {
                                        $temp["STATUS " . $fieldValue] = "Belum verifikasi";
                                    } else if ($formValue['isLulus'] === true) {
                                        $temp["STATUS " . $fieldValue] = "Lulus verifikasi";
                                    } else if ($formValue['isLulus'] === false) {
                                        $temp["STATUS " . $fieldValue] = "Tidak lulus verifikasi";
                                    }
                                }
                            }
                        }
                    }
                }

                if (!isset($request['isVerificator'])) {
                    $temp['Verificator'] = petugas($valueP['verificator_id']);
                    $temp['Pewawancara'] = petugas($valueP['interviewer_id']);
                    $temp['Surveyor'] = petugas($valueP['surveyor_id']);
                }
                if (isset($request['akun'])) {
                    if ($valueP['verificator_id'] != $request['akun']) {
                        continue;
                    }
                }
                $temp['Keterangan'] = $valueP['keterangan'];
                // $temp['is akun'] = isset($request['akun']);
                // $temp['value akun'] = isset($request['akun'])?$request['akun']:null;
                // $temp['akun dari sono'] = $valueP['verificator_id'] ;
                array_push($finalData, $temp);
            }
        }
        // return [$whereFakultas,$whereTahap]
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
    public function getAllWithPermohonan(Request $request)
    {
        $beasiswa = Beasiswa::orderBy('id', 'DESC')->get();
        $tahap = $request->tahap ?? [
            'berkas',
            'interview',
            'survey',
            'selection',
            'lulus'
        ];
        $beasiswa->makeVisible($tahap);
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

    public function getPermohonan(Request $request, $id)
    {
        $beasiswa = Beasiswa::withTrashed()
            ->with(['instansi'])
            ->findOrFail($id);
        $tahap = $request->tahap ?? [
            'permohonan',
            'berkas',
            'interview',
            'survey',
            'selection',
            'lulus'
        ];
        $beasiswa->makeVisible($tahap);
        return response()->json($beasiswa);
    }

    public function countBeasiswa()
    {
        return Beasiswa::all()->count();
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
        $beasiswa = Beasiswa::with('permohonan')->find($id);
        $beasiswa->close();
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
        $data = [
            'selesai' => $beasiswaOnProgress,
        ];
        return response()->json($data);
    }
}
