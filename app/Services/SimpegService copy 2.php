<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SimpegService
{
    public function __construct()
    {
       
     $this->token = session('token');
    }

   
    public function login($nip, $password)
    {

        $response = Http::asForm()->post(
            config('services.simpeg.url') . '/auth-simpel',
            [
                'key' => config('services.simpeg.key'),
                'nip' => $nip,
                'password' => $password,
            ]
        );

        if (!$response->successful()) {

            return [
                'success' => false,
                'message' => 'Server SIMPEG tidak merespon'
            ];

        }
        return $response->json();
    }

    public function get($endpoint)
    {
        return Http::withToken($this->token)
            ->timeout(30)
            ->get(config('services.simpeg.url') . '/' . $endpoint)
            ->json()['data'] ?? [];
    }

    public function getRaw($endpoint)
    {
        return Http::withToken($this->token)
            ->timeout(30)
            ->get(config('services.simpeg.url') . '/' . $endpoint)
            ->json();
    }
    public function dataPribadi($id)
    {
        return $this->getRaw("pegawai-profile/$id", $this->token);
    }

    public function dataCPNS($id)
    {
        return $this->getRaw("data-cpns/$id", $this->token);
    }
    public function dataPNS($id)
    {
        return $this->getRaw("data-pns/$id", $this->token);
    }
    public function riwayatPangkat($id)
    {
        return $this->get("data-riwayat/pangkat/$id", $this->token);
    }

    public function riwayatJabatan($id)
    {
        return $this->get("data-riwayat/jabatan/$id", $this->token);
    }

    public function riwayatPendidikan($id)
    {
        return $this->get("data-riwayat/pendidikan/$id", $this->token);
    }

    public function diklatStruktural($id)
    {
        return $this->get("data-riwayat/diklat-struktural/$id", $this->token);
    }
    public function diklatLainnya($id)
    {
        return $this->get("data-riwayat/diklat-lainnya/$id", $this->token);
    }
    public function riwayatGaji($id)
    {
        return $this->get("data-riwayat/gaji-berkala/$id", $this->token);
    }
    public function riwayatPenghargaan($id)
    {
        return $this->get("data-riwayat/penghargaan/$id", $this->token);
    }
    public function riwayatCuti($id)
    {
        return $this->get("data-riwayat/cuti/$id", $this->token);
    }
    public function riwayatHukdis($id)
    {
        return $this->get("data-riwayat/hukuman-disiplin/$id", $this->token);
    }
    public function riwayatPI($id)
    {
        return $this->get("data-riwayat/pi/$id", $this->token);
    }
    public function riwayatPMK($id)
    {
        return $this->get("data-riwayat/pmk/$id", $this->token);
    }
    public function master($type)
    {
        return $this->get("referensi/$type", $this->token);
    }
    public function riwayatKeluarga($id)
    {
        return $this->getRaw("data-keluarga/$id", $this->token);
    }
    public function filterData(array $filters, $page = 1, $perPage = 10)
    {
        $filters = collect($filters)
            ->filter(fn($v) => !is_null($v) && $v !== '')
            ->toArray();
    
        $filters['key'] = config('services.simpeg.key');
        $filters['page'] = $page;
        $filters['per_page'] = $perPage;
    
        $response = Http::withToken($this->token)
            ->asForm()
            ->post(config('services.simpeg.url') . '/filter-data', $filters);
    
        return [
            'success' => $response->successful(),
            'data'    => $response->json() ?? []
        ];
    }
    // public function filterData(array $filters, $this->token, $page = 1, $perPage = 10)
    // {
    //     $filters = collect($filters)
    //         ->filter(fn($v) => !is_null($v) && $v !== '')
    //         ->toArray();

    //     $filters['key'] = config('services.simpeg.key');

    //     // tambahkan pagination ke API
    //     $filters['page'] = $page;
    //     $filters['per_page'] = $perPage;

    //     $response = Http::withToken($this->token)
    //         ->asForm()
    //         ->post(config('services.simpeg.url') . '/filter-data', $filters);

    //     $json = $response->json();

    //     return [
    //         'success' => $response->successful(),
    //         'data' => [
    //             'data' => $json['results'] ?? [],
    //             'current_page' => $json['current_page'] ?? 1,
    //             'last_page' => $json['last_page'] ?? 1,
    //             'total' => $json['total'] ?? 0,
    //         ]
    //     ];
    // }

}
