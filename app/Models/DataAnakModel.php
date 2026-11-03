<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataAnakModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_anak';
    protected $primaryKey = 'id_anak';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_anak',
        'id_pegawai',
        'nama_anak',
        'id_jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'pekerjaan',
        'pendidikan',
        'status_keluarga',
        'status_tunjangan',
        'status_supervisi',
        'nama_ayah',
        'nama_ibu',
        'id_bkn_anak'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_anak', $data['id_anak'])
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
