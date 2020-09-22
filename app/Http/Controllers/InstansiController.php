<?php

namespace App\Http\Controllers;

use App\Instansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    public function getAll(){
        return response()->json( Instansi::all());
    }
    public function get($id){
        return response()->json( Instansi::find($id));
    }
    public function store(Instansi $instansi, Request $request){
        $instansi->name = $request['name'];
        $instansi->save();
        return response()->json(['status'=>"Success: Instansi Added"]);
    }
    public function edit(Request $request, $id){
        $instansi = Instansi::find($id);
        // return $request['name'];
        $instansi->name = $request['name'];
        $instansi->save();
        return response()->json(['status'=>"Success: Instansi Updated"]);
    }
    public function delete(Request $request, $id){
        $instansi = Instansi::find($id);
        $instansi->delete();
        return response()->json(['status'=>"Success: Instansi Deleted"]);
    }
}
