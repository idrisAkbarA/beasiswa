<?php

namespace App\Http\Controllers;

use App\User;
use App\Imports\UserImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function getAll(){
        return response()->json( User::all());
    }
    public function get($id){
        return response()->json( User::find($id));
    }
    public function store(User $user, Request $request){
        $request['password'] = Hash::make($request->password);
        $user->create($request->all());
        return response()->json(['status'=>"Success: Mahasiswa Added"]);
    }
    public function edit(Request $request, $id){
        $user = User::find($id);
        $user->update($request->all());
        return response()->json(['status'=>"Success: Mahasiswa Updated"]);
    }
    public function delete(Request $request, $id){
        $user = User::find($id);
        $user->delete();
        return response()->json(['status'=>"Success: Mahasiswa Deleted"]);
    }
    public function import(Request $request)
    {
        Excel::import(new UserImport, $request->file('file'));
        return response()->json(['status'=>"Success: Mahasiswa Added"]);
    }
    public function export()
    {
        $file= public_path() . "/template/UserMahasiswa.xlsx";
        $headers = [
              'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
           ];
        return response()->download($file, 'UserMahasiswa.xlsx', $headers);
    }
}
