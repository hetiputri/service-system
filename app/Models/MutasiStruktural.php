<?php

namespace App\Models;

use App\Enums\Mutasi\StatusKonsep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MutasiStruktural extends Model
{
    use SoftDeletes;

    protected $table = 'mutasi_struktural';

    protected $fillable = [
        'kode_konsep',
        'nama_konsep',
        'tanggal_konsep',
        'keterangan',
        'status_konsep',
        'dibuat_oleh',
        'disetujui_oleh',
    ];

    protected $casts = [
        'status_konsep' => StatusKonsep::class,
    ];

    public function mutasiStrukturalDetail()
    {
        return $this->hasOne(MutasiStrukturalDetail::class, 'id_konsep', 'id');
    }
}
