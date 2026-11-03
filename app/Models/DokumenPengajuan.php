<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPengajuan extends Model
{
    protected $table = 'dokumen_pengajuan';

    protected $casts = [
        'data_json' => 'array',
    ];

    protected $fillable = [
        'pengajuan_layanan_id',
        'persyaratan_layanan_id',
        'file_path',
        'data_json',
        'status_validasi',
        'catatan_verifikator'
    ];

    // ================= RELATION =================

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanLayanan::class, 'pengajuan_layanan_id');
    }

    public function persyaratan()
    {
        return $this->belongsTo(PersyaratanLayanan::class, 'persyaratan_layanan_id');
    }

    // Helper untuk URL file secure
    public function getFileUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }

        return route('file.show', ['path' => $this->file_path]);
    }
}