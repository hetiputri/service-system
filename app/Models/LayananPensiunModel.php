<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananPensiunModel extends Model
{
    use SoftDeletes;

    protected $table = 'layanan_pensiun_detail';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    protected $fillable = [
        'pengajuan_id',
        'pegawai_id',
        'tmt_pensiun',
        'no_sk',
        'tgl_sk',
        'tgl_pertek',
        'pejabat_ttd',
        'id_keterangan_jabatan'
    ];

    public $incrementing = true;
    public $timestamps = true;
}
