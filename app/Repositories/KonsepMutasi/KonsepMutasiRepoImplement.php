<?php

namespace App\Repositories\KonsepMutasi;

use App\Models\MutasiStruktural;

class KonsepMutasiRepoImplement implements KonsepMutasiRepo
{
    public function all()
    {
        return MutasiStruktural::all();
    }

    public function findById(string $id_konsep)
    {
        return MutasiStruktural::findOrFail($id_konsep);
    }

    public function create(array $data)
    {
        return MutasiStruktural::create($data);
    }

    public function delete(MutasiStruktural $mutasiStruktural)
    {
        $mutasiStruktural->delete();

        return true;
    }

    public function update(MutasiStruktural $mutasiStruktural, array $request)
    {
        return $mutasiStruktural->update($request);
    }

    public function deleteDetail(MutasiStruktural $mutasiStruktural)
    {
        $mutasiStruktural->mutasiStrukturalDetail()->delete();
    }
}
