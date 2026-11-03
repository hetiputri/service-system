<?php

namespace App\Repositories\Filter;

interface FilterRepository
{
    /**
     * Get data by ID and jenis_data
     *
     * @param  $id_pegawai
     * @param  $jenis_data
     * @return mixed
     */
    public function filterData(array $filters);

    public function getRef($jenis_data);
}
