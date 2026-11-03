<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Filter\FilterRepository;
use Illuminate\Http\Request;

class PencarianController extends Controller
{
    protected $filterRepository;

    public function __construct(FilterRepository $filterRepository)
    {
        $this->filterRepository = $filterRepository;
    }

    public function filter(Request $request)
    {
        $arrays = [
            'nama_pegawai' => $request->input('nama_pegawai'),
            'nip' => $request->input('nip'),
            'id_pangkat' => $request->input('id_pangkat'),
            'id_eselon' => $request->input('id_eselon'),
            'id_tipe_jabatan' => $request->input('id_tipe_jabatan'),
            'id_jenjang_jabatan' => $request->input('id_jenjang_jabatan'),
            'nama_jabatan' => $request->input('nama_jabatan'),
            'id_jabatan_fungsional' => $request->input('id_jabatan_fungsional'),
            'id_jabatan_struktural' => $request->input('id_jabatan_struktural'),
            'id_jabatan_pelaksana' => $request->input('id_jabatan_pelaksana'),
            'id_jenis_kelamin' => $request->input('id_jenis_kelamin'),
            'id_agama' => $request->input('id_agama'),
            'tingkat_pendidikan' => $request->input('tingkat_pendidikan'),
            'id_skpd' => $request->input('id_skpd'),
            'nama_sekolah' => $request->input('nama_sekolah'),
            'jurusan' => $request->input('jurusan'),
            'id_kedudukan_kepegawaian' => $request->input('id_kedudukan_kepegawaian'),
            'id_status_pegawai' => $request->input('id_status_pegawai'),
            'jenis_jabatan_struktural' => $request->input('jenis_jabatan_struktural'),

        ];

        $results = $this->filterRepository->filterData($arrays);

        return response()->json($results);
    }
}
