<?php

namespace App\Repositories\KonsepMutasi;

use App\Models\MutasiStruktural;

interface KonsepMutasiRepo
{
    public function all();

    public function findById(string $id_konsep);

    public function create(array $data);

    public function delete(MutasiStruktural $mutasiStruktrual);

    public function update(MutasiStruktural $mutasiStruktural, array $request);

    public function deleteDetail(MutasiStruktural $mutasiStruktural);
}
