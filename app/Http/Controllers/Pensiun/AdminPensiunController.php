<?php

namespace App\Http\Controllers\Pensiun;

use App\Http\Controllers\Controller;
use App\Models\PengajuanLayanan;
use App\Models\WorkflowLog;
use App\Models\WorkflowStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\SimpegService;
use App\Models\PersyaratanLayanan;
use App\Models\DokumenPengajuan;
use App\Models\DataModel;
use App\Models\LayananPensiunModel;
use App\Models\RiwJabatanModel;
use App\Repositories\Filter\FilterRepository;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\Layanan\SuratPidanaService;
use App\Services\Layanan\SuratDisiplinService;
use App\Services\Layanan\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminPensiunController extends Controller
{
    protected $simpeg;
    protected $pidanaService;
    protected $disiplinService;
    protected $fileUploadService;
    protected $model;
    protected $filterRepository;

    public function __construct(
        SimpegService $simpeg,
        SuratPidanaService $pidanaService,
        SuratDisiplinService $disiplinService,
        FileUploadService $fileUploadService,
        DataModel $model,
        FilterRepository $filterRepository,
    ) {
        $this->simpeg = $simpeg;
        $this->pidanaService = $pidanaService;
        $this->disiplinService = $disiplinService;
        $this->fileUploadService = $fileUploadService;
        $this->model = $model;
        $this->filterRepository = $filterRepository;
    }


    public function index()
    {
        $role = request()->query('role');
        $id_pegawai = session('pegawai')['id_pegawai'];

        $step = null;

        // if (!auth()->user()->hasRole($role)) {
        //     abort(403);
        // }


        if ($role == 'AdminOpdPensiun') {
            $step = 2;
        } elseif ($role == 'AdminBkdPensiun') {
            $step = 3;
        }

        if (!$step) {
            abort(400, 'Role tidak valid');
        }

        $data = PengajuanLayanan::pensiun()->with(['layanan', 'lastLog'])
            ->where('status_akhir', '!=', 'draft')
            ->where('current_step', $step)

            // 🔥 sama dengan EXISTS (pernah ada step = $step)
            // ->whereHas('workflowLogs', function ($q) use ($step) {
            //     $q->where('urutan_step', $step);
            // })

            ->latest()
            ->paginate(25);

        //dd($data);
        // 🔥 ambil nama database dari config (biar gak hardcode)
        // $secondDb = config('database.connections.mysql_second.database');
        // // 🔥 ambil prefix hanya kalau OPD
        // $kodePrefix = null;
        // if ($role == 'AdminOpdPensiun') {
        //     $kodeSkpdUser = DB::connection('mysql_second')
        //         ->table('ref_skpd')
        //         ->where('id_skpd', Auth::user()->id_skpd)
        //         ->value('kode_skpd');

        //     $kodePrefix = substr($kodeSkpdUser, 0, 3);
        // }
        // $query = PengajuanLayanan::pensiun()
        //     // ->with([
        //     //     // 'layanan',
        //     //     // 'lastLog',
        //     //     // 'lastCatatan',
        //     //     'workflowLogs' => function ($q) {
        //     //         $q->orderByDesc('id_log');
        //     //     }
        //     // ])
        //     ->join('workflow_step as ws', function ($join) {
        //         $join->on('pengajuan_layanan.current_step', '=', 'ws.urutan_step')
        //             ->on('pengajuan_layanan.id_jenis_layanan', '=', 'ws.id_layanan');
        //     })
        //     ->join("$secondDb.tbl_pegawai as p", 'pengajuan_layanan.pegawai_id', '=', 'p.id_pegawai')
        //     ->join("$secondDb.ref_skpd as opd", 'p.id_satuan_organisasi', '=', 'opd.id_skpd');


        // if ($role == 'AdminOpdPensiun') {
        //     $query->join("$secondDb.ref_skpd as s", 'p.id_satuan_organisasi', '=', 's.id_skpd')
        //         ->where('s.kode_skpd', 'like', $kodePrefix . '%');
        // }

        // if ($role == 'AdminBkdPensiun') {
        //     $query->join("$secondDb.ref_skpd as s", \DB::RAW('substr(opd.kode_skpd, 1, 3)'), '=', 's.kode_skpd')
        //         ->leftJoin('wilayah_admin_pensiun as f', 'f.kode_skpd', '=', 's.kode_skpd')
        //         ->where('f.id_pegawai', '=', $id_pegawai);
        // }

        // $query->where('pengajuan_layanan.status_akhir', '!=', 'draft')
        //     ->select('pengajuan_layanan.*', 'ws.nama_step', 'p.nama_pegawai', 'p.nip', 'opd.skpd');
        // // ->get();

        //dd($query);
        // foreach ($query as $q) {
        //     echo ($q->nama_pegawai);
        // }
        // // ✅ FILTER PERIODE
        // if ($request->periode) {
        //     $query->whereYear('tanggal_pengajuan', substr($request->periode, 0, 4))
        //         ->whereMonth('tanggal_pengajuan', substr($request->periode, 5, 2));
        // }

        // $data = $query->latest()->get();
        //dd($data);


        // //dd($data->lastLog);
        // return response()->json([
        //     'data' => $data->map(function ($row, $i) {
        //         $lastLog = $row->lastLog;
        //         $lastCatatan = $row->lastCatatan;
        //         $current_step = $row->current_step;
        //         $detailStep = $lastLog
        //             ? WorkflowStep::where('urutan_step', $lastLog->urutan_step)->first()
        //             : null;

        //         return [
        //             'no' => $i + 1,
        //             'id' => $row->id ?? null,
        //             'nomor_tiket' => $row->nomor_tiket ?? '-',
        //             'tanggal' => $row->tanggal_pengajuan
        //                 ? Carbon::parse($row->tanggal_pengajuan)->format('d M Y')
        //                 : '-',
        //             'nama_pegawai' => $row->nama_pegawai ?? '-',
        //             'current_step' => $row->current_step ?? '-',
        //             // 'role' => $role,
        //             'nip' => $row->nip ?? '-',
        //             'opd' => $row->skpd ?? '-',
        //             'jenis' => $row->layanan->nama_layanan ?? '-',
        //             'nama_step' => $row->nama_step ?? '-', //$detailStep?->nama_step,
        //             'status' => $row->status_akhir ?? '-',
        //             'last_step' => $lastLog->urutan_step ?? '-',
        //             'last_tanggal' => $lastLog->created_at
        //                 ? Carbon::parse($lastLog->created_at)->format('d M Y H:i')
        //                 : '-',
        //             'catatan' => $lastLog->catatan ?? '', //$lastLog->aksi == 'diajukan' ? $lastCatatan->aksi . ' pegawai-' . $lastCatatan->catatan : '',
        //             'logs' => $row->workflowLogs->map(function ($log) {
        //                 $userlog = $this->model->getDapri($log->id_user_proses)->first();
        //                 return [
        //                     'urutan_step' => $log->urutan_step,
        //                     'id_user_proses' => $userlog->nama_pegawai . ' - ' . $userlog->nip,
        //                     'catatan' => $log->catatan,
        //                     'nama_steps' => $log->nama_step,
        //                     'aksi' => $log->aksi,
        //                     'role' => $log->role,
        //                     'created_at' => Carbon::parse($log->created_at)->format('d M Y H:i'),
        //                 ];
        //             }),
        //         ];
        //     })
        // ]);

        return view('pages.layanan.pensiun.admin.index', compact('role'));
    }

    public function detail($id)
    {

        $role = request()->query('role');

        $pengajuanId = $id;
        $pengajuan = PengajuanLayanan::with(['layanan', 'lastLog', 'workflowLogs'])->findOrFail($id);

        $step = workflowStep::where("urutan_step", $pengajuan->current_step)->where("id_layanan", $pengajuan->id_jenis_layanan)->first();

        $id_eselon_pegawai = 0;
        //cek jika pegawai yang akan pensiun adalah eselon 2
        if ($pengajuan->pegawai_id) {
            $id_eselon_pegawai = $this->model->getDapri($pengajuan->pegawai_id)->first()->id_eselon;
        }

        //$pejabat = $this->model->getPejabatEselon2($role, $id_eselon_pegawai);
        $pejabat = $this->simpeg->getPensiun($role, $pengajuan->pegawai_id);
        //dd($pejabat);
        $refKetJab = $this->filterRepository->getRef('keterangan_jabatan');

        if (!auth()->user()->hasRole($role)) {
            abort(403);
        }

        // ✅ ubah ke array
        $layananIds = explode(',', $pengajuan->layanan_id);
        // $role ='';

        // ✅ ambil semua persyaratan dari multiple layanan
        $persyaratan = PersyaratanLayanan::where(function ($query) use ($layananIds) {
            foreach ($layananIds as $id) {
                $query->orWhereRaw("FIND_IN_SET(?, layanan_id)", [$id]);
            }
        })
            ->orderBy('id')
            ->get();

        // CPNS
        $dataCPNS = json_decode($pengajuan->data_cpns, true);
        $fileSkCpns = $dataCPNS['file_sk_cpns'] ?? null;

        $riwayatPMK = json_decode($pengajuan->riwayat_pmk, true) ?? [];
        $lastPMK = collect($riwayatPMK)->first();
        $filePMK = $lastPMK['file_sk'] ?? null;

        $riwayatPangkat = json_decode($pengajuan->riwayat_pangkat, true);
        $lastPangkat = collect($riwayatPangkat)->first();
        $filePangkat = $lastPangkat['file_sk_pangkat'] ?? null;

        $data = $this->simpeg->riwayatKeluarga($pengajuan->pegawai_id);

        $pasangan = $data['data-pasangan'] ?? [];
        $anak     = $data['data-anak'] ?? [];

        $lastAnak = collect($anak)
            ->sortByDesc('tgl_lahir')
            ->first();

        //dd($lastAnak);

        $lastPasangan = collect($pasangan)
            ->sortByDesc('tgl_nikah')
            ->first();

        $anakDiBawah25 = 0;
        $anak = $this->simpeg->riwayatKeluarga($pengajuan->pegawai_id)['data-anak'] ?? [];
        $anakDiBawah25 = collect($anak)->filter(function ($a) {
            $usia = Carbon::parse($a['tgl_lahir'])->age;
            return $usia < 25;
        })->count();

        // ambil dokumen yg sudah diupload
        $dokumenPengajuan = DokumenPengajuan::where('pengajuan_layanan_id', $pengajuan->id)
            ->get()
            ->keyBy('persyaratan_layanan_id'); // ⬅️ penting
        // dd($filePangkat );
        $persyaratanIdDisiplin = config("dokumen.disiplin.$role");
        $persyaratanIdPidana = config("dokumen.pidana.$role");
        $dokumenDisiplin = $dokumenPengajuan[$persyaratanIdDisiplin] ?? null;
        $dokumenPidana   = $dokumenPengajuan[$persyaratanIdPidana] ?? null;
        $dataDisiplin = $dokumenDisiplin->data_json ?? [];
        $dataPidana   = $dokumenPidana->data_json ?? [];

        $dokumenList = $persyaratan->map(function ($item) use (
            $dokumenPengajuan,
            $pengajuan,
            $fileSkCpns,
            $filePangkat,
            $filePMK,
            $anakDiBawah25,
            $lastPasangan,
            $lastAnak
        ) {

            $doc = $dokumenPengajuan->get($item->id);

            // default source
            $filePengajuan = [
                '1' => route('pensiun.preview.dpcp', $pengajuan->pegawai_id) ?? null,
                '2' => $fileSkCpns,
                '3' => $filePangkat,
                '4' => $filePMK,
                '7'  => $anakDiBawah25 > 0 ? $lastAnak['akta_lahir'] : null,
                '21'  => $lastPasangan['kartu_keluarga'] ?? null,
                // misal tambahan:
                // '5' => route('pensiun.preview', ['type' => 'disiplin', 'id' => $pengajuan->pegawai_id]),
                // '6' => route('pensiun.preview', ['type' => 'pidana', 'id' => $pengajuan->pegawai_id]),
            ];

            // ✅ PRIORITAS 1: kalau ada upload → pakai itu
            if ($doc) {
                $fileUrl = route('dokumen.preview', $doc->id);
            }
            // ✅ PRIORITAS 2: kalau tidak ada → pakai default
            else {
                $fileUrl = $filePengajuan[$item->id] ?? null;
            }

            return [
                'id' => $item->id,
                'label' => $item->nama_persyaratan,
                'file_url' => $fileUrl,
                'uploaded' => $fileUrl ? true : false,
                'wajib' => $item->wajib == 1,
                'sumber' => $item->sumber,
                'penyedia' => $item->penyedia,
                'catatan' => $item->lastLog->catatan ?? '-',
            ];
        });

        $secondDb = config('database.connections.mysql_second.database');

        $pensiunDetail = LayananPensiunModel::where('pengajuan_id', $pengajuanId)
            ->leftJoin("$secondDb.ref_keterangan_jabatan as ket", 'ket.id_keterangan_jabatan', '=', 'layanan_pensiun_detail.id_keterangan_jabatan')
            ->select('layanan_pensiun_detail.*', 'ket.keterangan_jabatan');

        $dataPensiun = $pensiunDetail->count() > 0 ? $pensiunDetail->first() : '';
        //dd($dataPensiun);
        return view('pages.layanan.pensiun.admin.detail', compact('pengajuan', 'dokumenList', 'step', 'role', 'pejabat', 'dataDisiplin', 'dataPidana', 'pengajuanId', 'refKetJab', 'dataPensiun'));
    }

    public function aksi(Request $request)
    {
        $pengajuan = PengajuanLayanan::findOrFail($request->pengajuan_id);
        $role = $request->role;
        $step = workflowStep::where("urutan_step", $pengajuan->current_step)->where("id_layanan", $pengajuan->id_jenis_layanan)->first();
        $role_name = '';
        if ($role == 'AdminBkdPensiun') {
            if ($pengajuan->current_step > 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah tidak bisa proses'
                ]);
            }
            $role_name = 'BKPSDM';
        } elseif ($role == 'AdminOpdPensiun') {


            if ($pengajuan->current_step != 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah tidak bisa proses'
                ]);
            }
            $role_name = 'OPD';
        }

        // 🔥 KHUSUS BKD
        if ($role == 'AdminBkdPensiun') {

            $targetStep = (int) $request->revisi_step;

            if (!in_array($targetStep, [1, 2])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Step revisi tidak valid'
                ]);
            }
        } else {
            // 🔥 selain BKD (misal OPD) → default ke step 1
            $targetStep = 1;
        }

        $form_step = 6;
        if ($targetStep == 1) {
            $form_step = 5;
        }

        if ($request->aksi == 'revisi') {

            $pengajuan->update([
                'status_akhir' => ucfirst($request->aksi),
                'current_step' => $targetStep,
                'form_step' => $form_step,
            ]);
        } else {

            $pengajuan->update([
                'status_akhir' => $request->aksi,
                'current_step' => $targetStep,
                'form_step' => $form_step,
            ]);
        }


        // 🔥 LOG
        WorkflowLog::addLog(
            $pengajuan->id,
            $step->id_step,
            $step->urutan_step,
            strtolower($pengajuan->status_akhir),
            $role_name,
            $request->catatan,

        );

        return response()->json([
            'success' => true
        ]);
    }

    public function uploadDokumen(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:2048',
                'persyaratan_id' => 'required',
                'pengajuan_id' => 'required',
            ], [
                'file.required' => 'File wajib diupload ',
                'file.mimes' => 'Format harus PDF',
                // 'file.mimes' => 'Format harus PDF/JPG/PNG ',
                'file.max' => 'Ukuran maksimal 2MB '
            ]);
            $pengajuanId = $request->pengajuan_id;
            $persyaratanId = $request->persyaratan_id;

            $pengajuan = PengajuanLayanan::findOrFail($pengajuanId);

            $user = $this->model->getDapri($pengajuan->pegawai_id)->first();

            $nip = str_replace(' ', '', $user->nip);
            $file = $request->file('file');
            $persyaratan = PersyaratanLayanan::where("id", $persyaratanId)->first();

            if (!$persyaratan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Persyaratan tidak ditemukan ❌'
                ], 200); // ✅ ubah jadi 200
            }

            $filename = 'id_syarat_layanan-' . $persyaratanId . '-' . Str::slug($persyaratan->nama_persyaratan, '_') . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('pegawai/' . $nip, $filename);

            $dokumen = DokumenPengajuan::updateOrCreate(
                [
                    'pengajuan_layanan_id' => $request->pengajuan_id,
                    'persyaratan_layanan_id' => $request->persyaratan_id,
                ],
                [
                    'file_path' => $path,
                    'status_validasi' => 'pending'
                ]
            );

            return response()->json([
                'success' => true,
                'file_url' => route('dokumen.preview', $dokumen->id)
            ]);
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

    public function submitFinal(Request $request)
    {
        $pengajuan = PengajuanLayanan::findOrFail($request->pengajuan_id);
        $workflowStep = WorkflowStep::where('urutan_step', $pengajuan->current_step)->where("id_layanan", $pengajuan->id_jenis_layanan)->first();

        $role = $request->role;
        if ($role == 'AdminBkdPensiun') {
            $pengajuan->update([
                'status_akhir' => 'Diterima',
                'current_step' => $pengajuan->current_step + 1
            ]);
            $role_name = 'BKPSDM';
        } elseif ($role == 'AdminOpdPensiun') {
            $pengajuan->update([
                'status_akhir' => 'diajukan',
                'current_step' => $pengajuan->current_step + 1
            ]);
            $role_name = 'OPD';
        }

        // 🔥 LOG
        WorkflowLog::addLog(
            $pengajuan->id,
            $workflowStep->id_step,
            $workflowStep->urutan_step,
            $pengajuan->status_akhir,
            $role_name

        );

        return response()->json([
            'success' => true
        ]);
    }

    public function proses(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'catatan' => 'nullable',
            'file' => 'required|file|mimes:pdf|max:2048'
        ]);

        $pengajuan = PengajuanLayanan::findOrFail($request->id);

        // upload file
        $path = $request->file('file')->store(
            "layanan/{$pengajuan->id}/surat",
            'private'
        );

        // update status
        $pengajuan->update([
            'status_akhir' => 'verifikasi_bkpsdm'
        ]);

        // log
        WorkflowLog::addLog(
            $pengajuan->id,
            $workflowStep->id_step,
            $pengajuan->status_akhir,
            'OPD'
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function data(Request $request)
    {
        $role = request()->query('role');
        $id_pegawai = session('pegawai')['id_pegawai'];

        $step = null;

        if ($role == 'AdminOpdPensiun') {
            $step = 2;
        } elseif ($role == 'AdminBkdPensiun') {
            $step = 3;
        }

        if (!$step) {
            return response()->json(['data' => []]);
        }

        // 🔥 ambil nama database dari config (biar gak hardcode)
        $secondDb = config('database.connections.mysql_second.database');

        // 🔥 ambil prefix hanya kalau OPD
        $kodePrefix = null;
        if ($role == 'AdminOpdPensiun') {
            $kodeSkpdUser = DB::connection('mysql_second')
                ->table('ref_skpd')
                ->where('id_skpd', auth()->user()->id_skpd)
                ->value('kode_skpd');

            $kodePrefix = substr($kodeSkpdUser, 0, 3);
        }

        $query = PengajuanLayanan::pensiun()->with([
            'layanan',
            'lastLog',
            'lastCatatan',
            'workflowLogs' => function ($q) {
                $q->orderByDesc('id_log');
            }
        ])
            ->join('workflow_step as ws', function ($join) {
                $join->on('pengajuan_layanan.current_step', '=', 'ws.urutan_step')
                    ->on('pengajuan_layanan.id_jenis_layanan', '=', 'ws.id_layanan');
            })
            ->join("$secondDb.tbl_pegawai as p", 'pengajuan_layanan.pegawai_id', '=', 'p.id_pegawai')
            ->join("$secondDb.ref_skpd as opd", 'pengajuan_layanan.id_skpd', '=', 'opd.id_skpd');


        if ($role == 'AdminOpdPensiun') {
            $query->join("$secondDb.ref_skpd as s", 'opd.id_skpd', '=', 's.id_skpd')
                ->where('s.kode_skpd', 'like', $kodePrefix . '%');
        }

        if ($role == 'AdminBkdPensiun') {
            $query->join("$secondDb.ref_skpd as s", \DB::RAW('substr(opd.kode_skpd, 1, 3)'), '=', 's.kode_skpd')
                ->leftJoin('wilayah_admin_pensiun as f', 'f.kode_skpd', '=', 's.kode_skpd')
                ->where('f.id_pegawai', '=', $id_pegawai);
        }

        $query->where('pengajuan_layanan.status_akhir', '!=', 'draft')
            ->select('pengajuan_layanan.*', 'ws.nama_step', 'p.nama_pegawai', 'p.nip', 'opd.skpd');

        // ✅ FILTER PERIODE
        if ($request->periode) {
            $query->whereYear('tanggal_pengajuan', substr($request->periode, 0, 4))
                ->whereMonth('tanggal_pengajuan', substr($request->periode, 5, 2));
        }

        $data = $query->latest()->get();
        //dd($data->current_step);

        //dd($data->lastLog);
        return response()->json([
            'data' => $data->map(function ($row, $i) {
                $lastLog = $row->lastLog;
                $lastCatatan = $row->lastCatatan;
                $current_step = $row->current_step;
                $detailStep = $lastLog
                    ? WorkflowStep::where('urutan_step', $lastLog->urutan_step)->first()
                    : null;

                return [
                    'no' => $i + 1,
                    'id' => $row->id ?? null,
                    'nomor_tiket' => $row->nomor_tiket ?? '-',
                    'tanggal' => $row->tanggal_pengajuan
                        ? Carbon::parse($row->tanggal_pengajuan)->format('d M Y')
                        : '-',
                    'nama_pegawai' => $row->nama_pegawai ?? '-',
                    'current_step' => $row->current_step ?? '-',
                    // 'role' => $role,
                    'nip' => $row->nip ?? '-',
                    'opd' => $row->skpd ?? '-',
                    'jenis' => $row->layanan->nama_layanan ?? '-',
                    'nama_step' => $row->nama_step ?? '-', //$detailStep?->nama_step,
                    'status' => $row->status_akhir ?? '-',
                    'last_step' => $lastLog->urutan_step ?? '-',
                    'last_tanggal' => $lastLog->created_at
                        ? Carbon::parse($lastLog->created_at)->format('d M Y H:i')
                        : '-',
                    'catatan' => $lastLog->catatan ?? '', //$lastLog->aksi == 'diajukan' ? $lastCatatan->aksi . ' pegawai-' . $lastCatatan->catatan : '',
                    'logs' => $row->workflowLogs->map(function ($log) {
                        $userlog = $this->model->getDapri($log->id_user_proses)->first();
                        return [
                            'urutan_step' => $log->urutan_step,
                            'id_user_proses' => $userlog->nama_pegawai . ' - ' . $userlog->nip,
                            'catatan' => $log->catatan,
                            'nama_steps' => $log->nama_step,
                            'aksi' => $log->aksi,
                            'role' => $log->role,
                            'created_at' => Carbon::parse($log->created_at)->format('d M Y H:i'),
                        ];
                    }),
                ];
            })
        ]);
    }

    public function generateDisiplin(Request $request)
    {
        $data = $this->disiplinService->getData($request->user_id, $request);

        $pdf = Pdf::loadView('pdf.disiplin', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream('surat-disiplin.pdf');
    }

    public function GenerateDocx(Request $request)
    {
        // $data = $this->disiplinService->getData($request->user_id, $request);

        // $pdf = Pdf::loadView('pdf.disiplin', $data)
        //     ->setPaper('A4', 'portrait');

        //return $pdf->stream('surat-disiplin.pdf');

        $phpWord = new \PhpOffice\PhpWord\PhpWord();



        $section = $phpWord->addSection();



        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod

tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,

quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo

consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse

cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non

proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";



        // $section->addImage("http://itsolutionstuff.com/frontTheme/images/logo.png");

        $section->addText($description);



        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        try {

            $objWriter->save(storage_path('helloWorld.docx'));
        } catch (Exception $e) {
        }

        return response()->download(storage_path('helloWorld.docx'));
    }

    public function storeDisiplin(Request $request)
    {
        $this->disiplinService->store($request);

        return response()->json(['success' => true]);
    }

    public function generatePidana(Request $request)
    {
        $data = $this->pidanaService->getData($request->user_id, $request);

        $pdf = Pdf::loadView('pdf.pidana', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream('surat-pidana.pdf');
    }

    public function storePidana(Request $request)
    {
        $this->pidanaService->store($request);

        return response()->json(['success' => true]);
    }

    // public function storePidana(Request $request)
    // {
    //     $model = new DataModel();

    //     $user = $model->getDapri($request->user_id)->first();
    //     $pejabat = $model->getDapri($request->id_ttd_oleh)->first();

    //     $nama_pejabat = trim(
    //         ($pejabat->gelar_depan ?? '') . ' ' .
    //         ($pejabat->nama_pegawai ?? '') . ' ' .
    //         ($pejabat->gelar_belakang ?? '')
    //     );

    //     SuratPidana::updateOrCreate(
    //         [
    //             'user_id' => $request->user_id,
    //             'nomor_surat' => $request->nomor_surat,
    //         ],
    //         [
    //             'pengajuan_id' => $request->pengajuan_id,
    //             'id_ttd_oleh' => $request->id_ttd_oleh,

    //             'tanggal_surat' => now(),

    //             'pejabat_nama' => $nama_pejabat,
    //             'pejabat_nip' => $pejabat->nip,
    //             'pejabat_pangkat' => $pejabat->pangkat,
    //             'pejabat_jabatan' => $pejabat->jabatan,

    //             'nama' => $user->nama_pegawai,
    //             'nip' => $user->nip,
    //             'pangkat' => $user->pangkat,
    //             'jabatan' => $user->jabatan,
    //             'instansi' => $user->unit_kerja,

    //             'status' => 'generated'
    //         ]
    //     );

    //     return response()->json(['success' => true]);
    // }


    public function preview($type, $id)
    {
        if ($type == 'disiplin') {
            $data = $this->disiplinService->getData($id);
            $view = 'pdf.disiplin';
            $filename = 'surat-disiplin.pdf';
        } elseif ($type == 'pidana') {
            $data = $this->pidanaService->getData($id);
            $view = 'pdf.pidana';
            $filename = 'surat-pidana.pdf';
        } else {
            abort(404);
        }

        $pdf = Pdf::loadView($view, $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream($filename);
    }

    public function uploadSk(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
            'pengajuan_id' => 'required',
            'tmt_pensiun' => 'required',
            'nomor_sk' => 'required',
            'tanggal_sk' => 'required',
            'tanggal_pertek' => 'required',
            'pejabat_ttd' => 'required',
            'keterangan_jabatan' => 'required',
        ]);

        $pengajuan = PengajuanLayanan::findOrFail($request->pengajuan_id);
        $pensiun = LayananPensiunModel::where('pengajuan_id', $request->pengajuan_id);

        $add = [
            'pengajuan_id' => $request->pengajuan_id,
            'pegawai_id' => $pengajuan->pegawai_id,
            'tmt_pensiun' => $request->tmt_pensiun,
            'no_sk' => $request->nomor_sk,
            'tgl_sk' => $request->tanggal_sk,
            'tgl_pertek' => $request->tanggal_pertek,
            'pejabat_ttd' => $request->pejabat_ttd,
            'id_keterangan_jabatan' => $request->keterangan_jabatan
        ];

        if ($pensiun->count() > 0) {
            $pensiun = $pensiun->first();
            $pensiun->update($add);
        } else {
            LayananPensiunModel::create($add);
        }

        $this->fileUploadService->uploadSk(
            $pengajuan,
            $request->file('file'),
            'SK_Pensiun',
        );

        //masukkan dalam log sudah selesai
        $workflowStep = WorkflowStep::where('urutan_step', $pengajuan->current_step)->where("id_layanan", $pengajuan->id_jenis_layanan)->first();

        $role = $request->role;
        $role_name = 'BKPSDM';

        // 🔥 LOG
        WorkflowLog::addLog(
            $pengajuan->id,
            $workflowStep->id_step,
            $workflowStep->urutan_step,
            $pengajuan->status_akhir,
            $role_name
        );

        //Tambahkan Ke Riwayat jabatan Pegawai
        $pensiun = LayananPensiunModel::where('pengajuan_id', $request->pengajuan_id)->first();
        // $data = [
        //     'id_pegawai' => $pensiun->pegawai_id,
        //     'id_jabatan' => 0,
        //     'jabatan' => 'PENSIUN',
        //     'id_tipe_jabatan' => 2, // ini berdasarkan apa?
        //     'id_jab_ganda' => 0,
        //     'id_plt' => 0,
        //     'id_keterangan_jabatan' => $pensiun->id_keterangan_jabatan,
        //     'id_eselon' => 0,
        //     'id_skpd' => 0,
        //     'skpd' => 'PENSIUN',
        //     'id_unit' => 0,
        //     'sub_unit' => 'PENSIUN',
        //     'tmt' => $pensiun->tmt_pensiun,
        //     'tmt_akhir' => NULL,
        //     'gol_pppk' => '',
        //     'no_sk_jabatan' => $pensiun->no_sk,
        //     'tgl_sk_jabatan' => $pensiun->tgl_sk,
        //     'pejabat_penetapan' => $pensiun->pejabat_ttd,
        //     'no_sk_pelantikan' => '',
        //     'tgl_sk_pelantikan' => '',
        //     'pejabat_pelantik' => '',
        //     'sumpah_jabatan' => '',
        //     'img_sk_jabatan' => '',
        //     'img_sk_pelantikan' => '',
        //     'urut' => 0,
        //     'kedudukan' => '',
        //     'jenis_jabfung' => '',
        //     'jenjang_jabfung' => '',
        //     'id_jenjang' => 0,
        //     'status_supervisi' => 0,
        //     'catatan_dari_bkd' => ''
        // ];

        $data = [
            'id_user' => session('pegawai')['id_pegawai'],
            'id_pegawai' => $pensiun->pegawai_id,
            'id_keterangan_jabatan' => $pensiun->id_keterangan_jabatan,
            'tmt' => $pensiun->tmt_pensiun,
            'no_sk_jabatan' => $pensiun->no_sk,
            'tgl_sk_jabatan' => $pensiun->tgl_sk,
            'pejabat_penetapan' => $pensiun->pejabat_ttd,
        ];

        $filePath = Storage::path($pengajuan->sk_path);

        $this->simpeg->updateData($data, 'pensiun', $filePath);

        // $riwjab = RiwJabatanModel::where('id_pegawai', $pengajuan->pegawai_id)
        //     ->where('jabatan', 'like', '%pensiun%');
        // if ($riwjab->count() > 0) {
        //     $riwjab = $riwjab->first();
        //     $riwjab->update($data);
        // } else {
        //     RiwJabatanModel::create($data);
        // }

        return response()->json([
            'status' => true,
            'message' => 'SK berhasil diupload'
        ]);
    }

    public function previewSk($id)
    {
        $pengajuan = PengajuanLayanan::findOrFail($id);

        return $this->fileUploadService->previewFile(
            $pengajuan->sk_path,
            'SK_Pensiun.pdf'
        );
    }

    public function lanjutProses(Request $request)
    {
        $pengajuan = PengajuanLayanan::findorFail($request->pengajuanId);
        if ($pengajuan->current_step == 4) {

            $workflowStep = WorkflowStep::where('urutan_step', $pengajuan->current_step)->where("id_layanan", $pengajuan->id_jenis_layanan)->first();
            $role = $request->role;
            $catatan = 'Lolos Verifikasi BKN';
            if ($role == 'AdminBkdPensiun') {
                $role_name = 'BKPSDM';
                WorkflowLog::addLog(
                    $pengajuan->id,
                    $workflowStep->id_step,
                    $workflowStep->urutan_step,
                    $pengajuan->status_akhir,
                    $role_name,
                    $catatan,
                );

                $pengajuan->update([
                    'status_akhir' => 'Diterima',
                    'current_step' => $pengajuan->current_step + 1
                ]);

                return [
                    'success' => true,
                    'message' => 'Berhasil diproses Lolos Verifikasi BKN untuk lanjut ke Finalisasi',
                ];
            }
        }
    }
}
