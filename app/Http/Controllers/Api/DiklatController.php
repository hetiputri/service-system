<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;

class DiklatController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function DiklatStruktural($id_pegawai = null)
    {
        $data = $this->userRepository->getData('diklat-struktural', $id_pegawai);
        return response()->json($data);
    }

    public function DiklatLainnya($id_pegawai = null)
    {
        $data = $this->userRepository->getData('diklat-lainnya', $id_pegawai);
        return response()->json($data);
    }
}
