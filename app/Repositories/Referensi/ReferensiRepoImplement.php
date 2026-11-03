<?php

namespace App\Repositories\Referensi;

use Illuminate\Support\Facades\DB;

class ReferensiRepoImplement implements ReferensiRepo
{
    public function eselonAll()
    {
        return DB::connection('mysql_second')
            ->table('ref_eselon')
            ->select('id_eselon', 'eselon')
            ->get();
    }

    public function searchJabatanWithFilters(array $filters)
    {
        DB::connection('mysql_second')->statement('SET SESSION MAX_EXECUTION_TIME=12000');

        return DB::connection('mysql_second')
            ->table('ref_jabatan as rj')
            ->leftJoin('ref_skpd as rs', 'rs.id_skpd', '=', 'rj.id_skpd')
            ->leftJoin('ref_skpd as rs2', function ($join) {
                $join->on(DB::raw('LEFT(rs.kode_skpd, 3)'), '=', 'rs2.kode_skpd');
            })
            ->leftJoin('tbl_pegawai as tp', function ($join) {
                $join->on('tp.id_jabatan', '=', 'rj.id_jabatan')
                    ->whereIn('tp.id_kedudukan_kepegawaian', ['1', '2', '3', '4', '5', '6']);
            })
            ->leftJoin('tbl_riwayat_jabatan as trj', function ($join) {
                $join->on('rj.id_jabatan', '=', 'trj.id_jabatan')
                    ->where('trj.id_jab_ganda', '>', 0)
                    ->whereIn('trj.id_plt', [1, 2])
                    ->where('trj.status_supervisi', 3)
                    ->where('trj.tmt', '=', function ($q) {
                        $q->selectRaw('MAX(trj1.tmt)')
                            ->from('tbl_riwayat_jabatan as trj1')
                            ->whereColumn('trj.id_jabatan', 'trj1.id_jabatan')
                            ->where('trj1.status_supervisi', '=', 3);
                    });
            })
            ->leftJoin('tbl_pegawai as tp2', 'tp2.id_pegawai', 'trj.id_pegawai')
            ->where('rj.id_tipe_jabatan', '=', 1)
            ->where('rj.id_eselon', '>', 0)
            ->when(isset($filters['nama_jabatan']), function ($q) use ($filters) {
                $q->where('rj.jabatan', 'like', '%'.$filters['nama_jabatan'].'%');
            })
            ->when(isset($filters['status_jabatan']), function ($q) use ($filters) {
                if ($filters['status_jabatan'] === '1') {
                    $q->whereNull('tp.nama_pegawai')
                        ->whereNull('tp2.nama_pegawai');
                } else {
                    $q->where(function ($query) {
                        $query->whereNotNull('tp.nama_pegawai')
                            ->orWhereNotNull('tp2.nama_pegawai');
                    });
                }
            })

            ->when(isset($filters['kode_skpd']), function ($q) use ($filters) {
                $q->where('rs.kode_skpd', 'like', $filters['kode_skpd'].'%');
            })
            ->select(
                'rj.id_jabatan',
                'rj.jabatan',
                DB::raw("
                    CASE
                        WHEN tp.nama_pegawai IS NOT NULL OR tp2.nama_pegawai IS NOT NULL
                        THEN 'Berisi'
                        ELSE 'Lowong'
                    END as status_jabatan
                "),
                DB::raw('
                    IF (tp.nama_pegawai IS NOT NULL, tp.nama_pegawai, tp2.nama_pegawai) as nama_pejabat
                ')
            )->get();
    }

    public function findJabatanById(string $idJabatan)
    {
        return DB::connection('mysql_second')
            ->table('ref_jabatan as rj')
            ->where('id_jabatan', '=', $idJabatan)
            ->first();
    }

    public function pangkatAll()
    {
        return DB::connection('mysql_second')
            ->table('ref_pangkat')
            ->select('id_pangkat', 'pangkat')
            ->get();
    }

    public function tipeJabatanAll()
    {
        return DB::connection('mysql_second')
            ->table('ref_tipe_jabatan')
            ->select('id_tipe_jabatan', 'tipe_jabatan')
            ->get();
    }

    public function skpdAll()
    {
        return DB::connection('mysql_second')
            ->table('ref_skpd')
            ->select('id_skpd', 'skpd', 'kode_skpd')
            ->where('status', '=', 1)
            ->whereRaw('length(kode_skpd) BETWEEN 1 AND 3')
            ->get();
    }

    public function agamaAll()
    {
        return DB::connection('mysql_second')
            ->table('ref_agama')
            ->select('id_agama', 'agama');
    }

    public function jenisKelaminAll()
    {
        return DB::connection('mysql_second')
            ->table('ref_jenis_kelamin')
            ->select('id_jenis_kelamin', 'jenis_kelamin');
    }
}
