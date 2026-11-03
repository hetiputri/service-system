<?php

namespace App\Services\Bezetting;

use App\Exports\BezettingExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class BezettingService
{
    public function getBezettingBySkpd(array $request)
    {
        $params = array_filter($request, fn ($value) => ! is_null($value));
        $url = config('services.python.url');
        $timestamp = time();
        ksort($params);
        $signature = create_signature($params, $timestamp);

        $response = Http::withHeaders([
            'X-Signature' => $signature,
            'X-Timestamp' => $timestamp,
            'Content-Type' => 'application/json',
        ])->get($url.'/bezetting', $params);

        return $response;
    }

    public function exportPdf(array $request)
    {
        $bezet_data = $this->getBezettingBySkpd($request);
        $pdf = Pdf::loadView('pages.bezetting.partials.bezetting_table_export', [
            'data' => $bezet_data->json(),
            'status_kepegawaian' => $request['status_kepegawaian'],
        ])
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan_bezetting.pdf');
    }

    public function exportExcel(array $request)
    {
        return Excel::download(
            app()->make(BezettingExport::class, [
                'params' => $request,
            ]),
            'laporan_bezetting.xlsx'
        );
    }
}
