<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiUploadModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_pegawai_upload';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_pegawai',
        'pasfoto',
        'karpeg',
        'scan_ktp',
        'scan_konversi_nip',
        'scan_npwp',
        'scan_taspen',
        'akta_kelahiran',
        'sk_pencantuman_gelar',
        'askes_pribadi',
        'sk_cpns',
        'sk_pns',
        'sumpah_janji',
        'catatan_upload',
        'catatan_karpeg_dan_pasfoto',
        'catatan_cpns',
        'catatan_pns'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_pegawai', $data['id_pegawai'])
            ->where('id_pegawai', '>', 0);

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
