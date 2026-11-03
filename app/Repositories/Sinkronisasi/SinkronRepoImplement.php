<?php

namespace App\Repositories\Sinkronisasi;

use App\Models\DapriModel;
use App\Models\DataAnakModel;
use App\Models\DataCpnsModel;
use App\Models\DataPnsModel;
use App\Models\PasanganModel;
use App\Models\PegawaiUploadModel;
use App\Models\Pengguna;
use App\Models\PenggunaLama;
use App\Models\RiwCutiModel;
use App\Models\RiwDiklatLainnya;
use App\Models\RiwDiklatStruktural;
use App\Models\RiwGajiBerkala;
use App\Models\RiwHukumanDisiplin;
use App\Models\RiwJabatanModel;
use App\Models\RiwPangkatModel;
use App\Models\RiwPendidikanModel;
use App\Models\RiwPenghargaan;
use App\Models\RiwPenyIjazah;
use App\Models\RiwPmkModel;
use App\Models\SinkronDataModel;
use App\Models\UploadAnakModel;
use App\Models\UploadDetailModel;
use App\Repositories\Sinkronisasi\SinkronRepo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class SinkronRepoImplement implements SinkronRepo
{
    public function ambilData($id_data, $urldata, $tgl_update)
    {
        ini_set('max_execution_time', 0);
        $response = Http::timeout(1000)->asForm()->post(
            config('services.simpeg.url') . '/sinkron-data',
            [
                'key' => config('services.simpeg.key'),
                'nama_data' => $urldata,
                'tgl_update' => $tgl_update
            ]
        );

        if (!$response->successful()) {

            return [
                'success' => false,
                'message' => 'Server SIMPEG tidak merespon'
            ];
        }

        if ($id_data <= 1 || $id_data >= 3 && $id_data <= 9 || $id_data >= 11 && $id_data <= 14) {
            return [
                'success' => true,
                'total' => $response->json()['total'],
                'data' => $response->json()['data'],
                'data-upload' => $response->json()['data-upload']
            ];
        } elseif ($id_data == 2) {
            return [
                'success' => true,
                'total' => $response->json()['total-data-pasangan'],
                'total-anak' => $response->json()['total-data-anak'],
                'data-pasangan' => $response->json()['data-pasangan'],
                'data-anak' => $response->json()['data-anak'],
                'data-anak-upload' => $response->json()['data-upload-anak']
            ];
        } elseif ($id_data == 10) {
            return [
                'success' => true,
                'total-diklat-str' => $response->json()['total-data-diklat-str'],
                'total-diklat-lain' => $response->json()['total-data-diklat-lain'],
                'data-diklat-str' => $response->json()['data-diklat-struktural'],
                'data-diklat-lain' => $response->json()['data-diklat-lainnya'],
                'data-upload-str' => $response->json()['data-upload-diklat-struktural'],
                'data-upload-lain' => $response->json()['data-upload-diklat-lainnya']
            ];
        } elseif ($id_data == 15) {
            return [
                'success' => true,
                'total' => $response->json()['total'],
                'data' => $response->json()['data']
            ];
        }
    }

    public function UploadDetail($data)
    {
        foreach ($data as $item) {
            $data_upload = [
                'id' => $item['id'],
                'id_pegawai' => $item['id_pegawai'],
                'id_jenis' => $item['id_jenis'],
                'id_riwayat' => $item['id_riwayat'],
                'scan_sk' => $item['scan_sk'],
                'catatan_upload' => $item['catatan_upload'],
                'scan_temp' => $item['scan_temp']
            ];

            UploadDetailModel::createorupdate($data_upload);
        }
    }

    public function sinkrondata($id_data)
    {
        $tgl_update = date('2026-04-01'); // tanggal awal dump database
        if ($id_data == 13) { // perlakuan khusus riwayat cuti
            $tgl_update = date('2026-04-16');
        }
        $sinkrondata = SinkronDataModel::where('id', $id_data)->first();
        if ($sinkrondata->updated_at >= $tgl_update) {
            $tgl_update = Carbon::parse($sinkrondata->updated_at)->format('Y-m-d');
        }

        $result = $this->ambilData($id_data, $sinkrondata->nama_url, $tgl_update);

        if ($id_data == 1) {
            //Data pribadi
            if ($result['success'] == true) {
                $total_data = $result['total'];
                foreach ($result['data'] as $item) {
                    $dapri = [
                        'id_pegawai' => $item['id_pegawai'],
                        'nip' => $item['nip'],
                        'nip_lama' => $item['nip_lama'],
                        'nama_pegawai' => $item['nama_pegawai'],
                        'ktp' => $item['ktp'],
                        'taspen' => $item['taspen'],
                        'no_karpeg' => $item['no_karpeg'],
                        'no_askes' => $item['no_askes'],
                        'foto' => $item['foto'],
                        'id_status_kepegawaian' => $item['id_status_kepegawaian'],
                        'id_jenis_kepegawaian' => $item['id_jenis_kepegawaian'],
                        'id_kedudukan_kepegawaian' => $item['id_kedudukan_kepegawaian'],
                        'id_instansi' => $item['id_instansi'],
                        'id_satuan_organisasi' => $item['id_satuan_organisasi'],
                        'id_pangkat' => $item['id_pangkat'],
                        'id_tipe_jabatan' => $item['id_tipe_jabatan'],
                        'id_jabatan' => $item['id_jabatan'],
                        'gelar_depan' => $item['gelar_depan'],
                        'gelar_belakang' => $item['gelar_belakang'],
                        'id_golongan_darah' => $item['id_golongan_darah'],
                        'id_jenis_kelamin' => $item['id_jenis_kelamin'],
                        'tempat_lahir' => $item['tempat_lahir'],
                        'tanggal_lahir' => $item['tanggal_lahir'] == '0000-00-00' ? null : $item['tanggal_lahir'],
                        'alamat' => $item['alamat'],
                        'rt' => $item['rt'],
                        'rw' => $item['rw'],
                        'marga' => $item['marga'],
                        'id_suku' => $item['id_suku'],
                        'id_agama' => $item['id_agama'],
                        'id_provinsi' => $item['id_provinsi'],
                        'id_kabupaten' => $item['id_kabupaten'],
                        'id_kecamatan' => $item['id_kecamatan'],
                        'id_kelurahan' => $item['id_kelurahan'],
                        'kode_pos' => $item['kode_pos'],
                        'no_telp' => $item['no_telp'],
                        'no_hp' => $item['no_hp'],
                        'email' => $item['email'],
                        'nama_ayah' => $item['nama_ayah'],
                        'nama_ibu' => $item['nama_ibu'],
                        'id_status_pernikahan' => $item['id_status_pernikahan'],
                        'npwp' => $item['npwp'],
                        'tinggi' => $item['tinggi'],
                        'berat' => $item['berat'],
                        'id_rambut' => $item['id_rambut'],
                        'id_bentuk_muka' => $item['id_bentuk_muka'],
                        'id_warna_kulit' => $item['id_warna_kulit'],
                        'ciri_khas' => $item['ciri_khas'],
                        'cacat_tubuh' => $item['cacat_tubuh'],
                        'hobi' => $item['hobi'],
                        'tipe_pegawai' => $item['tipe_pegawai'],
                        'status_supervisi' => $item['status_supervisi'],
                        'posisi_data' => $item['posisi_data'],
                        'id_pembuat' => $item['id_pembuat'],
                        'status' => $item['status'],
                        'catatan' => $item['catatan'],
                        'id_pendidikan' => $item['id_pendidikan'],
                        'id_jenjang_jabfung' => $item['id_jenjang_jabfung'],
                        'id_pegawai_bkn' => $item['id_pegawai_bkn'],
                        'id_pengadaan' => $item['id_pengadaan'] ?? null,
                        'id_hukuman' => $item['id_hukuman'],
                        'jam_jp' => $item['jam_jp']
                    ];

                    $update = DapriModel::createorupdate($dapri);
                }

                foreach ($result['data-upload'] as $dupload) {
                    $dataupload = [
                        'id_pegawai' => $dupload['id_pegawai'],
                        'pasfoto' => $dupload['pasfoto'],
                        'karpeg' => $dupload['karpeg'],
                        'scan_ktp' => $dupload['scan_ktp'],
                        'scan_konversi_nip' => $dupload['scan_konversi_nip'],
                        'scan_npwp' => $dupload['scan_npwp'],
                        'scan_taspen' => $dupload['scan_taspen'],
                        'akta_kelahiran' => $dupload['akta_kelahiran'],
                        'sk_pencantuman_gelar' => $dupload['sk_pencantuman_gelar'],
                        'askes_pribadi' => $dupload['askes_pribadi']
                    ];

                    PegawaiUploadModel::createorupdate($dataupload);
                }

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total_data . ' Data Pribadi berhasil disinkronisasi'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Pribadi Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 2) {
            //Data keluarga            
            if ($result['success'] == true) {
                $total_data = $result['total'];
                $total_anak = $result['total-anak'];
                //1.Data Pasangan
                foreach ($result['data-pasangan'] as $item) {
                    $pasangan = [
                        'id_pasangan' => $item['id_pasangan'],
                        'id_pegawai' => $item['id_pegawai'],
                        'status_pernikahan' => $item['status_pernikahan'],
                        'pasangan_ke' => $item['pasangan_ke'],
                        'id_pekerjaan' => $item['id_pekerjaan'],
                        'status_hidup' => $item['status_hidup'],
                        'nama_pasangan' => $item['nama_pasangan'],
                        'tempat_lahir' => $item['tempat_lahir'],
                        'tgl_lahir' => $item['tgl_lahir'],
                        'id_jenis_kelamin' => $item['id_jenis_kelamin'],
                        'id_agama' => $item['id_agama'],
                        'id_pendidikan' => $item['id_pendidikan'],
                        'pekerjaan' => $item['pekerjaan'],
                        'no_telp' => $item['no_telp'],
                        'nik_pasangan' => $item['nik_pasangan'],
                        'no_akta_nikah' => $item['no_akta_nikah'],
                        'tgl_nikah' => $item['tgl_nikah'],
                        'no_akta_cerai' => $item['no_akta_cerai'],
                        'tgl_cerai' => $item['tgl_cerai'] == '0000-00-00' ? null : $item['tgl_cerai'],
                        'no_akta_meninggal' => $item['no_akta_meninggal'],
                        'tgl_meninggal' => $item['tgl_meninggal'] == '0000-00-00' ? null : $item['tgl_meninggal'],
                        'alamat' => $item['alamat'],
                        'rt' => $item['rt'],
                        'rw' => $item['rw'],
                        'id_provinsi' => $item['id_provinsi'],
                        'id_kabupaten' => $item['id_kabupaten'],
                        'id_kecamatan' => $item['id_kecamatan'],
                        'id_kelurahan' => $item['id_kelurahan'],
                        'kode_pos' => $item['kode_pos'],
                        'surat_nikah' => $item['surat_nikah'],
                        'kartu_keluarga' => $item['kartu_keluarga'],
                        'karisu' => $item['karisu'],
                        'askes' => $item['askes'],
                        'akta_meninggal' => $item['akta_meninggal'],
                        'akta_cerai' => $item['akta_cerai'],
                        'status_supervisi' => $item['status_supervisi']
                    ];

                    PasanganModel::createorupdate($pasangan);
                }

                //2. Data Anak
                foreach ($result['data-anak'] as $item) {
                    $dataanak = [
                        'id_anak' => $item['id_anak'],
                        'id_pegawai' => $item['id_pegawai'],
                        'nama_anak' => $item['nama_anak'],
                        'id_jenis_kelamin' => $item['id_jenis_kelamin'],
                        'tempat_lahir' => $item['tempat_lahir'],
                        'tgl_lahir' => $item['tgl_lahir'] == '0000-00-00' ? null : $item['tgl_lahir'],
                        'pekerjaan' => $item['pekerjaan'],
                        'pendidikan' => $item['pendidikan'],
                        'nama_ayah' => $item['nama_ayah'],
                        'nama_ibu' => $item['nama_ibu'],
                        'status_keluarga' => $item['status_keluarga'],
                        'status_tunjangan' => $item['status_tunjangan'],
                        'status_supervisi' => $item['status_supervisi'],
                        'id_bkn_anak' => $item['id_bkn_anak']
                    ];

                    DataAnakModel::createorupdate($dataanak);
                }

                //3. Data upload anak
                foreach ($result['data-anak-upload'] as $item) {
                    $uploadanak = [
                        'id_tabel' => $item['id_tabel'],
                        'id_pegawai' => $item['id_pegawai'],
                        'id_anak' => $item['id_anak'],
                        'scan_askes_anak' => $item['scan_askes_anak'],
                        'status' => $item['status']
                    ];

                    UploadAnakModel::createorupdate($uploadanak);
                }

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total_data . ' Data Pasangan, dan ' . $total_anak . ' Data Anak berhasil disinkronisasi'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Keluarga Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 3) {
            //Data Pengangkatan CPNS
            if ($result['success'] == true) {
                $total = $result['total'];
                //1.Data CPNS
                foreach ($result['data'] as $item) {
                    $cpns = [
                        'id_tblcpns' => $item['id_tblcpns'],
                        'id_pegawai' => $item['id_pegawai'],
                        'NIP' => $item['NIP'],
                        'NoNota' => $item['NoNota'],
                        'TanggalNota' => $item['TanggalNota'] == '0000-00-00' ? null : $item['TanggalNota'],
                        'NoSK' => $item['NoSK'],
                        'TanggalSK' => $item['TanggalSK'] == '0000-00-00' ? null : $item['TanggalSK'],
                        'PejabatSK' => $item['PejabatSK'],
                        'KodePangkat' => $item['KodePangkat'],
                        'MKGTahun' => $item['MKGTahun'],
                        'MKGBulan' => $item['MKGBulan'],
                        'TMTCPNS' => $item['TMTCPNS'],
                        'KodeTkPendidikan' => $item['KodeTkPendidikan'],
                        'Jurusan' => $item['Jurusan'],
                        'NamaJabatan' => $item['NamaJabatan'],
                        'NamaSKPD' => $item['NamaSKPD'],
                        'NoSPMT' => $item['NoSPMT'],
                        'TglSPMT' => $item['TglSPMT'],
                        'id_pendidikan_cpns' => $item['id_pendidikan_cpns'],
                        'id_detail_pendidikan' => $item['id_detail_pendidikan'],
                        'id_pengadaan' => $item['id_pengadaan'],
                        'status_supervisi' => $item['status_supervisi']
                    ];

                    DataCpnsModel::createorupdate($cpns);
                }

                //2.Data Upload CPNS
                foreach ($result['data-upload'] as $item) {
                    $cpns_upload = [
                        'id_pegawai' => $item['id_pegawai'],
                        'sk_cpns' => $item['sk_cpns']
                    ];

                    PegawaiUploadModel::createorupdate($cpns_upload);
                }

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total . ' Data Pengangkatan CPNS berhasil disinkronisasi'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Pengangkatan CPNS Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 4) {
            //Data Pengangkatan PNS
            if ($result['success'] == true) {
                $total = $result['total'];
                //1.Data PNS
                foreach ($result['data'] as $item) {
                    $pns = [
                        'id' => $item['id'],
                        'id_pegawai' => $item['id_pegawai'],
                        'NIP' => $item['NIP'],
                        'NoSK' => $item['NoSK'],
                        'TanggalSK' => $item['TanggalSK'] == '0000-00-00' ? null : $item['TanggalSK'],
                        'PejabatSK' => $item['PejabatSK'],
                        'NoKetKesehatan' => $item['NoKetKesehatan'],
                        'TanggalKetKesehatan' => $item['TanggalKetKesehatan'] == '0000-00-00' ? null : $item['TanggalKetKesehatan'],
                        'NoSTTPLPrajab' => $item['NoSTTPLPrajab'],
                        'TanggalSTTPLPrajab' => $item['TanggalSTTPLPrajab'] == '0000-00-00' ? null : $item['TanggalSTTPLPrajab'],
                        'TMTPNS' => $item['TMTPNS'] == '0000-00-00' ? null : $item['TMTPNS'],
                        'KodePangkat' => $item['KodePangkat'],
                        'MKGTahun' => $item['MKGTahun'],
                        'MKGBulan' => $item['MKGBulan'],
                        'NoNota' => $item['NoNota'],
                        'TanggalNota' => $item['TanggalNota'] == '0000-00-00' ? null : $item['TanggalNota'],
                        'id_pengadaan' => $item['id_pengadaan'],
                        'no_sk_spj' => $item['no_sk_spj'],
                        'tgl_sk_spj' => $item['tgl_sk_spj'] == '0000-00-00' ? null : $item['tgl_sk_spj'],
                        'status_spv' => $item['status_spv']
                    ];

                    DataPnsModel::createorupdate($pns);
                }

                //2.Data Upload PNS
                foreach ($result['data-upload'] as $item) {
                    $pns_upload = [
                        'id_pegawai' => $item['id_pegawai'],
                        'sk_pns' => $item['sk_pns']
                    ];

                    PegawaiUploadModel::createorupdate($pns_upload);
                }

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total . ' Data Pengangkatan PNS berhasil disinkronisasi.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Pengangkatan PNS Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 5) {
            //Riwayat Pendidikan
            if ($result['success'] == true) {
                $total = $result['total'];
                //1.Data Riwayat Pendidikan
                foreach ($result['data'] as $item) {
                    $pend = [
                        'id_pendidikan' => $item['id_pendidikan'],
                        'id_pegawai' => $item['id_pegawai'],
                        'id_tingkat_pendidikan' => $item['id_tingkat_pendidikan'],
                        'id_detail_pendidikan' => $item['id_detail_pendidikan'],
                        'id_jurusan' => $item['id_jurusan'],
                        'nama_sekolah' => $item['nama_sekolah'],
                        'tempat' => $item['tempat'],
                        'nama_kepala' => $item['nama_kepala'],
                        'no_ijazah' => $item['no_ijazah'],
                        'tanggal_ijazah' => $item['tanggal_ijazah'] == '0000-00-00' ? null : $item['tanggal_ijazah'],
                        'ip' => $item['ip'],
                        'img_ijazah' => $item['img_ijazah'],
                        'keterangan' => $item['keterangan'],
                        'status_supervisi' => $item['status_supervisi'],
                        'catatan_dari_bkd' => $item['catatan_dari_bkd']
                    ];

                    RiwPendidikanModel::createorupdate($pend);
                }

                //2.Riwayat Pendidikan Upload
                $this->UploadDetail($result['data-upload']);

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total . ' Data Riwayat Pendidikan berhasil disinkronisasi.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Riwayat Pendidikan Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 6) {
            //Riwayat Gaji Berkala
            if ($result['success'] == true) {
                $total = $result['total'];
                //1.Data Riwayat Gaji Berkala
                foreach ($result['data'] as $item) {
                    $gajiberkala = [
                        'id_riwayat_gaji' => $item['id_riwayat_gaji'],
                        'id_pegawai' => $item['id_pegawai'],
                        'NIP' => $item['NIP'],
                        'TMTGaji' => $item['TMTGaji'],
                        'NoSKGaji' => $item['NoSKGaji'],
                        'TglSKGaji' => $item['TglSKGaji'],
                        'PejabatSK' => $item['PejabatSK'],
                        'tgl_sk_usulan' => $item['tgl_sk_usulan'] == '0000-00-00' ? null : $item['tgl_sk_usulan'],
                        'mkg_tahun' => $item['mkg_tahun'],
                        'mkg_bulan' => $item['mkg_bulan'],
                        'Keterangan' => $item['Keterangan'],
                        'status_spv' => $item['status_spv'],
                        'catatan_dari_bkd' => $item['catatan_dari_bkd'] ?? '',
                        'status_sync' => $item['status_sync'],
                        'tanggal_sync' => $item['tanggal_sync'] == '0000-00-00 00:00:00' ? null : $item['tanggal_sync']
                    ];
                    RiwGajiBerkala::createorupdate($gajiberkala);
                }

                //2.Riwayat Gaji Berkala Upload
                $this->UploadDetail($result['data-upload']);

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total . ' Data Riwayat Gaji Berkala berhasil disinkronisasi.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Riwayat Gaji Berkala Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 7) {
            //Riwayat Pangkat
            if ($result['success'] == true) {
                $total = $result['total'];
                //1.Data Riwayat Pangkat
                foreach ($result['data'] as $item) {
                    $pangkat = [
                        'id_riwayat_pangkat' => $item['id_riwayat_pangkat'],
                        'id_pegawai' => $item['id_pegawai'],
                        'id_pangkat' => $item['id_pangkat'],
                        'tmt' => $item['tmt'],
                        'no_sk' => $item['no_sk'],
                        'tgl_sk' => $item['tgl_sk'] == '0000-00-00' ? null : $item['tgl_sk'],
                        'pejabat_penetapan' => $item['pejabat_penetapan'],
                        'img_sk' => $item['img_sk'],
                        'urut' => $item['urut'],
                        'mkg_tahun' => $item['mkg_tahun'],
                        'mkg_bulan' => $item['mkg_bulan'],
                        'no_bkn' => $item['no_bkn'],
                        'tgl_bkn' => $item['tgl_bkn'] == '0000-00-00' ? null : $item['tgl_bkn'],
                        'keterangan' => $item['keterangan'],
                        'angka_kredit' => $item['angka_kredit'],
                        'status_supervisi' => $item['status_supervisi'],
                        'catatan_dari_bkd' => $item['catatan_dari_bkd'],
                        'no_sk_lama' => $item['no_sk_lama'],
                        'tgl_sk_lama' => $item['tgl_sk_lama'] == '0000-00-00' ? null : $item['tgl_sk_lama'],
                    ];

                    RiwPangkatModel::createorupdate($pangkat);
                }

                //2.Riwayat Pangkat Upload
                $this->UploadDetail($result['data-upload']);

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total . ' Data Riwayat Pangkat berhasil disinkronisasi.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Riwayat Pangkat Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 8) {
            //Riwayat Jabatan
            if ($result['success'] == true) {
                $total = $result['total'];
                //1.Data Riwayat Jabatan
                foreach ($result['data'] as $item) {
                    $jabatan = [
                        'id_riwayat_jabatan' => $item['id_riwayat_jabatan'],
                        'id_pegawai' => $item['id_pegawai'],
                        'id_jabatan' => $item['id_jabatan'],
                        'jabatan' => $item['jabatan'],
                        'id_tipe_jabatan' => $item['id_tipe_jabatan'],
                        'id_jab_ganda' => $item['id_jab_ganda'],
                        'id_plt' => $item['id_plt'],
                        'id_keterangan_jabatan' => $item['id_keterangan_jabatan'],
                        'id_eselon' => $item['id_eselon'],
                        'id_skpd' => $item['id_skpd'],
                        'skpd' => $item['skpd'],
                        'id_unit' => $item['id_unit'],
                        'sub_unit' => $item['sub_unit'],
                        'tmt' => $item['tmt'] == '0000-00-00' ? null : $item['tmt'],
                        'tmt_akhir' => $item['tmt_akhir'] == '0000-00-00' ? null : $item['tmt_akhir'],
                        'gol_pppk' => $item['gol_pppk'],
                        'no_sk_jabatan' => $item['no_sk_jabatan'],
                        'tgl_sk_jabatan' => $item['tgl_sk_jabatan'],
                        'pejabat_penetapan' => $item['pejabat_penetapan'],
                        'no_sk_pelantikan' => $item['no_sk_pelantikan'],
                        'tgl_sk_pelantikan' => $item['tgl_sk_pelantikan'] == '0000-00-00' ? null : $item['tgl_sk_pelantikan'],
                        'pejabat_pelantik' => $item['pejabat_pelantik'],
                        'sumpah_jabatan' => $item['sumpah_jabatan'],
                        'img_sk_jabatan' => $item['img_sk_jabatan'],
                        'img_sk_pelantikan' => $item['img_sk_pelantikan'],
                        'urut' => $item['urut'],
                        'kedudukan' => $item['kedudukan'],
                        'jenis_jabfung' => $item['jenis_jabfung'],
                        'jenjang_jabfung' => $item['jenjang_jabfung'],
                        'id_jenjang' => $item['id_jenjang'],
                        'status_supervisi' => $item['status_supervisi'],
                        'catatan_dari_bkd' => $item['catatan_dari_bkd'],
                        'status_sync' => $item['status_sync'] == '' ? '0' : $item['status_sync'],
                        'tanggal_sync' => $item['tanggal_sync'] == '0000-00-00 00:00:00' ? null : $item['tanggal_sync']
                    ];

                    RiwJabatanModel::createorupdate($jabatan);
                }

                //2.Riwayat Jabatan Upload
                $this->UploadDetail($result['data-upload']);

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total . ' Data Riwayat Jabatan berhasil disinkronisasi.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Riwayat Jabatan Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 9) {
            //Riwayat Penyesuaian Masa Kerja
            if ($result['success'] == true) {
                $total = $result['total'];
                //1.Data PMK
                foreach ($result['data'] as $item) {
                    $pmk = [
                        'id_riwayat_pmk' => $item['id_riwayat_pmk'],
                        'id_pegawai' => $item['id_pegawai'],
                        'nip' => $item['nip'],
                        'pengalaman' => $item['pengalaman'],
                        'tgl_mulai' => $item['tgl_mulai'] == '0000-00-00' ? null : $item['tgl_mulai'],
                        'tgl_selesai' => $item['tgl_selesai'] == '0000-00-00' ? null : $item['tgl_selesai'],
                        'dinilai' => $item['dinilai'],
                        'no_persetujuan' => $item['no_persetujuan'],
                        'tgl_persetujuan' => $item['tgl_persetujuan'],
                        'tmt' => $item['tmt'],
                        'no_sk' => $item['no_sk'],
                        'tgl_sk' => $item['tgl_sk'],
                        'pejabat_penetapan' => $item['pejabat_penetapan'],
                        'mkg_tahun' => $item['mkg_tahun'],
                        'mkg_bulan' => $item['mkg_bulan'],
                        'catatan_dari_bkd' => $item['catatan_dari_bkd'],
                        'status_spv' => $item['status_spv']
                    ];

                    RiwPmkModel::createorupdate($pmk);
                }

                //2.Riwayat PMK Upload
                $this->UploadDetail($result['data-upload']);

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total . ' Data Riwayat Penyesuaian Masa Kerja berhasil disinkronisasi.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Riwayat Penyesuaian Masa Kerja Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 10) {
            //Riwayat Diklat           
            if ($result['success'] == true) {
                $total_diklat_str = $result['total-diklat-str'];
                $total_diklat_lain = $result['total-diklat-lain'];
                //1.Data Diklat Struktural
                foreach ($result['data-diklat-str'] as $item) {
                    $diklat_struktural = [
                        'id_riwayat_diklat_struktural' => $item['id_riwayat_diklat_struktural'],
                        'id_pegawai' => $item['id_pegawai'],
                        'id_diklat_struktural' => $item['id_diklat_struktural'],
                        'tempat_diklat' => $item['tempat_diklat'],
                        'penyelenggara' => $item['penyelenggara'],
                        'anggaran' => $item['anggaran'],
                        'mulai' => $item['mulai'],
                        'selesai' => $item['selesai'],
                        'lama' => $item['lama'],
                        'no_ijazah' => $item['no_ijazah'],
                        'tgl_ijazah' => $item['tgl_ijazah'],
                        'peringkat' => $item['peringkat'],
                        'img_ijazah' => $item['img_ijazah'],
                        'status_supervisi' => $item['status_supervisi'],
                        'catatan_dari_bkd' => $item['catatan_dari_bkd']
                    ];

                    RiwDiklatStruktural::createorupdate($diklat_struktural);
                }

                //2.Data Diklat Struktural Upload
                $this->UploadDetail($result['data-upload-str']);

                //3.Data Diklat Lainnya
                foreach ($result['data-diklat-lain'] as $item) {
                    $diklat_lain = [
                        'id_riwayat_diklat_lainnya' => $item['id_riwayat_diklat_lainnya'],
                        'id_riwayat_diklat' => $item['id_riwayat_diklat'],
                        'id_bentuk_diklat' => $item['id_bentuk_diklat'],
                        'id_pegawai' => $item['id_pegawai'],
                        'nama_diklat' => $item['nama_diklat'],
                        'tempat_diklat' => $item['tempat_diklat'],
                        'penyelenggara' => $item['penyelenggara'],
                        'anggaran' => $item['anggaran'],
                        'mulai' => $item['mulai'] == '0000-00-00' ? null : $item['mulai'],
                        'selesai' => $item['selesai'] == '0000-00-00' ? null : $item['selesai'],
                        'lama' => $item['lama'],
                        'no_ijazah' => $item['no_ijazah'],
                        'tgl_ijazah' => $item['tgl_ijazah'] == '0000-00-00' ? null : $item['tgl_ijazah'],
                        'peringkat' => $item['peringkat'],
                        'img_ijazah' => $item['img_ijazah'],
                        'status_supervisi' => $item['status_supervisi'],
                        'catatan_dari_bkd' => $item['catatan_dari_bkd']
                    ];

                    RiwDiklatLainnya::createorupdate($diklat_lain);
                }

                //4.Data Diklat Lainnya Upload                
                $this->UploadDetail($result['data-upload-lain']);

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total_diklat_str . ' Data Riwayat Diklat Struktural, dan ' . $total_diklat_lain . ' Data Riwayat Diklat Lainnya berhasil disinkronisasi.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Riwayat Diklat Struktural dan Lainnya Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 11) {
            //Riwayat Penghargaan
            if ($result['success'] == true) {
                $total = $result['total'];
                //1.Data Riwayat Penghargaan
                foreach ($result['data'] as $item) {
                    $penghargaan = [
                        'id_riwayat_penghargaan' => $item['id_riwayat_penghargaan'],
                        'id_pegawai' => $item['id_pegawai'],
                        'id_penghargaan' => $item['id_penghargaan'],
                        'nama_penghargaan' => $item['nama_penghargaan'],
                        'no_sk' => $item['no_sk'],
                        'tgl_sk' => $item['tgl_sk'],
                        'tahun' => $item['tahun'],
                        'asal_perolehan' => $item['asal_perolehan'],
                        'status_spv' => $item['status_spv']
                    ];
                    RiwPenghargaan::createorupdate($penghargaan);
                }

                //2.Data Riwayat Penghargaan Upload   
                $this->UploadDetail($result['data-upload']);

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total . ' Data Riwayat Penghargaan berhasil disinkronisasi.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Riwayat Penghargaan Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 12) {
            //Riwayat Penyesuaian Ijazah
            if ($result['success'] == true) {
                $total = $result['total'];
                //1.Data Riwayat Penyesuaian Ijazah
                foreach ($result['data'] as $item) {
                    $pi = [
                        'id_riwayat_penyesuaian_ijazah' => $item['id_riwayat_penyesuaian_ijazah'],
                        'id_pegawai' => $item['id_pegawai'],
                        'id_tingkat_pendidikan' => $item['id_tingkat_pendidikan'],
                        'no_stlupi' => $item['no_stlupi'],
                        'tgl_sertifikat_nilai' => $item['tgl_sertifikat_nilai'],
                        'program_studi' => $item['program_studi'],
                        'pejabat_sertifikat' => $item['pejabat_sertifikat'],
                        'pejabat_nilai' => $item['pejabat_nilai'],
                        'status_spv' => $item['status_spv'],
                        'catatan_dari_bkd' => $item['catatan_dari_bkd']
                    ];
                    RiwPenyIjazah::createorupdate($pi);
                }

                //2.Data Riwayat Penyesuaian Ijazah Upload                
                $this->UploadDetail($result['data-upload']);

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total . ' Data Riwayat Penyesuaian Ijazah berhasil disinkronisasi.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Riwayat Penyesuaian Ijazah Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 13) {
            //Riwayat Cuti
            if ($result['success'] == true) {
                $total = $result['total'];
                //1.Data Riwayat Cuti
                foreach ($result['data'] as $item) {
                    $cuti = [
                        'id_riwayat_cuti' => $item['id_riwayat_cuti'],
                        'id_pegawai' => $item['id_pegawai'],
                        'id_jenis_cuti' => $item['id_jenis_cuti'],
                        'lama' => $item['lama'],
                        'dari' => $item['dari'],
                        'sampai' => $item['sampai'],
                        'keterangan' => $item['keterangan'],
                        'no_sk' => $item['no_sk'],
                        'tgl_sk' => $item['tgl_sk'] == '0000-00-00' ? null : $item['tgl_sk'],
                        'pejabat_sk' => $item['pejabat_sk'],
                        'scan_sk' => $item['scan_sk'],
                        'status_spv' => $item['status_spv']
                    ];

                    RiwCutiModel::createorupdate($cuti);
                }

                //2.Data Riwayat Cuti Upload                
                $this->UploadDetail($result['data-upload']);

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total . ' Data Riwayat Cuti berhasil disinkronisasi.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Riwayat Cuti Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 14) {
            //Riwayat Hukuman Disiplin
            if ($result['success'] == true) {
                $total = $result['total'];
                //1.Data Riwayat Hukuman Disiplin
                foreach ($result['data'] as $item) {
                    $hukdis = [
                        'id_riwayat_hd' => $item['id_riwayat_hd'],
                        'id_pegawai' => $item['id_pegawai'],
                        'no_sk' => $item['no_sk'],
                        'tgl_sk' => $item['tgl_sk'],
                        'id_jenis_hukuman' => $item['id_jenis_hukuman'],
                        'id_sub_jenis_hukuman' => $item['id_sub_jenis_hukuman'],
                        'tmt' => $item['tmt'],
                        'tmt_akhir' => $item['tmt_akhir'] == '0000-00-00' ? null : $item['tmt_akhir'],
                        'pejabat_ttd_sk' => $item['pejabat_ttd_sk'],
                        'scan_sk' => $item['scan_sk'],
                        'status_spv' => $item['status_spv'],
                        'alasan_hukdis_id' => $item['alasan_hukdis_id'],
                        'golongan_id' => $item['golongan_id'],
                        'golongan_lama' => $item['golongan_lama'],
                        'hukdis_yg_diberhentikan_id' => $item['hukdis_yg_diberhentikan_id'],
                        'keterangan' => $item['keterangan'],
                        'masa_bulan' => $item['masa_bulan'],
                        'masa_tahun' => $item['masa_tahun'],
                        'nomor_pp' => $item['nomor_pp'],
                        'no_sk_pembatalan' => $item['no_sk_pembatalan'],
                        'tgl_sk_pembatalan' => $item['tgl_sk_pembatalan'] == '0000-00-00' ? null : $item['tgl_sk_pembatalan']
                    ];

                    RiwHukumanDisiplin::createorupdate($hukdis);
                }

                //2.Data Riwayat Hukuman Disiplin Upload                
                $this->UploadDetail($result['data-upload']);

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total . ' Data Riwayat Hukuman Disiplin berhasil disinkronisasi.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Riwayat Hukuman Disiplin Gagal disinkronisasi'
                ];
            }
        } elseif ($id_data == 15) {
            //Data Pengguna
            if ($result['success'] == true) {
                $total = $result['total'];
                //1.Data Pengguna
                foreach ($result['data'] as $item) {
                    $pengguna = [
                        'id_user' => $item['id_user'],
                        'nama' => $item['nama'],
                        'username' => $item['username'],
                        'password' => $item['password'],
                        'pertanyaan' => $item['pertanyaan'],
                        'jawaban' => $item['jawaban'],
                        'role' => $item['role'],
                        'modul' => $item['modul'],
                        'is_login' => $item['is_login'],
                        'login_terakhir' => $item['login_terakhir'],
                        'id_skpd' => $item['id_skpd'],
                        'id_pegawai' => $item['id_pegawai'],
                        'is_penanggung_jawab' => $item['is_penanggung_jawab'],
                        'ms_level_id' => $item['ms_level_id'],
                        'passwordhash' => $item['passwordhash'],
                        'android_id' => $item['android_id'],
                        'status_login' => $item['status_login'],
                        'is_android_dev' => $item['is_android_dev'],
                        'is_admin' => $item['is_admin'],
                        'generate_dashboard' => $item['generate_dashboard'],
                        'id_user_relasi' => $item['id_user_relasi'],
                        'android_id_v2' => $item['android_id_v2']
                    ];

                    Pengguna::createorupdate($pengguna);
                    PenggunaLama::createorUpdate($pengguna);
                }

                //update tanggal sinkronisasi
                $sinkrondata->updated_at = Carbon::now();
                $sinkrondata->save();

                return [
                    'success' => true,
                    'message' => 'Terdapat ' . $total . ' Data Pengguna berhasil disinkronisasi.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Pengguna Gagal disinkronisasi'
                ];
            }
        }
    }
}
