<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
class authAPIController extends Controller
{
    public function retrieveUserMhs(){
        return response()->json(Auth::user());
    }
    public function retrieveUserPetugas(){
        return response()->json(Auth::user());
    }

    // Check user sebelumnya pernah login sebagai mhs atau petugas,
    // jika petugas maka arahkan ke url login petugas
    public function check(Request $request){
        if(Hash::check("petugas",$request['user'])){
            return response()->json([
                'user_url' => "api/user/petugas",
                'login_url' => 'login-petugas',
            ]);
        }
        return response()->json([
            'user_url' => "api/user",
            'login_url' => 'login',
        ]);
    }

    public function login(Request $request){
        $credentials = $request->only('nim', 'password');
        if (Auth::attempt($credentials)) {
            // Authentication passed...

            $mahasiswa = Hash::make("mahasiswa");
            return response()->json([
                'status' => 'Authenticated','role' => $mahasiswa,  'user'=>Auth::user()
            ]);
        }
        return response()->json([
            'status' => 'Not Authenticated',
        ]);
    }

    public function loginPetugas(Request $request){
        $credentials = $request->only('name', 'password');
        if(Auth::guard('petugas')->attempt($credentials)){
            //Authentication passed...
            $petugas = Hash::make("petugas");
            return response()->json([
                'status' => 'Authenticated', 'role' => $petugas, 'user'=>Auth::guard("petugas")->user()
            ]);
        }
        return response()->json([
            'status' => 'Not Authenticated',
        ]);
    }
    public function logout(Request $request){
        if(Hash::check("petugas",$request['user'])){
            Auth::guard('petugas')->logout();
            return response()->json(["user"=>"petugas"]);
        }
        return Auth::logout();
    }
    public function changePass(Request $request){
        $user = User::find(Auth::id());
        if(!Hash::check($request->input("old"), $user->password)){
            return response()->json(["status"=>"invalid old password","detail"=>$request['old']]);
        }
        $newPass = Hash::make($request->input("new"));
        $user->password = $newPass;
        $user->save();
        return response()->json(["status"=>"ok"]);
    }
}
