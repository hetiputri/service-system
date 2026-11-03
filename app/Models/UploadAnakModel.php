<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadAnakModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_pegawai_upload_anak';
    protected $primaryKey = 'id_tabel';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_tabel',
        'id_pegawai',
        'id_anak',
        'scan_askes_anak',
        'status'
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
