<?php

namespace App\Exports;

use App\Services\Bezetting\BezettingService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class BezettingExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $params;

    protected $bezettingService;

    public function __construct(array $params, BezettingService $bezettingService)
    {
        $this->params = $params;
        $this->bezettingService = $bezettingService;
    }

    public function view(): View
    {
        $response = $this->bezettingService->getBezettingBySkpd($this->params);

        return view('pages.bezetting.partials.bezetting_table_export', [
            'data' => $response->json(),
            'status_kepegawaian' => $this->params['status_kepegawaian'],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $sheet->getPageSetup()->setOrientation(
                    PageSetup::ORIENTATION_LANDSCAPE
                );

                $sheet->getPageSetup()->setPaperSize(
                    PageSetup::PAPERSIZE_A4
                );
            },
        ];
    }
}
