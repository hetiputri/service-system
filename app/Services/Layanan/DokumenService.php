<?php

namespace App\Services\Layanan;

use App\Models\DokumenPengajuan;
use App\Models\DataModel;
use App\Models\PersyaratanLayanan;
use App\Helpers\DokumenHelper;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DokumenService
{

    public function store($jenis, $request)
    {
        // 🔥 ambil persyaratan
        $persyaratanId = DokumenHelper::resolvePersyaratanId($jenis, $request);
        $persyaratan = PersyaratanLayanan::findOrFail($persyaratanId);

        // 🔥 ambil data dari service existing
        $data = match ($jenis) {
            'disiplin' => app(\App\Services\Layanan\SuratDisiplinService::class)
                ->getData($request->user_id, $request),

            'pidana' => app(\App\Services\Layanan\SuratPidanaService::class)
                ->getData($request->user_id, $request),

            default => abort(404, 'Jenis tidak dikenali')
        };

        // 🔥 ambil nip dari data (lebih konsisten)
        // dd($data);
        $nip = str_replace(' ', '', $data['nip'] ?? 'unknown');

        // =========================
        // GENERATE PDF
        // =========================
        if ($request->role == 'AdminBkdPensiun') {
            $role = 'bkd';
        } else if ($request->role == 'AdminOpdPensiun') {
            $role = 'opd';
        }

        // $view = "pdf.$jenis.'-'.$role";
        $view = "pdf.$jenis-$role";
        $pdf = Pdf::loadView($view, $data)->setPaper('A4', 'portrait');

        // =========================
        // SIMPAN FILE
        // =========================
        $filename = 'id_syarat_layanan-' . $persyaratanId . '-' .
            Str::slug($persyaratan->nama_persyaratan, '_') . '.pdf';

        $path = 'pensiun/pegawai/' . $nip . '/' . $filename;

        Storage::put($path, $pdf->output());

        // =========================
        // SIMPAN DB
        // =========================
        return DokumenPengajuan::updateOrCreate(
            [
                'pengajuan_layanan_id' => $request->pengajuan_id,
                'persyaratan_layanan_id' => $persyaratanId
            ],
            [
                'data_json' => $data,
                'file_path' => $path,
                'status_validasi' => 'generated'
            ]
        );
    }
    // public function store($jenis, $request)
    // {
    //     $model = new DataModel();

    //     $user = $model->getDapri(22777)->first();
    //     // $user = $model->getDapri($request->user_id)->first();
    //     $pejabat = $model->getDapri($request->id_ttd_oleh)->first();

    //     $nip = str_replace(' ', '', $user->nip);

    //     // 🔥 mapping persyaratan
    //     $persyaratanId = DokumenHelper::resolvePersyaratanId($jenis, $request);

    //     $persyaratan = PersyaratanLayanan::find($persyaratanId);

    //     if (!$persyaratan) {
    //         abort(404, 'Persyaratan tidak ditemukan');
    //     }

    //     // =========================
    //     // BUILD DATA JSON
    //     // =========================
    //     $data = [
    //         'jenis' => $jenis,
    //         'nomor' => $request->nomor_surat,
    //         'tanggal' => now()->translatedFormat('d F Y'),

    //         'nama' => $user->nama_pegawai ?? '-',
    //         'nip' => $user->nip ?? '-',
    //         'pangkat' => ($user->pangkat ?? '-') . ' (' . ($user->gol_ruang ?? '-') . ')',
    //         'jabatan' => $user->jabatan ?? '-',
    //         'instansi' => $user->unit_kerja ?? '-',

    //         'pejabat_nama' => trim(
    //             ($pejabat->gelar_depan ?? '') . ' ' .
    //             ($pejabat->nama_pegawai ?? '') . ' ' .
    //             ($pejabat->gelar_belakang ?? '')
    //         ),
    //         'pejabat_nip' => $pejabat->nip ?? '-',
    //         'pejabat_id' => $pejabat->id ?? '-',
    //         'pejabat_pangkat' => $pejabat->pangkat ?? '-',
    //         'pejabat_jabatan' => $request->jabatan ?? $pejabat->jabatan,
    //     ];

    //     if ($jenis === 'pidana') {
    //         $data['referensi_surat'] = $request->referensi_surat ?? null;
    //     }

    //     // =========================
    //     // GENERATE PDF 🔥
    //     // =========================
    //     $view = $jenis === 'disiplin' ? 'pdf.disiplin' : 'pdf.pidana';

    //     $pdf = Pdf::loadView($view, $data)->setPaper('A4', 'portrait');

    //     // =========================
    //     // SIMPAN FILE 🔥
    //     // =========================
    //     $filename = 'id_syarat_layanan-' . $persyaratanId . '-' .
    //         Str::slug($persyaratan->nama_persyaratan, '_') . '.pdf';

    //     $path = 'pegawai/' . $nip . '/' . $filename;

    //     Storage::put($path, $pdf->output());

    //     // =========================
    //     // SIMPAN DB 🔥
    //     // =========================
    //     return DokumenPengajuan::updateOrCreate(
    //         [
    //             'pengajuan_layanan_id' => $request->pengajuan_id,
    //             'persyaratan_layanan_id' => $persyaratanId
    //         ],
    //         [
    //             'data_json' => $data,
    //             'file_path' => $path,
    //             'status_validasi' => 'generated'
    //         ]
    //     );
    // }

    public function generate($jenis, $user_id, $request)
    {
        if ($jenis === 'disiplin') {

            $data = app(\App\Services\Layanan\SuratDisiplinService::class)
                ->getData($user_id, $request);


            // $view = 'pdf.disiplin';
            $filename = 'surat-disiplin.pdf';
        } elseif ($jenis === 'pidana') {

            $data = app(\App\Services\Layanan\SuratPidanaService::class)
                ->getData($user_id, $request);

            // $view = 'pdf.pidana';
            $filename = 'surat-pidana.pdf';
        } else {
            abort(404, 'Jenis dokumen tidak dikenali');
        }
        // dd($data);

        if ($request->role == 'AdminBkdPensiun') {
            $dataOpdReq = (object) [
                'role' => 'AdminOpdPensiun',
                'pengajuan_id' => $request->pengajuan_id,
            ];
            $role = 'bkd';


            // $dataOpd = app(\App\Services\Layanan\SuratPidanaService::class)
            // ->getData('22777', $dataOpdReq)->first();
            $role2 = 'AdminOpdPensiun';
            // 🔥 ambil persyaratan id
            $persyaratanId = config("dokumen.$jenis.$role2");
            // dd($persyaratanId);
            $dataOpd = DokumenPengajuan::where('persyaratan_layanan_id', $persyaratanId)->where('pengajuan_layanan_id', $request->pengajuan_id)->first();
            // dd($dataOpd);
            $data['dataOpd'] = $dataOpd;
        } else if ($request->role == 'AdminOpdPensiun') {
            $role = 'opd';
        }
        $view = "pdf.$jenis-$role";
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, $data)
            ->setPaper('A4', 'portrait');



        return $pdf->stream($filename);
    }
}
