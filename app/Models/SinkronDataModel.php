<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinkronDataModel extends Model
{
    protected $table = 'tbl_sinkron_data';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama',
        'nama_tabel',
        'status_update',
        //'jumlah_record',

    ];

    public $incrementing = true;
    public $timestamps = true;
}
