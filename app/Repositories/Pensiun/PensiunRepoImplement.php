<?php

namespace App\Repositories\Pensiun;


use App\Models\PensiunModel;
use App\Repositories\Pensiun\PensiunRepo;
use DateTime;

class PensiunRepoImplement implements PensiunRepo
{
    protected $pensiunModel;
    public function __construct(PensiunModel $pensiunModel)
    {
        $this->pensiunModel = $pensiunModel;
    }
    /**
     * Get data by ID and jenis_data
     * @param $id_pegawai     *
     * @return mixed
     */

    public function syaratUmur($id_pegawai)
    {
        $syarat_umur = 58;
        $dapeg = $this->pensiunModel->syaratPensiun($id_pegawai);
        if ($dapeg->count() > 0) {
            $dapeg = $dapeg->first();
            if (strpos($dapeg->jenjang_jabfung, 'Utama') && $dapeg->id_tipe_jabatan == 3) {
                #Usia 65 untuk Jabfung Utama only   
                $syarat_umur = 65;
            } elseif ($dapeg->id_jabatan == 2098 || $dapeg->id_eselon == 4 || $dapeg->id_eselon == 3 || strpos($dapeg->jenjang_jabfung, 'Madya')) {
                #Usia 60 untuk JPT (Eselon II), Jabfung Madya, dan Guru(id_jabatan=2098)
                $syarat_umur = 60;
            } else {
                #Pelaksana,Pengawas, Administrator,Fungsional Jenjang Terampil,Pertama,dan Muda
                $syarat_umur = 58;
            }

            $birthdate = new DateTime($dapeg->tanggal_lahir);
            $now = new DateTime();

            $acc = false;
            $age = $now->diff($birthdate);
            if ($age->y >= $syarat_umur - 1) {
                $acc = true;
            }

            $tanggal = \Carbon\Carbon::parse($birthdate);
            $tmt_pensiun = $tanggal->copy()->addYears($syarat_umur)
                ->addMonth()
                ->startOfMonth()->format('Y-m-d');

            return [
                'BUP' => $syarat_umur,
                'Umur Pegawai' => $age->y . ' tahun ' . $age->m . ' bulan ' . $age->d . ' hari.',
                'lolos' => $acc == true ? 'ya' : 'tidak',
                'tmt_pensiun' => $tmt_pensiun
            ];
        } else {
            return [
                'message' => 'Pegawai tidak terdaftar'
            ];
        }
    }
}
