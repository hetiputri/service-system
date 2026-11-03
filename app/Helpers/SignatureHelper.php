<?php

if (! function_exists('create_signarure')) {
    function create_signature($params, $timestamp)
    {
        $secret = config('services.python.secret');

        $body = json_encode($params, JSON_UNESCAPED_SLASHES);

        $dataToSign = $body . $timestamp;
        $signature = hash_hmac('sha256', $dataToSign, $secret);

        return $signature;
    }
}

function tglindonesia($date)
{
    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    $tgl = date('d', strtotime($date));
    $bln = date('n', strtotime($date));
    $thn = date('Y', strtotime($date));

    if ($date != '') {
        return $tgl . ' ' . $bulan[$bln] . ' ' . $thn;
    } else {
        return '';
    }
}

function nama_gelar($nama, $gelar_depan, $gelar_belakang)
{
    $nama_gelar = $nama;
    if ($gelar_depan != '' && $gelar_belakang == '') {
        $nama_gelar = $gelar_depan . " " . $nama;
    } elseif ($gelar_depan == '' && $gelar_belakang != '') {
        $nama_gelar = $nama . ", " . $gelar_belakang;
    } elseif ($gelar_depan != '' && $gelar_belakang != '') {
        $nama_gelar = $gelar_depan . " " . $nama . ", " . $gelar_belakang;
    }

    return $nama_gelar;
}
