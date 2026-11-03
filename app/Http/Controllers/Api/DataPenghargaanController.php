<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;

class DataPenghargaanController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function Riwayatpenghargaan($id_pegawai = null)
    {
        $data = $this->userRepository->getData('penghargaan', $id_pegawai);
        return response()->json($data);
    }
}
