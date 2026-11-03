<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';

    protected $fillable = [
        'id_jenis_layanan',
        'kode_layanan',
        'nama_layanan',
        'deskripsi',
        'sla_hari',
        'aktif'
    ];

    public function jenis()
    {
        return $this->belongsTo(JenisLayanan::class, 'id_jenis_layanan');
    }

    public function pengajuan()
    {
        return $this->hasMany(PengajuanLayanan::class, 'layanan_id');
    }
}
