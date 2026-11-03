<?php

namespace App\Models;

use App\Enums\Mutasi\StatusValidasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MutasiStrukturalDetail extends Model
{
    use SoftDeletes;

    protected $table = 'mutasi_struktural_detail';

    protected $fillable = [
        'id_konsep',
        'urutan',
        'id_pegawai',
        'dari_id_jabatan',
        'ke_id_jabatan',
        'jenis_mutasi',
        'alasan',
        'status_validasi',
        'catatan',
    ];

    protected $casts = [
        'status_validasi' => StatusValidasi::class,
    ];

    public function mutasiStrukturalDetail()
    {
        $this->belongsTo(MutasiStruktural::class, 'id_konsep', 'id');
    }
}
