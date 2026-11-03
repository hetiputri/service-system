<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SimpegService;

class ProfileController extends Controller
{
    protected $simpeg;


    public function __construct(SimpegService $simpeg)
    {
        $this->simpeg = $simpeg;
        $this->tokenn   = session('token');
    }

    public function index()
    {
        $pegawai = session('pegawai');
        $token   = session('token');

        if (!$pegawai || !$token) {
            return redirect('/login');
        }

        $id = $pegawai['id_pegawai'];

        $dataPribadi = $this->simpeg->dataPribadi($id, $token);
        $dataPNS     = $this->simpeg->dataPNS($id, $token);
        $dataCPNS    = $this->simpeg->dataCPNS($id, $token);
        $keluarga    = $this->simpeg->riwayatKeluarga($id, $token);

        return view('pages.profile', [
            'dataPribadi' => $dataPribadi['data'] ?? [],
            'filePribadi' => $dataPribadi['file_upload'][0] ?? $dataPribadi['file_upload'] ?? [],

            'dataPNS' => $dataPNS['data-pns'] ?? [],
            'filePNS' => $dataPNS['file-sk-pns'] ?? [],

            'dataCPNS' => $dataCPNS['data-cpns'][0] ?? $dataCPNS['data-cpns'] ?? [],

            'riwayatPangkat'    => $this->simpeg->riwayatPangkat($id, $token) ?? [],
            'riwayatJabatan'    => $this->simpeg->riwayatJabatan($id, $token) ?? [],
            'riwayatPendidikan' => $this->simpeg->riwayatPendidikan($id, $token) ?? [],

            'riwayatDiklatStruktural' => $this->simpeg->diklatStruktural($id, $token) ?? [],
            'riwayatDiklatLainnya'    => $this->simpeg->diklatLainnya($id, $token) ?? [],

            'riwayatGaji'        => $this->simpeg->riwayatGaji($id, $token) ?? [],
            'riwayatPenghargaan' => $this->simpeg->riwayatPenghargaan($id, $token) ?? [],
            'riwayatCuti'        => $this->simpeg->riwayatCuti($id, $token) ?? [],
            'riwayatHukdis'      => $this->simpeg->riwayatHukdis($id, $token) ?? [],

            'pasangan' => $keluarga['data-pasangan'] ?? [],
            'anak'     => $keluarga['data-anak'] ?? [],

            'riwayatPMK' => $this->simpeg->riwayatPMK($id, $token) ?? [],
            'riwayatPI'  => $this->simpeg->riwayatPI($id, $token) ?? [],

            'id_pegawai' => $id
        ]);
    }

    public function show($id)
    {
        $token = session('token');

        if (!$token) {
            return redirect('/login');
        }
        // dd($this->simpeg->dataPNS($id, $token)['data-pns']);
        $data = $this->simpeg->riwayatKeluarga($id, $token);
        // dd($this->simpeg->dataPNS($id, $token)['file-sk-pns']);
        $anak = $data['data-anak'] ?? [];
        if (!is_array($anak)) {
            $anak = [];
        }
        return view('pages.profile', [
            'dataPribadi'       => $this->simpeg->dataPribadi($id, $token)['data'] ?? [],
            'filePribadi'       => $this->simpeg->dataPribadi($id, $token)['file_upload'][0] ?? $this->simpeg->dataPribadi($id, $token)['file_upload'] ?? [],
            'dataPNS'       => $this->simpeg->dataPNS($id, $token)['data-pns'] ?? [],
            'filePNS'       => $this->simpeg->dataPNS($id, $token)['file-sk-pns'] ?? [],
            'dataCPNS'       => $this->simpeg->dataCPNS($id, $token)['data-cpns'][0] ?? $this->simpeg->dataCPNS($id, $token)['data-cpns'] ?? null,
            'riwayatPangkat'    => $this->simpeg->riwayatPangkat($id, $token) ?? [],
            'riwayatJabatan'    => $this->simpeg->riwayatJabatan($id, $token) ?? [],
            'riwayatPendidikan' => $this->simpeg->riwayatPendidikan($id, $token) ?? [],
            'riwayatDiklatStruktural'  => $this->simpeg->diklatStruktural($id, $token) ?? [],
            'riwayatDiklatLainnya'  => $this->simpeg->diklatLainnya($id, $token) ?? [],
            'riwayatGaji'  => $this->simpeg->riwayatGaji($id, $token) ?? [],
            'riwayatPenghargaan'  => $this->simpeg->riwayatPenghargaan($id, $token) ?? [],
            'riwayatCuti'  => $this->simpeg->riwayatCuti($id, $token) ?? [],
            'riwayatHukdis'  => $this->simpeg->riwayatHukdis($id, $token) ?? [],
            'pasangan'  =>  $data['data-pasangan'] ?? [],
            'anak' => $anak,
            'riwayatPMK' => $this->simpeg->riwayatPMK($id, $token) ?? [],
            'riwayatPI'  => $this->simpeg->riwayatPI($id, $token) ?? [],
            'id_pegawai' => $id ?? [],
        ]);
    }

    public function master($type)
    {
        if (in_array($type, ['status-tk-pendidikan', 'status-jurusan'])) {
            $data = [
                [
                    'id_stat' => 1,
                    'nama_stat' => 'Pendidikan Terakhir',
                ],
                [
                    'id_stat' => 2,
                    'nama_stat' => 'Pendidikan Minimal',
                ],
            ];
            return response()->json($data);
        } else {
            return response()->json(
                $this->simpeg->master($type, $this->tokenn)
            );
        }
    }
    public function ajaxSearch(Request $request, SimpegService $simpegService)
    {

        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 10;

        $result = $simpegService->filterData(
            $request->all(),
            $page,
            $perPage
        );

        if (!$result['success']) {
            return response()->json($result, 500);
        }

        return response()->json($result['data']);
    }
    // public function ajaxSearch(Request $request, SimpegService $simpegService)
    // {
    //     $token = session('token');

    //     $validated = $request->validate([
    //         'page' => 'nullable|integer|min:1',
    //         'per_page' => 'nullable|integer|min:5|max:100',
    //     ]);

    //     $page = $validated['page'] ?? 1;
    //     $perPage = $validated['per_page'] ?? 10;

    //     $result = $simpegService->filterData(
    //         $request->all(),
    //         $token,
    //         $page,
    //         $perPage
    //     );

    //     if (!$result['success']) {
    //         return response()->json(['message' => 'Gagal ambil data'], 500);
    //     }

    //     return response()->json($result['data']);
    // }

    public function cariPegawai()
    {
        return view('pages.cari-pegawai');
    }

    public function search(Request $request)
    {
        //$data = $this->simpegService->searchPegawai($request);

        //return response()->json($data);
    }
}
