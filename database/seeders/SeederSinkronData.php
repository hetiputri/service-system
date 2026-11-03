<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeederSinkronData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_sinkron_data')->insert([
            // [
            //     'id' => '1',
            //     'nama' => 'Data Pribadi',
            //     'nama_url' => 'data-pribadi',
            //     'nama_tabel' => 'tbl_pegawai,tbl_pegawai_upload',
            // ],
            // [
            //     'id' => '2',
            //     'nama' => 'Data Keluarga',
            //     'nama_url' => 'data-keluarga',
            //     'nama_tabel' => 'tbl_pasangan,tbl_anak,tbl_pegawai_upload_anak',
            // ],
            // [
            //     'id' => '3',
            //     'nama' => 'Data Pengangkatan CPNS',
            //     'nama_url' => 'data-cpns',
            //     'nama_tabel' => 'tblcpns,tbl_pegawai_upload',
            // ],
            // [
            //     'id' => '4',
            //     'nama' => 'Data Pengangkatan PNS',
            //     'nama_url' => 'data-pns',
            //     'nama_tabel' => 'tbl_pns,tbl_pegawai_upload',
            // ],
            // [
            //     'id' => '5',
            //     'nama' => 'Riwayat Pendidikan',
            //     'nama_url' => 'riwayat-pendidikan',
            //     'nama_tabel' => 'tbl_pendidikan,tbl_pegawai_upload_detail',
            // ],
            // [
            //     'id' => '6',
            //     'nama' => 'Riwayat Gaji Berkala',
            //     'nama_url' => 'riwayat-gaji',
            //     'nama_tabel' => 'tblriwayatgaji,tbl_pegawai_upload_detail',
            // ],
            // [
            //     'id' => '7',
            //     'nama' => 'Riwayat Pangkat',
            //     'nama_url' => 'riwayat-pangkat',
            //     'nama_tabel' => 'tbl_riwayat_pangkat,tbl_pegawai_upload_detail',
            // ],
            // [
            //     'id' => '8',
            //     'nama' => 'Riwayat Jabatan',
            //     'nama_url' => 'riwayat-jabatan',
            //     'nama_tabel' => 'tbl_riwayat_jabatan,tbl_pegawai_upload_detail',
            // ],
            // [
            //     'id' => '9',
            //     'nama' => 'Riwayat Penyesuaian Masa Kerja',
            //     'nama_url' => 'riwayat-pmk',
            //     'nama_tabel' => 'tbl_riwayat_pmk,tbl_pegawai_upload_detail',
            // ],
            // [
            //     'id' => '10',
            //     'nama' => 'Riwayat Diklat',
            //     'nama_url' => 'riwayat-diklat',
            //     'nama_tabel' => 'tbl_riwayat_diklat_struktural,tbl_riwayat_diklat_lainnya,tbl_pegawai_upload_detail',
            // ],
            // [
            //     'id' => '11',
            //     'nama' => 'Riwayat Penghargaan',
            //     'nama_url' => 'riwayat-penghargaan',
            //     'nama_tabel' => 'tbl_riwayat_penghargaan,tbl_pegawai_upload_detail',
            // ],
            // [
            //     'id' => '12',
            //     'nama' => 'Riwayat Penyesuaian Ijazah',
            //     'nama_url' => 'riwayat-pi',
            //     'nama_tabel' => 'tbl_riwayat_pi,tbl_pegawai_upload_detail',
            // ],
            // [
            //     'id' => '13',
            //     'nama' => 'Riwayat Cuti',
            //     'nama_url' => 'riwayat-cuti',
            //     'nama_tabel' => 'tbl_cuti_riwayat,tbl_pegawai_upload_detail',
            // ],
            // [
            //     'id' => '14',
            //     'nama' => 'Riwayat Hukuman Disiplin',
            //     'nama_url' => 'riwayat-hukdis',
            //     'nama_tabel' => 'tbl_riwayat_hukuman_disiplin,tbl_pegawai_upload_detail',
            // ],
            [
                'id' => '15',
                'nama' => 'Data Pengguna',
                'nama_url' => 'data-pengguna',
                'nama_tabel' => 'tbl_pengguna',
            ],
        ]);
    }
}
