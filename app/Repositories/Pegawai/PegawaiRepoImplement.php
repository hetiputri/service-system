<?php

namespace App\Repositories\Pegawai;

use Illuminate\Support\Facades\DB;

class PegawaiRepoImplement implements PegawaiRepo
{
    public function searchWithFilters(array $filters)
    {
        $query = DB::connection('mysql_second')->table('tbl_pegawai as tp')
            ->leftJoin('ref_skpd as rs', 'tp.id_satuan_organisasi', 'rs.id_skpd')
            ->leftJoin('ref_jabatan as rj', 'tp.id_jabatan', 'rj.id_jabatan')
            ->where('tp.id_satuan_organisasi', '>', 0)
            ->whereIn('tp.id_kedudukan_kepegawaian', ['1', '2', '3', '4', '5', '6'])
            ->when(isset($filters['nama_pegawai']), function ($q) use ($filters) {
                $q->where('tp.nama_pegawai', 'like', '%'.$filters['nama_pegawai'].'%');
            })
            ->when(isset($filters['nip']), function ($q) use ($filters) {
                $q->where('tp.nip', 'like', '%'.$filters['nip'].'%');
            })
            ->when(isset($filters['kode_skpd']), function ($q) use ($filters) {
                $q->where('rs.kode_skpd', 'like', $filters['kode_skpd'].'%');
            })
            ->select('tp.nama_pegawai', 'tp.id_pegawai', 'tp.nip', 'rj.id_jabatan', 'rj.jabatan', 'rs.skpd', 'rs.kode_skpd');

        return $query->get();
    }

    public function findById(string $id_pegawai)
    {
        return DB::connection('mysql_second')
            ->table('tbl_pegawai')
            ->where('id_pegawai', $id_pegawai)
            ->select('id_pegawai', 'nama_pegawai', 'nip')
            ->firstOrFail();

    }
}
