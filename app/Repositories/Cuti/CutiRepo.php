<?php

namespace App\Repositories\Cuti;

interface CutiRepo
{
    /**
     * Get data by ID and jenis_data
     *
     * @param  $id_pegawai
     * @param  $jenis_data
     * @return mixed
     */

    public function stepUsulanCuti();
    public function pejabat($id_pegawai, $jenis_ttd, $jenis_cuti);
    //public function kepalaOPD($id_skpd_peg, $id_pegawai, $jenis_cuti);
    //public function AtasanLangsung($id_skpd_peg, $id_pegawai, $jenis_cuti);
    public function getDataPegawai($id_pegawai);

    public function getCutiDetail($pengajuanId);
    public function getDaftarPegawai($id_proses);
    public function cekDokumen($pengajuanId);
    public function masaKerja($id_pegawai);
    public function hitungCuti($id_pegawai);
    public function getRiwayatBaru($id_pegawai, $id_pengajuan);
}
