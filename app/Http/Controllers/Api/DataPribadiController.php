<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;


class DataPribadiController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function DataPribadi($id_pegawai = null)
    {
        $model = $this->userRepository->getData('pegawai-profile', $id_pegawai);
        return response()->json($model);
    }



    public function Data2(Request $request)
    {
        return response()->json([
            'message' => $request->user()->id_pegawai,
        ]);
    }
}
