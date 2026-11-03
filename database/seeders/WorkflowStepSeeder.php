<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkflowStepSeeder extends Seeder
{
    public function run(): void
    {
        // DB::table('workflow_step')->insert([

        //     [
        //         'id_layanan' => 1,
        //         'nama_step' => 'Draft',
        //         'urutan_step' => 1,
        //         'role_pemroses' => 'pegawai',
        //         'sla_step_hari' => 0,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],

        //     [
        //         'id_layanan' => 1,
        //         'nama_step' => 'Diajukan',
        //         'urutan_step' => 2,
        //         'role_pemroses' => 'pegawai',
        //         'sla_step_hari' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],

        //     [
        //         'id_layanan' => 1,
        //         'nama_step' => 'Verifikasi Operator OPD',
        //         'urutan_step' => 3,
        //         'role_pemroses' => 'operator',
        //         'sla_step_hari' => 2,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],

        //     [
        //         'id_layanan' => 1,
        //         'nama_step' => 'Pembuatan Surat Pidana dan Disiplin',
        //         'urutan_step' => 4,
        //         'role_pemroses' => 'operator',
        //         'sla_step_hari' => 2,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],

        //     [
        //         'id_layanan' => 1,
        //         'nama_step' => 'Verifikasi BKD',
        //         'urutan_step' => 5,
        //         'role_pemroses' => 'bkd',
        //         'sla_step_hari' => 3,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],

        //     [
        //         'id_layanan' => 1,
        //         'nama_step' => 'Selesai',
        //         'urutan_step' => 6,
        //         'role_pemroses' => 'system',
        //         'sla_step_hari' => 0,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],

        // ]);

        DB::table('workflow_step')->insert([

            [
                'id_layanan' => 2,
                'nama_step' => 'Pengajuan Cuti',
                'urutan_step' => 1,
                'role_pemroses' => 'pegawai',
                'sla_step_hari' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_layanan' => 2,
                'nama_step' => 'Verifikasi Berkas',
                'urutan_step' => 2,
                'role_pemroses' => 'bkd',
                'sla_step_hari' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_layanan' => 2,
                'nama_step' => 'Proses Penandatanganan SK',
                'urutan_step' => 3,
                'role_pemroses' => 'system',
                'sla_step_hari' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // [
            //     'id_layanan' => 1,
            //     'nama_step' => 'Diajukan',
            //     'urutan_step' => 2,
            //     'role_pemroses' => 'pegawai',
            //     'sla_step_hari' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],



            // [
            //     'id_layanan' => 1,
            //     'nama_step' => 'Pembuatan Surat Pidana dan Disiplin',
            //     'urutan_step' => 4,
            //     'role_pemroses' => 'operator',
            //     'sla_step_hari' => 2,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],

            // [
            //     'id_layanan' => 2,
            //     'nama_step' => 'Verifikasi BKD',
            //     'urutan_step' => 3,
            //     'role_pemroses' => 'bkd',
            //     'sla_step_hari' => 3,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],



        ]);
    }
}
