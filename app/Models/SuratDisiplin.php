<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratDisiplin extends Model
{
    protected $table = 'surat_disiplin';
    protected $fillable = [
        'user_id',
        'pengajuan_id',
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
}
