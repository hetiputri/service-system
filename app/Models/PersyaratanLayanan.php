<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersyaratanLayanan extends Model
{
    protected $table = 'persyaratan_layanan';

    protected $fillable = [
        'layanan_id',
        'nama_persyaratan',
        'wajib'
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenPengajuan::class, 'persyaratan_layanan_id');
    }
}