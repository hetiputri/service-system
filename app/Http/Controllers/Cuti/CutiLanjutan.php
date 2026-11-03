<?php

namespace App\Http\Controllers\Cuti;

use App\Http\Controllers\Controller;
use App\Models\PengajuanLayanan;
use App\Models\WorkflowStep;

//use Illuminate\Http\Request;

class CutiLanjutan extends Controller
{
    public function index()
    {

        $pegawai = session('pegawai');
        $token = session('token');

        if (!$pegawai || !$token) {
            return redirect('/login');
        }

        $id_pegawai = $pegawai['id_pegawai'];

        $id_skpd = $pegawai['id_satuan_organisasi'];
        $riwayats = PengajuanLayanan::where('pegawai_id', $id_pegawai)
            ->Join('layanan as b', 'pengajuan_layanan.layanan_id', '=', 'b.id')
            ->leftJoin('layanan_cuti_detail as c', 'c.pengajuan_id', '=', 'pengajuan_layanan.id')
            ->where('current_step', '>=', 1)
            ->where('b.id_jenis_layanan', '=', 2)
            ->select('pengajuan_layanan.*', 'b.nama_layanan', 'c.tgl_mulai', 'c.tgl_selesai')
            ->get();

        //$riwayats = $riwayats->count() > 0 ? $riwayats->get() : null;
        //dd($riwayats);
        return view('pages.layanan.cuti.rekap-pengajuan', compact('riwayats'));
    }

    public function detailCuti(string $id)
    {
        $pengajuan = PengajuanLayanan::find($id);
        $step_now = $pengajuan->current_step;
        $status_now = $pengajuan->status_akhir;

        //Step in timeline
        //$steps = $pengajuan->workflowSteps;
        $steps = WorkflowStep::where('workflow_step.id_layanan', 2)
            ->leftJoin('workflow_log as b', function ($join) use ($id) {
                $join->on('workflow_step.id_step', '=', 'b.id_step')
                    ->where('b.id_pengajuan', '=', $id);
            })
            ->leftJoin('pengajuan_layanan as c', 'c.id', '=', 'b.id_pengajuan')
            ->leftJoin('proses_layanan_cuti_detail as d', 'd.pengajuan_id', '=', 'c.id')
            //->orderBy('workflow_step.urutan_step', 'asc')
            //->orderBy('b.tanggal_proses', 'asc')
            ->where('b.tanggal_proses', '!=', null)
            ->orderByRaw('b.tanggal_proses IS NULL, b.tanggal_proses ASC')
            ->select('workflow_step.*', 'b.tanggal_proses', 'b.aksi', 'b.catatan', 'd.pengajuan_id')
            ->union(
                WorkflowStep::where('workflow_step.id_layanan', 2)
                    ->leftJoin('pengajuan_layanan as c', function ($join) use ($id) {
                        $join->on('workflow_step.id_step', '=', 'c.current_step')
                            ->where('c.id', '=', $id);
                    })
                    ->where(function ($query) use ($step_now, $status_now) {
                        if ($step_now == 3 && $status_now == 'selesai') {
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

        //dd($steps);
        return view('pages.layanan.cuti.detail-cuti', compact('steps', 'step_now'));
    }
}
