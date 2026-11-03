<?php

namespace App\Services\Pegawai;

use App\Repositories\Pegawai\PegawaiRepo;

class PegawaiService
{
    private $pegawaiRepo;

    public function __construct(PegawaiRepo $pegawaiRepo)
    {
        $this->pegawaiRepo = $pegawaiRepo;
    }

    public function searchWithFilters(array $filters)
    {
        return $this->pegawaiRepo->searchWithFilters($filters);
    }

    public function findById(string $id_pegawai)
    {
        $result = $this->pegawaiRepo->findById($id_pegawai);
        if (! $result) {
            return response()->json([
                'message' => 'Not Found',
            ], 404);
        }

        return $result;
    }
}
