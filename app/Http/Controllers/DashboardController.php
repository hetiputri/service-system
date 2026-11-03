<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SimpegService;
class DashboardController extends Controller
{
    protected $simpeg;
    public function __construct(SimpegService $simpeg)
    {
        $this->simpeg = $simpeg;
    }
    public function index()
    {
        $pegawai = session('pegawai');
        $token   = session('token');

        if (!$pegawai || !$token) {
            return redirect('/login');
        }
        // dd($token);
        $id = $pegawai['id_pegawai'];
        return view('pages.dashboard', [
            'dataPribadi' => $this->simpeg->dataPribadi($id, $token)['data'] ?? null,
            'filePribadi' => $this->simpeg->dataPribadi($id, $token)['file_upload'][0] ?? $this->simpeg->dataPribadi($id, $token)['file_upload'],
            'riwayatPangkat' => $this->simpeg->riwayatPangkat($id, $token),
            'riwayatJabatan' => $this->simpeg->riwayatJabatan($id, $token),
            'riwayatCuti' => $this->simpeg->riwayatCuti($id, $token),
        ]);
    }
}
