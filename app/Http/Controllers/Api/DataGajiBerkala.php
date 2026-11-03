<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;

class DataGajiBerkala extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function Riwayatgajiberkala($id_pegawai = null)
    {
        $data = $this->userRepository->getData('gaji-berkala', $id_pegawai);
        return response()->json($data);
    }
}
