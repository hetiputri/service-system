<?php

namespace App\Services\KonsepMutasi;

use App\Models\MutasiStruktural;
use App\Repositories\KonsepMutasi\KonsepMutasiRepo;
use App\Repositories\Pegawai\PegawaiRepo;
use App\Repositories\Referensi\ReferensiRepo;

class KonsepMutasiService
{
    private $konsepMutasiRepo;

    private $referensiRepo;

    private $pegawaiRepo;

    public function __construct(KonsepMutasiRepo $konsepMutasiRepo, PegawaiRepo $pegawaiRepo, ReferensiRepo $jabatanRepo)
    {
        $this->konsepMutasiRepo = $konsepMutasiRepo;
        $this->pegawaiRepo = $pegawaiRepo;
        $this->referensiRepo = $jabatanRepo;
    }

    public function all()
    {
        return $this->konsepMutasiRepo->all();
    }

    public function findById(string $id_konsep)
    {
        $konsep = $this->konsepMutasiRepo->findById($id_konsep);
        $konsep->load('mutasiStrukturalDetail');
        if ($konsep->mutasiStrukturalDetail) {
            $dari_jabatan = $this->referensiRepo->findJabatanById($konsep->mutasiStrukturalDetail->dari_id_jabatan);
            $ke_jabatan = $this->referensiRepo->findJabatanById($konsep->mutasiStrukturalDetail->ke_id_jabatan);
            $pegawai = $this->pegawaiRepo->findById($konsep->mutasiStrukturalDetail->id_pegawai);
            $konsep->mutasiStrukturalDetail->pegawai = $pegawai;
            $konsep->mutasiStrukturalDetail->dari_jabatan = $dari_jabatan;
            $konsep->mutasiStrukturalDetail->ke_jabatan = $ke_jabatan;
        }

        $dibuat_oleh = $this->pegawaiRepo->findById($konsep->dibuat_oleh);
        $konsep->pembuat = $dibuat_oleh;

        return $konsep;
    }

    public function create(array $data)
    {
        $pegawai = session('pegawai');
        $data['dibuat_oleh'] = $pegawai['id_pegawai'];

        return $this->konsepMutasiRepo->create($data);
    }

    public function createDetail(MutasiStruktural $mutasiStruktural, array $data)
    {
        $data['urutan'] = 1;
        $data['jenis_mutasi'] = 'Struktural';
        $data['alasan'] = 'Struktural';

        return $mutasiStruktural->mutasiStrukturalDetail()->updateOrCreate([
            'id_konsep' => $mutasiStruktural->id,
        ],
            $data);
    }

    public function update(MutasiStruktural $mutasiStruktural, array $request)
    {
        return $this->konsepMutasiRepo->update($mutasiStruktural, $request);
    }

    public function delete(MutasiStruktural $mutasiStruktural)
    {
        $this->konsepMutasiRepo->delete($mutasiStruktural);
    }

    public function deleteDetail(MutasiStruktural $mutasiStruktural)
    {
        $this->konsepMutasiRepo->deleteDetail($mutasiStruktural);
    }
}
