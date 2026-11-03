<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;

class DataHukdis extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function Riwayathukdis($id_pegawai = null)
    {
        $data = $this->userRepository->getData('hukuman-disiplin', $id_pegawai);
        return response()->json($data);
    }
}
