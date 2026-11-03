<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkflowUserSeederCuti extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('workflow_user')->insert([
            [
                'id' => 1,
                'urutan' => 1,
                'label' => 'Pilih Jenis Cuti',
                'id_layanan' => 2,
                'aktif' => 1,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'urutan' => 2,
                'label' => 'Tinjau Persyaratan',
                'id_layanan' => 2,
                'aktif' => 1,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'urutan' => 3,
                'label' => 'Pengisian Formulir',
                'id_layanan' => 2,
                'aktif' => 1,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'urutan' => 4,
                'label' => 'Unduh Dokumen',
                'id_layanan' => 2,
                'aktif' => 1,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'urutan' => 5,
                'label' => 'Finalisasi',
                'id_layanan' => 2,
                'aktif' => 1,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
