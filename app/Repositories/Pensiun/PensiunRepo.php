<?php

namespace App\Repositories\Pensiun;

interface PensiunRepo
{
    /**
     * Get data by ID and jenis_data
     * @param $id_pegawai
     * @param $jenis_data
     * @return mixed
     */

    public function syaratUmur($id_pegawai);
}
