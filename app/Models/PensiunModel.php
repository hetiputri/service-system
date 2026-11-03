<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PensiunModel extends Model
{
    public function syaratPensiun($id_pegawai)
    {
        $dapeg = DapriModel::leftJoin('ref_jabatan as b', 'b.id_jabatan', '=', 'tbl_pegawai.id_jabatan')
            ->leftJoin('tbl_riwayat_jabatan as e', function ($join2) {
                $join2->on('tbl_pegawai.id_pegawai', '=', 'e.id_pegawai');
                $join2->on('tbl_pegawai.id_jabatan', '=', 'e.id_jabatan')
                    ->whereRaw('e.tmt = (select max(zz.tmt) from tbl_riwayat_jabatan zz where zz.id_pegawai = tbl_pegawai.id_pegawai and zz.id_jabatan = tbl_pegawai.id_jabatan and zz.status_supervisi=3 and zz.id_plt=0)')
                    ->where('e.status_supervisi', '=', 3)
                    ->where('e.id_plt', '=', 0);;
            })
            ->select('tbl_pegawai.id_jabatan', 'b.id_tipe_jabatan', 'b.id_eselon', 'tbl_pegawai.tanggal_lahir', 'e.jenjang_jabfung')
            ->where('tbl_pegawai.id_pegawai', $id_pegawai);

        return $dapeg;
    }

    public function rekapPensiun($mulai_tanggal, $sampai_tanggal, $tmt_pensiun_from, $tmt_pensiun_to, $id_jenis_pensiun, $status, $urutan_step)
    {
        //ambil nama database dari config
        $secondDb = config('database.connections.mysql_second.database');

        $query = PengajuanLayanan::pensiun()
            ->join("$secondDb.tbl_pegawai as pegawai", 'pengajuan_layanan.pegawai_id', '=', 'pegawai.id_pegawai')
            ->leftJoin("$secondDb.ref_skpd as skpd", 'skpd.id_skpd', '=', 'pegawai.id_satuan_organisasi')
            ->leftJoin("$secondDb.ref_pangkat as pangkat", 'pangkat.id_pangkat', '=', 'pegawai.id_pangkat')
            ->leftJoin('layanan as layanan', 'layanan.id', '=', 'pengajuan_layanan.layanan_id')
            ->leftJoin('dpcp', 'dpcp.pengajuan_layanan_id', '=', 'pengajuan_layanan.id')
            ->leftJoin('workflow_step as wf', function ($q) {
                $q->on('wf.urutan_step', '=', 'pengajuan_layanan.current_step')
                    ->where('wf.id_layanan', '=', 1);
            });

        if ($mulai_tanggal && $sampai_tanggal) {
            $query->whereBetween('tanggal_pengajuan', [$mulai_tanggal, $sampai_tanggal]);
        } elseif ($mulai_tanggal) {
            $query->where('tanggal_pengajuan', '>=', $mulai_tanggal);
        } elseif ($sampai_tanggal) {
            $query->where('tanggal_pengajuan', '<=', $sampai_tanggal);
        }

        if ($tmt_pensiun_from && $tmt_pensiun_to) {
            $query->whereBetween('dpcp.tmt_pensiun', [$tmt_pensiun_from, $tmt_pensiun_to]);
        } elseif ($tmt_pensiun_from) {
            $query->where('dpcp.tmt_pensiun', '>=', $tmt_pensiun_from);
        } elseif ($tmt_pensiun_to) {
            $query->where('dpcp.tmt_pensiun', '<=', $tmt_pensiun_to);
        }

        if ($urutan_step) {
            $query->where('pengajuan_layanan.current_step', '=', $urutan_step);
        }

        if ($id_jenis_pensiun) {
            $query->where('layanan_id', $id_jenis_pensiun);
        }

        if ($status) {
            $query->where('status_akhir', $status);
        }
        $query->where('status_akhir', '!=', 'draft');
        $query->selectRaw(
            'pengajuan_layanan.*,
            if(pengajuan_layanan.status_akhir IN ("selesai","Revisi"),pengajuan_layanan.status_akhir,"") as stats,
             DATE_FORMAT(pengajuan_layanan.tanggal_pengajuan, "%Y-%m-%d") as tgl_ajuan,
             pegawai.nama_pegawai,
             pegawai.nip,
             skpd.skpd,
             pangkat.pangkat,
             pangkat.gol_ruang,
             layanan.nama_layanan,
             dpcp.tmt_pensiun,
            wf.nama_step'
        );

        return $query->orderByDesc('pengajuan_layanan.tanggal_pengajuan')->get();
    }

    public function getPegawaiByIdJabatan($id_jabatan)
    {
        $query = DapriModel::Join('ref_jabatan as b', 'b.id_jabatan', '=', 'tbl_pegawai.id_jabatan')
            ->leftJoin('ref_pangkat as c', 'c.id_pangkat', '=', 'tbl_pegawai.id_pangkat')
            ->where('b.id_jabatan', '=', $id_jabatan)
            ->select(
                'tbl_pegawai.nama_pegawai',
                'tbl_pegawai.nip',
                'tbl_pegawai.gelar_depan',
                'tbl_pegawai.gelar_belakang',
                'tbl_pegawai.id_pegawai',
                'b.jabatan',
                'c.pangkat',
                'c.gol_ruang'
            );

        return $query ? $query->first() : collect();
    }

    public function getPegawaiBkpsdm()
    {
        $sql = DapriModel::leftJoin('ref_skpd as b', 'b.id_skpd', '=', 'tbl_pegawai.id_satuan_organisasi')
            ->whereRaw('substr(b.kode_skpd,1,3)  like "%029%"')
            //->where('b.id_skpd', '=', 3062)
            ->select(
                'tbl_pegawai.nama_pegawai',
                'tbl_pegawai.nip',
                'tbl_pegawai.gelar_depan',
                'tbl_pegawai.gelar_belakang',
                'tbl_pegawai.id_pegawai',
                \DB::RAW('substr(b.kode_skpd,1,3) as kode')
            )->get();

        return $sql; //query ? $query->get() : collect();
    }
}
