<?php

namespace App\Services\DaftarPegawai;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class DaftarPegawaiService
{
    public function daftarPegawaiData(array $query)
    {
        $params = array_filter($query, fn ($value) => ! is_null($value));
        $url = config('services.python.url');
        $timestamp = time();
        ksort($params);
        $signature = create_signature($params, $timestamp);
        $response = Http::withHeaders([
            'X-Signature' => $signature,
            'X-Timestamp' => $timestamp,
            'Content-Type' => 'application/json',
        ])->get($url.'/employees', $params);

        return $response;
    }

    public function exportPdf(array $request)
    {
        $daftar_pegawai = $this->daftarPegawaiData($request);
        $pdf = Pdf::loadView('pages.daftar_pegawai.partials.daftar_pegawai_table_export', [
            'data' => $daftar_pegawai->json(),
            'type' => 'pdf',
        ])
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan_daftar_pegawai.pdf');
    }

    public function exportCsv(array $request)
    {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '120');
        $response = $this->daftarPegawaiData($request);
        $data = $response->json();

        if (empty($data)) {
            return response()->json(['error' => 'No data'], 404);
        }

        $columnMap = [
            'No.' => 'No.',
            'nama_pegawai' => 'Nama Pegawai',
            'nip' => 'NIP',
            'status_kepegawaian' => 'Status Kepegawaian',
            'jenis_pengadaan_pppk' => 'Jenis Pengadaan PPPK',
            'pangkat_gol_ruang' => 'Pangkat/Gol Ruang',
            'tmt_pangkat' => 'TMT Pangkat',
            'no_sk_cpns' => 'No SK CPNS',
            'tgl_sk_cpns' => 'Tanggal SK CPNS',
            'tmt_cpns' => 'TMT CPNS',
            'no_sk_pns' => 'No SK PNS',
            'tgl_sk_pns' => 'Tanggal SK PNS',
            'tmt_pns' => 'TMT PNS',
            'nik' => 'NIK',
            'npwp' => 'NPWP',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'umur' => 'Umur',
            'jenis_kelamin' => 'Jenis Kelamin',
            'agama' => 'Agama',
            'telepon' => 'Telepon',
            'tipe_jabatan' => 'Tipe Jabatan',
            'eselon' => 'Eselon',
            'jabatan' => 'Jabatan',
            'tmt_jabatan' => 'TMT Jabatan',
            'jenis_jabfung' => 'Jenis Jenjang Jabatan',
            'jenjang_jabfung' => 'Jenjang Jabatan',
            'skpd' => 'SKPD',
            'pendidikan_terakhir' => 'Pendidikan Terakhir',
            'jurusan' => 'Jurusan',
            'nama_sekolah' => 'Nama Sekolah',
            'alamat' => 'Alamat',
            'email' => 'Email',
            'status_pernikahan' => 'Status Pernikahan',
            'nama_pasangan' => 'Nama Suami/Istri',
            'pekerjaan_pasangan' => 'Pekerjaan Pasangan',
            'tgl_nikah' => 'Tanggal Nikah',
        ];

        $excludedColumns = ['id_pegawai', 'kode_skpd'];

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="export.csv"',
        ];

        $callback = function () use ($data, $excludedColumns, $columnMap) {
            $handle = fopen('php://output', 'w');

            $headerRow = array_diff_key($data[0], array_flip($excludedColumns));
            $customHeaders = array_map(function ($key) use ($columnMap) {
                return $columnMap[$key] ?? $key;
            }, array_keys($headerRow));

            fputcsv($handle, array_merge(['No.'], $customHeaders));

            $no = 1;
            foreach ($data as $row) {
                $filtered = array_diff_key($row, array_flip($excludedColumns));
                fputcsv($handle, array_merge([$no], array_values($filtered)));
                $no++;
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
