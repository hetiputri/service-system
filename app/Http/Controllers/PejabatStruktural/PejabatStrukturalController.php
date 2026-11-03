<?php

namespace App\Http\Controllers\PejabatStruktural;

use App\Http\Controllers\Controller;
use App\Http\Requests\PejabatStruktural\GetPejabatStrukturalRequest;
use App\Services\PejabatStruktural\PejabatStrukturalService;
use App\Services\Referensi\ReferensiService;

class PejabatStrukturalController extends Controller
{
    private $pejabatStrukturalService;

    private $referensiService;

    public function __construct(ReferensiService $referensiService, PejabatStrukturalService $pejabatStrukturalService)
    {
        $this->referensiService = $referensiService;
        $this->pejabatStrukturalService = $pejabatStrukturalService;
    }

    public function pejabatStrukturalView()
    {
        $skpd = $this->referensiService->skpdAll();
        $agama = $this->referensiService->agamaAll();
        $jenisKelamin = $this->referensiService->jenisKelaminAll();

        $statusJabatan = [
            0 => 'Seluruh Status Jabatan',
            1 => 'Jabatan Defenitif',
            2 => 'Jabatan Tambahan',
            3 => 'Jabatan Pelaksana Tugas',
            4 => 'Jabatan Lowong',
        ];

        $jenisJabatan = [
            0 => 'Seluruh Jenis Jabatan / Eselon',
            1 => 'JPT, Administrator, Pengawas',
            2 => 'Jabatan Pimpinan Tinggi',
            3 => 'Jabatan Administrator',
            4 => 'Jabatan Pengawas',
            5 => 'Jabatan Eselon II.a',
            6 => 'Jabatan Eselon II.b',
            7 => 'Jabatan Eselon III.a',
            8 => 'Jabatan Eselon III.b',
            9 => 'Jabatan Eselon IV.a',
            10 => 'Jabatan Eselon IV.b',
            11 => 'Jabatan Tugas Tambahan / Non Eselon',
        ];

        $urutan = [
            0 => 'Unit Kerja',
            1 => 'Eselon',
        ];

        return view('pages.pejabat_struktural.pejabat_struktural', [
            'skpd' => $skpd,
            'agama' => $agama,
            'jenis_kelamin' => $jenisKelamin,
            'status_jabatan' => $statusJabatan,
            'jenis_jabatan' => $jenisJabatan,
            'urutan' => $urutan,
        ]);
    }

    public function pejabatStrukturalData(GetPejabatStrukturalRequest $request)
    {

        $result = $this->pejabatStrukturalService->pejabatStrukturalData($request->validated());

        return response()->json($result->json());

    }

    public function exportCsv(GetPejabatStrukturalRequest $request)
    {
        return $this->pejabatStrukturalService->exportCsv($request->validated());
    }
}
