<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\DataLaporan;
use App\Services\SimpegService;
//use Illuminate\Http\Request;

class LaporanDashController extends Controller
{
    protected $simpeg;
    public function __construct(SimpegService $simpeg)
    {
        $this->simpeg = $simpeg;
    }
    public function index()
    {
        $this->reloadData();
        $report = DataLaporan::where('status_aktif', 1)->get();

        $report2 = DataLaporan::whereIN('status_aktif', [1, 2])->get();
        $data = [];
        foreach ($report2 as $item) {
            if ($item->id_status == 2) {
                $data['pindah_masuk'] = $item->nilai_data;
            } elseif ($item->id_status == 3) {
                $data['pindah_keluar'] = $item->nilai_data;
            } elseif ($item->id_status == 4) {
                $data['pensiun_bup'] = $item->nilai_data;
            } elseif ($item->id_status == 8) {
                $data['hukdis_berat'] = $item->nilai_data;
            } elseif ($item->id_status == 9) {
                $data['hukdis_sedang'] = $item->nilai_data;
            } elseif ($item->id_status == 10) {
                $data['hukdis_ringan'] = $item->nilai_data;
            }
            //berdasarkan tipe kepegawaian
            elseif ($item->id_status == 16) {
                $data['cpns'] = $item->nilai_data;
            } elseif ($item->id_status == 17) {
                $data['pns'] = $item->nilai_data;
            } elseif ($item->id_status == 18) {
                $data['pppk'] = $item->nilai_data;
            } elseif ($item->id_status == 19) {
                $data['pppk_pw'] = $item->nilai_data;
            }
            //berdasarkan detail hukuman
            elseif ($item->id_status == 20) {
                $data['teguran_lisan'] = $item->nilai_data;
            } elseif ($item->id_status == 21) {
                $data['teguran_tertulis'] = $item->nilai_data;
            } elseif ($item->id_status == 22) {
                $data['perny_tidak_puas'] = $item->nilai_data;
            } elseif ($item->id_status == 23) {
                $data['tunda_gb1'] = $item->nilai_data;
            } elseif ($item->id_status == 24) {
                $data['tunda_pangkat1'] = $item->nilai_data;
            } elseif ($item->id_status == 25) {
                $data['turun_pangkat1'] = $item->nilai_data;
            } elseif ($item->id_status == 26) {
                $data['potong_gaji'] = $item->nilai_data;
            } elseif ($item->id_status == 27) {
                $data['pemberhentian'] = $item->nilai_data;
            } elseif ($item->id_status == 28) {
                $data['sanksi_berat_lain'] = $item->nilai_data;
            }
            //berdasarkan jenis kelamin
            elseif ($item->id_status == 29) {
                $data['laki2'] = $item->nilai_data;
            } elseif ($item->id_status == 30) {
                $data['perempuan'] = $item->nilai_data;
            }
            //data golongan utama
            elseif ($item->id_status == 31) {
                $data['asn_gol_1'] = $item->nilai_data;
            } elseif ($item->id_status == 32) {
                $data['asn_gol_2'] = $item->nilai_data;
            } elseif ($item->id_status == 33) {
                $data['asn_gol_3'] = $item->nilai_data;
            } elseif ($item->id_status == 34) {
                $data['asn_gol_4'] = $item->nilai_data;
            }
            //data golongan detail
            elseif ($item->id_status == 35) {
                $data['asn_1a'] = $item->nilai_data;
            } elseif ($item->id_status == 36) {
                $data['asn_1b'] = $item->nilai_data;
            } elseif ($item->id_status == 37) {
                $data['asn_1c'] = $item->nilai_data;
            } elseif ($item->id_status == 38) {
                $data['asn_1d'] = $item->nilai_data;
            } elseif ($item->id_status == 39) {
                $data['asn_2a'] = $item->nilai_data;
            } elseif ($item->id_status == 40) {
                $data['asn_2b'] = $item->nilai_data;
            } elseif ($item->id_status == 41) {
                $data['asn_2c'] = $item->nilai_data;
            } elseif ($item->id_status == 42) {
                $data['asn_2d'] = $item->nilai_data;
            } elseif ($item->id_status == 43) {
                $data['asn_3a'] = $item->nilai_data;
            } elseif ($item->id_status == 44) {
                $data['asn_3b'] = $item->nilai_data;
            } elseif ($item->id_status == 45) {
                $data['asn_3c'] = $item->nilai_data;
            } elseif ($item->id_status == 46) {
                $data['asn_3d'] = $item->nilai_data;
            } elseif ($item->id_status == 47) {
                $data['asn_4a'] = $item->nilai_data;
            } elseif ($item->id_status == 48) {
                $data['asn_4b'] = $item->nilai_data;
            } elseif ($item->id_status == 49) {
                $data['asn_4c'] = $item->nilai_data;
            } elseif ($item->id_status == 50) {
                $data['asn_4d'] = $item->nilai_data;
            } elseif ($item->id_status == 51) {
                $data['asn_4e'] = $item->nilai_data;
            }
            //berdasarkan tingkat pendidikan
            elseif ($item->id_status == 52) {
                $data['sd'] = $item->nilai_data;
            } elseif ($item->id_status == 53) {
                $data['smp'] = $item->nilai_data;
            } elseif ($item->id_status == 54) {
                $data['sma'] = $item->nilai_data;
            } elseif ($item->id_status == 55) {
                $data['d2d3'] = $item->nilai_data;
            } elseif ($item->id_status == 56) {
                $data['s1'] = $item->nilai_data;
            } elseif ($item->id_status == 57) {
                $data['s2'] = $item->nilai_data;
            } elseif ($item->id_status == 58) {
                $data['s3'] = $item->nilai_data;
            }
            //berdasarkan tipe jabatan
            elseif ($item->id_status == 59) {
                $data['struktural'] = $item->nilai_data;
            } elseif ($item->id_status == 60) {
                $data['fungsional'] = $item->nilai_data;
            } elseif ($item->id_status == 61) {
                $data['pelaksana'] = $item->nilai_data;
            }
            //gol pppk
            elseif ($item->id_status == 62) {
                $data['p3k_gol_i'] = $item->nilai_data;
            } elseif ($item->id_status == 63) {
                $data['p3k_gol_ii'] = $item->nilai_data;
            } elseif ($item->id_status == 64) {
                $data['p3k_gol_iii'] = $item->nilai_data;
            } elseif ($item->id_status == 65) {
                $data['p3k_gol_iv'] = $item->nilai_data;
            } elseif ($item->id_status == 66) {
                $data['p3k_gol_v'] = $item->nilai_data;
            } elseif ($item->id_status == 67) {
                $data['p3k_gol_vi'] = $item->nilai_data;
            } elseif ($item->id_status == 68) {
                $data['p3k_gol_vii'] = $item->nilai_data;
            } elseif ($item->id_status == 69) {
                $data['p3k_gol_viii'] = $item->nilai_data;
            } elseif ($item->id_status == 70) {
                $data['p3k_gol_ix'] = $item->nilai_data;
            } elseif ($item->id_status == 71) {
                $data['p3k_gol_x'] = $item->nilai_data;
            } elseif ($item->id_status == 72) {
                $data['p3k_gol_xi'] = $item->nilai_data;
            } elseif ($item->id_status == 73) {
                $data['p3k_gol_xii'] = $item->nilai_data;
            } elseif ($item->id_status == 74) {
                $data['p3k_gol_xiii'] = $item->nilai_data;
            } elseif ($item->id_status == 75) {
                $data['p3k_gol_xiv'] = $item->nilai_data;
            } elseif ($item->id_status == 76) {
                $data['p3k_gol_xv'] = $item->nilai_data;
            } elseif ($item->id_status == 77) {
                $data['p3k_gol_xvi'] = $item->nilai_data;
            } elseif ($item->id_status == 78) {
                $data['p3k_gol_xvii'] = $item->nilai_data;
            }
            //berdasarkan tipe jabatan
            elseif ($item->id_status == 79) {
                $data['nakes'] = $item->nilai_data;
            } elseif ($item->id_status == 80) {
                $data['guru'] = $item->nilai_data;
            } elseif ($item->id_status == 81) {
                $data['teknis'] = $item->nilai_data;
            }
        }

        $akumulasi = DataLaporan::where('status_aktif', 1)
            ->selectRaw(
                'SUM(if(laporan_data.kategori="ASN", laporan_data.nilai_data, 0)) as total_asn,
            SUM(if(laporan_data.kategori="Mutasi", laporan_data.nilai_data, 0)) as total_mutasi,
            SUM(if(laporan_data.kategori="Pemberhentian", laporan_data.nilai_data, 0)) as total_berhenti,
            SUM(if(laporan_data.kategori="Hukuman", laporan_data.nilai_data, 0)) as total_hukuman'
            )
            ->first();


        $total = $data['cpns'] + $data['pns'] + $data['pppk'] + $data['pppk_pw'];
        $data['total'] = $total;
        //dd($report, $data);
        return view('pages.laporan_utama.dashboardpimpinan', compact('report', 'data', 'akumulasi'));
    }

    public function reloadData()
    {

        $data = $this->simpeg->getDataLaporan();
        //dd($data);
        $nilai_1 = $data['total']; //jlh_asn
        $nilai_2 = $data['pindah_masuk']; //$mutasi_masuk
        $nilai_3 = $data['pindah_keluar']; // //$mutasi_keluar
        $nilai_4 = $data['pensiun_bup']; //$pensiun
        $nilai_5 = $data['pensiun_hormat']; //0; //$berhenti_dengan_hormat_tanpa_permintaan = 0;
        $nilai_6 = $data['pensiun_sendiri']; //0; //$berhenti_undur_diri = 0;
        $nilai_7 = $data['meninggal']; //$meninggal_dunia
        $nilai_8 = $data['disiplin_berat']; //$disiplin_berat = 0;
        $nilai_9 = $data['disiplin_sedang']; //$disiplin_sedang = 0;
        $nilai_10 = $data['disiplin_ringan']; //$disiplin_ringan = 0;
        $nilai_11 = $data['cltn'];
        $nilai_12 = $data['cuti_besar'];
        $nilai_13 = $data['tugas_belajar']; //$tugas_belajar = 0;
        $nilai_14 = $data['tugas_keluar']; //$tugas_keluar = 0;
        $nilai_15 = $data['tugas_kedalam']; //$tugas_kedalam = 0;
        $nilai_16 = $data['cpns'];
        $nilai_17 = $data['pns'];
        $nilai_18 = $data['pppk'];
        $nilai_19 = $data['pppk_pw'];
        $nilai_20 = $data['teguran_lisan'];
        $nilai_21 = $data['teguran_tertulis'];
        $nilai_22 = $data['perny_tidak_puas'];
        $nilai_23 = $data['tunda_gb1'];
        $nilai_24 = $data['tunda_pangkat1'];
        $nilai_25 = $data['turun_pangkat1'];
        $nilai_26 = $data['potong_gaji'];
        $nilai_27 = $data['pemberhentian'];
        $nilai_28 = $data['sanksi_berat_lain'];
        $nilai_29 = $data['laki-laki'];
        $nilai_30 = $data['perempuan'];
        $nilai_31 = $data['gol_1'];
        $nilai_32 = $data['gol_2'];
        $nilai_33 = $data['gol_3'];
        $nilai_34 = $data['gol_4'];
        $nilai_35 = $data['gol_1a'];
        $nilai_36 = $data['gol_1b'];
        $nilai_37 = $data['gol_1c'];
        $nilai_38 = $data['gol_1d'];
        $nilai_39 = $data['gol_2a'];
        $nilai_40 = $data['gol_2b'];
        $nilai_41 = $data['gol_2c'];
        $nilai_42 = $data['gol_2d'];
        $nilai_43 = $data['gol_3a'];
        $nilai_44 = $data['gol_3b'];
        $nilai_45 = $data['gol_3c'];
        $nilai_46 = $data['gol_3d'];
        $nilai_47 = $data['gol_4a'];
        $nilai_48 = $data['gol_4b'];
        $nilai_49 = $data['gol_4c'];
        $nilai_50 = $data['gol_4d'];
        $nilai_51 = $data['gol_4e'];
        //tingkat Pendidikan
        $nilai_52 = $data['sd'];
        $nilai_53 = $data['smp'];
        $nilai_54 = $data['sma'];
        $nilai_55 = $data['d2d3'];
        $nilai_56 = $data['s1'];
        $nilai_57 = $data['s2'];
        $nilai_58 = $data['s3'];
        //tipe jabatan
        $nilai_59 = $data['struktural'];
        $nilai_60 = $data['fungsional'];
        $nilai_61 = $data['pelaksana'];
        //gol pppk
        $nilai_62 = $data['gol_i'];
        $nilai_63 = $data['gol_ii'];
        $nilai_64 = $data['gol_iii'];
        $nilai_65 = $data['gol_iv'];
        $nilai_66 = $data['gol_v'];
        $nilai_67 = $data['gol_vi'];
        $nilai_68 = $data['gol_vii'];
        $nilai_69 = $data['gol_viii'];
        $nilai_70 = $data['gol_ix'];
        $nilai_71 = $data['gol_x'];
        $nilai_72 = $data['gol_xi'];
        $nilai_73 = $data['gol_xii'];
        $nilai_74 = $data['gol_xiii'];
        $nilai_75 = $data['gol_xiv'];
        $nilai_76 = $data['gol_xv'];
        $nilai_77 = $data['gol_xvi'];
        $nilai_78 = $data['gol_xvii'];
        //jabfungsional
        $nilai_79 = $data['nakes'];
        $nilai_80 = $data['guru'];
        $nilai_81 = $data['teknis'];


        $dalap = DataLaporan::orderBy('id', 'asc');
        $i = 1;
        $count = $dalap->count();
        $dalap = $dalap->get();
        foreach ($dalap as $data) {
            if ($i <= $count && $data->id_status == $i) {
                $query = DataLaporan::where('id_status', $i)->first();
                if ($query) {
                    $query->update([
                        'nilai_data' => ${"nilai_" . $i}
                    ]);
                }
                $i++;
            }
        }

        // return view('pages.laporan_utama.dashboardpimpinan', compact('dalap'));
    }

    public function detailLaporan($id_data)
    {
        $datarequest = [];
        $judul = '';

        $cek_nilai = DataLaporan::where('id', $id_data)->first();
        $judul = $id_data == 1 ? $cek_nilai->nama_data . ' ' . tglindonesia(date('Y-m-d')) : $cek_nilai->nama_data . ' ' . date('Y');

        if ($id_data == 1) {
            //1. Jumlah Seluruh ASN aktif            
            $pegawai_aktif = 1;
        } elseif ($id_data == 2) {
            //2. ASN Mutasi Pindah Masuk
            $pensiun = 1; //hanya penanda untuk menggunakan data di riwayat_jabatan
            $id_ref_pensiun = 4;
        } elseif ($id_data == 3) {
            //3. ASN Mutasi Pindah Keluar
            $pensiun = 1; //hanya penanda untuk menggunakan data di riwayat_jabatan
            $id_ref_pensiun = 5;
        } elseif ($id_data == 4) {
            //4. ASN Pensiun BUP
            $pensiun = 1;
            $id_ref_pensiun = 6;
        } elseif ($id_data == 5) {
            //5. ASN Pemberhentian Dengan Hormat Tidak Atas Permintaan Sendiri
            $pensiun = 1;
            $id_ref_pensiun = 11;
        } elseif ($id_data == 6) {
            //6. ASN Pensiun Atas Permintaan Sendiri
            $pensiun = 1;
            $id_ref_pensiun = 8;
        } elseif ($id_data == 7) {
            //7. ASN Meninggal Dunia
            $pensiun = 1;
            $id_ref_pensiun = 13;
        } elseif ($id_data == 8) {
            //8. ASN Hukuman Disiplin Berat
            $hukuman = 1;
            $id_jenis_hukuman = 3;
        } elseif ($id_data == 9) {
            //9. ASN Hukuman Disiplin Sedang
            $hukuman = 1;
            $id_jenis_hukuman = 2;
        } elseif ($id_data == 10) {
            //10. ASN Hukuman Disiplin Ringan
            $hukuman = 1;
            $id_jenis_hukuman = 1;
        } elseif ($id_data == 11) {
            //11. ASN CLTN
            $hukuman = 1;
            $id_jenis_hukuman = 1;
        } elseif ($id_data == 12) {
            //12. ASN Cuti Besar
            $hukuman = 1;
            $id_jenis_hukuman = 1;
        } elseif ($id_data == 13) {
            //13. ASN Tugas Belajar
            //$pensiun = 1;
            $id_ref_pensiun = 17;
        } elseif ($id_data == 14) {
            //14. ASN Penugasan keluar Kota Medan
            $pensiun = 1;
            $id_ref_pensiun = 17;
        } elseif ($id_data == 15) {
            //15. ASN Penugasan kedalam Kota Medan
            $pensiun = 1;
            $id_ref_pensiun = 17;
        }


        if ($cek_nilai->nilai_data > 0) {
            $datarequest = [
                'nip' => $nip ?? '',
                'nama_pegawai' => $nama_pegawai ?? '',
                'id_skpd' => $id_skpd ?? '',
                'id_jabatan_pelaksana' => $id_jabatan_pelaksana ?? '',
                'id_jabatan_fungsional' => $id_jabatan_fungsional ?? '',
                'id_jenis_kelamin' => $id_jenis_kelamin ?? '',
                'id_jenjang_jabatan' => $id_jenjang_jabatan ?? '',
                'nama_sekolah' => $nama_sekolah ?? '',
                'nama_jabatan' => $nama_jabatan ?? '',
                'jurusan' => $jurusan ?? '',
                'id_pangkat' => $id_pangkat ?? '',
                'id_agama' => $id_agama ?? '',
                'id_eselon' => $id_eselon ?? '',
                'tingkat_pendidikan' => $tingkat_pendidikan ?? '',
                'status_tk_pendidikan' => '1',
                'id_tipe_jabatan' => $id_tipe_jabatan ?? '',
                'id_jabatan_struktural' => $id_jabatan_struktural ?? '',
                'id_status_pegawai' => $id_status_pegawai ?? '',
                'id_kedudukan_kepegawaian' => $id_kedudukan_kepegawaian ?? '',
                'jenis_jabatan_struktural' => $jenis_jabatan_struktural  ?? '',
                'pegawai_aktif' => $pegawai_aktif ?? '',
                'pensiun' => $pensiun ?? '',
                'id_ref_pensiun' => $id_ref_pensiun ?? '',
                'hukuman' => $hukuman ?? '',
                'id_jenis_hukuman' => $id_jenis_hukuman ?? '',

            ];


            $page = $request->page ?? 1;
            $perPage = $request->per_page ?? 10;

            $result = $this->simpeg->filterData(
                $datarequest,
                $page,
                $perPage
            );

            if (!$result['success']) {
                return response()->json($result, 500);
            }
        } else {
            $dapeg = [];
        }


        $dapeg = $result['data']['results'] ?? [];

        return view('pages.laporan_utama.laporanDetail', compact('dapeg', 'judul'));
    }
}
