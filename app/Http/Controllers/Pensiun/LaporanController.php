<?php

namespace App\Http\Controllers\Pensiun;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\PengajuanLayanan;
use App\Models\PensiunModel;
use App\Models\WorkflowStep;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    protected $pensiunModel;
    public function __construct(PensiunModel $pensiunModel)
    {
        $this->pensiunModel = $pensiunModel;
    }
    public function index(Request $request)
    {

        $mulai_tanggal = $request->dariTanggal ?? '';
        $sampai_tanggal = $request->sampaiTanggal ?? '';
        $tmt_pensiun_from = $request->tmt_pensiun_from ?? '';
        $tmt_pensiun_to = $request->tmt_pensiun_to ?? '';
        $id_jenis_pensiun = $request->selectedAt['id'] ?? 0;
        $selectedStatus = $request->selectedStat['nama_status'] ?? '';
        $urutan_step = $request->selectedFase['urutan_step'] ?? '';

        $selected = $request->selectedAt ?? [];
        $selectedStat = $request->selectedStat ?? [];
        $selectedFase = $request->selectedFase ?? [];

        //Lihat Hasil Pensiun
        $rekap_pensiun = $this->pensiunModel->rekapPensiun(
            $mulai_tanggal,
            $sampai_tanggal,
            $tmt_pensiun_from,
            $tmt_pensiun_to,
            $id_jenis_pensiun,
            $selectedStatus,
            $urutan_step
        );

        $jenis_pensiun = Layanan::where('id_jenis_layanan', 1)
            ->orderBy('id', 'ASC')->get();

        $fase = WorkflowStep::where('id_layanan', 1)->orderBy('urutan_step', 'asc')
            ->select('nama_step', 'urutan_step')->get();

        $status = [
            [
                'status' => 'selesai',
                'nama_status' => 'selesai'
            ],
            [
                'status' => 'diterima/ dalam proses',
                'nama_status' => 'diterima'
            ]

        ];

        $title = "Rekap Pensiun";

        return view(
            'pages.layanan.pensiun.admin.laporan',
            compact(
                'rekap_pensiun',
                'jenis_pensiun',
                'status',
                'mulai_tanggal',
                'sampai_tanggal',
                'tmt_pensiun_from',
                'tmt_pensiun_to',
                'selected',
                'selectedStat',
                'selectedFase',
                'title',
                'fase'
            )
        );
    }

    public function ringkasan()
    {
        $pengajuan = PengajuanLayanan::pensiun()
            ->where('status_akhir', '!=', 'draft')
            ->get(['id', 'layanan_id', 'status_akhir', 'current_step', 'tanggal_pengajuan']);

        $steps = WorkflowStep::where('id_layanan', 1)
            ->orderBy('urutan_step')
            ->get(['nama_step', 'urutan_step']);

        $statusOrder = ['diajukan', 'Diterima', 'selesai', 'ditolak', 'Revisi'];
        $statusCount = collect($statusOrder)->mapWithKeys(function ($status) use ($pengajuan) {
            return [$status => $pengajuan->where('status_akhir', $status)->count()];
        });

        $statusCounts = $statusCount->toArray();

        $stepCounts = $steps->map(function ($step) use ($pengajuan) {
            return [
                'label' => $step->nama_step,
                'step' => $step->urutan_step,
                'total' => $pengajuan->where('current_step', $step->urutan_step)
                    ->where('status_akhir', '!=', 'selesai')->count(),
            ];
        });

        $labels = $stepCounts->pluck('label')->values();
        $totals = $stepCounts->pluck('total')->values();

        $activeCount = $pengajuan->whereNotIn('status_akhir', ['selesai', 'ditolak'])->where('current_step', '>', 1)->count();
        $completedCount = $statusCount->get('selesai', 0);
        $rejectedCount = $pengajuan->whereIn('status_akhir', ['ditolak', 'Revisi'])
            ->where('current_step', 1)->count();
        $completionRate = $pengajuan->count() > 0
            ? round(($completedCount / $pengajuan->count()) * 100)
            : 0;

        return view('pages.layanan.pensiun.supervisor.ringkasan', compact(
            'steps',
            'stepCounts',
            'statusCount',
            'statusCounts',
            'activeCount',
            'completedCount',
            'rejectedCount',
            'completionRate',
            'totals',
            'labels'
        ));
    }
}
