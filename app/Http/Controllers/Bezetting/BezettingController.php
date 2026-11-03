<?php

namespace App\Http\Controllers\Bezetting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bezetting\GetBezettingBySkpdRequest;
use App\Services\Bezetting\BezettingService;
use App\Services\Referensi\ReferensiService;
use Illuminate\Http\Request;

class BezettingController extends Controller
{
    private $referensiService;

    private $bezettingService;

    public function __construct(BezettingService $bezettingService, ReferensiService $referensiService)
    {
        $this->bezettingService = $bezettingService;
        $this->referensiService = $referensiService;
    }

    public function bezettingView()
    {
        $skpd = $this->referensiService->skpdAll();

        return view('pages.bezetting.bezetting', [
            'skpd' => $skpd,
        ]);
    }

    // public function bezettingPDF(Request $request)
    // {
    //     $bezet_data = $this->getBezettingBySkpd($request);

    //     return view('pages.bezetting.partials.bezetting_table_export', [
    //         'data' => $bezet_data,
    //     ]);
    // }

    public function exportPDF(GetBezettingBySkpdRequest $request)
    {
        return $this->bezettingService->exportPdf($request->validated());
    }

    public function exportExcel(GetBezettingBySkpdRequest $request)
    {
        return $this->bezettingService->exportExcel($request->validated());
    }

    public function getBezettingBySkpd(GetBezettingBySkpdRequest $request)
    {
        $result = $this->bezettingService->getBezettingBySkpd($request->validated());

        return response()->json($result->json());
    }
}
