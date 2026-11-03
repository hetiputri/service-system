<?php

namespace App\Repositories\User;

interface UserRepository
{
    /**
     * Get data by ID and jenis_data
     * @param $id_pegawai
     * @param $jenis_data
     * @return mixed
     */

    public function getData($jenis_data, $id_pegawai);
}
