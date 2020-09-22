<?php

namespace App\Http\Controllers;

use App\Beasiswa;
use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    public function getAll(){
        return response()->json( Beasiswa::all());
    }
    public function get($id){
        return response()->json( Beasiswa::find($id));
    }
    public function store(Beasiswa $Beasiswa, Request $request){
        $Beasiswa->name = $request['name'];
        $Beasiswa->save();
        return response()->json(['status'=>"Success: Beasiswa Added"]);
    }
    public function edit(Request $request, $id){
        $Beasiswa = Beasiswa::find($id);
        // return $request['name'];
        $Beasiswa->name = $request['name'];
        $Beasiswa->save();
        return response()->json(['status'=>"Success: Beasiswa Updated"]);
    }
    public function delete(Request $request, $id){
        $Beasiswa = Beasiswa::find($id);
        $Beasiswa->delete();
        return response()->json(['status'=>"Success: Beasiswa Deleted"]);
    }
}
