<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PencarianRefModel extends Model
{
    public function getRef($jenis = null, $detail = null)
    {
        $success = false;
        if ($jenis == 'pangkat') {
            $model = \DB::connection('mysql_second')->table('ref_pangkat as a')
                ->select('a.id_pangkat', 'a.pangkat', 'a.gol_ruang')
                ->orderBy('a.id_pangkat', 'asc');

            $success = true;
        } elseif ($jenis == 'eselon') {
            $model = \DB::connection('mysql_second')->table('ref_eselon as a')
                ->select('a.id_eselon', 'a.eselon')
                ->orderBy('a.id_eselon', 'asc');

            $success = true;
        } elseif ($jenis == 'jenis-kelamin') {
            $model = \DB::connection('mysql_second')->table('ref_jenis_kelamin as a')
                ->select('a.id_jenis_kelamin', 'a.jenis_kelamin');

            $success = true;
        } elseif ($jenis == 'agama') {
            $model = \DB::connection('mysql_second')->table('ref_agama as a')
                ->select('a.id_agama', 'a.agama')
                ->orderBy('a.id_agama', 'asc');

            $success = true;
        } elseif ($jenis == 'jenis-jabatan') {
            $model = \DB::connection('mysql_second')->table('ref_tipe_jabatan as a')
                ->select('a.id_tipe_jabatan', 'a.tipe_jabatan');

            $success = true;
        } elseif ($jenis == 'kedudukan-pegawai') {
            $model = \DB::connection('mysql_second')->table('ref_kedudukan_kepegawaian as a')
                ->select('a.id_kedudukan_kepegawaian', 'a.kedudukan_kepegawaian')
                ->orderBy('a.id_kedudukan_kepegawaian', 'asc');

            $success = true;
        } elseif ($jenis == 'status-pegawai') {
            $model = \DB::connection('mysql_second')->table('ref_status_kepegawaian as a')
                ->select('a.id_status_kepegawaian', 'a.status_kepegawaian')
                ->orderBy('a.id_status_kepegawaian', 'asc');

            $success = true;
        } elseif ($jenis == 'jabatan-struktural') {
            $model = \DB::connection('mysql_second')->table('ref_jabatan as a')
                ->select('a.id_jabatan', 'a.jabatan')
                ->where('a.id_tipe_jabatan', '=', 1)
                ->where('a.status', '=', 1)
                ->where('a.id_eselon', '>', 0)
                ->orderBy('a.id_jabatan', 'asc');

            $success = true;
        } elseif ($jenis == 'jabatan-fungsional') {
            $model = \DB::connection('mysql_second')->table('ref_jabatan as a')
                ->select('a.id_jabatan', 'a.jabatan')
                ->where('a.id_tipe_jabatan', '=', 3)
                ->where('a.status', '=', 1)
                ->orderBy('a.id_jabatan', 'asc');

            $success = true;
        } elseif ($jenis == 'jabatan-pelaksana') {
            $model = \DB::connection('mysql_second')->table('ref_jabatan as a')
                ->select('a.id_jabatan', 'a.jabatan')
                ->where('a.id_tipe_jabatan', '=', 2)
                ->where('a.status', '=', 1)
                ->orderBy('a.id_jabatan', 'asc');

            $success = true;
        } elseif ($jenis == 'tingkat-pendidikan') {
            $model = \DB::connection('mysql_second')->table('ref_tk_pendidikan as a')
                ->select(
                    'a.kode as kode_pendidikan',
                    'a.namaTkPendidikan as tk_pendidikan'
                )
                ->whereRaw('length(a.kode)=2')
                ->orderBy('a.kode', 'asc');

            $success = true;
        } elseif ($jenis == 'skpd') {
            $model = \DB::connection('mysql_second')->table('ref_skpd as a')
                ->select(
                    'a.id_skpd',
                    'a.skpd',
                    'a.kode_skpd'
                )
                ->where('a.status', '=', 1)
                ->orderBy('a.kode_skpd', 'asc');

            $success = true;
        } elseif ($jenis == 'jenjang-jabatan') {
            $models = \DB::connection('mysql_second')->table('tbljenjangjabfung as a')
                ->select(
                    'a.id_kelompok_jenjang as id_jenjang_jabatan'
                )
                ->whereRaw('a.id_kelompok_jenjang>0')
                ->orderBy('a.id_kelompok_jenjang', 'asc')
                ->groupBy('a.id_kelompok_jenjang')
                ->get();

            $model = [];
            foreach ($models as $mod) {
                $jenjang_jabatan = \DB::connection('mysql_second')->table('tbljenjangjabfung')
                    ->where('id_kelompok_jenjang', $mod->id_jenjang_jabatan)
                    ->pluck('NamaJenjangJabFung')->first();
                $model[] = array(
                    'id_jenjang_jabatan' => $mod->id_jenjang_jabatan,
                    'jenjang_jabatan' => str_replace('*', '', $jenjang_jabatan)
                );
            }

            $success = true;
        } elseif ($jenis == 'jenjang-jabatan-detail') {
            if ($detail != null && $detail != '') {
                $model = \DB::connection('mysql_second')->table('tbljenjangjabfung')
                    ->where('id_kelompok_jenjang', $detail)
                    ->pluck('NamaJenjangJabFung');

                $success = true;
            }
        } elseif ($jenis == 'tipe_jabatan') {
            $model = \DB::connection('mysql_second')
                ->table('ref_tipe_jabatan as a')
                ->select('a.id_tipe_jabatan', 'a.tipe_jabatan')
                ->orderBy('a.id_tipe_jabatan', 'asc');
            $success = true;
        } elseif ($jenis == 'keterangan_jabatan') {
            $model = \DB::connection('mysql_second')
                ->table('ref_keterangan_jabatan as a')
                ->select('a.id_keterangan_jabatan', 'a.keterangan_jabatan')
                ->whereIn('a.id_keterangan_jabatan', [6, 7, 8, 9, 10])
                ->orderBy('a.id_keterangan_jabatan', 'asc');
            $success = true;
        }

        if ($success == true) {
            return $model;
        } else {
            return null;
        }
    }

    public function getSkpdDetail($id_skpd)
    {
        $kode_skpd = \DB::connection('mysql_second')->table('ref_skpd')
            ->where('id_skpd', '=', $id_skpd);

        return $kode_skpd;
    }

    public function getFilter(array $array_data)
    {
        $nama = $array_data['nama_pegawai'];
        $nip = $array_data['nip'];
        $id_pangkat = $array_data['id_pangkat'];
        $id_eselon = $array_data['id_eselon'];

        $id_eselon_str = $array_data['id_eselon_str'];
        $id_tipe_jabatan = $array_data['id_tipe_jabatan'];
        $id_jenjang_jabatan = $array_data['id_jenjang_jabatan'];
        $nama_jabatan = $array_data['nama_jabatan'];
        $id_jabatan = $array_data['id_jabatan'];
        $id_jenis_kelamin = $array_data['id_jenis_kelamin'];
        $id_agama = $array_data['id_agama'];
        $tingkat_pendidikan = $array_data['tingkat_pendidikan'];
        $kode_skpd = $array_data['kode_skpd'];
        $nama_sekolah = $array_data['nama_sekolah'];
        $jurusan = $array_data['jurusan'];
        $id_kedudukan_pegawai = $array_data['id_kedudukan_pegawai'];
        $id_status_pegawai = $array_data['id_status_pegawai'];

        $data = \DB::connection('mysql_second')->table('tbl_pegawai')
            ->leftJoin('ref_agama', 'tbl_pegawai.id_agama', '=', 'ref_agama.id_agama')
            ->leftJoin('ref_pangkat', 'tbl_pegawai.id_pangkat', '=', 'ref_pangkat.id_pangkat')
            ->leftJoin('ref_jabatan', 'tbl_pegawai.id_jabatan', '=', 'ref_jabatan.id_jabatan')
            ->leftJoin('ref_skpd', 'tbl_pegawai.id_satuan_organisasi', '=', 'ref_skpd.id_skpd')
            ->leftJoin('ref_eselon', 'ref_jabatan.id_eselon', '=', 'ref_eselon.id_eselon')
            ->leftJoin('tbljenjangjabfung as z', 'z.id', '=', 'tbl_pegawai.id_jenjang_jabfung')
            ->leftJoin('tbl_pendidikan as y', function ($join3) {
                $join3->on('y.id_pegawai', '=', 'tbl_pegawai.id_pegawai')
                    ->whereRaw('y.id_tingkat_pendidikan = (SELECT max(yy.id_tingkat_pendidikan) from tbl_pendidikan yy where yy.id_pegawai=tbl_pegawai.id_pegawai and yy.status_supervisi=3)');
            })
            ->leftJoin('ref_jenis_kelamin', 'tbl_pegawai.id_jenis_kelamin', '=', 'ref_jenis_kelamin.id_jenis_kelamin')
            ->where(function ($query) use ($nama, $nip, $id_agama, $id_jenis_kelamin, $id_pangkat, $id_eselon, $id_tipe_jabatan, $id_eselon_str) {
                if ($nama != null && $nama != '') {
                    $query->where('tbl_pegawai.nama_pegawai', 'like', '%' . $nama . '%');
                }
                if ($nip != null && $nip != '') {
                    $query->where('tbl_pegawai.nip', 'like', '%' . $nip . '%');
                }
                if ($id_agama != null && $id_agama != '') {
                    $query->where('tbl_pegawai.id_agama', '=', $id_agama);
                }
                if ($id_jenis_kelamin != null && $id_jenis_kelamin != '') {
                    $query->where('tbl_pegawai.id_jenis_kelamin', '=', $id_jenis_kelamin);
                }
                if ($id_pangkat != null && $id_pangkat != '') {
                    $query->where('tbl_pegawai.id_pangkat', '=', $id_pangkat);
                }
                if ($id_eselon != null && $id_eselon != '') {
                    $query->where('ref_jabatan.id_eselon', '=', $id_eselon);
                }
                if ($id_eselon_str > 0) {
                    $query->where('ref_jabatan.id_eselon', '=', $id_eselon_str)
                        ->orWhere('ref_jabatan.id_eselon', '=', $id_eselon_str + 1);
                }
                if ($id_tipe_jabatan != null && $id_tipe_jabatan != '') {
                    $query->where('ref_jabatan.id_tipe_jabatan', '=', $id_tipe_jabatan);
                }
            })
            ->where(function ($query2) use ($nama_jabatan, $id_jabatan, $id_kedudukan_pegawai, $id_status_pegawai, $kode_skpd, $id_jenjang_jabatan) {
                if ($nama_jabatan != null && $nama_jabatan != '') {
                    $query2->where('ref_jabatan.jabatan', 'like', '%' . $nama_jabatan . '%');
                }
                if ($id_jabatan != null && $id_jabatan != '') {
                    $query2->where('tbl_pegawai.id_jabatan', '=', $id_jabatan);
                }
                if ($id_kedudukan_pegawai != null && $id_kedudukan_pegawai != '') {
                    $query2->where('tbl_pegawai.id_kedudukan_kepegawaian', '=', $id_kedudukan_pegawai);
                }
                if ($id_status_pegawai != null && $id_status_pegawai != '') {
                    $query2->where('tbl_pegawai.id_status_kepegawaian', '=', $id_status_pegawai);
                }
                if ($kode_skpd != '0') {
                    $query2->where('ref_skpd.kode_skpd', 'like', $kode_skpd . '%');
                }
                if ($id_jenjang_jabatan != null && $id_jenjang_jabatan != '') {
                    $query2->where('z.id_kelompok_jenjang', '=', $id_jenjang_jabatan);
                }
            })
            ->where(function ($query3) use ($tingkat_pendidikan, $nama_sekolah, $jurusan) {
                if ($tingkat_pendidikan != null && $tingkat_pendidikan != '') {
                    $query3->where('y.id_tingkat_pendidikan', '=', $tingkat_pendidikan);
                }
                if ($nama_sekolah != null && $nama_sekolah != '') {
                    $query3->where('y.nama_sekolah', 'like', '%' . $nama_sekolah . '%');
                }
                if ($jurusan != null && $jurusan != '') {
                    $query3->where('y.id_jurusan', 'like', '%' . $jurusan . '%');
                }
            })
            ->select(
                'tbl_pegawai.id_pegawai',
                'tbl_pegawai.nama_pegawai',
                'tbl_pegawai.nip',
                'ref_pangkat.pangkat',
                'ref_pangkat.gol_ruang',
                'ref_jabatan.jabatan',
                'ref_skpd.skpd as unit_kerja',
                'ref_jenis_kelamin.jenis_kelamin'
            )->groupby('tbl_pegawai.id_pegawai');

        return $data;
    }
}
