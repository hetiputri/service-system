<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProsesLayananCuti extends Model
{
    protected $table = 'proses_layanan_cuti';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    protected $fillable = [
        'nama_admin',
        'tgl_kerja',
        'tgl_input',
        'scan_sk',
        'id_pemroses'
    ];

    public $incrementing = true;
    public $timestamps = true;
}
