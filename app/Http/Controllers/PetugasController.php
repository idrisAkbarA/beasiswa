<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserPetugas;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function getAll()
    {
        return response()->json(UserPetugas::all());
    }
    public function get($id)
    {
        return response()->json(UserPetugas::find($id));
    }
    public function store(UserPetugas $petugas, Request $request)
    {
        $petugas->name = $request['name'];
        $petugas->nama_lengkap = $request['nama_lengkap'];
        $petugas->role = $request['role'];
        $petugas->password = Hash::make($request['password']);
        if ($request['role'] == 5) {
            $petugas->fakultas_id = $request['fakultas_id'];
        }
        $petugas->save();
        return response()->json(['status' => "Success: User Added"]);
    }
    public function edit(Request $request, $id)
    {
        $petugas = UserPetugas::find($id);
        if ($request['password'] != "") {
            $petugas->password = Hash::make($request['password']);
        }
        $petugas->save();
        return response()->json(['status' => "Success: User Updated"]);
        // return $request['password'];
    }
    public function delete(Request $request, $id)
    {
        $petugas = UserPetugas::find($id);
        $petugas->delete();
        return response()->json(['status' => "Success: User Deleted"]);
    }
}
