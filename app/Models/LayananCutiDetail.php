<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananCutiDetail extends Model
{
    use SoftDeletes;

    protected $table = 'layanan_cuti_detail';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    protected $fillable = [
        'pengajuan_id',
        'kaopd_id',
        'atasan_langsung_id',
        'lama_cuti',
        'satuan_waktu',
        'tgl_mulai',
        'tgl_selesai',
        'deskripsi',
        'no_telepon',
        'alamat'
    ];

    public $incrementing = true;
    public $timestamps = true;
}
