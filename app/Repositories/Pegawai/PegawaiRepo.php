<?php

namespace App\Repositories\Pegawai;

interface PegawaiRepo
{
    public function searchWithFilters(array $filters);

    public function findById(string $id_pegawai);
}
