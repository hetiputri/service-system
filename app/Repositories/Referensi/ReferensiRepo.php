<?php

namespace App\Repositories\Referensi;

interface ReferensiRepo
{
    public function skpdAll();

    public function pangkatAll();

    public function searchJabatanWithFilters(array $filters);

    public function findJabatanById(string $id_jabatan);

    public function tipeJabatanAll();

    public function eselonAll();

    public function agamaAll();

    public function jenisKelaminAll();
}
