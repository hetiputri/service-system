<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Jabatan\SearchJabatanWithFiltersRequest;
use App\Services\Referensi\ReferensiService;

class ReferensiController extends Controller
{
    private $referensiService;

    public function __construct(ReferensiService $referensiService)
    {
        $this->referensiService = $referensiService;
    }

    public function searchJabatanWithFilters(SearchJabatanWithFiltersRequest $request)
    {
        $result = $this->referensiService->searchJabatanWithFilters($request->validated());

        return response()->json($result);
    }
}
