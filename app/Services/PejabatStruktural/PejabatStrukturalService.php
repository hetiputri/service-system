<?php

namespace App\Services\PejabatStruktural;

use Illuminate\Support\Facades\Http;

class PejabatStrukturalService
{
    public function pejabatStrukturalData(array $request)
    {
        $params = array_filter($request, fn ($value) => ! is_null($value));
        $url = config('services.python.url');
        $timestamp = time();
        ksort($params);
        $signature = create_signature($params, $timestamp);
        $response = Http::withHeaders([
            'X-Signature' => $signature,
            'X-Timestamp' => $timestamp,
            'Content-Type' => 'application/json',
        ])->get($url.'/pejabat', $params);

        return $response;
    }

    public function exportCsv(array $request)
    {
        $params = array_filter($request, fn ($value) => ! is_null($value));
        $response = $this->pejabatStrukturalData($params);
        $data = $response->json();

        if (empty($data)) {
            return response()->json(['error' => 'No data'], 404);
        }

        $columnMap = [
            'No.' => 'No.',
            'jabatan' => 'Jabatan',
            'eselon' => 'Eselon',
            'perangkat_daerah' => 'Perangkat Daerah',
            'status_jabatan' => 'Status Jabatan',
            'tmt_jabatan' => 'TMT Jabatan',
            'no_sk_jabatan' => 'No SK Jabatan',
            'tgl_sk_jabatan' => 'Tanggal SK Jabatan',
            'tgl_sk_cpns' => 'Tanggal SK CPNS',
            'lama_menjabat' => 'Lama Menjabat',
            'no_sk_pns' => 'No SK PNS',
            'nip' => 'NIP',
            'nama_pegawai' => 'Nama Pegawai',
            'gol_ruang' => 'Gol Ruang',
        ];

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="export.csv"',
        ];

        $callback = function () use ($data, $columnMap) {
            $handle = fopen('php://output', 'w');

            $customHeaders = array_map(function ($key) use ($columnMap) {
                return $columnMap[$key] ?? $key;
            }, array_keys($data[0]));

            fputcsv($handle, array_merge(['No.'], $customHeaders));

            $no = 1;
            foreach ($data as $row) {
                fputcsv($handle, array_merge([$no], array_values($row)));
                $no++;
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
