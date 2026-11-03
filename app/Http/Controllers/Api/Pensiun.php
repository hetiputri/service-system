<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Pensiun\PensiunRepo;
//use Illuminate\Http\Request;

class Pensiun extends Controller
{
    protected $pensiunRepo;
    public function __construct(PensiunRepo $pensiunRepo)
    {
        $this->pensiunRepo = $pensiunRepo;
    }

    public function AjuanPensiun($id_pegawai)
    {
        $data = $this->pensiunRepo->syaratUmur($id_pegawai);
        return response()->json($data);
    }
}
