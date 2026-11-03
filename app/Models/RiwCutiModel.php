<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwCutiModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_riwayat_cuti';
    protected $primaryKey = 'id_riwayat_cuti';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_riwayat_cuti',
        'id_pegawai',
        'id_jenis_cuti',
        'lama',
        'dari',
        'sampai',
        'keterangan',
        'no_sk',
        'tgl_sk',
        'pejabat_sk',
        'scan_sk',
        'status_spv'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_riwayat_cuti', $data['id_riwayat_cuti'])
            ->where('id_pegawai', $data['id_pegawai']);
        if ($record->count() > 0) {
            $record = $record->first();
            $record->update($data);
            return 'berhasil update';
        } else {
            if ($data['id_pegawai'] > 0) {
                self::create($data);
                return 'berhasil create';
            }
        }
    }
}
