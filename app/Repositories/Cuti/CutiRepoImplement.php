<?php

namespace App\Repositories\Cuti;


use App\Models\CutiModel;
use App\Models\DokumenPengajuan;
use App\Models\LayananCutiDetail;
use App\Models\PengajuanLayanan;

class CutiRepoImplement implements CutiRepo
{
    private $model;

    public function __construct(CutiModel $model)
    {
        $this->model = $model;
    }

    /**
     * Get data by ID and jenis_data
     *
     * @param  $id_skpd_peg
     * @param  $id_pegawai
     * @return mixed
     */

    public function stepUsulanCuti()
    {
        return $this->model->getStepUsulan();
    }

    public function pejabat($id_pegawai, $jenis_ttd, $jenis_cuti)
    {
        return $this->model->getAtasan($jenis_ttd, $id_pegawai, $jenis_cuti);
    }

    // public function kepalaOPD($id_skpd_peg, $id_pegawai, $jenis_cuti)
    // {
    //     return $this->model->getAtasan($id_skpd_peg, 'kaopd', $id_pegawai, $jenis_cuti);
    // }

    // public function AtasanLangsung($id_skpd_peg, $id_pegawai, $jenis_cuti)
    // {
    //     return $this->model->getAtasan($id_skpd_peg, 'langsung', $id_pegawai, $jenis_cuti);
    // }

    public function getDataPegawai($id_pegawai)
    {
        return $this->model->getPegawai($id_pegawai);
    }

    public function getDaftarPegawai($id_proses)
    {
        return $this->model->getPegawaiByProses($id_proses);
    }

    public function getCutiDetail($pengajuanId)
    {
        $cutiDetail = LayananCutiDetail::where('pengajuan_id', $pengajuanId)
            ->Join('pengajuan_layanan as b', 'b.id', '=', 'layanan_cuti_detail.pengajuan_id')
            ->Join('layanan as c', 'c.id', '=', 'b.layanan_id')
            ->select('layanan_cuti_detail.*', 'b.pegawai_id', 'b.layanan_id', 'c.nama_layanan')
            ->first();

        return $cutiDetail;
    }

    public function cekDokumen($pengajuanId)
    {
        //file upload cuti
        $doc_sp_skpd = '';
        $doc_form_cuti = '';
        $doc_pendukung = '';
        $files = DokumenPengajuan::where('pengajuan_layanan_id', $pengajuanId)->get();
        if ($files->count() > 0) {
            foreach ($files as $doc) {
                if ($doc->persyaratan_layanan_id == 17) {
                    $doc_sp_skpd = $doc->file_path; //file(storage_path('app/private/' . $doc->file_path));
                }
                if ($doc->persyaratan_layanan_id == 18) {
                    $doc_form_cuti = $doc->file_path; //file(storage_path('app/private/' . $doc->file_path));
                }
                if ($doc->persyaratan_layanan_id == 19) {
                    $doc_pendukung = $doc->file_path; //file(storage_path('app/private/' . $doc->file_path));
                }
            }
        }

        return [
            'doc_sp_skpd' => $doc_sp_skpd,
            'doc_form_cuti' => $doc_form_cuti,
            'doc_pendukung' => $doc_pendukung
        ];
    }

    public function masaKerja($id_pegawai)
    {
        return $this->model->getMasaKerja($id_pegawai);
    }

    public function hitungCuti($id_pegawai)
    {
        return $this->model->hitungCuti($id_pegawai);
    }

    public function getRiwayatBaru($id_pegawai, $id_pengajuan)
    {
        $data =  PengajuanLayanan::cuti()
            ->Join('layanan_cuti_detail as b', 'b.pengajuan_id', '=', 'pengajuan_layanan.id')
            ->leftJoin('proses_layanan_cuti_detail as c', 'c.pengajuan_id', '=', 'b.pengajuan_id')
            ->where([
                ['pengajuan_layanan.pegawai_id', '=', $id_pegawai],
                ['pengajuan_layanan.status_akhir', '!=', 'draft'],
                ['pengajuan_layanan.id', '!=', $id_pengajuan]
            ])
            ->select('b.*', 'pengajuan_layanan.layanan_id', 'c.no_sk', 'c.tgl_sk', 'c.pejabat_sk')
            ->orderBy('b.tgl_mulai', 'desc')
            ->get();

        $result = [];
        foreach ($data as $datum) {
            $result[] = [
                'jenis_cuti' => $this->model->KonversiJenisCuti($datum->layanan_id)->jenis_cuti,
                'lama' => $datum->lama_cuti,
                'dari' => $datum->tgl_mulai,
                'sampai' => $datum->tgl_selesai,
                'nomor_sk' => $datum->no_sk,
                'tanggal_sk' => $datum->tgl_sk,
                'pejabat_sk' => $datum->pejabat_sk,
            ];
        }

        return $result;
    }
}
