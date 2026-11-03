<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;

class DataCuti extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function Riwayatcuti($id_pegawai = null)
    {
        $data = $this->userRepository->getData('cuti', $id_pegawai);
        return response()->json($data);
    }
}
