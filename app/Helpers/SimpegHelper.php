<?php

namespace App\Helpers;

class SimpegHelper
{
    public static function agama($id)
    {
        return [
            1 => 'Islam',
            2 => 'Kristen Katolik',
            3 => 'Kristen Protestan',
            4 => 'Hindu',
            5 => 'Budha',
            6 => 'Kong Hu Chu',
            7 => 'Lainnya',
        ][$id] ?? '-';
    }

    public static function jenisKelamin($id)
    {
        return [
            1 => 'Laki - Laki',
            2 => 'Perempuan',
        ][$id] ?? '-';
    }

    public static function status($id)
    {
        return [
            1 => 'Menikah',
            2 => 'Cerai',
            3 => 'Janda / Duda',
            4 => 'Belum Menikah',
        ][$id] ?? '-';
    }

    public static function golonganDarah($id)
    {
        return [
            1 => 'A',
            2 => 'B',
            3 => 'AB',
            4 => 'O',
        ][$id] ?? '-';
    }

    public static function rupiah($angka)
    {
        return 'Rp. ' . number_format($angka ?? 0, 0, ',', '.');
    }
}
