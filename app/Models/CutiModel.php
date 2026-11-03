<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CutiModel extends Model
{

    public function getStepUsulan()
    {
        $data = \DB::table('workflow_user')
            ->where('id_layanan', 2)
            ->where('aktif', 1)
            ->orderBy('urutan')
            ->get();

        return $data;
    }

    public function getAtasan(string $jenis, string $id_pegawai, string $jenis_cuti) //switch dengan API
    {
        $dapeg = $this->getPegawai($id_pegawai);
        $id_skpd_peg = $dapeg->id_satuan_organisasi ?? null;
        //1. Cari SKPD Induk dari pegawai ybs
        $kodeskpdinduk = \DB::connection('mysql_second')->table('ref_skpd')
            ->selectRaw('left(kode_skpd,3) as kode_skpd')
            ->where('id_skpd', $id_skpd_peg)
            ->first();

        //Syarat untuk eselon 2 (id_eselon=4),camat dan lurah ttd atasan langsung dan pejabat berwenang adalah sekda
        //Syarat untuk selain poin atas ttd atasan langsung adalah pejabat eselon 2
        //2 Atasan Langsung        
        if ($jenis == 'langsung') {
            if ($dapeg->id_eselon == 4 || str_starts_with($dapeg->jabatan, 'Camat ')) {  //eselon II.b, camat dan lurah ttdnya sekda
                $model = \DB::connection('mysql_second')->table('tbl_pegawai as a')
                    ->Join('ref_jabatan as c', 'a.id_jabatan', '=', 'c.id_jabatan')
                    ->select('a.id_pegawai', 'a.nama_pegawai', 'a.nip')
                    ->whereIn('c.id_eselon', [3])->orderBy('a.nama_pegawai', 'asc');
            } elseif ($kodeskpdinduk->kode_skpd == '029' && $jenis_cuti != '11') {
                $model = \DB::connection('mysql_second')->table('tbl_pegawai as a')
                    ->Join('ref_skpd as b', 'a.id_satuan_organisasi', '=', 'b.id_skpd')
                    ->Join('ref_jabatan as c', 'a.id_jabatan', '=', 'c.id_jabatan')
                    ->where('b.kode_skpd', 'like', $kodeskpdinduk->kode_skpd . '%')
                    ->select('a.id_pegawai', 'a.nama_pegawai', 'a.nip')
                    ->whereIn('c.id_eselon', [4]) //->where('c.id_eselon', '>', 0)
                    ->orderBy('a.nama_pegawai', 'asc');
            } elseif ($jenis_cuti == '11') {
                $model = \DB::connection('mysql_second')->table('tbl_pegawai as a')
                    ->Join('ref_skpd as b', 'a.id_satuan_organisasi', '=', 'b.id_skpd')
                    ->Join('ref_jabatan as c', 'a.id_jabatan', '=', 'c.id_jabatan')
                    ->where('b.kode_skpd', 'like', $kodeskpdinduk->kode_skpd . '%')
                    ->whereIn('c.id_eselon', [2, 3, 4, 5, 6, 7, 8])
                    ->select('a.id_pegawai', 'a.nama_pegawai', 'a.nip')
                    ->orderBy('a.nama_pegawai', 'asc');
            } else {
                $model = \DB::connection('mysql_second')->table('tbl_pegawai as a')
                    ->Join('ref_skpd as b', 'a.id_satuan_organisasi', '=', 'b.id_skpd')
                    ->Join('ref_jabatan as c', 'a.id_jabatan', '=', 'c.id_jabatan')
                    ->where('b.kode_skpd', 'like', $kodeskpdinduk->kode_skpd . '%')
                    ->whereIn('c.id_eselon', [2, 3, 4, 5, 6, 7])
                    ->select('a.id_pegawai', 'a.nama_pegawai', 'a.nip')
                    ->orderBy('a.nama_pegawai', 'asc');
            }

            return $model->get();
        } elseif ($jenis == 'kaopd') {
            // kalau bukan cuti diluar tanggungan negara, ttd persetujuan hanya Kaban BKD atau sekda
            //khusus cuti diluar tanggungan negara, ttd persetujuan Kepala OPD            
            //if ($dapeg->id_eselon == 4 || str_contains($dapeg->jabatan, 'Camat ') || str_contains($dapeg->jabatan, 'Lurah ')) { //eselon ii.b, camat dan lurah ttdnya sekda
            if ($dapeg->id_eselon == 4 || str_starts_with($dapeg->jabatan, 'Camat ') || str_starts_with($dapeg->jabatan, 'Lurah ')) { //eselon ii.b, camat dan lurah ttdnya sekda 
                $model = \DB::connection('mysql_second')->table('tbl_pegawai as a')
                    ->Join('ref_jabatan as c', 'a.id_jabatan', '=', 'c.id_jabatan')
                    ->select('a.id_pegawai', 'a.nama_pegawai', 'a.nip')
                    ->whereIn('c.id_eselon', [3])->orderBy('a.nama_pegawai', 'asc');
            } elseif ($jenis_cuti != 11) { //11 -> cuti diluar tanggungan negara
                $model = \DB::connection('mysql_second')->table('tbl_pegawai as a')
                    ->Join('ref_jabatan as c', 'a.id_jabatan', '=', 'c.id_jabatan')
                    ->where('c.id_jabatan', 51583)
                    ->select('a.id_pegawai', 'a.nama_pegawai', 'a.nip')
                    ->orderBy('a.nama_pegawai', 'asc');
            } else {
                $model = \DB::connection('mysql_second')->table('tbl_pegawai as a')
                    ->Join('ref_skpd as b', 'a.id_satuan_organisasi', '=', 'b.id_skpd')
                    ->Join('ref_jabatan as c', 'a.id_jabatan', '=', 'c.id_jabatan')
                    ->where('b.kode_skpd', 'like', $kodeskpdinduk->kode_skpd . '%')
                    ->whereIn('c.id_eselon', [2, 3, 4])
                    ->select('a.id_pegawai', 'a.nama_pegawai', 'a.nip')
                    ->orderBy('a.nama_pegawai', 'asc');
            }

            if ($jenis == 'kaopd') {
                return $model ? $model : null;
            } else {
                return $model ? $model->get() : null;
            }
        }
    }

    public function getPegawai($id_pegawai)
    {
        if ($id_pegawai == null || $id_pegawai == '') {
            return null;
        }
        $data = \DB::connection('mysql_second')->table('tbl_pegawai as a')
            ->leftJoin('ref_jabatan as b', 'a.id_jabatan', '=', 'b.id_jabatan')
            ->leftJoin('ref_skpd as c', 'a.id_satuan_organisasi', '=', 'c.id_skpd')
            // ->leftJoin('tbl_riwayat_pangkat as d', function ($join) {
            //     $join->on('a.id_pegawai', '=', 'd.id_pegawai')
            //         ->on('a.id_pangkat', '=', 'd.id_pangkat')
            //         ->where('d.status_supervisi', 3);
            // })
            ->where('a.id_pegawai', $id_pegawai)
            ->selectRaw('a.id_pegawai, a.nama_pegawai, a.nip, a.id_satuan_organisasi,a.id_status_kepegawaian,b.jabatan,b.id_jabatan,b.id_eselon, c.skpd, a.gelar_depan,a.gelar_belakang');
        //concat(d.mkg_tahun, " Tahun " , d.mkg_bulan, " Bulan") as masa_kerja,
        return $data ? $data->first() : null;
    }

    public function allUsulan()
    {
        $usulan = PengajuanLayanan::where([
            ['current_step', '=', 2],
            ['id_jenis_layanan', '=', 2]
        ])
            ->Join('db_simpeg_medan_lama.tbl_pegawai as b', 'b.id_pegawai', '=', 'pengajuan_layanan.pegawai_id')
            //->leftJoin('db_simpeg_medan_lama.ref_skpd as c', 'c.id_skpd', '=', 'b.id_satuan_organisasi')
            ->leftJoin('db_simpeg_medan_lama.ref_skpd as c', 'c.id_skpd', '=', 'pengajuan_layanan.id_skpd')
            ->leftJoin('proses_layanan_cuti_detail as aa', 'aa.pengajuan_id', '=', 'pengajuan_layanan.id')
            ->selectRaw('
                DATE_FORMAT(pengajuan_layanan.tanggal_pengajuan, "%d-%m-%Y") as tanggal_ajuan,
                pengajuan_layanan.*,
                b.nama_pegawai, 
                b.nip,
                c.skpd')
            ->orderBy('pengajuan_layanan.tanggal_pengajuan', 'asc')
            ->where('aa.tgl_proses', '=', null)
            ->get();

        return $usulan ? $usulan : [];
    }

    public function search($nama, $nip, $id_skpd)
    {

        $secondDb = config('database.connections.mysql_second.database');

        $pengajuan = PengajuanLayanan::where([
            ['current_step', '=', 2],
            ['id_jenis_layanan', '=', 2]
        ])
            ->Join("$secondDb.tbl_pegawai as b", 'b.id_pegawai', '=', 'pengajuan_layanan.pegawai_id')
            ->leftJoin("$secondDb.ref_skpd as c", 'c.id_skpd', '=', 'pengajuan_layanan.id_skpd')
            ->where(function ($query) use ($nip, $nama, $id_skpd) {
                if ($nama != '') {
                    $query->where('b.nama_pegawai', 'like', '%' . $nama . '%');
                }
                if ($nip != '') {
                    $query->where('b.nip', 'like', '%' . $nip . '%');
                }
                if ($id_skpd != '') {
                    $query->where('pengajuan_layanan.id_skpd', 'like', '%' . $id_skpd . '%');
                }
            })
            ->selectRaw(
                'pengajuan_layanan.*, 
                DATE_FORMAT(pengajuan_layanan.tanggal_pengajuan, "%d-%m-%Y") as tanggal_ajuan,
                b.nama_pegawai, 
                b.nip, 
                c.skpd'
            )
            ->orderBy('pengajuan_layanan.tanggal_pengajuan', 'asc');


        return $pengajuan ? $pengajuan->get() : [];
    }

    public function allProses()
    {
        $proses = ProsesLayananCuti::leftJoin('proses_layanan_cuti_detail as b', 'b.id_proses', '=', 'proses_layanan_cuti.id')
            ->selectRaw('proses_layanan_cuti.*,count(b.pengajuan_id) as jumlah')
            ->groupBy('proses_layanan_cuti.id')
            ->orderBy('id', 'asc')->get();
        return $proses ? $proses : [];
    }

    public function insertProses(array $data)
    {
        ProsesLayananCuti::create($data);
        return [
            'success' => true,
            'message' => 'Input Nama Proses Kerja Cuti berhasil ditambahkan'
        ];
    }

    public function getPegawaiByProses(string $id_proses)
    {
        $data =  ProsesLayananCutiDetail::Join('pengajuan_layanan as b', 'proses_layanan_cuti_detail.pengajuan_id', '=', 'b.id')
            ->leftJoin('layanan_cuti_detail as aa', 'aa.pengajuan_id', '=', 'b.id')
            ->leftJoin('layanan as a', 'a.id', '=', 'b.layanan_id')
            ->where('proses_layanan_cuti_detail.id_proses', $id_proses)
            ->Join('db_simpeg_medan_lama.tbl_pegawai as c', 'c.id_pegawai', '=', 'b.pegawai_id')
            ->leftJoin('db_simpeg_medan_lama.ref_skpd as d', 'd.id_skpd', '=', 'b.id_skpd')
            ->leftJoin('db_simpeg_medan_lama.ref_jabatan as e', 'e.id_jabatan', '=', 'b.id_jabatan')
            ->select('c.id_pegawai', 'c.nama_pegawai', 'c.nip', 'd.skpd', 'e.jabatan', 'a.nama_layanan', 'aa.tgl_mulai', 'aa.tgl_selesai', 'b.id as pengajuan_id', 'b.sk_path')
            ->get();

        return $data;
    }

    public function rekapCuti($mulai_tanggal, $sampai_tanggal, string $id_jenis_cuti, string $status)
    {
        $mulai = $mulai_tanggal ?? '';
        $sampai = $sampai_tanggal ?? '';
        $jenis_cuti = $id_jenis_cuti ?? 0;

        //ambil nama database dari config
        $secondDb = config('database.connections.mysql_second.database');

        if ($jenis_cuti != 0 || $mulai != '' || $sampai != '') {
            $sql = \DB::table('proses_layanan_cuti_detail as a')
                ->join('proses_layanan_cuti as aa', 'aa.id', '=', 'a.id_proses')
                ->join('pengajuan_layanan as b', 'b.id', '=', 'a.pengajuan_id')
                ->join("$secondDb.tbl_pegawai as pegawai", 'b.pegawai_id', '=', 'pegawai.id_pegawai')
                ->leftJoin("$secondDb.ref_jabatan as jabatan", 'jabatan.id_jabatan', '=', 'b.id_jabatan')
                ->leftJoin("$secondDb.ref_skpd as skpd", 'skpd.id_skpd', '=', 'b.id_skpd')
                ->join('layanan_cuti_detail as cuti_detail', 'cuti_detail.pengajuan_id', '=', 'a.pengajuan_id')
                ->leftJoin('layanan as layanan', 'layanan.id', '=', 'b.layanan_id')
                ->where(function ($query) use ($mulai, $sampai, $jenis_cuti, $status) {
                    if ($jenis_cuti != 0) {
                        $query->where('b.layanan_id', $jenis_cuti);
                    }
                    if ($mulai != '') {
                        $query->where('cuti_detail.tgl_mulai', '>=', $mulai);
                    }
                    if ($sampai != '') {
                        $query->where('cuti_detail.tgl_selesai', '<=', $sampai);
                    }
                    if ($status != '') {
                        $query->where('b.status_akhir', '=', $status);
                    }
                })
                ->selectRaw(
                    'b.*,
                 DATE_FORMAT(b.tanggal_pengajuan, "%Y-%m-%d") as tgl_ajuan,
                 pegawai.nama_pegawai,
                 pegawai.nip,
                 jabatan.jabatan,
                 skpd.skpd,
                 layanan.nama_layanan,
                 DATE_FORMAT(cuti_detail.tgl_mulai, "%Y-%m-%d") as tgl_mulai,
                 DATE_FORMAT(cuti_detail.tgl_selesai,"%Y-%m-%d") as tgl_selesai,
                 aa.nama_admin as nama_proses'
                )
                ->get();
        }

        return $sql ?? [];
    }

    public function KonversiJenisCuti(string $id_jenis)
    {
        $jenis = \DB::connection('mysql_second')->table('ref_jenis_cuti')
            ->where('id_konversi', $id_jenis)
            ->first();

        return $jenis ?? 0;
    }

    // public function RiwayatCuti(string $id_pegawai)
    // {
    //     $riwayat = \DB::connection('mysql_second')->table('tbl_cuti_riwayat as a')
    //         ->leftJoin('db_simpeg_medan_lama.ref_jenis_cuti as b', 'b.id_jenis_cuti', '=', 'a.id_jenis_cuti')
    //         ->where('a.id_pegawai', $id_pegawai)
    //         ->select('a.*', 'b.jenis_cuti');


    //     return $riwayat ? $riwayat->get() : 0;
    // }

    public function hitungCuti(string $id_pegawai) //switch dengan API
    {
        $year = date('Y');
        $last_year = $year - 1;
        $last_two_year = $year - 2;

        $hitung = \DB::connection('mysql_second')->table('tbl_cuti_riwayat')
            ->where('id_pegawai', $id_pegawai)
            ->where('id_jenis_cuti', 5)
            ->selectRaw('SUM(CASE WHEN year(dari) = ' . $year . ' AND year(sampai) = ' . $year . ' THEN lama ELSE 0 END) as total_cuti_now,
                        SUM(CASE WHEN year(dari) = ' . $last_year . ' AND year(sampai) = ' . $last_year . ' THEN lama ELSE 0 END) as total_cuti_last_year,
                        SUM(CASE WHEN year(dari) = ' . $last_two_year . ' AND year(sampai) = ' . $last_two_year . ' THEN lama ELSE 0 END) as total_cuti_last_two_year')
            ->first();

        $hitungan = [];
        $hitungan = [
            'total_cuti_now' => 12 - $hitung->total_cuti_now,
            'total_cuti_last_year' => 12 - $hitung->total_cuti_last_year,
            'total_cuti_last_two_year' => 12 - $hitung->total_cuti_last_two_year
        ];

        return array(
            'data' => $hitungan,
        );
        //return $hitung ? $hitung : 0;
    }

    public function getMasaKerja($id_pegawai) // switch dengan API
    {
        $diff = '';
        $dapeg = $this->getPegawai($id_pegawai);

        $stat_peg = $dapeg->id_status_kepegawaian ?? null;
        if ($stat_peg == 1 || $stat_peg == 2) {
            //PNS && CPNS    
            $mk = DataCpnsModel::where('id_pegawai', $id_pegawai)
                ->orWhere('NIP', $dapeg->nip)->first();

            $awal = $mk ? Carbon::parse($mk->TMTCPNS) : now();
            $akhir = now();

            //Calculate the difference
            $diff = $awal->diff($akhir);

            //$masa_kerja = "{$diff->y} Tahun {$diff->m} Bulan"
        } elseif ($stat_peg == 3) {
            //PPPK
            $mk = DataPnsModel::where('id_pegawai', $id_pegawai)
                ->orWhere('NIP', $dapeg->nip)->first();

            $awal = $mk ? Carbon::parse($mk->TMTPNS) : now();
            $akhir = now();

            //Calculate the difference
            $diff = $awal->diff($akhir);
        }
        return [
            'y' => $diff->y,
            'm' => $diff->m
        ];
    }
}
