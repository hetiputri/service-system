<?php

namespace App\Repositories\Integrasi;

use App\Models\IntegrasiModel;
use App\Repositories\Integrasi\IntegrasiRepo;
use Illuminate\Support\Facades\Http;


class IntegrasiRepoImplement implements IntegrasiRepo
{
    public function getToken1()
    {
        $user = '';
        $pass = '';
        $weblog1 = '';
        $data = IntegrasiModel::get();
        foreach ($data as $item) {
            if ($item->id == 'siasn_username') {
                $user = $item->value;
            }
            if ($item->id == 'siasn_password') {
                $pass = $item->value;
            }
            if ($item->id == 'sapk_client_id') {
                $client = $item->value;
            }
            if ($item->id == 'siasn_login1') {
                $weblog1 = $item->value;
            }
        }

        $response = Http::asForm()->post(
            $weblog1,
            [
                'username' => $user,
                'password' => $pass,
                'grant_type' => 'password',
                'client_id' => $client
            ]
        );

        if (!$response->successful()) {
            return [
                'success' => false,
                'message' => 'Server SIASN BKN tidak merespon'
            ];
        }

        $token = $response->json()['access_token'];
        $update = IntegrasiModel::where('id', 'siasn_token1')->first();
        if ($update) {
            $update->update([
                'value' => $token,
                'keterangan' => date("Y-m-d H:i:s")
            ]);
        }
    }

    public function getToken2()
    {
        $consuser = '';
        $conspass = '';
        $weblog2 = '';
        $data = IntegrasiModel::get();
        foreach ($data as $item) {
            if ($item->id == 'siasn_api_consumer_key') {
                $consuser = $item->value;
            }
            if ($item->id == 'siasn_api_consumer_secret') {
                $conspass = $item->value;
            }
            if ($item->id == 'siasn_login2') {
                $weblog2 = $item->value;
            }
        }

        $response = Http::withBasicAuth($consuser, $conspass)
            ->asForm()->post(
                $weblog2,
                [
                    'grant_type' => 'client_credentials'
                ]
            );

        if (!$response->successful()) {
            return [
                'success' => false,
                'message' => 'Server SIASN BKN tidak merespon'
            ];
        }

        $token2 = $response->json()['access_token'];
        $uptoken = IntegrasiModel::where('id', 'siasn_token2')->first();
        if ($uptoken) {
            $uptoken->update([
                'value' => $token2,
                'keterangan' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function apisiasn($isian)
    {
        $this->getToken1();
        $this->getToken2();

        $link = '';
        $url = '';
        $token1 = '';
        $token2 = '';
        $data = IntegrasiModel::get();
        foreach ($data as $item) {
            if ($item->id == 'siasn_base_url') {
                $url = $item->value;
            }
            if ($item->id == 'siasn_token1') {
                $token1 = $item->value;
            }
            if ($item->id == 'siasn_token2') {
                $token2 = $item->value;
            }
        }

        if ($isian == 'data-utama') {
            $link = 'pns/data-utama/198905142011011004';
        }

        //Execute API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
            'Auth' => 'bearer ' . $token1,
            'Authorization' => 'Bearer ' . $token2
        ])->get($url . '/' . $link);


        return $response;
    }
}
