<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Riwayat sync data SIASN ke SIMPEG, satu baris per kolom tbl_pegawai yang diubah
class SiasnSyncLogModel extends Model
{
    protected $connection = 'mysql_third';
    protected $table = 'siasn_sync_log';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    protected $fillable = [
        'nip',
        'id_pegawai',
        'id_siasn',
        'arah',
        'atribut',
        'tabel',
        'kolom',
        'nilai_lama',
        'nilai_baru',
        'id_user',
        'nama_user',
    ];

    public $timestamps = true;
}
