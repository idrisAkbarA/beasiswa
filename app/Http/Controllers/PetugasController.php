<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserPetugas;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function getAll(){
        return response()->json( UserPetugas::all());
    }
    public function get($id){
        return response()->json( UserPetugas::find($id));
    }
    public function store(UserPetugas $petugas, Request $request){
        $petugas->name = $request['name'];
        $petugas->role = $request['role'];
        $petugas->password = Hash::make($request['password']);
        $petugas->save();
        return response()->json(['status'=>"Success: User Added"]);
    }
    public function edit(Request $request, $id){
        // $petugas = UserPetugas::find($id);
        // // return $request['name'];
        // $petugas->name = $request['name'];
        // $petugas->role = $request['role'];
        // // if(isset($request['password'])){
        // // }
        // $petugas->password = Hash::make($request['password']);
        // $petugas->save();
        // return response()->json(['status'=>"Success: User Updated"]);
        return $request['password'];
    }
    public function delete(Request $request, $id){
        $petugas = UserPetugas::find($id);
        $petugas->delete();
        return response()->json(['status'=>"Success: User Deleted"]);
    }
}
