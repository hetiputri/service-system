<?php

namespace App\Enums\Mutasi;

enum StatusKonsep: int
{
    case BelumVerifikasi = 0;
    case Disetujui = 1;
    case Ditolak = 2;

    public function label(): string
    {
        return match ($this) {
            self::BelumVerifikasi => 'Belum Verifikasi',
            self::Disetujui => 'Disetujui',
            self::Ditolak => 'Ditolak'
        };
    }
}
