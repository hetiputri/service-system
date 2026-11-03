<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwPendidikanModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_pendidikan';
    protected $primaryKey = 'id_pendidikan';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_pendidikan',
        'id_pegawai',
        'id_tingkat_pendidikan',
        'id_detail_pendidikan',
        'id_jurusan',
        'nama_sekolah',
        'tempat',
        'nama_kepala',
        'no_ijazah',
        'tanggal_ijazah',
        'ip',
        'img_ijazah',
        'keterangan',
        'status_supervisi',
        'catatan_dari_bkd'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_pendidikan', $data['id_pendidikan'])
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
