<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;

class DataPmk extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function Riwayatpmk($id_pegawai = null)
    {
        $data = $this->userRepository->getData('pmk', $id_pegawai);
        return response()->json($data);
    }
}
