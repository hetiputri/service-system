<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisLayananSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_layanan')->insert([
            [
                'nama_layanan' => 'Pensiun',
                'prefix' => 'PSN',
                'deskripsi' => '',
            ],
            [
                'nama_layanan' => 'Cuti',
                'prefix' => 'CUT',
                'deskripsi' => '',
            ],
        ]);
    }
}