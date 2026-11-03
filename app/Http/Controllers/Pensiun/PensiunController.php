<?php

namespace App\Http\Controllers\Pensiun;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\PensiunEligibilityService;
use App\Services\SimpegService;
use App\Models\JenisLayanan;
use App\Models\Dpcp;
use App\Models\PengajuanLayanan;
use App\Models\PersyaratanLayanan;
use App\Models\DokumenPengajuan;
//use App\Models\Layanan;
use App\Models\WorkflowLog;
use App\Models\WorkflowStep;
use App\Models\RefGaji;
use Carbon\Carbon;


class PensiunController extends Controller
{
    protected $simpeg;
    public function __construct(SimpegService $simpeg)
    {
        $this->simpeg = $simpeg;
    }

    public function index()
    {
        $pegawai = session('pegawai');
        $token = session('token');

        if (!$pegawai || !$token) {
            return redirect('/login');
        }

        $id = $pegawai['id_pegawai'];

        $pengajuan = PengajuanLayanan::pensiun()
            // ->aktif()
            ->where('pegawai_id', $id)
            ->first();

        $pengajuanId = $pengajuan ? $pengajuan->id : 'null';

        /*
        |--------------------------------------------------------------------------
        | 🔥 SIMPEG DATA 
        |--------------------------------------------------------------------------
        */

        $data = $this->simpeg->riwayatKeluarga($id);

        $dataPribadiResponse = $this->simpeg->dataPribadi($id);
        $dataPribadi = $dataPribadiResponse['data'];
        $filePribadi = $dataPribadiResponse['file_upload'][0] ?? $dataPribadiResponse['file_upload'];

        $riwayatResponse = $this->simpeg->riwayatJabatan($id);

        $riwayatData = collect($riwayatResponse['data'] ?? [])
            ->sortByDesc('tmt_jabatan')
            ->first();

        $tipeJabatan = $riwayatData['tipe_jabatan'] ?? null;

        /*
        |--------------------------------------------------------------------------
        |  CEK / BUAT PENGAJUAN DRAFT PENSIUN
        |--------------------------------------------------------------------------
        */
        // ambil data dpcp jika ada
        $dpcp = Dpcp::where('pegawai_id', $id)->first();
        $alamatPensiun = $dataPribadi['alamat'] ? $dataPribadi['alamat'] . ' Kel. ' . $dataPribadi['kelurahan'] . ' Kec. ' . $dataPribadi['kecamatan'] . ' Kab./Kota ' . $dataPribadi['kotakabupaten'] . ' Prov. ' . $dataPribadi['provinsi'] : ''; //$dpcp ? $dpcp->alamat_sesudah_pensiun :
        $nama_ttd = $dpcp->nama_ttd ?? '';
        $signature = $dpcp->signature_base64 ?? '';


        $service = new PensiunEligibilityService();

        $eligibility = $service->check(
            $dataPribadi['tanggal_lahir'],
            $tipeJabatan,
            $dataPribadi['mk_tahun']
        );

        $currentStep = $pengajuan ? $pengajuan->form_step - 1 : 0;
        $stepUser = $pengajuan ? $pengajuan->current_step : 0;
        $selectedJenis = $pengajuan->layanan_id ?? 'null';
        $status_akhir = $pengajuan?->status_akhir ?? 'draft';
        $maxStep = ($pengajuan->form_step ?? 1) - 1;

        $jenisPensiun = JenisLayanan::with('layanan')
            ->findOrFail(1)
            ->layanan;

        $cpnsResponse = $this->simpeg->dataCPNS($id);
        $dataCPNS = $cpnsResponse['data-cpns'][0] ?? $cpnsResponse['data-cpns'];

        $dataPNS  = $this->simpeg->dataPNS($id)['data-pns'] ?? null;
        $filePNS  = $this->simpeg->dataPNS($id)['file-sk-pns'] ?? null;

        $riwayatPangkat = $this->simpeg->riwayatPangkat($id) ?? null;
        $riwayatJabatan = $this->simpeg->riwayatJabatan($id) ?? null;
        $riwayatPMK     = $this->simpeg->riwayatPMK($id) ?? null;

        $pasangan = $data['data-pasangan'] ?? [];
        $anak     = $data['data-anak'] ?? [];


        $lastPangkat = collect($riwayatPangkat)
            ->sortByDesc('tmt_pangkat')
            ->first();

        $lastPMK = collect($riwayatPMK)
            ->sortByDesc('tmt')
            ->first();

        $lastAnak = collect($anak)
            ->sortByDesc('tgl_lahir')
            ->first();

        $lastPasangan = collect($pasangan)
            ->sortByDesc('tgl_nikah')
            ->first();

        //cek keberadaan anak dengan usia<25 tahun
        $anakDiBawah25 = 0;
        $lastLog = '';
        if ($pengajuan) {
            $anak = $this->simpeg->riwayatKeluarga($pengajuan->pegawai_id)['data-anak'] ?? [];
            $anakDiBawah25 = collect($anak)->filter(function ($a) {
                $usia = Carbon::parse($a['tgl_lahir'])->age;
                return $usia < 25;
            })->count();

            //Cek catatan workflow terakhir        
            $lastLog = $pengajuan->workflowLogs()->latest()->first();
        }

        // sesuaikan dengan id persyaratan layanan
        $filePengajuan = [
            '1' => route('pensiun.preview.dpcp', session('pegawai')['id_pegawai']) ?? null,
            '2'  => $dataCPNS['file_sk_cpns'] ?? null,
            '3'  => $lastPangkat['file_sk_pangkat'] ?? null,
            '4'  => $lastPMK['file_sk'] ?? null,
            '7'  => $anakDiBawah25 > 0 ? $lastAnak['akta_lahir'] ?? null : null,
            '21'  => $lastPasangan['kartu_keluarga'] ?? null,
        ];
        // $dpcp = Dpcp::findOrFail($id);
        /*
        |--------------------------------------------------------------------------
        |  RETURN VIEW + KIRIM $pengajuan
        |--------------------------------------------------------------------------
        */
        $baseUrl = config('app.url');

        // =========================
        // 🔥 HITUNG TMT PENSIUN
        // =========================
        $tmt_pensiun = '';
        $tmt_bup = $pengajuan ? $this->simpeg->lolosUmurPensiun($pengajuan->pegawai_id)['tmt_pensiun'] : '';
        //Tentukan TMT Pensiun
        #1. Jika BUP dari tgl_lahir+usia_pensiun->next bulan
        if ($pengajuan?->layanan_id == 1) {
            $tmt_pensiun = $tmt_bup;
        } elseif ($pengajuan?->layanan_id == 2 || $pengajuan?->layanan_id == 3) {
            $tmt_pensiun = $dpcp?->tmt_pensiun ?? ''; //input pegawai sendiri
        } elseif ($pengajuan?->layanan_id == 4) { //4 janda duda->berdasarkan tanggal meninggal
            $tmt_pensiun = $dpcp?->tmt_pensiun ?? ''; //khusus ini, tanggal meninggal +next month
        }

        $tmt_pensiun = $tmt_pensiun ? \Carbon\Carbon::parse($tmt_pensiun)->format('Y-m-d') : null;

        //dd($pengajuan->current_step);
        return view('pages.layanan.pensiun.pengajuan-pensiun', compact(
            'pengajuan',
            'eligibility',
            'filePribadi',
            'dataPNS',
            'filePNS',
            'dataCPNS',
            'riwayatPangkat',
            'dataPribadi',
            'riwayatJabatan',
            'riwayatPMK',
            'jenisPensiun',
            'pasangan',
            'anak',
            'filePengajuan',
            'dpcp',
            'currentStep',
            'pengajuanId',
            'selectedJenis',
            'status_akhir',
            'maxStep',
            'alamatPensiun',
            'nama_ttd',
            'signature',
            'lastLog',
            'baseUrl',
            'stepUser',
            'tmt_pensiun'
        ));
    }

    public function detail($id)
    {
        $pengajuan = PengajuanLayanan::with([
            'layanan',
            'dokumen',
            'workflowLogs',
            'workflowSteps'
        ])
            ->findOrFail($id);

        $steps = WorkflowStep::where('workflow_step.id_layanan', 1)
            ->leftJoin('workflow_log as b', function ($join) use ($id) {
                $join->on('workflow_step.id_step', '=', 'b.id_step')
                    ->where('b.id_pengajuan', '=', $id);
            })
            ->leftJoin('pengajuan_layanan as c', 'c.id', '=', 'b.id_pengajuan')
            ->where('b.tanggal_proses', '!=', null)
            ->orderByRaw('b.tanggal_proses ASC')
            ->select('workflow_step.*', 'b.tanggal_proses', 'b.aksi', 'b.catatan', 'b.id_pengajuan')
            ->union(
                WorkflowStep::where('workflow_step.id_layanan', 1)
                    ->leftJoin('pengajuan_layanan as c', function ($join) use ($id) {
                        $join->on('workflow_step.id_step', '=', 'c.current_step')
                            ->where('c.id', '=', $id);
                    })
                    ->where(function ($query) use ($pengajuan) {
                        $step_now = $pengajuan->current_step;
                        $status_now = $pengajuan->status_akhir;
                        if ($step_now == 5 && $status_now == 'selesai') {
                            $query->where('workflow_step.urutan_step', '>=', $step_now + 1);
                        } else {
                            $query->where('workflow_step.urutan_step', '=', $step_now);
                        }
                    })
                    ->orderByRaw('workflow_step.id_step ASC')
                    ->selectRaw('workflow_step.*, NULL as tanggal_proses, NULL as aksi, NULL as catatan, NULL as pengajuan_id')
            )
            ->orderByRaw('tanggal_proses IS NULL, tanggal_proses ASC')
            ->get();

        $step_now = $pengajuan->current_step;

        return view('pages.layanan.pensiun.detail', compact('pengajuan', 'steps', 'step_now'));
    }

    public function cekDataPenunjang($doc_id, $pegawai_id)
    { // memastikan dokumen optional namun memiliki data harus tetap diupload

        if ($doc_id == 4) { // cek data PMK
            $riwayatPMK     = $this->simpeg->riwayatPMK($pegawai_id) ?? null;
            $lastPMK = collect($riwayatPMK)
                ->sortByDesc('tmt')
                ->first();

            return $lastPMK ? true : false;
        } elseif ($doc_id == 7) { // cek data anak
            $anakDiBawah25 = 0;
            //cek keberadaan anak dengan usia<25 tahun
            $anak = $this->simpeg->riwayatKeluarga($pegawai_id)['data-anak'] ?? [];
            $anakDiBawah25 = collect($anak)->filter(function ($a) {
                $usia = Carbon::parse($a['tgl_lahir'])->age;
                return $usia < 25;
            })->count();

            return $anakDiBawah25 > 0 ? true : false;
        } else {
            return false;
        }
    }

    public function getPersyaratan(Request $request)
    {
        $pengajuanId = $request->pengajuan_id;

        $pengajuan = PengajuanLayanan::findOrFail($pengajuanId);

        // ambil semua dokumen yang sudah diupload
        $dokumen = DokumenPengajuan::where('pengajuan_layanan_id', $pengajuanId)
            ->get()
            ->keyBy('persyaratan_layanan_id');



        $persyaratan = PersyaratanLayanan::whereRaw("FIND_IN_SET(?, layanan_id)", $pengajuan->layanan_id)
            ->where("is_active", 1)
            ->where("tipe", "Dokumen")
            ->where("penyedia", "Pegawai")
            ->get()
            ->map(function ($p) use ($dokumen, $pengajuan) {

                $doc = $dokumen->get($p->id);

                return [
                    'id' => $p->id,
                    'label' => $p->nama_persyaratan,
                    'wajib' => $p->wajib,
                    'ada_data' => $this->cekDataPenunjang($p->id, $pengajuan->pegawai_id),
                    'sumber' => $p->sumber,
                    'uploaded' => $doc ? true : false,
                    // 'file_url' => $doc->file_url ?? null,
                    'file_url' => $doc ? route('dokumen.preview', $doc->id) : null,
                    'dokumen_id' => $doc->id ?? null
                ];
            });

        //dd($persyaratan);
        return response()->json([
            'success' => true,
            'data' => $persyaratan
        ]);
    }


    public function setJenis(Request $request)
    {
        $pegawai = session('pegawai');
        $idPegawai = $pegawai['id_pegawai'];

        $pengajuan = PengajuanLayanan::where('pegawai_id', $idPegawai);

        if ($pengajuan->count() > 0) {
            $pengajuan = $pengajuan->first();
            $pengajuan->update([
                'id_pangkat' => session('pegawai')['id_pangkat'],
                'id_jabatan' => session('pegawai')['id_jabatan'],
                'id_skpd' => session('pegawai')['id_satuan_organisasi'],
                'id_jenis_layanan' => '1',
                'layanan_id' => $request->jenis_pensiun,
                'tanggal_pengajuan' => now(),
                'status_akhir' => 'draft',
                'current_step' => 1,
                'form_step' => 2
            ]);
        } else {
            $pengajuan = PengajuanLayanan::create([
                'pegawai_id' => $idPegawai,
                'id_pangkat' => session('pegawai')['id_pangkat'],
                'id_jabatan' => session('pegawai')['id_jabatan'],
                'id_skpd' => session('pegawai')['id_satuan_organisasi'],
                'id_jenis_layanan' => '1',
                'layanan_id' => $request->jenis_pensiun,
                'tanggal_pengajuan' => now(),
                'status_akhir' => 'draft',
                'current_step' => 1,
                'form_step' => 2
            ]);
        }

        return response()->json([
            'success' => true,
            'pengajuan_id' => $pengajuan->id
        ]);
    }

    public function riwayat()
    {
        $pegawai = session('pegawai');
        $idPegawai = $pegawai['id_pegawai'];

        $data = PengajuanLayanan::pensiun()
            ->where('pegawai_id', $idPegawai)
            ->latest()
            ->get();

        return view('pages.layanan.pensiun.riwayat', compact('data'));
    }



    public function uploadDokumen(Request $request)
    {
        try {

            $persyaratanId = $request->persyaratan_id;
            if ($persyaratanId == 20) {
                $mimes = 'File harus dalam format jpg, jpeg, atau png';
                $format = 'jpg,jpeg,png';
            } else {
                $mimes = 'File harus dalam format pdf';
                $format = 'pdf';
            }

            $request->validate([
                'file' => 'required|file|mimes:' . $format . '|max:2048',
                'persyaratan_id' => 'required|integer',
                'pengajuan_id' => 'required|integer'
            ], [
                'file.required' => 'File wajib diupload ',
                'file.mimes' => $mimes,
                // 'file.mimes' => 'Format harus PDF/JPG/PNG ',
                'file.max' => 'Ukuran maksimal 2MB '
            ]);

            $pengajuanId = $request->pengajuan_id;
            $persyaratanId = $request->persyaratan_id;
            $nip = str_replace(' ', '', session('pegawai')['nip']);

            $persyaratan = PersyaratanLayanan::where("id", $persyaratanId)->first();

            if (!$persyaratan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Persyaratan tidak ditemukan ❌'
                ], 200); // ✅ ubah jadi 200
            }

            $file = $request->file('file');

            $filename = 'id_syarat_layanan-' . $persyaratanId . '-' . Str::slug($persyaratan->nama_persyaratan, '_') . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('pensiun/pegawai/' . $nip, $filename);

            $dokumen = DokumenPengajuan::updateOrCreate(
                [
                    'pengajuan_layanan_id' => $pengajuanId,
                    'persyaratan_layanan_id' => $persyaratanId
                ],
                [
                    'file_path' => $path,
                    'status_validasi' => 'pending'
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload ✅',
                'file_url' => route('dokumen.preview', $dokumen->id),
                'path' => $path
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 200); // ✅ ubah jadi 200

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server ❌',
                'debug' => $e->getMessage() // optional (aktifin kalau mau debug)
            ], 200); // ✅ ubah jadi 200
        }
    }


    public function viewDokumen(Request $request)
    {
        $path = $request->path;

        if (!Storage::exists($path)) {
            abort(404);
        }

        $user = auth()->user();

        // contoh rule
        if (!$user->is_verifikator && !$user->is_owner) {
            abort(403);
        }

        return response()->file(
            storage_path('app/' . $path)
        );
    }

    public function previewDpcp(Request $request, $id)
    {
        $useApi = $request->use_api ?? 0;

        $dpcp = Dpcp::where('pegawai_id', $id)->first();
        $pengajuan = PengajuanLayanan::pensiun()->where('pegawai_id', $id)->first();
        $layanan_id = $pengajuan->layanan_id;

        // ✅ ambil dari API
        $dataPribadi     = $this->simpeg->dataPribadi($id);
        $alamat          =  $dpcp->alamat_sesudah_pensiun ?? $dataPribadi['data']['alamat'] . ' Kel. ' . $dataPribadi['data']['kelurahan'] . ' Kec. ' . $dataPribadi['data']['kecamatan'] . ' Kab./Kota ' . $dataPribadi['data']['kotakabupaten'] . ' Prov. ' . $dataPribadi['data']['provinsi'];
        $riwayatKeluarga = $this->simpeg->riwayatKeluarga($id);
        $dataCPNS = $this->simpeg->dataCPNS($id)['data-cpns'][0]
            ?? $this->simpeg->dataCPNS($id)['data-cpns']
            ?? null;

        $dataPNS = $this->simpeg->dataPNS($id)['data-pns'] ?? null;

        if ($dpcp && !$useApi) {
            $tte = 0; // dari tandatangan elektronik   
            $nama_ttd = $dpcp->nama_ttd ?? '';
            $cek_sarana = false;

            $data_dpcp = [
                'pegawai_id' => $id,
                'pengajuan_layanan_id' => $pengajuan->id,
                'data_pribadi'       => json_encode($dataPribadi),
                'riwayat_keluarga'   => json_encode($riwayatKeluarga),
                'data_cpns'          => json_encode($dataCPNS),
                'data_pns'           => json_encode($dataPNS),
                'status_pengajuan'   => 'locked' // 🔥 langsung kunci
            ];

            $dpcp->update($data_dpcp);
        } else {
            $alamat = $request->al ?? '';
            $tte = 1; // dari tandatangan basah

            $cek_sarana = $request->ch ?? false;
            $nama_ttd = $request->ttd ?? '';

            //isi Dpcp jika kosong atau perlu update        
            $data_dpcp = [
                'pegawai_id' => $id,
                'pengajuan_layanan_id' => $pengajuan->id,
                'nama_ttd'           => $nama_ttd,
                'data_pribadi'       => json_encode($dataPribadi),
                'riwayat_keluarga'   => json_encode($riwayatKeluarga),
                'data_cpns'          => json_encode($dataCPNS),
                'data_pns'           => json_encode($dataPNS),
                'tmt_pensiun'        => $request->tmt ?? NULL,
                'status_pengajuan'   => 'locked' // 🔥 langsung kunci
            ];

            if ($dpcp) {
                $dpcp->update($data_dpcp);
            } else {
                Dpcp::create($data_dpcp);
            }
        }






        // =========================
        // 🔥 HITUNGAN (TETAP SAMA)
        // =========================
        $bup = $this->simpeg->lolosUmurPensiun($id)['BUP'];
        $tgl_lahir = session('pegawai')['tanggal_lahir'];

        //Tampilkan Pasfoto
        $pasfoto = '';
        $pasfotoUrl = '';
        if ($pengajuan && $pengajuan->layanan_id == 4) {
            $dokumen = DokumenPengajuan::where('pengajuan_layanan_id', $pengajuan->id)
                ->where('persyaratan_layanan_id', 20)
                ->first();
            $pasfoto = $dokumen->file_path ?? '';
        } else {
            $pasfoto = ''; //$dataPribadi['file_upload'][0]['pasfoto'] ?? '';
        }

        if (!empty($pasfoto)) {
            $pasfotoUrl = str_starts_with($pasfoto, 'bkpsdm') ? 'https://' . $pasfoto :  storage_path('app/private/' . $pasfoto);
        }

        $today = \Carbon\Carbon::now();
        $tmt = $request->tmt ?? '';

        $tmtPensiun = !empty($dpcp->tmt_pensiun) ? $dpcp->tmt_pensiun : (!empty($request->tmt) ? $request->tmt : $today);
        if ($pengajuan?->layanan_id == 1) {
            $tmt = $this->simpeg->lolosUmurPensiun($pengajuan?->pegawai_id)['tmt_pensiun'];
            $tmtPensiun = \Carbon\Carbon::parse($tmt)->format('d-m-Y');
        } elseif ($pengajuan?->layanan_id >= 2) {
            if ($tmt) {
                $tanggal = \Carbon\Carbon::parse($tmt);
            } else {
                $tanggal = \Carbon\Carbon::parse($tmtPensiun);
            }
            $tmtPensiun = $tanggal->copy()
                ->addMonth()
                ->startOfMonth()->format('d-m-Y');
        }

        $tmtCpns = !empty($dataCPNS['tmt_cpns'] ?? null)
            ? \Carbon\Carbon::parse($dataCPNS['tmt_cpns'])
            : null;

        $tmtPns = !empty($dataPNS['TMTPNS'] ?? null)
            ? \Carbon\Carbon::parse($dataPNS['TMTPNS'])
            : null;

        //$masaKerjaPensiun = $tmtCpns->diff($today);
        $masaKerjaPensiun = $tmtCpns->diff($tmtPensiun);
        $masaKerjaPns = $tmtPns
            ? $tmtPns->diff($today)
            : (object)[
                'y' => 0,
                'm' => 0,
                'd' => 0
            ];

        $masaKerjaPmk = $this->simpeg->riwayatPMK($id);

        $gajiTerakhir = RefGaji::where('id_gol', $dataPribadi['data']['id_pangkat_bkn'])
            ->where('masa_kerja', '<=', $dataPribadi['data']['mk_tahun'])
            ->orderBy('masa_kerja', 'desc')
            ->first();

        $penandatangan = '(' . nama_gelar($dataPribadi['data']['nama_pegawai'], $dataPribadi['data']['gelar_depan'], $dataPribadi['data']['gelar_belakang']) . ')';
        $nip = 'NIP ' . str_replace(' ', '', $dataPribadi['data']['nip']);

        if ($layanan_id == 4) {
            $penandatangan = '(' . $nama_ttd . ')';
            $nip = '';
        }

        if ($cek_sarana == 'true') {
            $penandatangan = '';
            $nip = '';
            //hapus nama dan nip
        }

        //dd($dataPribadi);
        // =========================
        // 🔥 RENDER PDF
        // =========================
        $pdf = Pdf::loadView('pdf.dpcp', compact(
            'dataPribadi',
            'riwayatKeluarga',
            'pasfotoUrl',
            'dataCPNS',
            'dataPNS',
            'masaKerjaPensiun',
            'masaKerjaPns',
            'masaKerjaPmk',
            'gajiTerakhir',
            'dpcp',
            'tmtPensiun',
            'bup',
            'alamat',
            //'nama_ttd',
            //'cek_sarana',
            //'layanan_id',
            'penandatangan',
            'cek_sarana',
            'nip',
            'tte'
        ))->setPaper('A4', 'landscape');

        //$pdf->getDomPDF()->set_option('isRemoteEnabled', true);
        $pdf->getDomPDF()->getOptions()->setIsRemoteEnabled(true);

        return $pdf->stream('dpcp.pdf');
    }



    public function downloadDpcp($id)
    {
        $pengajuan = PengajuanLayanan::findOrFail($id);

        $data =  $this->simpeg->getDpcpData($pengajuan);

        $pdf = Pdf::loadView(
            'pdf.dpcp',
            $data
        );

        //$pdf->getDomPDF()->set_option('isRemoteEnabled', true);
        $pdf->getDomPDF()->getOptions()->setIsRemoteEnabled(true);

        return $pdf->download('DPCP_' . $pengajuan->nip . '.pdf');
    }



    // protected function downloadRemoteImage(string $url): ?string
    // {
    //     if (empty($url)) {
    //         return null;
    //     }

    //     $normalizedUrl = 'https://' . ltrim($url, '/');

    //     $response = Http::withHeaders([
    //         'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
    //         'Referer' => 'https://bkpsdm.medan.go.id/',
    //     ])
    //         ->timeout(20)
    //         ->withOptions(['http_errors' => false])
    //         ->get($normalizedUrl);

    //     if ($response->successful()) {
    //         return (string) $response->body();
    //     }

    //     $context = stream_context_create([
    //         'http' => [
    //             'method' => 'GET',
    //             'header' => "User-Agent: Mozilla/5.0\r\nReferer: https://bkpsdm.medan.go.id/\r\n",
    //             'ignore_errors' => true,
    //             'timeout' => 20,
    //         ],
    //         'ssl' => [
    //             'verify_peer' => false,
    //             'verify_peer_name' => false,
    //         ],
    //     ]);

    //     $body = @file_get_contents($normalizedUrl, false, $context);

    //     return $body === false ? null : $body;
    // }

    public function saveSignature(Request $request)
    {
        $id = session('pegawai')['id_pegawai'];
        $pengajuan = PengajuanLayanan::where('pegawai_id', $id)->first();

        // =========================
        // 🔥 AMBIL DATA API (SNAPSHOT)
        // =========================
        $dataPribadi     = $this->simpeg->dataPribadi($id);
        $riwayatKeluarga = $this->simpeg->riwayatKeluarga($id);

        //dd($request->nama_signature);
        //ambil pasfoto dan simpan
        $filePribadi = $dataPribadi['file_upload'][0];
        // $pasfoto = $filePribadi['pasfoto'];
        // $imagepasfoto = $this->downloadRemoteImage($pasfoto);

        // if ($imagepasfoto === null) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Gagal mengambil foto profil dari sumber SIMPEG. Coba lagi nanti.'
        //     ], 422);
        // }

        // $targetDir = public_path('storage/pasfoto');
        // if (!is_dir($targetDir)) {
        //     mkdir($targetDir, 0755, true);
        // }

        // file_put_contents($targetDir . '/photo.jpg', $imagepasfoto);

        $dataCPNS = $this->simpeg->dataCPNS($id)['data-cpns'][0]
            ?? $this->simpeg->dataCPNS($id)['data-cpns']
            ?? null;

        $dataPNS = $this->simpeg->dataPNS($id)['data-pns'] ?? null;

        // =========================
        // 🔥 HANDLE SIGNATURE
        // =========================
        $signature = $request->signature;

        $image = str_replace('data:image/png;base64,', '', $signature);
        $image = str_replace(' ', '+', $image);

        $fileName = 'ttd_' . time() . '.png';

        \Storage::disk('public')->put(
            'ttd/' . $fileName,
            base64_decode($image)
        );

        // =========================
        // 🔥 HITUNG TMT PENSIUN
        // =========================
        $bup = $this->simpeg->lolosUmurPensiun($id)['BUP'];
        $tgl_lahir = session('pegawai')['tanggal_lahir'];

        if ($pengajuan->layanan_id == 1) {
            $tmt = \Carbon\Carbon::parse($tgl_lahir)
                ->addYears($bup)
                ->addMonth()
                ->startOfMonth();
        } else {
            $tmt = $request->tmt_pensiun;
        }

        // =========================
        // 🔥 SAVE KE DB (SNAPSHOT)
        // =========================

        //memastikan jika ada databaru update terpakai
        $dpcp_lama = Dpcp::where('pegawai_id', $id);
        if ($dpcp_lama->count() > 0) {
            $dpcp_lama->first();
            $dpcp_lama->delete();
        }

        Dpcp::create(
            [
                'pegawai_id' => $id,
                'pengajuan_layanan_id' => $request->id_pengajuan,
                'nama_ttd'           => $request->nama_signature,
                'data_pribadi'       => json_encode($dataPribadi),
                'riwayat_keluarga'   => json_encode($riwayatKeluarga),
                'data_cpns'          => json_encode($dataCPNS),
                'data_pns'           => json_encode($dataPNS),

                'tmt_pensiun'        => $tmt,

                'alamat_sesudah_pensiun' => $request->alamat_sesudah_pensiun,

                'signature_base64'   => $signature,
                'signature_file'     => $fileName,
                'signed_at'          => now(),

                'status_pengajuan'   => 'locked' // 🔥 langsung kunci
            ]
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function updateStep(Request $request)
    {
        $pengajuan = PengajuanLayanan::where('id', $request->id)->first();

        if (!$pengajuan) {
            return response()->json(['status' => false]);
        }

        $pengajuan->updateStep($request->step);

        return response()->json([
            'status' => true,
            'data' => $pengajuan
        ]);
    }

    private function resolveNextStep($pengajuan)
    {
        $lastLog = WorkflowLog::where('id_pengajuan', $pengajuan->id)
            ->latest('id_log')
            ->first();

        if ($lastLog && $lastLog->aksi == 'revisi') {
            $step = WorkflowStep::find($lastLog->id_step);
            return $step ? $step->urutan_step : $pengajuan->current_step;
        }

        return $pengajuan->current_step + 1;
    }

    public function submit(Request $request)
    {
        $id = $request->id;

        $pengajuan = PengajuanLayanan::findOrFail($id);

        $lastLog = WorkflowLog::where('id_pengajuan', $pengajuan->id)
            ->latest('id_log')
            ->first();

        if ($lastLog?->aksi == 'revisi') {
            $workflowStep = WorkflowStep::where('urutan_step', $pengajuan->current_step)
                ->where("id_layanan", $pengajuan->id_jenis_layanan)
                ->first();
        } else {
            $workflowStep = WorkflowStep::where('urutan_step', $pengajuan->current_step + 1)
                ->where("id_layanan", $pengajuan->id_jenis_layanan)
                ->first();
        }

        // ❗ validasi penting
        if (!$workflowStep) {
            throw new \Exception('Workflow step tidak ditemukan');
        }

        // 🔥 AMBIL DATA API (sekali saja)
        $dataPribadi     = $this->simpeg->dataPribadi($pengajuan->pegawai_id);
        $riwayatKeluarga = $this->simpeg->riwayatKeluarga($pengajuan->pegawai_id);
        $riwayatPangkat  = $this->simpeg->riwayatPangkat($pengajuan->pegawai_id);
        $riwayatPMK      = $this->simpeg->riwayatPMK($pengajuan->pegawai_id);

        $cpnsResponse = $this->simpeg->dataCPNS($pengajuan->pegawai_id);
        $pnsResponse  = $this->simpeg->dataPNS($pengajuan->pegawai_id);

        $dataCPNS = $cpnsResponse['data-cpns'][0]
            ?? $cpnsResponse['data-cpns']
            ?? null;

        $dataPNS = $pnsResponse['data-pns'] ?? null;

        $maxRetry = 5;
        $success = false;

        for ($i = 0; $i < $maxRetry; $i++) {

            try {

                DB::beginTransaction();

                // 🔥 reload biar tidak stale
                $pengajuan->refresh();

                // 🔥 generate nomor tiket
                if (!$pengajuan->nomor_tiket) {
                    $pengajuan->nomor_tiket = PengajuanLayanan::generateNomorTiket(
                        $pengajuan->layanan_id
                    );
                    $pengajuan->tanggal_pengajuan = now();
                }

                // update status & step
                $pengajuan->status_akhir = 'diajukan';
                $pengajuan->current_step = 2; //$this->resolveNextStep($pengajuan); tetap ke step 2 dulu agar diverifikasi ulang oleh OPD
                $pengajuan->form_step = 6;
                // snapshot data
                $pengajuan->data_pribadi     = json_encode($dataPribadi);
                $pengajuan->riwayat_keluarga = json_encode($riwayatKeluarga);
                $pengajuan->data_cpns        = json_encode($dataCPNS);
                $pengajuan->data_pns         = json_encode($dataPNS);
                $pengajuan->riwayat_pangkat  = json_encode($riwayatPangkat);
                $pengajuan->riwayat_PMK      = json_encode($riwayatPMK);

                // 🔥 DEBUG sebelum save
                logger('BEFORE SAVE', [
                    'id' => $pengajuan->id,
                    'nomor_tiket' => $pengajuan->nomor_tiket,
                ]);

                $pengajuan->save();

                // 🔥 pastikan benar-benar ke-save
                if (!$pengajuan->wasChanged()) {
                    logger('WARNING: tidak ada perubahan tersimpan', [
                        'id' => $pengajuan->id
                    ]);
                }

                DB::commit();
                $success = true;

                break;
            } catch (\Throwable $e) {

                DB::rollBack();

                logger('ERROR DETAIL SUBMIT', [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'line' => $e->getLine(),
                ]);

                if ($e instanceof \Illuminate\Database\QueryException) {

                    logger('SQL ERROR', [
                        'sql' => $e->getSql(),
                        'bindings' => $e->getBindings(),
                    ]);

                    if ($e->getCode() == 23000) {
                        $pengajuan->nomor_tiket = null;
                        continue;
                    }
                }

                throw $e;
            }
        }

        if (!$success) {
            throw new \Exception('Gagal submit setelah beberapa percobaan');
        }

        // 🔥 LOG (di luar transaction biar tidak double saat retry)
        WorkflowLog::addLog(
            $pengajuan->id,
            1, //$workflowStep->id_step,
            1, //$workflowStep->urutan_step,
            $pengajuan->status_akhir,
            'USER'
        );

        return response()->json([
            'success' => true,
            'nomor_tiket' => $pengajuan->nomor_tiket,
        ]);
    }

    // public function submit(Request $request)
    // {
    //     $id = $request->id;

    //     $pengajuan = PengajuanLayanan::findOrFail($id);

    //     $lastLog = WorkflowLog::where('id_pengajuan', $pengajuan->id)
    //     ->latest('id_log')
    //     ->first();

    //     if ($lastLog?->aksi == 'revisi') {
    //         $workflowStep = WorkflowStep::where('urutan_step',$pengajuan->current_step)->where("id_layanan", $pengajuan->id_jenis_layanan)->first();
    //     }else{
    //         $workflowStep = WorkflowStep::where('urutan_step',$pengajuan->current_step+1)->where("id_layanan", $pengajuan->id_jenis_layanan)->first();
    //     }

    //     // dd($workflowStep);
    //     // $pengajuan = PengajuanLayanan::where('id', $request->id)->first();

    //     // update step
    //     // $pengajuan->updateStep(2);

    //     // generate nomor tiket jika belum ada
    //     if (!$pengajuan->nomor_tiket) {
    //         $pengajuan->nomor_tiket = PengajuanLayanan::generateNomorTiket(
    //             $pengajuan->layanan_id
    //         );
    //         $pengajuan->tanggal_pengajuan = now();
    //     }

    //     $pengajuan->status_akhir = 'diajukan';
    //     // $pengajuan->current_step = $pengajuan->current_step+1;
    //     $pengajuan->current_step = $this->resolveNextStep($pengajuan);

    //     // 🔥 AMBIL DATA API (sekali saja)
    //     $dataPribadi     = $this->simpeg->dataPribadi($pengajuan->pegawai_id);
    //     $riwayatKeluarga = $this->simpeg->riwayatKeluarga($pengajuan->pegawai_id);

    //     $riwayatPangkat = $this->simpeg->riwayatPangkat($id);
    //     // $riwayatJabatan = $this->simpeg->riwayatJabatan($id);
    //     $riwayatPMK     = $this->simpeg->riwayatPMK($id);

    //     $cpnsResponse = $this->simpeg->dataCPNS($pengajuan->pegawai_id);
    //     $pnsResponse  = $this->simpeg->dataPNS($pengajuan->pegawai_id);

    //     // amankan struktur data
    //     $dataCPNS = $cpnsResponse['data-cpns'][0] 
    //         ?? $cpnsResponse['data-cpns'] 
    //         ?? null;

    //     $dataPNS = $pnsResponse['data-pns'] ?? null;

    //     // 🔥 SIMPAN SNAPSHOT KE RECORD YANG SAMA
    //     $pengajuan->data_pribadi     = json_encode($dataPribadi);
    //     $pengajuan->riwayat_keluarga = json_encode($riwayatKeluarga);
    //     $pengajuan->data_cpns        = json_encode($dataCPNS);
    //     $pengajuan->data_pns         = json_encode($dataPNS);
    //     $pengajuan->riwayat_pangkat   = json_encode($riwayatPangkat);
    //     $pengajuan->riwayat_PMK         = json_encode($riwayatPMK);

    //     $pengajuan->save();

    //     // 🔥 LOG
    //     WorkflowLog::addLog(
    //         $pengajuan->id,
    //         $workflowStep->id_step,
    //         $workflowStep->urutan_step,
    //         $pengajuan->status_akhir,
    //         'USER'

    //     );

    //     return response()->json([
    //         'success' => true,
    //         'nomor_tiket' => $pengajuan->nomor_tiket,
    //         // 'message' => 'bermasalah
    //     ]);
    // }


}
