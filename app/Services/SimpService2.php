<?php

namespace App\Services;

use App\Repositories\Filter\FilterRepository;
use App\Repositories\Otentikasi\AuthRepository;
use App\Repositories\User\UserRepository;
//use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Http;

class SimpegService
{

    protected $userRepository;
    protected $authRepository;
    protected $filterRepository;
    protected $token;

    public function __construct(UserRepository $userRepository, AuthRepository $authRepository, FilterRepository $filterRepository)
    {
        $this->userRepository = $userRepository;
        $this->authRepository = $authRepository;
        $this->filterRepository = $filterRepository;
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

    // public function login($nip, $password)
    // {
    //     $credentials = [
    //         'nip' => $nip,
    //         'password' => $password
    //     ];

    //     // $response = $this->authRepository->attempt($credentials);

    //    if ($response['success'] == false) {

    //         return [
    //             'success' => false,
    //             'message' => 'Server SIMPEG tidak merespon'
    //         ];
    //     }
    //     //return $response;
    //    
    // }

    // public function get($endpoint)
    // {
    // $jenis = '';
    // $input = explode('/', $endpoint);

    // if ($input[0] == 'data-riwayat') {
    //     $jenis = $input[1];
    //     $id_pegawai = end($input);
    //     $response = $this->userRepository->getData($jenis, $id_pegawai);
    // } else if ($input[0] == 'referensi') {
    //     $jenis = $input[1];
    //     $response = $this->filterRepository->getRef($jenis);
    // } else {
    //     $jenis = $input[0];
    //     $id_pegawai = end($input);
    //     $response = $this->userRepository->getData($jenis, $id_pegawai);
    // }
    // return $response['data'] ?? [];

    // }

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

    // public function getRaw($endpoint)
    // {
    // $jenis = '';
    // $input = explode('/', $endpoint);
    // $id_pegawai = end($input);
    // if ($input[0] == 'data-riwayat') {
    //     $jenis = $input[1];
    // } else {
    //     $jenis = $input[0];
    // }

    // $response = $this->userRepository->getData($jenis, $id_pegawai);
    // return $response;
    //     
    // }
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

    // public function filterData(array $filters, $token, $page = 1, $perPage = 10)
    // {
    //     $filters = collect($filters)
    //         ->filter(fn($v) => !is_null($v) && $v !== '')
    //         ->toArray();

    //     $filters['key'] = config('services.simpeg.key');
    //     $filters['page'] = $page;
    //     $filters['per_page'] = $perPage;

    //     $response = Http::withToken($token)
    //         ->asForm()
    //         ->post(config('services.simpeg.url') . '/filter-data', $filters);

    //     return [
    //         'success' => $success,
    //         'data'    => $result,
    //         'total'   => $result['total'],
    //         'message' => $result['message'] ?? null,
    //     ];
    // }

    public function filterData(array $filters, $token, $page = 1, $perPage = 10)
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
}
