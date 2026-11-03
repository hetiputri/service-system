<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pegawai\SearchPegawaiWithFiltersRequest;
use App\Services\Pegawai\PegawaiService;

class PegawaiController extends Controller
{
    private $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function searchWithFilters(SearchPegawaiWithFiltersRequest $request)
    {
        $result = $this->pegawaiService->searchWithFilters($request->validated());

        return response()->json($result);
    }
}
