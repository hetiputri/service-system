<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPidana extends Model
{
    protected $table = 'surat_pidana';

    protected $fillable = [
        'user_id',
        'pengajuan_id',
        'id_ttd_oleh',

        // SURAT
        'nomor_surat',
        'tanggal_surat',

        // PEJABAT
        'pejabat_nama',
        'pejabat_nip',
        'pejabat_pangkat',
        'pejabat_jabatan',

        // PEGAWAI
        'nama',
        'nip',
        'pangkat',
        'jabatan',
        'instansi',

        // FILE
        'file_pdf',

        // OPTIONAL
        'status',
    ];

    // 🔥 OPTIONAL RELATION
    public function pejabat()
    {
        return $this->belongsTo(\App\Models\Pegawai::class, 'id_ttd_oleh', 'id_pegawai');
    }
}