<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;

class DataCpnsController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function Datacpns($id_pegawai = null)
    {
        $data = $this->userRepository->getData('data-cpns', $id_pegawai);
        return response()->json($data);
    }
}
