<?php

namespace App\Services\Referensi;

use App\Repositories\Referensi\ReferensiRepo;

class ReferensiService
{
    private $referensiRepo;

    public function __construct(ReferensiRepo $referensiRepo)
    {
        $this->referensiRepo = $referensiRepo;
    }

    public function skpdAll()
    {
        return $this->referensiRepo->skpdAll();
    }

    public function pangkatAll()
    {
        return $this->referensiRepo->pangkatAll();
    }

    public function searchJabatanWithFilters(array $filters)
    {
        return $this->referensiRepo->searchJabatanWithFilters($filters);
    }

    public function findJabatanById(string $id_jabatan)
    {
        return $this->referensiRepo->findJabatanById($id_jabatan);
    }

    public function tipeJabatanAll()
    {
        return $this->referensiRepo->tipeJabatanAll();
    }

    public function eselonAll()
    {
        return $this->referensiRepo->eselonAll();
    }

    public function agamaAll()
    {
        return $this->referensiRepo->agamaAll();
    }

    public function jenisKelaminAll()
    {
        return $this->referensiRepo->jenisKelaminAll();
    }
}
