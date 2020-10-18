<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Beasiswa;
use App\UserPetugas;
use App\PemohonBeasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\PemohonExport;
use App\Imports\KelulusanImport;
use Maatwebsite\Excel\Facades\Excel;

class PemohonBeasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
    }
    // public function downloadReport(Request $request){
    //     // return PemohonBeasiswa::with("beasiswa")->get();
    //     return Excel::download(new PemohonExport, 'pemohon.xlsx');
    // }
    public function IsHasBeasiswa()
    {
        $user = Auth::guard('mahasiswa')->user();
        $beasiswa = PemohonBeasiswa::where('mhs_id', $user['nim'])
                    ->where('is_submitted', 1)
                    ->with('beasiswa')
                    ->get();
        return response()->json($beasiswa);
    }
    public function IsHasBeasiswaAdmin(Request $request)
    {
        $user = $request['nim'];
        $beasiswa = PemohonBeasiswa::where("mhs_id", $user)->with("beasiswa")->first();
        // $beasiswa = PemohonBeasiswa::where("mhs_id",$user[nim])->get();
        return $beasiswa;
        // return $user;
    }
    public function countBerkas()
    {
        $berkas = $this->cekBerkas();
        return count($berkas);
    }
    public function cekBerkas()
    {

        $permohonan = PemohonBeasiswa::where("is_berkas_passed", null)
            ->where("akhir_berkas", ">=", Carbon::now())
            ->join("beasiswas", "beasiswas.id", "=", "pemohon_beasiswas.beasiswa_id")
            ->join("users", "users.nim", "=", "pemohon_beasiswas.mhs_id")
            ->select(["pemohon_beasiswas.*", "beasiswas.nama AS nama_beasiswa", "beasiswas.akhir_berkas", "users.nama"])
            ->get();


        foreach ($permohonan as $key => $value) {
            $value['form'] = json_decode($value['form']);
        }
        return $permohonan;
    }
    public function countInterview()
    {
        $interview = $this->cekInterview();
        return count($interview);
    }
    public function countLulus()
    {
        $permohonan = PemohonBeasiswa::where("is_selection_passed", 1)->get();
        return count($permohonan);
    }
    public function lulus()
    {
        $permohonan = PemohonBeasiswa::where("is_selection_passed", 1)->get();
        return response()->json($permohonan);
    }
    public function cekInterview()
    {

        $permohonan = PemohonBeasiswa::where("beasiswas.is_interview", "=", 1)
            ->where("is_interview_passed", null)
            ->where("akhir_interview", ">=", Carbon::now())
            ->join("beasiswas", "beasiswas.id", "=", "pemohon_beasiswas.beasiswa_id")
            ->join("users", "users.nim", "=", "pemohon_beasiswas.mhs_id")
            ->select(["pemohon_beasiswas.*", "beasiswas.nama AS nama_beasiswa", "beasiswas.akhir_interview", "users.nama"])
            ->get();


        foreach ($permohonan as $key => $value) {
            $value['form'] = json_decode($value['form']);
        }
        return $permohonan;
    }
    public function cekSurvey()
    {

        $permohonan = PemohonBeasiswa::where("beasiswas.is_survey", "=", 1)
            ->where("is_survey_passed", null)
            ->where("akhir_survey", ">=", Carbon::now())
            ->join("beasiswas", "beasiswas.id", "=", "pemohon_beasiswas.beasiswa_id")
            ->join("users", "users.nim", "=", "pemohon_beasiswas.mhs_id")
            ->select(["pemohon_beasiswas.*", "beasiswas.nama AS nama_beasiswa", "beasiswas.akhir_survey", "users.nama"])
            ->get();


        foreach ($permohonan as $key => $value) {
            $value['form'] = json_decode($value['form']);
        }
        return $permohonan;
    }
    public function cekSelection()
    {

        $permohonan = PemohonBeasiswa::where("is_selection_passed", null)
            ->join("beasiswas", "beasiswas.id", "=", "pemohon_beasiswas.beasiswa_id")
            ->join("users", "users.nim", "=", "pemohon_beasiswas.mhs_id")
            ->select(["pemohon_beasiswas.*", "beasiswas.nama AS nama_beasiswa", "users.nama"])
            ->get();


        foreach ($permohonan as $key => $value) {
            $value['form'] = json_decode($value['form']);
        }
        return $permohonan;
    }
    public function setBerkas(Request $request)
    {
        $permohonan = PemohonBeasiswa::find($request['id']);
        $permohonan->is_berkas_passed = $request['bool'];
        $permohonan->keterangan = $request['keterangan'];
        $permohonan->form = $request['form'];
        $permohonan->save();
        return response()->json([
            'status' => $permohonan->wasChanged('is_berkas_passed')
        ]);
    }
    public function setSurvey(Request $request)
    {
        $permohonan = PemohonBeasiswa::find($request['id']);
        $permohonan->is_Survey_passed = $request['bool'];
        $permohonan->save();
        return response()->json([
            'status' => $permohonan->wasChanged('is_survey_passed')
        ]);
    }
    public function setInterview(Request $request)
    {
        $permohonan = PemohonBeasiswa::find($request['id']);
        $permohonan->is_interview_passed = $request['bool'];
        $permohonan->save();
        return response()->json([
            'status' => $permohonan->wasChanged('is_interview_passed')
        ]);
    }
    public function setSelection(Request $request)
    {
        $permohonan = PemohonBeasiswa::find($request['id']);
        $permohonan->is_selection_passed = $request['bool'];
        $permohonan->save();
        return response()->json([
            'status' => $permohonan->wasChanged('is_selection_passed')
        ]);
    }
    public function store(Request $request)
    {
        $user = Auth::guard("mahasiswa")->user();
        // return $user->nim;
        $request['nim'] = $user->nim;
        $permohonan = PemohonBeasiswa::updateOrCreate(
            ['mhs_id' => $user->nim, 'beasiswa_id' => $request['beasiswa_id']],
            $request->all()
        );
        // $permohonan = new PemohonBeasiswa;
        // $permohonan->mhs_id         = $user->nim;
        // $permohonan->beasiswa_id    = $request['beasiswa_id'];
        // $permohonan->form           = json_encode($request['form']);
        // $permohonan->save();

        return response()->json([
            'status' => 'Success: Permohonan added'
        ]);
    }
    public function storeFile(Request $request)
    {
        $user = Auth::guard("mahasiswa")->user();
        $fileName = "files/" . $request['id'] . '/' . $user['nim'] . "/" . Carbon::now()->format("Y-m-d-H-i-s") . $request->file->getClientOriginalName();
        $request->file->move(public_path('files/' . $request['id'] . '/' . $user['nim']), $fileName);

        return response()->json(['success' => 'You have successfully upload file.', 'file_name' => $fileName]);
    }
    public function search(Request $request)
    {
        $query = $request->q;
        $permohonan = PemohonBeasiswa::with(['mahasiswa' => function ($q) use ($query) {
            return $q->where('nama',  $query)
                ->orWhere('nim',  $query);
        }])->get();
        return response()->json($permohonan);
    }

    public function myHistory(Request $request)
    {
        $petugas = Auth::guard('petugas')->user();
        return $this->history($request, $petugas);
    }

    public function history(Request $request, UserPetugas $petugas)
    {
        $key = $request->key;
        $verifiedBy = $this->__verifiedBy($key);
        $petugasId = $petugas->id;
        $beasiswa = Beasiswa::withTrashed()
            ->with(['permohonan' => function ($q) use ($verifiedBy, $petugasId) {
                return $q->where('pemohon_beasiswas.' . $verifiedBy, $petugasId);
            }])
            ->whereHas('permohonan', function ($q) use ($verifiedBy, $petugasId) {
                return $q->where($verifiedBy, $petugasId);
            })
            ->get();
        $beasiswa->makeVisible(['permohonan']);
        return response()->json($beasiswa);
    }

    public function import(Request $request, $id)
    {
        $beasiswa = Beasiswa::withTrashed()->findOrFail($id);
        Excel::import(new KelulusanImport($beasiswa), $request->file('file'));
        try {
            $reply['status'] = true;
            $reply['message'] = 'Success: Mahasiswa Added';
            $reply['data'] = $beasiswa->makeVisible(['berkas', 'interview', 'survey', 'selection', 'lulus', 'permohonan']);
        } catch (\Throwable $th) {
            $reply['status'] = false;
            $reply['message'] = $th;
        }
        return response()->json($reply);
    }

    public function __verifiedBy($key)
    {
        switch ($key) {
            case 'berkas':
                $value = 'verificator_id';
                break;
            case 'interview':
                $value = 'interviewer_id';
                break;
            case 'survey':
                $value = 'surveyor_id';
                break;
        }
        return $value;
    }
}
