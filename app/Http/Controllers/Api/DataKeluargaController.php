<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;


class DataKeluargaController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function Datakeluarga($id_pegawai = null)
    {
        $data = $this->userRepository->getData('data-keluarga', $id_pegawai);
        return response()->json($data);
    }
}
