<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\User;
class authAPIController extends Controller
{
    public function loginServer(Request $request){
        $response = Http::post('https://api-iraise.uin-suska.ac.id/login', [
            'username' => $request["username"],
            'password' =>  $request["password"],
        ]);

        $user = User::updateOrCreate(
            ['nim'=>$response["data"]["user"]["id_pd"]],
            [
                'nama'=>$response["data"]["user"]["nm_pd"],
                'password'=>Hash::make($response["data"]["token"]),
                'jurusan_id'=>$response["data"]["user"]["id_pd"][4], // jurusan diambil dari digit ke 5 nim
                'email'=>$response["data"]["user"]["email"],
                'hp'=>$response["data"]["user"]["telepon_seluler"],
                'semester'=>null,
                'ips'=>$response["data"]["user"]["ips"],
                'ipk'=>$response["data"]["user"]["ipk"],
                'tgl_lahir'=>$response["data"]["user"]["tgl_lahir"],
                'tmpt_lahir'=>$response["data"]["user"]["tmpt_lahir"],
                'jml_bayar'=>$response["data"]["user"]["jlh_bayar"],
            ]
        );
        // return $response["data"]["user"]["jlh_bayar"];
        // return $response;
        return response()->json(["status"=>"Authenticated","token"=>$response["data"]["token"]]);
    }

    public function retrieveUserMhs(){
        return response()->json(Auth::guard("mahasiswa")->user());
    }
    public function retrieveUserPetugas(){
        return response()->json(Auth::guard("petugas")->user());
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
        if (Auth::guard("mahasiswa")->attempt($credentials)) {
            // Authentication passed...

            $mahasiswa = Hash::make("mahasiswa");
            return response()->json([
                'status' => 'Authenticated','role' => $mahasiswa,  'user'=>Auth::guard("mahasiswa")->user()
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
        return Auth::guard('mahasiswa')->logout();
    }
    public function logoutPetugas(Request $request){
            Auth::guard('petugas')->logout();
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
