<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;

class DataJabatanController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function DataJabatan($id_pegawai = null)
    {
        $data = $this->userRepository->getData('jabatan', $id_pegawai);

        return response()->json($data);
    }
}
