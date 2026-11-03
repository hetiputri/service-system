<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;

class DataPendidikanController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function DataPendidikan($id_pegawai = null)
    {
        $data = $this->userRepository->getData('pendidikan', $id_pegawai);
        return response()->json($data);
    }
}
