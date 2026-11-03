<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProsesLayananCutiDetail extends Model
{
    protected $table = 'proses_layanan_cuti_detail';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_proses',
        'pengajuan_id',
        'tgl_proses',
        'no_sk',
        'tgl_sk',
        'pejabat_sk',
        'scan_sk',
        'id_pemroses'
    ];

    public $incrementing = true;
    public $timestamps = true;
}
