<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;


class DataPnsController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function Datapns($id_pegawai = null)
    {
        $data = $this->userRepository->getData('data-pns', $id_pegawai);
        return response()->json($data);
    }
}
