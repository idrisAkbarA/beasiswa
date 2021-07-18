<?php

namespace App\Http\Controllers;

use App\LPJ;
use App\PermohonanLPJ;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PermohonanLPJController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!is_null(Auth::guard('petugas')->user())) {
            $permohonan = PermohonanLPJ::create($request->all());
        } else {
            $user = Auth::guard('mahasiswa')->user();
            $permohonan = PermohonanLPJ::updateOrCreate(
                ['mhs_id' => $user->nim, 'lpj_id' => $request->lpj_id],
                $request->all()
            );
        }
        $reply = [
            'status' => true,
            'data' => $permohonan
        ];
        return response()->json($reply);
    }

    /**
     * Store a new file resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFile(Request $request)
    {
        $LPJ =  LPJ::find($request['id']);
        $namaLPJ = str_replace(" ", "-", $LPJ['nama']);
        $user = Auth::guard("mahasiswa")->user();
        $fileName = "files/" . $request['id'] . '-' . $namaLPJ . '/' . $user['nim'] . "/" . Carbon::now()->format("Y-m-d-H-i-s") . $request->file->getClientOriginalName();
        $request->file->move(public_path('files/' . $request['id'] . '-' . $namaLPJ . '/' . $user['nim']), $fileName);

        return response()->json(['success' => 'You have successfully upload file.', 'file_name' => $fileName]);
    }

    /**
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PermohonanLPJ $permohonan)
    {
        try {
            $permohonan->update($request->all());
            $reply = [
                'status' => true,
                'data' => $permohonan
            ];
        } catch (Exception $e) {
            $reply = [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
        return response()->json($reply);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PermohonanLPJ  $permohonanLPJ
     * @return \Illuminate\Http\Response
     */
    public function show(PermohonanLPJ $permohonanLPJ)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PermohonanLPJ  $permohonanLPJ
     * @return \Illuminate\Http\Response
     */
    public function destroy(PermohonanLPJ $permohonanLPJ)
    {
        //
    }
}
