<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LayananCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('layanan')->insert([
            [
                'id_jenis_layanan' => 2,
                'kode_layanan' => 'CutiTHN',
                'nama_layanan' => 'Cuti Tahunan',
                'deskripsi' => null,
                'sla_hari' => 7,
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jenis_layanan' => 2,
                'kode_layanan' => 'CutiBSR',
                'nama_layanan' => 'Cuti Besar',
                'deskripsi' => null,
                'sla_hari' => 7,
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jenis_layanan' => 2,
                'kode_layanan' => 'CutiSKT',
                'nama_layanan' => 'Cuti Sakit',
                'deskripsi' => null,
                'sla_hari' => 7,
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jenis_layanan' => 2,
                'kode_layanan' => 'CutiLH',
                'nama_layanan' => 'Cuti Melahirkan',
                'deskripsi' => '',
                'sla_hari' => 7,
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jenis_layanan' => 2,
                'kode_layanan' => 'CutiAPT',
                'nama_layanan' => 'Cuti Karena Alasan Penting',
                'deskripsi' => '',
                'sla_hari' => 7,
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jenis_layanan' => 2,
                'kode_layanan' => 'CutiDLTN',
                'nama_layanan' => 'Cuti di Luar Tanggungan Negara',
                'deskripsi' => '',
                'sla_hari' => 7,
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
