<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dpcp extends Model
{
    protected $table = 'dpcp';

    protected $fillable = [

        'pegawai_id',
        'pengajuan_layanan_id',
        'pasfoto',
        'nama_ttd',
        'jenis_pensiun_id',
        'data_pribadi',
        'riwayat_keluarga',
        'data_cpns',
        'data_pns',
        'tmt_pensiun',
        'alamat_sesudah_pensiun',
        'signature_base64',
        'signature_file',
        'signed_at',
        'status_pengajuan',
        'created_at',
        'updated_at'
    ];
}
