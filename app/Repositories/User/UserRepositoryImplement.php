<?php

namespace App\Repositories\User;

use App\Models\DataModel;

class UserRepositoryImplement implements UserRepository
{
    private $model;

    public function __construct(DataModel $model)
    {
        $this->model = $model;
    }

    /**
     * Get data by ID and jenis_data
     *
     * @return mixed
     */
    public function getData($jenis_data, $id_pegawai)
    {
        $data = [];
        if ($id_pegawai <= 0) {
            $data = [
                'pesan' => 'id_pegawai tidak valid',
            ];
        } else {
            if ($jenis_data == 'pegawai-profile') {
                $pegawai = $this->model->getDapri($id_pegawai);

                if ($pegawai->count() > 0) {
                    $model = $pegawai->first();

                    $upload = $this->model->getUploadPeg($id_pegawai);

                    $link_pasfoto = '';
                    $link_ktp = '';
                    $link_karpeg = '';
                    $link_npwp = '';
                    $link_bpjs = '';
                    $link_taspen = '';

                    if (! empty($upload->pasfoto) && $upload->pasfoto != '') {
                        $link_pasfoto = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_peg_asli.php?id_pegawai=' . $id_pegawai . '&type=pasfoto';
                    }
                    if (! empty($upload->scan_ktp) && $upload->scan_ktp != '') {
                        $link_ktp = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_peg_asli.php?id_pegawai=' . $id_pegawai . '&type=scan_ktp';
                    }

                    if (! empty($upload->karpeg) && $upload->karpeg != '') {
                        $link_karpeg = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_peg_asli.php?id_pegawai=' . $id_pegawai . '&type=karpeg';
                    }

                    if (! empty($upload->scan_npwp) && $upload->scan_npwp != '') {
                        $link_npwp = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_peg_asli.php?id_pegawai=' . $id_pegawai . '&type=scan_npwp';
                    }

                    if (! empty($upload->scan_taspen) && $upload->scan_taspen != '') {
                        $link_taspen = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_peg_asli.php?id_pegawai=' . $id_pegawai . '&type=scan_taspen';
                    }

                    if (! empty($upload->askes_pribadi) && $upload->askes_pribadi != '') {
                        $link_bpjs = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_peg_asli.php?id_pegawai=' . $id_pegawai . '&type=askes_pribadi';
                    }

                    $data_upload = [];
                    $data_upload[] = [
                        'pasfoto' => $link_pasfoto,
                        'ktp' => $link_ktp,
                        'kartu_pegawai' => $link_karpeg,
                        'npwp' => $link_npwp,
                        'bpjs' => $link_bpjs,
                        'taspen' => $link_taspen,
                    ];

                    $data = [
                        'data' => (array) $model,
                        'file_upload' => $data_upload,

                    ];
                } else {
                    $data = [
                        'pesan' => 'data tidak tersedia',
                    ];
                }
            } elseif ($jenis_data == 'data-keluarga') {
                $dapas = [];
                $danak = [];
                $pasangan = $this->model->getKeluarga($id_pegawai);
                if ($pasangan->count() > 0) {
                    $pasangan = $pasangan->get();
                    foreach ($pasangan as $pas) {
                        $dapas[] = [
                            'nama_pasangan' => $pas->nama_pasangan,
                            'tempat_lahir' => $pas->tempat_lahir,
                            'tgl_lahir' => $pas->tgl_lahir,
                            'tgl_nikah' => $pas->tgl_nikah,
                            'pekerjaan' => $pas->pekerjaan,
                            'status_nikah' => $pas->status_nikah,
                            'status_hidup' => $pas->status_hidup,
                            'akta_nikah' => $pas->akta_nikah,
                            'akta_cerai' => $pas->akta_cerai,
                            'akta_meninggal' => $pas->akta_meninggal,
                            'kartu_keluarga' => $pas->kartu_keluarga,
                        ];
                    }
                    $anak = $this->model->getAnak($id_pegawai);

                    if ($anak->count() > 0) {
                        $anak = $anak->get();
                        foreach ($anak as $ank) {
                            $danak[] = [
                                'nama_anak' => $ank->nama_anak,
                                'tempat_lahir' => $ank->tempat_lahir,
                                'tgl_lahir' => $ank->tgl_lahir,
                                'jenis_kelamin' => $ank->jenis_kelamin,
                                'pendidikan' => $ank->pendidikan,
                                'pekerjaan' => $ank->pekerjaan,
                                'akta_lahir' => $ank->akta_lahir,
                                'nama_ayah' => $ank->nama_ayah,
                                'nama_ibu' => $ank->nama_ibu,
                            ];
                        }
                    }

                    $data = [
                        'data-pasangan' => $dapas,
                        'data-anak' => $danak,

                    ];
                } else {
                    $data = [
                        'pesan' => 'Maaf, data pasangan pegawai tidak tersedia',
                    ];
                }
            } elseif ($jenis_data == 'data-cpns') {
                $datacpns = $this->model->getCpns($id_pegawai);
                if ($datacpns->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data CPNS pegawai tidak tersedia',
                    ];
                } else {
                    $datacpns = $datacpns->first();

                    $file_cpns = '';
                    if ($datacpns->sk_cpns != '') {
                        $file_cpns = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_peg_asli.php?id_pegawai=' . $datacpns->id_pegawai . '&jenis=sk_cpns';
                    }

                    $cpns = [];
                    $cpns[] = [
                        'id_pegawai' => $datacpns->id_pegawai,
                        'pangkat_cpns' => $datacpns->pangkat_cpns,
                        'gol_ruang_cpns' => $datacpns->gol_ruang_cpns,
                        'no_bkn' => $datacpns->no_bkn,
                        'pejabat_penetapan' => $datacpns->pejabat_penetapan,
                        'no_sk' => $datacpns->NoSK,
                        'tanggal_sk' => $datacpns->TanggalSK,
                        'tmt_cpns' => $datacpns->TMTCPNS,
                        'tingkat_pendidikan_cpns' => $datacpns->tk_pendidikan_cpns,
                        'file_sk_cpns' => $file_cpns,
                    ];

                    $data = [
                        'data-cpns' => $cpns,
                    ];
                }
            } elseif ($jenis_data == 'data-pns') {
                $data_pns = $this->model->getPns($id_pegawai);

                if ($data_pns->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data PNS pegawai tidak tersedia',
                    ];
                } else {
                    $data_pns = $data_pns->first();

                    $file_upload = $this->model->getUploadPeg($id_pegawai);

                    $file_pns = '';
                    if ($file_upload && $file_upload->sk_pns != '') {
                        $file_pns = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_peg_asli.php?id_pegawai=' . $data_pns->id_pegawai . '&jenis=sk_pns';
                    }

                    $data = [
                        'data-pns' => (array) $data_pns,
                        'file-sk-pns' => $file_pns,
                    ];
                }
            } elseif ($jenis_data == 'gaji-berkala') {
                $gajis = $this->model->getGajiBerkala($id_pegawai);
                $datas = [];
                if ($gajis->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data Gaji Berkala pegawai tidak tersedia',
                    ];
                } else {
                    $gajis = $gajis->get();
                    foreach ($gajis as $gaji) {
                        $sk = '';
                        if ($gaji->sk != '') {
                            $sk = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_all_asli.php?id_pegawai=' . $gaji->id_pegawai . '&type=tblriwayatgaji&id=' . $gaji->id_riwayat_gaji . '';
                        }

                        $datas[] = [
                            'id_riwayat_gaji' => $gaji->id_riwayat_gaji,
                            'tmt_gaji' => $gaji->tmt_gaji,
                            'nomor_sk' => $gaji->no_sk,
                            'tanggal_sk' => $gaji->tgl_sk,
                            'pejabat_sk' => $gaji->pejabat_sk,
                            'file_sk' => $sk,
                        ];
                        $data = [
                            'data' => $datas,
                        ];
                    }
                }
            } elseif ($jenis_data == 'pangkat') {
                $pangkat = $this->model->getPangkat($id_pegawai);

                if ($pangkat->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data Pangkat pegawai tidak tersedia',
                    ];
                } else {
                    $datas = [];
                    $pangkat = $pangkat->get();
                    foreach ($pangkat as $pang) {
                        $file_sk = '';
                        if ($pang->sk_pangkat != '') {
                            $file_sk = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_all_asli.php?id_pegawai=' . $pang->id_pegawai . '&type=tbl_riwayat_pangkat&id=' . $pang->id_riwayat_pangkat . '';
                        }
                        $datas[] = [
                            'id_riwayat_pangkat' => $pang->id_riwayat_pangkat,
                            'pangkat' => $pang->pangkat,
                            'gol_ruang' => $pang->gol_ruang,
                            'tmt_pangkat' => $pang->tmt_pangkat,
                            'mkg_tahun' => $pang->mkg_tahun,
                            'mkg_bulan' => $pang->mkg_bulan,
                            'no_sk' => $pang->no_sk,
                            'tgl_sk' => $pang->tgl_sk,
                            'pejabat_penetapan' => $pang->pejabat_penetapan,
                            'file_sk_pangkat' => $file_sk,
                        ];
                        $data = [
                            'data' => $datas,
                        ];
                    }
                }
            } elseif ($jenis_data == 'jabatan') {
                $dajabs = $this->model->getJabatan($id_pegawai);
                $datas = [];
                if ($dajabs->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data Jabatan pegawai tidak tersedia',
                    ];
                } else {
                    $dajabs = $dajabs->get();
                    foreach ($dajabs as $dajab) {
                        $file_sk = '';
                        $plt = '';
                        if ($dajab->sk_jabatan != '') {
                            $file_sk = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_all_asli.php?id_pegawai=' . $dajab->id_pegawai . '&type=tbl_riwayat_jabatan&id=' . $dajab->id_riwayat_jabatan . '';
                        }

                        if ($dajab->id_plt > 0) {
                            $plt .= 'Plt. ';
                        }

                        $jabatan = $plt . $dajab->jabatan;
                        if ($jabatan == null || $jabatan == '') {
                            $jabatan = $plt . $dajab->jabatan1;
                        }

                        $skpd = $dajab->skpd1;

                        $datas[] = [
                            'id_riwayat_jabatan' => $dajab->id_riwayat_jabatan,
                            'tipe_jabatan' => $dajab->tipe_jabatan,
                            'jabatan' => $jabatan,
                            'eselon' => $dajab->eselon,
                            'unit_kerja' => $skpd,
                            'tmt_jabatan' => $dajab->tmt_jabatan,
                            'tmt_akhir' => $dajab->tmt_akhir,
                            'no_sk' => $dajab->no_sk,
                            'tgl_sk' => $dajab->tgl_sk,
                            'pejabat_penetapan' => $dajab->pejabat_penetapan,
                            'file_sk_jabatan' => $file_sk,
                        ];
                        $data = [
                            'data' => $datas,
                        ];
                    }
                }
            } elseif ($jenis_data == 'pendidikan') {
                $pend = $this->model->getPendidikan($id_pegawai);
                $datas = [];
                if ($pend->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data Pendidikan pegawai tidak tersedia',
                    ];
                } else {
                    $pend = $pend->get();
                    foreach ($pend as $pddk) {
                        $ijazah = '';
                        if ($pddk->ijazah != '') {
                            $ijazah = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_all_asli.php?id_pegawai=' . $pddk->id_pegawai . '&type=tbl_pendidikan&id=' . $pddk->id_pendidikan . '';
                        }

                        $datas[] = [
                            'id_pendidikan' => $pddk->id_pendidikan,
                            'tingkat_pendidikan' => $pddk->tingkat_pendidikan,
                            'detail_pendidikan' => $pddk->detail_pendidikan,
                            'jurusan' => $pddk->jurusan,
                            'nama_sekolah' => $pddk->nama_sekolah,
                            'tempat' => $pddk->tempat,
                            'kepala_sekolah' => $pddk->kepala_sekolah,
                            'no_ijazah' => $pddk->no_ijazah,
                            'tanggal_ijazah' => $pddk->tanggal_ijazah,
                            'nem_ip' => $pddk->nem_ip,
                            'file_ijazah' => $ijazah,
                        ];
                        $data = [
                            'data' => $datas,
                        ];
                    }
                }
            } elseif ($jenis_data == 'penghargaan') {
                $reward = $this->model->getPenghargaan($id_pegawai);
                $datas = [];
                if ($reward->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data Penghargaan pegawai tidak tersedia',
                    ];
                } else {
                    $reward = $reward->get();
                    foreach ($reward as $rwd) {
                        $sertifikat = '';
                        if ($rwd->sertifikat != '') {
                            $sertifikat = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_all_asli.php?id_pegawai=' . $rwd->id_pegawai . '&type=tbl_riwayat_penghargaan&id=' . $rwd->id_riwayat_penghargaan . '';
                        }

                        $datas[] = [
                            'id_riwayat_penghargaan' => $rwd->id_riwayat_penghargaan,
                            'nama_penghargaan' => $rwd->nama_penghargaan,
                            'nomor_sk' => $rwd->no_sk,
                            'tanggal_sk' => $rwd->tgl_sk,
                            'tahun' => $rwd->tahun,
                            'asal_perolehan' => $rwd->asal_perolehan,
                            'file_sertifikat' => $sertifikat,
                        ];
                        $data = [
                            'data' => $datas,
                        ];
                    }
                }
            } elseif ($jenis_data == 'hukuman-disiplin') {
                $hukdis = $this->model->getHukdis($id_pegawai);
                $datas = [];
                if ($hukdis->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data Hukuman Disiplin pegawai tidak tersedia',
                    ];
                } else {

                    $hukdis = $hukdis->get();
                    foreach ($hukdis as $hkd) {
                        $sk = '';
                        if ($hkd->sk != '') {
                            $sk = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_all_asli.php?id_pegawai=' . $hkd->id_pegawai . '&type=tbl_riwayat_hukuman_disiplin&id=' . $hkd->id_riwayat_hd . '';
                        }

                        $datas[] = [
                            'id_riwayat_hukdis' => $hkd->id_riwayat_hd,
                            'tingkat_hukdis' => $hkd->tingkat_hukuman,
                            'jenis_hukdis' => $hkd->jenis_hukuman,
                            'nomor_sk' => $hkd->no_sk,
                            'tanggal_sk' => $hkd->tgl_sk,
                            'tmt' => $hkd->tmt,
                            'tmt_akhir' => $hkd->tmt_akhir,
                            'file_sk' => $sk,
                        ];
                    }

                    $data = [
                        'data' => $datas,
                    ];
                }
            } elseif ($jenis_data == 'pmk') {
                $pmk = $this->model->getPmk($id_pegawai);
                $datas = [];
                if ($pmk->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data PMK pegawai tidak tersedia',
                    ];
                } else {
                    $pmks = $pmk->get();
                    foreach ($pmks as $pmk) {
                        $sk = '';
                        if ($pmk->sk != '') {
                            $sk = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_all_asli.php?id_pegawai=' . $pmk->id_pegawai . '&type=tbl_riwayat_pmk&id=' . $pmk->id_riwayat_pmk . '';
                        }

                        $datas[] = [
                            'id_riwayat_pmk' => $pmk->id_riwayat_pmk,
                            'pengalaman' => $pmk->pengalaman,
                            'tmt' => $pmk->tmt,
                            'nomor_sk' => $pmk->no_sk,
                            'tanggal_sk' => $pmk->tgl_sk,
                            'pejabat_sk' => $pmk->pejabat_penetapan,
                            'mkg_tahun' => $pmk->mkg_tahun,
                            'mkg_bulan' => $pmk->mkg_bulan,
                            'file_sk' => $sk,
                        ];
                    }

                    $data = [
                        'data' => $datas,
                    ];
                }
            } elseif ($jenis_data == 'pi') {
                $pis = $this->model->getPi($id_pegawai);
                $datas = [];
                if ($pis->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data Penyesuaian Ijazah pegawai tidak tersedia',
                    ];
                } else {
                    $pis = $pis->get();
                    foreach ($pis as $pi) {
                        $sk = '';
                        if ($pi->sk != '') {
                            $sk = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_all_asli.php?id_pegawai=' . $pi->id_pegawai . '&type=tbl_riwayat_pi&id=' . $pi->id_riwayat_penyesuaian_ijazah . '';
                        }

                        $datas[] = [
                            'id_riwayat_penyesuaian_ijazah' => $pi->id_riwayat_penyesuaian_ijazah,
                            'no_stlupi' => $pi->no_stlupi,
                            'tgl_sertifikat_nilai' => $pi->tgl_sertifikat_nilai,
                            'pendidikan' => $pi->tingkat_pendidikan,
                            'program_studi' => $pi->program_studi,
                            'pejabat_ttd_sertifikat' => $pi->pejabat_sertifikat,
                            'pejabat_ttd_nilai' => $pi->pejabat_nilai,
                            'file_sk' => $sk,
                        ];
                    }

                    $data = [
                        'data' => $datas,
                    ];
                }
            } elseif ($jenis_data == 'cuti') {
                $cutis = $this->model->getCuti($id_pegawai);
                $datas = [];
                if ($cutis->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data Cuti pegawai tidak tersedia',
                    ];
                } else {
                    $cutis = $cutis->get();
                    foreach ($cutis as $cuti) {
                        $sk = '';
                        if ($cuti->sk != '') {
                            $sk = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_all_asli.php?id_pegawai=' . $cuti->id_pegawai . '&type=tbl_cuti_riwayat&id=' . $cuti->id_riwayat_cuti . '';
                        }

                        $datas[] = [
                            'id_riwayat_cuti' => $cuti->id_riwayat_cuti,
                            'jenis_cuti' => $cuti->jenis_cuti,
                            'lama' => $cuti->lama,
                            'mulai' => $cuti->dari,
                            'sampai' => $cuti->sampai,
                            'nomor_sk' => $cuti->no_sk,
                            'tanggal_sk' => $cuti->tgl_sk,
                            'pejabat_sk' => $cuti->pejabat_sk,
                            'file_sk' => $sk,
                        ];

                        $data = [
                            'data' => $datas,
                        ];
                    }
                }
            } elseif ($jenis_data == 'diklat-struktural') {
                $diklats = $this->model->getDiklatStr($id_pegawai);
                $datas = [];
                if ($diklats->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data Diklat Struktural pegawai tidak tersedia',
                    ];
                } else {
                    $diklat = $diklats->get();
                    foreach ($diklat as $dikl) {
                        $sertifikat = '';
                        if ($dikl->sertifikat != '') {
                            $sertifikat = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_all_asli.php?id_pegawai=' . $dikl->id_pegawai . '&type=tbl_riwayat_diklat_struktural&id=' . $dikl->id_riwayat_diklat_struktural . '';
                        }

                        $datas[] = [
                            'id_riwayat_diklat_struktural' => $dikl->id_riwayat_diklat_struktural,
                            'diklat_struktural' => $dikl->diklat_struktural,
                            'tempat_diklat' => $dikl->tempat_diklat,
                            'penyelenggara' => $dikl->penyelenggara,
                            'tahun_anggaran' => $dikl->tahun_anggaran,
                            'mulai' => $dikl->mulai,
                            'selesai' => $dikl->selesai,
                            'jumlah_jam' => $dikl->jumlah_jam,
                            'no_ijazah' => $dikl->no_ijazah,
                            'tgl_ijazah' => $dikl->tgl_ijazah,
                            'peringkat' => $dikl->peringkat,
                            'file_sertifikat' => $sertifikat,
                        ];
                        $data = [
                            'data' => $datas,
                        ];
                    }
                }
            } elseif ($jenis_data == 'diklat-lainnya') {
                $diklatl = $this->model->getDiklatLain($id_pegawai);
                $datas = [];
                if ($diklatl->count() == 0) {
                    $data = [
                        'pesan' => 'Maaf, data Diklat Lainnya pegawai tidak tersedia',
                    ];
                } else {
                    $diklat1 = $diklatl->get();
                    foreach ($diklat1 as $dikl) {
                        $sertifikat = '';
                        if ($dikl->sertifikat != '') {
                            $sertifikat = 'bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_all_asli.php?id_pegawai=' . $dikl->id_pegawai . '&type=tbl_riwayat_diklat_lainnya&id=' . $dikl->id_riwayat_diklat_lainnya . '';
                        }

                        $datas[] = [
                            'id_riwayat_diklat_lainnya' => $dikl->id_riwayat_diklat_lainnya,
                            'nama_diklat' => $dikl->nama_diklat,
                            'jenis_diklat' => $dikl->bentuk_diklat,
                            'tempat_diklat' => $dikl->tempat_diklat,
                            'penyelenggara' => $dikl->penyelenggara,
                            'tahun_anggaran' => $dikl->tahun_anggaran,
                            'mulai' => $dikl->mulai,
                            'selesai' => $dikl->selesai,
                            'jumlah_jam' => $dikl->jumlah_jam,
                            'no_ijazah' => $dikl->no_ijazah,
                            'tgl_ijazah' => $dikl->tgl_ijazah,
                            'peringkat' => $dikl->peringkat,
                            'file_sertifikat' => $sertifikat,
                        ];

                        $data = [
                            'data' => $datas,
                        ];
                    }
                }
            } // next
        }

        return $data;
    }
}
