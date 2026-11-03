<?php

namespace App\Services\Layanan;

use App\Models\DokumenPengajuan;
use App\Models\DataModel;
use App\Helpers\DokumenHelper;
use App\Models\PensiunModel;

class SuratPidanaService
{
    public function getData($user_id, $request = null)
    {
        $model = new DataModel();
        $user = $model->getDapri($user_id)->first();

        // ambil persyaratan id
        $persyaratanId = DokumenHelper::resolvePersyaratanId('pidana', $request);

        // ambil dokumen dari tabel baru
        $dokumen = DokumenPengajuan::where('pengajuan_layanan_id', $request->pengajuan_id ?? null)
            ->where('persyaratan_layanan_id', $persyaratanId)
            ->latest()
            ->first();

        $json = $dokumen?->data_json;
        // dd($request);
        // =========================
        // DATA PEGAWAI
        // =========================
        if ($json && isset($json['nip'])) {
            $pegawai = $json;
        } else {
            $pegawai = [
                'nama' => $user->nama_pegawai ?? '-',
                'nip' => $user->nip ?? '-',
                'pangkat' => ($user->pangkat ?? '-') . ' (' . ($user->gol_ruang ?? '-') . ')',
                'jabatan' => $user->jabatan ?? '-',
                'instansi' => $user->unit_kerja ?? '-',
                'instansi_induk' => $user->instansi_induk ?? '-',
            ];
        }

        // =========================
        // DATA PEJABAT
        // =========================
        if ($request && $request->id_ttd_oleh) {
            $pejabat = $model->getDapri($request->id_ttd_oleh)->first();
            // $pejabat = $model->getDapri('22777')->first();

            $pejabatData = [
                'nama' => trim(
                    ($pejabat->gelar_depan ?? '') . ' ' .
                        ($pejabat->nama_pegawai ?? '') . ' ' .
                        ($pejabat->gelar_belakang ?? '')
                ),
                'nip' => $pejabat->nip ?? '-',
                'id' => $pejabat->id_pegawai ?? '-',
                'pangkat' => $pejabat->pangkat ?? '-',
                'gol_ruang' => $pejabat->gol_ruang ?? '-',
                'jabatan' => $pejabat->jabatan ?? '-',


            ];
        } elseif ($json && isset($json['pejabat'])) {
            $pejabatData = $json['pejabat'];
        } else {
            $id_jabatan = 51583; // Kepala BKPSDM Kota Medan
            $pensiunModel = new PensiunModel();
            $data = $pensiunModel->getPegawaiByIdJabatan($id_jabatan)->first();

            $pejabatData = [
                'nama' => nama_gelar($data->nama_pegawai, $data->gelar_depan,  $data->gelar_belakang),
                'nip' => $data->nip ?? '-',
                'id' => $data->id_pegawai ?? '-',
                'pangkat' => $data->pangkat ? $data->pangkat . ' (' . $data->gol_ruang . ')' : '-',
                'jabatan' => $data->jabatan ?? '-',
                'gol_ruang' => $data->gol_ruang ?? '-',
            ];
            // $pejabatData = [
            //     'nama' => 'SUBHAN FAJRI HARAHAP, S.STP, M.A.P',
            //     'nip' => '198412222003121001',
            //     'pangkat' => 'Pembina Tk. I (IV/b)',
            //     'jabatan' => 'Kepala BKPSDM Kota Medan',
            //     'id' => '2470',
            // ];
        }

        return [
            'nomor' => $request->nomor_surat ?? $json['nomor_surat'] ?? '',
            'tanggal' => now()->translatedFormat('d F Y'),

            'pejabat_nama' => $pejabatData['nama'],
            'pejabat_id' => $pejabatData['id'] ?? '',
            'pejabat_nip' => $pejabatData['nip'],
            'pejabat_pangkat' => $pejabatData['pangkat'],
            'pejabat_golruang' => $pejabatData['gol_ruang'],
            'pejabat_jabatan' => $request->jabatan ?? $json['pejabat_jabatan'] ?? $pejabatData['jabatan'] ?? '',

            'nama' => $pegawai['nama'],
            'nip' => $pegawai['nip'],
            'pangkat' => $pegawai['pangkat'],
            'jabatan' => $pegawai['jabatan'],
            'instansi' => $pegawai['instansi'],
            'instansi_induk' => $pegawai['instansi_induk'],

            // 🔥 tambahan khusus pidana
            'referensi_surat' => $json['referensi_surat'] ?? null,
            'nomor_opd' => $request->no_opd,
            'tgl_opd' => tglindonesia($request->tgl_opd),
            'pejabat_opd' => $request->pejabat_opd,
            'sarana' => $request->sarana ?? 0, // == true ? 'tidak' : 'ada'
        ];
    }
}
