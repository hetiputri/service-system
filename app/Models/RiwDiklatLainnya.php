<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwDiklatLainnya extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_riwayat_diklat_lainnya';
    protected $primaryKey = 'id_riwayat_diklat_lainnya';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_riwayat_diklat_lainnya',
        'id_riwayat_diklat',
        'id_bentuk_diklat',
        'id_pegawai',
        'nama_diklat',
        'tempat_diklat',
        'penyelenggara',
        'anggaran',
        'mulai',
        'selesai',
        'lama',
        'no_ijazah',
        'tgl_ijazah',
        'peringkat',
        'img_ijazah',
        'status_supervisi',
        'catatan_dari_bkd'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_riwayat_diklat_lainnya', $data['id_riwayat_diklat_lainnya'])
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
