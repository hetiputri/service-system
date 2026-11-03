<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = Pengguna::create([
            'id_user' => 22,
            'nama' => 'Administrator',
            'username' => 'adminsimpegmedan',
            'password' => bcrypt('adminsimpegmedan26'),
            'password_text' => 'adminsimpegmedan26',
            'id_pegawai' => '22555'
        ]);

        $admin->assignRole('admin-master');
    }
}
