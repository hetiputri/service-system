<?php

namespace App\Repositories\Otentikasi;

use App\Models\DapriModel;
use App\Models\Pengguna;
use App\Models\PenggunaLama;
use App\Repositories\Otentikasi\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepositoryImplement implements AuthRepository
{
    protected $penggunaLama;

    public function __construct(PenggunaLama $penggunaLama)
    {
        $this->penggunaLama = $penggunaLama;
    }
    /**
     * Get data by ID and jenis_data        *
     * @param array $login
     * @return mixed
     */

    public function attempt(array $login)
    {
        //untuk login menggunakan NIP dan password yang ada di database lama dari web ataupun api
        $pengguna = $this->penggunaLama::where('username', $login['nip'])
            ->where('modul', 1)->first();
        if (!$pengguna) {
            $data = array(
                "success" => false,
                "message" => "NIP atau Password Salah"
            );
            // return $data;
        } elseif (!Hash::check($login['password'], $pengguna->passwordhash)) {
            $data = array(
                "success" => false,
                "message" => "NIP atau Password Salah"
            );
            //return $data;
        } else {
            $penggunabaru = Pengguna::where('id_user', $pengguna->id_user)->first();

            $data = array(
                "success" => true,
                "token_simpeg" => $penggunabaru->createToken('auth')->plainTextToken,
                "pengguna" => $penggunabaru,
                "pegawai" => DapriModel::where('id_pegawai', $penggunabaru->id_pegawai)->first()
            );
        }
        return $data;
    }

    public function attemptext(string $id_user) //(array $data)
    {

        //untuk login menggunakan id_user dari session
        $pengguna = Pengguna::where('id_user', $id_user)
            ->where('modul', 1)->first();

        if ($pengguna) {
            Auth::login($pengguna);
            // $data = array(
            //     "success" => true,
            //     "message" => "Login berhasil",
            //     "data"  => Auth::user()
            // );
        } else {
            // $data = array(
            //     "success" => false,
            //     "message" => "NIP atau Password Salah",
            //     "data"  => null
            // );
        }

        //return $data;
    }
}
