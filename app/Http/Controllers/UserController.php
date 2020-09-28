<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAll(){
        return response()->json( User::all());
    }
    public function get($id){
        return response()->json( User::find($id));
    }
    public function store(User $user, Request $request){
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
        return response()->json(['status'=>"Success: User Deleted"]);
    }
}
