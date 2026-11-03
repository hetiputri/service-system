<?php

namespace App\Http\Controllers\DaftarPegawai;

use App\Exports\DaftarPegawaiExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\DaftarPegawai\GetDaftarPegawaiRequest;
use App\Services\DaftarPegawai\DaftarPegawaiService;
use App\Services\Referensi\ReferensiService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DaftarPegawaiController extends Controller
{
    private $daftarPegawaiService;

    private $referensiService;

    public function __construct(DaftarPegawaiService $daftarPegawaiService, ReferensiService $referensiService)
    {
        $this->daftarPegawaiService = $daftarPegawaiService;
        $this->referensiService = $referensiService;
    }

    public function daftarPegawaiView()
    {
        $pangkat = $this->referensiService->pangkatAll();
        $eselon = $this->referensiService->eselonAll();
        $tipe_jabatan = $this->referensiService->tipeJabatanAll();
        $skpd = $this->referensiService->skpdAll();

        return view('pages.daftar_pegawai.daftar_pegawai', [
            'pangkat' => $pangkat,
            'eselon' => $eselon,
            'tipe_jabatan' => $tipe_jabatan,
            'skpd' => $skpd,
        ]);
    }

    public function daftarPegawaiData(GetDaftarPegawaiRequest $request)
    {
        ini_set('memory_limit', '256M');
        $result = $this->daftarPegawaiService->daftarPegawaiData($request->validated());

        return response()->json($result->json());
    }

    public function exportPDF(GetDaftarPegawaiRequest $request)
    {
        return $this->daftarPegawaiService->exportPdf($request->validated());
    }

    // public function exportExcel(Request $request)
    // {
    //     ini_set('memory_limit', '1024M');
    //     ini_set('max_execution_time', '600');
    //     $params = array_filter($request->query(), fn ($value) => ! is_null($value));

    //     return Excel::download(
    //         app()->make(DaftarPegawaiExport::class, [
    //             'params' => $params,
    //         ]),
    //         'laporan_daftar_pegawai.xlsx'
    //     );
    // }

    public function exportCsv(GetDaftarPegawaiRequest $request)
    {
        return $this->daftarPegawaiService->exportCsv($request->validated());
    }
}
