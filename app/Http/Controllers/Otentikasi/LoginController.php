<?php

namespace App\Http\Controllers\Otentikasi;

use App\Http\Controllers\Controller;
use App\Repositories\Otentikasi\AuthRepository;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }


    public function login(Request $request)
    {
        //untuk login menggunakan NIP dan password yang ada di database lama
        $credentials = $request->validate([
            'nip' =>  'required',
            'password' =>  'required',
        ]);


        $pengguna = $this->authRepository->attempt($credentials);
        if ($pengguna['success'] == true) {
            $token = $pengguna['pengguna']->createToken('apiToken')->plainTextToken;
            return response()->json([
                'success' => true,
                'token_simpeg' => $token,
                'pengguna' => $pengguna['pengguna'],
                //'roles' => $pengguna['pengguna']->getRoleNames(),

            ]);
        } else {
            return response()->json($pengguna);
        }
    }

    public function loginext()
    {
        //untuk login menggunakan id_user dari session
        $id_user = session('user.id_user');
        if ($id_user) {
            // $data = [
            //     'id_user' =>  $id_user,
            //     // 'password' =>  'required',
            // ];

            $pengguna = $this->authRepository->attemptext($id_user);
            return $pengguna;
        } else {
            return null;
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }



    // public function menu(Request $request)
    // {
    //     $user = $request->user();

    //     $menu = \DB::table('dt_menu')->where('aktif', 1)->get();

    //     return response()->json([
    //         'user' => $user,
    //         'menu' => $menu,
    //     ]);
    // }
}
