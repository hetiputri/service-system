<?php

namespace App\Http\Controllers\KonsepMutasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mutasi\AddMutasiStrukturalDetailRequest;
use App\Http\Requests\Mutasi\KonsepMutasiRequest;
use App\Http\Requests\Mutasi\UpdateMutasiStrukturalRequest;
use App\Models\MutasiStruktural;
use App\Services\KonsepMutasi\KonsepMutasiService;
use App\Services\Referensi\ReferensiService;

class KonsepMutasiController extends Controller
{
    private $konsepMutasiService;

    private $referensiService;

    public function __construct(KonsepMutasiService $konsepMutasiService, ReferensiService $referensiService)
    {
        $this->referensiService = $referensiService;
        $this->konsepMutasiService = $konsepMutasiService;
    }

    public function mutasiKonsepView()
    {
        $konsepMutasi = $this->konsepMutasiService->all();

        return view('pages.mutasi.konsep_mutasi', [
            'konsepMutasi' => $konsepMutasi,
        ]);
    }

    public function create(KonsepMutasiRequest $request)
    {
        $this->konsepMutasiService->create($request->validated());

        return response()->json([
            'message' => 'created',
            'redirect' => '/mutasi/konsep',
        ]);
    }

    public function tambahKonsepView()
    {
        return view('pages.mutasi.tambah_konsep');
    }

    public function ubahKonsepView(MutasiStruktural $mutasiStruktural)
    {
        return view('pages.mutasi.update_konsep',
            ['konsep' => $mutasiStruktural]
        );
    }

    public function konsepDetailView(string $id_konsep)
    {
        $mutasiStruktural = $this->konsepMutasiService->findById($id_konsep);

        // return response()->json($mutasiStruktural);
        return view('pages.mutasi.konsep_mutasi_detail', [
            'mutasiStruktural' => $mutasiStruktural,
        ]);
    }

    public function tambahJabatanView(string $id_konsep)
    {
        $skpd = $this->referensiService->skpdAll();
        $statusJabatan = [
            0 => 'Berisi',
            1 => 'Lowong',
        ];

        $mutasiStruktural = $this->konsepMutasiService->findById($id_konsep);

        return view('pages.mutasi.tambah_jabatan', [
            'skpd' => $skpd,
            'status_jabatan' => $statusJabatan,
            'mutasi_struktural' => $mutasiStruktural,
        ]);
    }

    public function tambahJabatan(MutasiStruktural $mutasiStruktural, AddMutasiStrukturalDetailRequest $request)
    {
        $this->konsepMutasiService->createDetail($mutasiStruktural, $request->validated());

        return response()->json([
            'message' => 'created',
            'redirect' => "/mutasi/konsep/$mutasiStruktural->id",
        ]);
    }

    public function update(MutasiStruktural $mutasiStruktural, UpdateMutasiStrukturalRequest $request)
    {
        $this->konsepMutasiService->update($mutasiStruktural, $request->validated());

        return response()->json([
            'message' => 'updated',
            'redirect' => route('konsep_mutasi.view'),
        ]);
    }

    public function delete(MutasiStruktural $mutasiStruktural)
    {
        $this->konsepMutasiService->delete($mutasiStruktural);

        return response()->json([
            'message' => 'deleted',
            'redirect' => route('konsep_mutasi.view'),
        ]);
    }

    public function deleteDetail(MutasiStruktural $mutasiStruktural)
    {
        $this->konsepMutasiService->deleteDetail($mutasiStruktural);

        return response()->json([
            'message' => 'deleted',
            'redirect' => "/mutasi/konsep/$mutasiStruktural->id",
        ]);
    }
}
