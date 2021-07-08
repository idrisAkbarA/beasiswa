<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\User;
use App\Jurusan;
use App\Fakultas;

class authAPIController extends Controller
{
    public function loginServer(Request $request)
    {
        // get data from webservice
        $response = Http::post('https://api-iraise.uin-suska.ac.id/login', [
            'username' => $request["username"],
            'password' =>  $request["password"],
        ]);
        // from here we are going to store new data or update db
        // return $response;
        if ($response['success'] == false) {
            return response()->json(["status" => "Not Authenticated"]);
        }
        // update fakultas
        $value = $response["data"]["user"]["fakultas"];
        $fakultas = null;
        $words = explode(" DAN ", $value);
        // set singkatan
        if (count($words) > 2) {
            $first_letter = substr($words[0], 0, 1);
            $second_letter = substr($words[2], 0, 1);
            $fakultas = Fakultas::updateOrCreate(
                ['nama' => $value],
                ['singkatan' => "F" . $first_letter . $second_letter]
            );
        } else if (count($words) > 1) {
            $first_letter = substr($words[0], 0, 1);
            $second_letter = substr($words[1], 0, 1);
            $fakultas = Fakultas::updateOrCreate(
                ['nama' => $value],
                ['singkatan' => "F" . $first_letter . $second_letter]
            );
        } else {
            $first_letter = substr($words[0], 0, 2);
            // $second_letter = substr($words[0],1,2);
            $fakultas = Fakultas::updateOrCreate(
                ['nama' => $value],
                ['singkatan' => "F" . $first_letter]
            );
        }

        $fakultas_id = $fakultas->id; // jurusan id needed for user table

        //update/create jurusan
        $jurusan = Jurusan::updateOrCreate(
            ['singkatan' => $response["data"]["user"]["regpd_id_sms"]],
            [
                'nama' => $response["data"]["user"]["prodi"],
                'fakultas_id' => $fakultas_id
            ]
        );

        $jurusan_id = $jurusan->id;
        // test
        $user = User::updateOrCreate(
            ['nim' => $response["data"]["user"]["id_pd"]],
            [
                'nama' => $response["data"]["user"]["nm_pd"],
                'password' => Hash::make($response["data"]["token"]),
                // 'jurusan_id'=>$response["data"]["user"]["id_pd"][5], // jurusan diambil dari digit ke 5 nim
                'jurusan_id' => $jurusan_id, // jurusan diambil dari digit ke 5 nim
                'email' => $response["data"]["user"]["email"],
                'hp' => $response["data"]["user"]["telepon_seluler"],
                'semester' => $response["data"]["user"]["semester"],
                'ips' => $response["data"]["ipipk"]["ips"],
                'ipk' => $response["data"]["ipipk"]["ipk"],
                'tgl_lahir' => $response["data"]["user"]["tgl_lahir"],
                'tmpt_lahir' => $response["data"]["user"]["tmpt_lahir"],
                'ukt' => (int)$response['data']['ukt']["kelompok_ukt_final"],
            ]
        );
        // return $response["data"]["user"]["jlh_bayar"];
        // return $response;
        return response()->json(["status" => "Authenticated", "token" => $response["data"]["token"],]);
    }

    public function retrieveUserMhs()
    {
        // return 'asd';
        return response()->json(Auth::guard("mahasiswa")->user());
    }
    public function retrieveUserPetugas()
    {
        return response()->json(Auth::guard("petugas")->user());
    }

    // Check user sebelumnya pernah login sebagai mhs atau petugas,
    // jika petugas maka arahkan ke url login petugas
    public function check(Request $request)
    {
        if (Hash::check("petugas", $request['user'])) {
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

    public function login(Request $request)
    {
        $credentials = $request->only('nim', 'password');
        if (Auth::guard("mahasiswa")->attempt($credentials)) {
            // Authentication passed...

            $mahasiswa = Hash::make("mahasiswa");
            return response()->json([
                'status' => 'Authenticated', 'role' => $mahasiswa,  'user' => Auth::guard("mahasiswa")->user()
            ]);
        }
        return response()->json([
            'status' => 'Not Authenticated',
        ]);
    }

    public function loginPetugas(Request $request)
    {
        $credentials = $request->only('name', 'password');
        if (Auth::guard('petugas')->attempt($credentials)) {
            //Authentication passed...
            $petugas = Hash::make("petugas");
            return response()->json([
                'status' => 'Authenticated', 'role' => $petugas, 'user' => Auth::guard("petugas")->user()
            ]);
        }
        return response()->json([
            'status' => 'Not Authenticated',
        ]);
    }
    public function logout(Request $request)
    {
        return Auth::guard('mahasiswa')->logout();
    }
    public function logoutPetugas(Request $request)
    {
        Auth::guard('petugas')->logout();
    }
    public function changePass(Request $request)
    {
        $user = User::find(Auth::id());
        if (!Hash::check($request->input("old"), $user->password)) {
            return response()->json(["status" => "invalid old password", "detail" => $request['old']]);
        }
        $newPass = Hash::make($request->input("new"));
        $user->password = $newPass;
        $user->save();
        return response()->json(["status" => "ok"]);
    }
}
