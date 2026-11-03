<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Jabatan\JabatanWithFiltersRequest;
use App\Http\Requests\Pegawai\PegawaiDenganJabatanTerbaruRequest;
use App\Services\Jabatan\JabatanService;
use App\Services\Pegawai\PegawaiService;

class TestingApi extends Controller
{
    private $jabatanService;

    private $pegawaiService;

    public function __construct(JabatanService $jabatanService, PegawaiService $pegawaiService)
    {
        $this->jabatanService = $jabatanService;
        $this->pegawaiService = $pegawaiService;
    }

    public function getWithFiltersJabatan(JabatanWithFiltersRequest $request)
    {
        $result = $this->jabatanService->getWithFilters($request->validated());

        return response()->json($result);
    }

    public function pegawaiDenganJabatanTerbaru(PegawaiDenganJabatanTerbaruRequest $request)
    {
        $result = $this->pegawaiService->pegawaiDenganJabatanTerbaru($request->validated());

        return response()->json($result);
    }
}
