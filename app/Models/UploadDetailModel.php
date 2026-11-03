<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadDetailModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_pegawai_upload_detail';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_pegawai',
        'id_jenis',
        'id_riwayat',
        'scan_sk',
        'catatan_upload',
        'scan_temp'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $upload = self::where('id_pegawai', $data['id_pegawai'])
            ->where('id_jenis', $data['id_jenis'])
            ->where('id_riwayat', $data['id_riwayat']);
        if ($upload->count() > 0) {
            $upload = $upload->first();
            $upload->update($data);
            return 'berhasil update';
        } else {
            if ($data['id_pegawai'] > 0) {
                self::create($data);
                return 'berhasil create';
            }
        }
    }
}
