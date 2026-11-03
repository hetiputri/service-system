<?php

namespace App\Repositories\Filter;

use App\Models\PencarianRefModel;

class FilterRepositoryImplement implements FilterRepository
{
    private $model;

    public function __construct(PencarianRefModel $model)
    {
        $this->model = $model;
    }

    /**
     * Get data by ID and jenis_data
     *
     * @param  $id_pegawai
     * @return mixed
     */
    public function getRef($jenis_data, $detail = null)
    {
        $models = $this->model->getRef($jenis_data, $detail);
        if ($models != null && $models->count() > 0) {

            if ($jenis_data == 'jenjang-jabatan') {
                $jenjab = $models->get();
                $datas = [];
                foreach ($jenjab as $mod) {
                    $jenjang_jabatan = $this->model->getRef('jenjang-jabatan-detail', $mod->id_jenjang_jabatan)->first();
                    $datas[] = [
                        'id_jenjang_jabatan' => $mod->id_jenjang_jabatan,
                        'jenjang_jabatan' => str_replace('*', '', $jenjang_jabatan),
                    ];
                }
                $data = [
                    'status' => 'success',
                    'data' => $datas,
                ];
            } else {
                $data = [
                    'status' => 'success',
                    'data' => $models->get()->toArray(),
                ];
            }
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Jenis referensi tidak ditemukan',
            ];
        }

        return $data;
    }

    public function filterData(array $filters)
    {
        $nama = ! empty($filters['nama_pegawai']) ? $filters['nama_pegawai'] : '';
        $nip = ! empty($filters['nip']) ? $filters['nip'] : '';
        $id_pangkat = ! empty($filters['id_pangkat']) ? $filters['id_pangkat'] : '';
        $id_eselon = ! empty($filters['id_eselon']) ? $filters['id_eselon'] : '';
        $id_tipe_jabatan = ! empty($filters['id_tipe_jabatan']) ? $filters['id_tipe_jabatan'] : '';
        $id_jenjang_jabatan = ! empty($filters['id_jenjang_jabatan']) ? $filters['id_jenjang_jabatan'] : '';
        $nama_jabatan = ! empty($filters['nama_jabatan']) ? $filters['nama_jabatan'] : '';
        $id_jabfung = ! empty($filters['id_jabatan_fungsional']) ? $filters['id_jabatan_fungsional'] : '';
        $id_jabstr = ! empty($filters['id_jabatan_struktural']) ? $filters['id_jabatan_struktural'] : '';
        $id_jabpel = ! empty($filters['id_jabatan_pelaksana']) ? $filters['id_jabatan_pelaksana'] : '';
        $id_jenis_kelamin = ! empty($filters['id_jenis_kelamin']) ? $filters['id_jenis_kelamin'] : '';
        $id_agama = ! empty($filters['id_agama']) ? $filters['id_agama'] : '';
        $tingkat_pendidikan = ! empty($filters['tingkat_pendidikan']) ? $filters['tingkat_pendidikan'] : '';
        $id_skpd = ! empty($filters['id_skpd']) ? $filters['id_skpd'] : '';
        $nama_sekolah = ! empty($filters['nama_sekolah']) ? $filters['nama_sekolah'] : '';
        $jurusan = ! empty($filters['jurusan']) ? $filters['jurusan'] : '';
        $id_kedudukan_pegawai = ! empty($filters['id_kedudukan_kepegawaian']) ? $filters['id_kedudukan_kepegawaian'] : '';
        $id_status_pegawai = ! empty($filters['id_status_pegawai']) ? $filters['id_status_pegawai'] : '';
        $jenis_jabatan_struktural = ! empty($filters['jenis_jabatan_struktural']) ? $filters['jenis_jabatan_struktural'] : '';

        // Determine id_jabatan
        $id_jabatan = $id_jabfung ?: ($id_jabstr ?: ($id_jabpel ?: ''));

        $kode_skpd = '0';
        if ($id_skpd) {
            $kode_skpd = $this->model->getSkpdDetail($id_skpd);
            if ($kode_skpd->count() > 0) {
                $kode_skpd = $kode_skpd->pluck('kode_skpd')->first();
            }
        }

        if ($nip != '') {
            $nip_d = str_replace(' ', '', $nip);
            if (strlen($nip_d) == 18) {
                $nip = substr($nip_d, 0, 8).' '.substr($nip_d, 8, 6).' '.substr($nip_d, 14, 1).' '.substr($nip_d, 15, 3);
            }
        }

        $id_eselon_str = 0;
        if ($jenis_jabatan_struktural == 'JPT Pratama') {
            $id_eselon_str = 3; // AND +1
        } elseif ($jenis_jabatan_struktural == 'Administrator') {
            $id_eselon_str = 5; // AND +1
        } elseif ($jenis_jabatan_struktural == 'Pengawas') {
            $id_eselon_str = 7; // AND +1
        }

        $data = [
            'nama_pegawai' => $nama,
            'nip' => $nip,
            'id_pangkat' => $id_pangkat,
            'id_eselon' => $id_eselon,
            'id_eselon_str' => $id_eselon_str,
            'id_tipe_jabatan' => $id_tipe_jabatan,
            'id_jenjang_jabatan' => $id_jenjang_jabatan,
            'nama_jabatan' => $nama_jabatan,
            'id_jabatan' => $id_jabatan,
            'id_jenis_kelamin' => $id_jenis_kelamin,
            'id_agama' => $id_agama,
            'tingkat_pendidikan' => $tingkat_pendidikan,
            'kode_skpd' => $kode_skpd,
            'nama_sekolah' => $nama_sekolah,
            'jurusan' => $jurusan,
            'id_kedudukan_pegawai' => $id_kedudukan_pegawai,
            'id_status_pegawai' => $id_status_pegawai,
            'id_eselon_str' => $id_eselon_str,
        ];

        $results = [];
        $models = $this->model->getFilter($data);
        $count = $models->count();
        if ($count > 0) {
            $models = $models->get();
            foreach ($models as $model) {
                $results[] = [
                    'id_pegawai' => $model->id_pegawai,
                    'nama_pegawai' => $model->nama_pegawai,
                    'nip' => $model->nip,
                    'pangkat' => $model->pangkat,
                    'gol_ruang' => $model->gol_ruang,
                    'jabatan' => $model->jabatan,
                    'unit_kerja' => $model->unit_kerja,
                    'jenis_kelamin' => $model->jenis_kelamin,
                ];
            }
        }

        return [

            'results' => $results,
            'total' => $count,
            'message' => $count > 0 ? null : 'Tidak terdapat data sesuai filter pencarian',
        ];
    }
}
