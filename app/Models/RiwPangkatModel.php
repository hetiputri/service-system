<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwPangkatModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_riwayat_pangkat';
    protected $primaryKey = 'id_riwayat_pangkat';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_riwayat_pangkat',
        'id_pegawai',
        'id_pangkat',
        'tmt',
        'no_sk',
        'tgl_sk',
        'pejabat_penetapan',
        'img_sk',
        'urut',
        'mkg_tahun',
        'mkg_bulan',
        'no_bkn',
        'tgl_bkn',
        'keterangan',
        'angka_kredit',
        'status_supervisi',
        'catatan_dari_bkd',
        'no_sk_lama',
        'tgl_sk_lama'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_riwayat_pangkat', $data['id_riwayat_pangkat'])
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
