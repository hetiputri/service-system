<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwPenghargaan extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_riwayat_penghargaan';
    protected $primaryKey = 'id_riwayat_penghargaan';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_riwayat_penghargaan',
        'id_pegawai',
        'id_penghargaan',
        'nama_penghargaan',
        'no_sk',
        'tgl_sk',
        'tahun',
        'asal_perolehan',
        'status_spv'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_riwayat_penghargaan', $data['id_riwayat_penghargaan'])
            ->where('id_pegawai',  $data['id_pegawai']);
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
