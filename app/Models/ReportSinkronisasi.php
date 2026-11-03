<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportSinkronisasi extends Model
{
    protected $table = 'report_sinkronisasi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tgl_proses',
        'id_data',
        'nama_data',
        'status',
        'pesan',
    ];

    public $incrementing = true;
    public $timestamps = true;


    public static function InsertData($tgl_proses, $id_data, $nama_data, $status, $pesan)
    {
        self::create([
            'tgl_proses' => $tgl_proses,
            'id_data' => $id_data,
            'nama_data' => $nama_data,
            'status' => $status,
            'pesan' => $pesan
        ]);
    }
}
