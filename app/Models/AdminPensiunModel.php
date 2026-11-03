<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminPensiunModel extends Model
{
    protected $table = 'wilayah_admin_pensiun';

    protected $fillable = [
        'id_pegawai',
        'id_skpd',
        'kode_skpd',
    ];

    public $timestamps = true;
}
