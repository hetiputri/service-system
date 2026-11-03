<?php

namespace App\Repositories\Otentikasi;

interface AuthRepository
{
    /**
     * Get data by ID and jenis_data
     *
     * @param  $id_pegawai
     * @param  $jenis_data
     * @return mixed
     */
    public function attempt(array $login);

    public function attemptext(string $id_user);
}
