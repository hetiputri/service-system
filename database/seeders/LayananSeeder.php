<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('layanan')->insert([
            [
                'id_jenis_layanan' => 1,
                'kode_layanan' => 'PensiunBUP',
                'nama_layanan' => 'Pengajuan Pensiun BUP (Batas Usia Pensiun)',
                'deskripsi' => null,
                'sla_hari' => 7,
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jenis_layanan' => 1,
                'kode_layanan' => 'PensiunAPS',
                'nama_layanan' => 'Pensiun Atas Permintaan Sendiri',
                'deskripsi' => null,
                'sla_hari' => 7,
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jenis_layanan' => 1,
                'kode_layanan' => 'PensiunAPSTC',
                'nama_layanan' => 'Pensiun APS TIDAK CAKAP JASMANI',
                'deskripsi' => null,
                'sla_hari' => 7,
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jenis_layanan' => 1,
                'kode_layanan' => 'PensiunJDY',
                'nama_layanan' => 'Pensiun JANDA/DUDA/YATIM',
                'deskripsi' => 'Atas meninggalnya Suami/Istri',
                'sla_hari' => 7,
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}