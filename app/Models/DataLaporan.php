<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataLaporan extends Model
{
    protected $table = 'laporan_data';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    protected $fillable = [

        'nilai_data',
        'created_at',
        'updated_at'
    ];
}
