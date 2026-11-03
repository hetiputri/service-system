<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefPpGajiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ref_pp_gaji')->insert([
            [
                'nama' => 'No. 30 Tahun 2015',
                'tahun_berlaku_mulai' => '2015-01-01',
                'tahun_berlaku_sampai' => '2018-12-31',

            ],
            [
                'nama' => 'No. 15 Tahun 2019',
                'tahun_berlaku_mulai' => '2019-01-01',
                'tahun_berlaku_sampai' => '2023-12-31',

            ],
            [
                'nama' => 'No. 11 Tahun 2011',
                'tahun_berlaku_mulai' => '2011-01-01',
                'tahun_berlaku_sampai' => '2011-12-31',

            ],
            [
                'nama' => 'No. 15 Tahun 2012',
                'tahun_berlaku_mulai' => '2012-01-01',
                'tahun_berlaku_sampai' => '2012-12-31',

            ],
            [
                'nama' => 'No. 22 Tahun 2013',
                'tahun_berlaku_mulai' => '2013-01-01',
                'tahun_berlaku_sampai' => '2013-12-31',

            ],
            [
                'nama' => 'No. 25 Tahun 2010',
                'tahun_berlaku_mulai' => '2010-01-01',
                'tahun_berlaku_sampai' => '2010-12-31',

            ],
            [
                'nama' => 'No. 34 Tahun 2014',
                'tahun_berlaku_mulai' => '2014-01-01',
                'tahun_berlaku_sampai' => '2014-12-31',

            ],
            [
                'nama' => 'No. 5 Tahun 2024',
                'tahun_berlaku_mulai' => '2024-01-01',
                'tahun_berlaku_sampai' => '2025-01-01',

            ],
        ]);
    }
}