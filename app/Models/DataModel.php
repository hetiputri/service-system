<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataModel extends Model
{
    public function getDapri($id_pegawai)
    {
        return \DB::connection('mysql_second')->table('tbl_pegawai')
            ->leftJoin('ref_pangkat', 'tbl_pegawai.id_pangkat', '=', 'ref_pangkat.id_pangkat')
            ->leftJoin('ref_jabatan', 'tbl_pegawai.id_jabatan', '=', 'ref_jabatan.id_jabatan')
            ->leftJoin('ref_skpd', 'tbl_pegawai.id_satuan_organisasi', '=', 'ref_skpd.id_skpd')
            #tambah instansi induk
            ->leftJoin('ref_skpd as induk', \DB::RAW('LEFT(ref_skpd.kode_skpd, 3)'), '=', 'induk.kode_skpd')
            ->leftJoin('ref_jenis_kelamin', 'tbl_pegawai.id_jenis_kelamin', '=', 'ref_jenis_kelamin.id_jenis_kelamin')
            ->leftJoin('ref_agama', 'tbl_pegawai.id_agama', '=', 'ref_agama.id_agama')
            ->leftJoin('ref_golongan_darah', 'tbl_pegawai.id_golongan_darah', '=', 'ref_golongan_darah.id_golongan_darah')
            ->leftJoin('ref_status_kepegawaian', 'tbl_pegawai.id_status_kepegawaian', '=', 'ref_status_kepegawaian.id_status_kepegawaian')
            // ->leftJoin('tbl_riwayat_pangkat', function ($join) {
            //     $join->on('tbl_pegawai.id_pegawai', '=', 'tbl_riwayat_pangkat.id_pegawai');
            //     $join->on('tbl_pegawai.id_pangkat', '=', 'tbl_riwayat_pangkat.id_pangkat');
            // })
            // ->leftJoin('tbl_riwayat_jabatan as e', function ($join2) {
            //     $join2->on('tbl_pegawai.id_pegawai', '=', 'e.id_pegawai');
            //     $join2->on('tbl_pegawai.id_jabatan', '=', 'e.id_jabatan')
            //         ->whereRaw('e.tmt = (select max(zz.tmt) from tbl_riwayat_jabatan zz where zz.id_pegawai = tbl_pegawai.id_pegawai and zz.id_jabatan = tbl_pegawai.id_jabatan and zz.status_supervisi=3)')
            //         ->where('e.status_supervisi', '=', 3);
            // })
            ->select(
                'tbl_pegawai.nip',
                'tbl_pegawai.id_pegawai',
                'tbl_pegawai.nama_pegawai',
                'tbl_pegawai.ktp',
                'tbl_pegawai.tempat_lahir',
                'tbl_pegawai.tanggal_lahir',
                'tbl_pegawai.gelar_depan',
                'tbl_pegawai.gelar_belakang',
                'ref_pangkat.pangkat',
                'ref_pangkat.gol_ruang',
                'ref_pangkat.id_pangkat_bkn',
                'ref_status_kepegawaian.status_kepegawaian',
                // 'tbl_riwayat_pangkat.tmt as tmt_pangkat',
                // 'tbl_riwayat_pangkat.mkg_tahun as mk_tahun',
                // 'tbl_riwayat_pangkat.mkg_bulan as mk_bulan',
                'ref_jabatan.jabatan',
                'ref_jabatan.id_eselon',
                // 'e.tmt as tmt_jabatan',
                'ref_skpd.skpd as unit_kerja',
                'induk.skpd as instansi_induk',
                'ref_jenis_kelamin.jenis_kelamin',
                'ref_agama.agama',
                'tbl_pegawai.alamat',
                'tbl_pegawai.rt',
                'tbl_pegawai.rw',
                'tbl_pegawai.id_kecamatan as kecamatan',
                'tbl_pegawai.id_kelurahan as kelurahan',
                'tbl_pegawai.id_kabupaten as kotakabupaten',
                'tbl_pegawai.id_provinsi as provinsi',
                'tbl_pegawai.kode_pos',
                'tbl_pegawai.no_telp',
                'tbl_pegawai.no_hp',
                'tbl_pegawai.no_karpeg',
                'tbl_pegawai.taspen',
                'tbl_pegawai.npwp',
                'tbl_pegawai.no_askes',
                'tbl_pegawai.gelar_depan',
                'tbl_pegawai.gelar_belakang',
                'ref_golongan_darah.golongan_darah'
            )
            ->where('tbl_pegawai.id_pegawai', $id_pegawai);
    }

    public function getUploadPeg($id_pegawai)
    {
        $upload = \DB::connection('mysql_second')->table('tbl_pegawai_upload')
            ->where('tbl_pegawai_upload.id_pegawai', '=', $id_pegawai)
            ->first();

        return $upload;
    }

    public function getKeluarga($id_pegawai)
    {
        $pasangan = \DB::connection('mysql_second')->table('tbl_pasangan')
            ->selectRaw(
                'tbl_pasangan.nama_pasangan,
                    tbl_pasangan.tempat_lahir,
                    tbl_pasangan.tgl_lahir,
                    tbl_pasangan.tgl_nikah,
                    tbl_pasangan.pekerjaan,
                    IF(tbl_pasangan.surat_nikah <>"",CONCAT("bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_pasangan_asli.php?id_pegawai=",tbl_pasangan.id_pegawai,"&jenis=surat_nikah&id_pas=",tbl_pasangan.id_pasangan),"") as akta_nikah,
					IF(tbl_pasangan.akta_cerai <>"",CONCAT("bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_pasangan_asli.php?id_pegawai=",tbl_pasangan.id_pegawai,"&jenis=akta_cerai&id_pas=",tbl_pasangan.id_pasangan),"") as akta_cerai,
					IF(tbl_pasangan.akta_meninggal <>"",CONCAT("bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_pasangan_asli.php?id_pegawai=",tbl_pasangan.id_pegawai,"&jenis=akta_meninggal&id_pas=",tbl_pasangan.id_pasangan),"") as akta_meninggal,
                    IF(tbl_pasangan.kartu_keluarga <>"",CONCAT("bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_pasangan_asli.php?id_pegawai=",tbl_pasangan.id_pegawai,"&jenis=kartu_keluarga&id_pas=",tbl_pasangan.id_pasangan),"") as kartu_keluarga,
                    CASE 
						WHEN tbl_pasangan.status_pernikahan=1 THEN "Menikah"
						WHEN tbl_pasangan.status_pernikahan=2 THEN "Cerai Hidup"
						ELSE ""
					END as status_nikah,
					CASE 
						WHEN tbl_pasangan.status_hidup=1 THEN "Hidup"
						WHEN tbl_pasangan.status_hidup=2 THEN "Meninggal Dunia"
						ELSE ""
					END as status_hidup'
            )
            ->where('id_pegawai', $id_pegawai)
            ->where('tbl_pasangan.nama_pasangan', '<>', '')
            ->where('tbl_pasangan.status_supervisi', '=', 3);

        return $pasangan;
    }

    public function getAnak($id_pegawai)
    {
        $anak = \DB::connection('mysql_second')->table('tbl_anak')
            ->leftJoin('ref_jenis_kelamin as jk', 'jk.id_jenis_kelamin', '=', 'tbl_anak.id_jenis_kelamin')
            ->leftJoin('tbl_pegawai_upload_anak as upl', function ($join) {
                $join->on('upl.id_pegawai', '=', 'tbl_anak.id_pegawai')
                    ->on('upl.id_anak', '=', 'tbl_anak.id_anak');
            })
            ->selectRaw(
                'tbl_anak.nama_anak,
                tbl_anak.tempat_lahir,
                tbl_anak.tgl_lahir,
                jk.jenis_kelamin,
                tbl_anak.pendidikan,
                tbl_anak.pekerjaan,
                tbl_anak.nama_ayah,
				tbl_anak.nama_ibu,
                IF(upl.scan_askes_anak<>"",CONCAT("bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_anak_peg_asli.php?id_pegawai=",tbl_anak.id_pegawai,"&id_anak=",tbl_anak.id_anak,"&jenis=scan_askes_anak"),"") as akta_lahir'
            )

            ->where('tbl_anak.id_pegawai', $id_pegawai)
            ->where('tbl_anak.status_supervisi', '=', 3);

        return $anak;
    }

    public function getCpns($id_pegawai)
    {
        $datacpns = \DB::connection('mysql_second')->table('tblcpns')
            ->leftJoin('tbl_pegawai', function ($join) {
                $join->on('tbl_pegawai.nip', '=', 'tblcpns.NIP')
                    ->orOn('tbl_pegawai.id_pegawai', '=', 'tblcpns.id_pegawai');
            })
            ->leftJoin('ref_pangkat', 'ref_pangkat.id_pangkat', '=', 'tblcpns.KodePangkat')
            ->leftJoin('tbl_pegawai_upload as d', 'd.id_pegawai', '=', 'tbl_pegawai.id_pegawai')
            ->leftJoin('ref_tk_pendidikan as e', 'e.kode', '=', 'tblcpns.id_pendidikan_cpns')
            ->leftJoin('ref_tk_pendidikan as f', 'f.kode', '=', 'tblcpns.id_detail_pendidikan')
            ->leftJoin('tbl_pendidikan as g', function ($join) {
                $join->on('g.id_tingkat_pendidikan', '=', 'tblcpns.id_pendidikan_cpns')
                    ->on('g.id_pegawai', '=', 'tbl_pegawai.id_pegawai');
            })
            ->where('tbl_pegawai.id_pegawai', $id_pegawai)
            ->where('tblcpns.status_supervisi', '=', 3)
            ->select(
                'tbl_pegawai.id_pegawai',
                'ref_pangkat.pangkat as pangkat_cpns',
                'ref_pangkat.gol_ruang as gol_ruang_cpns',
                'tblcpns.NoNota as no_bkn',
                'tblcpns.PejabatSK as pejabat_penetapan',
                'tblcpns.NoSK',
                'tblcpns.TanggalSK',
                'tblcpns.TMTCPNS',
                'd.sk_cpns',
                'e.namaTkPendidikan as tk_pendidikan',
                'f.namaTkPendidikan as tk_pendidikan_cpns',
                'g.tanggal_ijazah'
            );

        return $datacpns;
    }

    public function getPns($id_pegawai)
    {
        $data_pns = \DB::connection('mysql_second')->table('tbl_pns')
            ->leftJoin('tbl_pegawai', function ($join) {
                $join->on('tbl_pegawai.nip', '=', 'tbl_pns.NIP')
                    ->orOn('tbl_pegawai.id_pegawai', '=', 'tbl_pns.id_pegawai');
            })
            ->where('tbl_pegawai.id_pegawai', $id_pegawai)
            ->where('tbl_pns.status_spv', '=', 3)
            ->select(
                'tbl_pegawai.id_pegawai',
                'tbl_pns.NoSK as no_sk_pns',
                'tbl_pns.TanggalSK as tanggal_sk_pns',
                'tbl_pns.PejabatSK as pejabat_penetapan',
                'tbl_pns.TMTPNS'
            );

        return $data_pns;
    }

    public function getGajiBerkala($id_pegawai)
    {
        $gajis = \DB::connection('mysql_second')->table('tblriwayatgaji as a')
            ->leftJoin('tbl_pegawai', function ($join2) {
                $join2->on('tbl_pegawai.id_pegawai', '=', 'a.id_pegawai')
                    ->orOn('tbl_pegawai.nip', '=', 'a.NIP');
            })
            ->leftJoin('tbl_pegawai_upload_detail as upl', function ($join) {
                $join->on('upl.id_pegawai', '=', 'a.id_pegawai')
                    ->on('upl.id_riwayat', '=', 'a.id_riwayat_gaji')
                    ->where('upl.id_jenis', '=', 'tblriwayatgaji');
            })
            ->where('tbl_pegawai.id_pegawai', $id_pegawai)
            ->where('a.status_spv', '=', 3)
            ->select(
                'a.id_pegawai',
                'a.id_riwayat_gaji',
                'a.TMTGaji as tmt_gaji',
                'a.NoSKGaji as no_sk',
                'a.TglSKGaji as tgl_sk',
                'a.PejabatSK as pejabat_sk',
                'upl.scan_sk as sk'
            )
            ->orderBy('a.TMTGaji', 'desc');

        return $gajis;
    }

    public function getPangkat($id_pegawai)
    {
        $pangkat = \DB::connection('mysql_second')->table('tbl_riwayat_pangkat')
            ->leftJoin('ref_pangkat', 'ref_pangkat.id_pangkat', '=', 'tbl_riwayat_pangkat.id_pangkat')
            ->leftJoin('tbl_pegawai_upload_detail as upl', function ($join) {
                $join->on('upl.id_pegawai', '=', 'tbl_riwayat_pangkat.id_pegawai')
                    ->on('upl.id_riwayat', '=', 'tbl_riwayat_pangkat.id_riwayat_pangkat')
                    ->where('upl.id_jenis', '=', 'tbl_riwayat_pangkat');
            })
            ->where('tbl_riwayat_pangkat.id_pegawai', $id_pegawai)
            ->where('tbl_riwayat_pangkat.status_supervisi', 3)
            ->select(
                'tbl_riwayat_pangkat.id_riwayat_pangkat',
                'tbl_riwayat_pangkat.id_pegawai',
                'ref_pangkat.pangkat',
                'ref_pangkat.gol_ruang',
                'tbl_riwayat_pangkat.tmt as tmt_pangkat',
                'tbl_riwayat_pangkat.mkg_tahun',
                'tbl_riwayat_pangkat.mkg_bulan',
                'tbl_riwayat_pangkat.no_sk',
                'tbl_riwayat_pangkat.tgl_sk',
                'tbl_riwayat_pangkat.pejabat_penetapan',
                'upl.scan_sk as sk_pangkat'
            )
            ->orderBy('tbl_riwayat_pangkat.id_pangkat', 'desc');

        return $pangkat;
    }

    public function getJabatan($id_pegawai)
    {
        $dajabs = \DB::connection('mysql_second')->table('tbl_riwayat_jabatan')
            ->leftJoin('ref_jabatan', 'ref_jabatan.id_jabatan', '=', 'tbl_riwayat_jabatan.id_jabatan')
            ->leftJoin('ref_tipe_jabatan', 'ref_tipe_jabatan.id_tipe_jabatan', '=', 'tbl_riwayat_jabatan.id_tipe_jabatan')
            ->leftJoin('ref_skpd', 'ref_skpd.id_skpd', '=', 'tbl_riwayat_jabatan.id_skpd')
            ->leftJoin('ref_eselon', 'ref_eselon.id_eselon', '=', 'tbl_riwayat_jabatan.id_eselon')
            ->leftJoin('tbl_pegawai_upload_detail as upl', function ($join) {
                $join->on('upl.id_pegawai', '=', 'tbl_riwayat_jabatan.id_pegawai')
                    ->on('upl.id_riwayat', '=', 'tbl_riwayat_jabatan.id_riwayat_jabatan')
                    ->where('upl.id_jenis', '=', 'tbl_riwayat_jabatan');
            })
            ->select(
                'tbl_riwayat_jabatan.id_riwayat_jabatan',
                'tbl_riwayat_jabatan.id_pegawai',
                'tbl_riwayat_jabatan.tmt as tmt_jabatan',
                'tbl_riwayat_jabatan.tmt_akhir',
                'tbl_riwayat_jabatan.no_sk_jabatan as no_sk',
                'tbl_riwayat_jabatan.tgl_sk_jabatan as tgl_sk',
                'tbl_riwayat_jabatan.pejabat_penetapan',
                'tbl_riwayat_jabatan.id_plt',
                'ref_tipe_jabatan.tipe_jabatan',
                'tbl_riwayat_jabatan.jabatan as jabatan1',
                'ref_skpd.skpd as skpd',
                'tbl_riwayat_jabatan.skpd as skpd1',
                'ref_jabatan.jabatan',
                'ref_eselon.eselon',
                'upl.scan_sk as sk_jabatan'
            )
            ->where('tbl_riwayat_jabatan.id_pegawai', $id_pegawai)
            ->where('tbl_riwayat_jabatan.status_supervisi', '=', 3)
            ->orderBy('tbl_riwayat_jabatan.tmt', 'asc');

        return $dajabs;
    }

    public function getPendidikan($id_pegawai)
    {
        $pend = \DB::connection('mysql_second')->table('tbl_pendidikan')
            ->leftJoin('ref_tk_pendidikan as b', 'b.kode', '=', 'tbl_pendidikan.id_tingkat_pendidikan')
            ->leftJoin('ref_tk_pendidikan as c', 'c.kode', '=', 'tbl_pendidikan.id_detail_pendidikan')
            ->leftJoin('tbl_pegawai_upload_detail as upl', function ($join) {
                $join->on('upl.id_pegawai', '=', 'tbl_pendidikan.id_pegawai')
                    ->on('upl.id_riwayat', '=', 'tbl_pendidikan.id_pendidikan')
                    ->where('upl.id_jenis', '=', 'tbl_pendidikan');
            })
            ->where('tbl_pendidikan.id_pegawai', $id_pegawai)
            ->where('tbl_pendidikan.status_supervisi', '=', 3)
            ->select(
                'tbl_pendidikan.id_pegawai',
                'tbl_pendidikan.id_pendidikan',
                'b.namaTkPendidikan as tingkat_pendidikan',
                'c.namaTkPendidikan as detail_pendidikan',
                'tbl_pendidikan.id_jurusan as jurusan',
                'tbl_pendidikan.nama_sekolah',
                'tbl_pendidikan.tempat',
                'tbl_pendidikan.nama_kepala as kepala_sekolah',
                'tbl_pendidikan.no_ijazah',
                'tbl_pendidikan.tanggal_ijazah',
                'tbl_pendidikan.ip as nem_ip',
                'upl.scan_sk as ijazah'
            )
            ->orderBy('tbl_pendidikan.tanggal_ijazah', 'asc');

        return $pend;
    }

    public function getPenghargaan($id_pegawai)
    {
        $reward = \DB::connection('mysql_second')->table('tbl_riwayat_penghargaan as a')
            ->leftJoin('tbl_pegawai_upload_detail as upl', function ($join) {
                $join->on('upl.id_pegawai', '=', 'a.id_pegawai')
                    ->on('upl.id_riwayat', '=', 'a.id_riwayat_penghargaan')
                    ->where('upl.id_jenis', '=', 'tbl_riwayat_penghargaan');
            })
            ->where('a.id_pegawai', $id_pegawai)
            ->where('a.status_spv', '=', 3)
            ->select(
                'a.id_pegawai',
                'a.id_riwayat_penghargaan',
                'a.nama_penghargaan',
                'a.no_sk',
                'a.tgl_sk',
                'a.tahun',
                'a.asal_perolehan',
                'upl.scan_sk as sertifikat'
            )
            ->orderBy('a.tgl_sk', 'asc');

        return $reward;
    }

    public function getHukdis($id_pegawai)
    {
        $hukdis = \DB::connection('mysql_second')->table('tbl_riwayat_hukuman_disiplin as a')
            ->leftJoin('ref_jenis_hukuman as b', 'b.id_jenis_hukuman', '=', 'a.id_jenis_hukuman')
            ->leftJoin('ref_sub_jenis_hukuman as c', 'c.id_sub_jenis_hukuman', '=', 'a.id_sub_jenis_hukuman')
            ->leftJoin('tbl_pegawai_upload_detail as upl', function ($join) {
                $join->on('upl.id_pegawai', '=', 'a.id_pegawai')
                    ->on('upl.id_riwayat', '=', 'a.id_riwayat_hd')
                    ->where('upl.id_jenis', '=', 'tbl_riwayat_hukuman_disiplin');
            })
            ->where('a.id_pegawai', $id_pegawai)
            ->where('a.status_spv', '=', 3)
            ->select(
                'a.id_pegawai',
                'a.id_riwayat_hd',
                'a.no_sk',
                'a.tgl_sk',
                'b.jenis_hukuman as tingkat_hukuman',
                'c.sub_jenis_hukuman as jenis_hukuman',
                'a.tmt',
                'a.tmt_akhir',
                'upl.scan_sk as sk'
            )
            ->orderBy('a.tmt', 'desc');

        return $hukdis;
    }

    public function getPmk($id_pegawai)
    {
        $pmks = \DB::connection('mysql_second')->table('tbl_riwayat_pmk as a')
            ->leftJoin('tbl_pegawai', function ($join2) {
                $join2->on('tbl_pegawai.id_pegawai', '=', 'a.id_pegawai')
                    ->orOn('tbl_pegawai.nip', '=', 'a.nip');
            })
            ->leftJoin('tbl_pegawai_upload_detail as upl', function ($join) {
                $join->on('upl.id_pegawai', '=', 'a.id_pegawai')
                    ->on('upl.id_riwayat', '=', 'a.id_riwayat_pmk')
                    ->where('upl.id_jenis', '=', 'tbl_riwayat_pmk');
            })
            ->where('a.id_pegawai', $id_pegawai)
            ->where('a.status_spv', '=', 3)
            ->select(
                'a.id_pegawai',
                'a.id_riwayat_pmk',
                'a.pengalaman',
                'a.tmt',
                'a.no_sk',
                'a.tgl_sk',
                'a.mkg_tahun',
                'a.mkg_bulan',
                'a.pejabat_penetapan',
                'upl.scan_sk as sk'
            )
            ->orderBy('a.tgl_sk', 'desc');

        return $pmks;
    }

    public function getPi($id_pegawai)
    {
        $pis = \DB::connection('mysql_second')->table('tbl_riwayat_pi as a')
            ->leftJoin('ref_tingkat_pendidikan as b', 'b.id_tingkat_pendidikan', '=', 'a.id_tingkat_pendidikan')
            ->leftJoin('tbl_pegawai_upload_detail as upl', function ($join) {
                $join->on('upl.id_pegawai', '=', 'a.id_pegawai')
                    ->on('upl.id_riwayat', '=', 'a.id_riwayat_penyesuaian_ijazah')
                    ->where('upl.id_jenis', '=', 'tbl_riwayat_pi');
            })
            ->where('a.id_pegawai', $id_pegawai)
            ->where('a.status_spv', '=', 3)
            ->select(
                'a.id_pegawai',
                'a.id_riwayat_penyesuaian_ijazah',
                'a.no_stlupi',
                'a.tgl_sertifikat_nilai',
                'b.tingkat_pendidikan',
                'a.program_studi',
                'a.pejabat_sertifikat',
                'a.pejabat_nilai',
                'upl.scan_sk as sk'
            )
            ->orderBy('a.tgl_sertifikat_nilai', 'desc');

        return $pis;
    }

    public function getCuti($id_pegawai)
    {
        $cutis = \DB::connection('mysql_second')->table('tbl_cuti_riwayat as a')
            ->leftJoin('ref_jenis_cuti as b', 'b.id_jenis_cuti', '=', 'a.id_jenis_cuti')
            ->leftJoin('tbl_pegawai_upload_detail as upl', function ($join) {
                $join->on('upl.id_pegawai', '=', 'a.id_pegawai')
                    ->on('upl.id_riwayat', '=', 'a.id_riwayat_cuti')
                    ->where('upl.id_jenis', '=', 'tbl_cuti_riwayat');
            })
            ->where('a.id_pegawai', $id_pegawai)
            ->where('a.status_spv', '=', 3)
            ->select(
                'a.id_pegawai',
                'a.id_riwayat_cuti',
                'b.jenis_cuti',
                'a.dari',
                'a.sampai',
                'a.lama',
                'a.no_sk',
                'a.tgl_sk',
                'a.pejabat_sk',
                'upl.scan_sk as sk'
            )
            ->orderBy('a.tgl_sk', 'desc');

        return $cutis;
    }

    public function getDiklatStr($id_pegawai)
    {
        $diklat = \DB::connection('mysql_second')->table('tbl_riwayat_diklat_struktural as a')
            ->leftJoin('ref_diklat_struktural as str', 'str.id_diklat_struktural', '=', 'a.id_diklat_struktural')
            ->leftJoin('tbl_pegawai_upload_detail as upl', function ($join) {
                $join->on('upl.id_pegawai', '=', 'a.id_pegawai')
                    ->on('upl.id_riwayat', '=', 'a.id_riwayat_diklat_struktural')
                    ->where('upl.id_jenis', '=', 'tbl_riwayat_diklat_struktural');
            })
            ->where('a.id_pegawai', $id_pegawai)
            ->where('a.status_supervisi', '=', 3)
            ->select(
                'a.id_pegawai',
                'a.id_riwayat_diklat_struktural',
                'str.diklat_struktural',
                'a.tempat_diklat',
                'a.penyelenggara',
                'a.anggaran as tahun_anggaran',
                'a.mulai',
                'a.selesai',
                'a.lama as jumlah_jam',
                'a.no_ijazah',
                'a.tgl_ijazah',
                'a.peringkat',
                'upl.scan_sk as sertifikat'
            )
            ->orderBy('a.mulai', 'asc');
        return $diklat;
    }

    public function getDiklatLain($id_pegawai)
    {
        $diklat1 = \DB::connection('mysql_second')->table('tbl_riwayat_diklat_lainnya as a')
            ->leftJoin('ref_bentuk_diklat as b', 'b.id_bentuk_diklat', '=', 'a.id_bentuk_diklat')
            ->leftJoin('tbl_pegawai_upload_detail as upl', function ($join) {
                $join->on('upl.id_pegawai', '=', 'a.id_pegawai')
                    ->on('upl.id_riwayat', '=', 'a.id_riwayat_diklat_lainnya')
                    ->where('upl.id_jenis', '=', 'tbl_riwayat_diklat_lainnya');
            })
            ->where('a.id_pegawai', $id_pegawai)
            ->where('a.status_supervisi', '=', 3)
            ->select(
                'a.id_pegawai',
                'a.id_riwayat_diklat_lainnya',
                'a.nama_diklat',
                'b.bentuk_diklat',
                'a.tempat_diklat',
                'a.penyelenggara',
                'a.anggaran as tahun_anggaran',
                'a.mulai',
                'a.selesai',
                'a.lama as jumlah_jam',
                'a.no_ijazah',
                'a.tgl_ijazah',
                'a.peringkat',
                'upl.scan_sk as sertifikat'
            )
            ->orderBy('a.mulai', 'asc');

        return $diklat1;
    }

    public function getPejabatEselon2($role, $id_eselon_peg)
    {
        $id_skpd = session('pegawai')['id_satuan_organisasi'];
        $kode_skpd = \DB::connection('mysql_second')->table('ref_skpd')
            ->where('id_skpd', $id_skpd)->selectRaw('substr(kode_skpd,1,3) as kode_skpd')->first()->kode_skpd;

        return \DB::connection('mysql_second')->table('tbl_pegawai as A')
            ->join('ref_jabatan as B', 'B.id_jabatan', '=', 'A.id_jabatan')
            ->leftJoin('ref_skpd as C', 'C.id_skpd', '=', 'A.id_satuan_organisasi')
            ->where(function ($join) use ($role, $kode_skpd, $id_eselon_peg) {

                if ($role == 'AdminOpdPensiun' && $id_eselon_peg == '4') {
                    $join->whereIn('B.id_jabatan', [1]);
                } elseif ($role == 'AdminOpdPensiun') {
                    $join->whereIn('B.id_eselon', [4, 5, 6])
                        ->where('C.kode_skpd', 'like', $kode_skpd . '%');
                } else {
                    $join->whereIn('B.id_jabatan', [1, 51583]);
                }
            })

            ->select(
                'A.id_pegawai',
                'A.nip',
                'A.nama_pegawai',
                'B.jabatan'
            )
            ->get();
    }
}
