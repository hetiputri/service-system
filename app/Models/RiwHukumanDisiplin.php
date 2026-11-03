<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwHukumanDisiplin extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_riwayat_hukuman_disiplin';
    protected $primaryKey = 'id_riwayat_hd';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_riwayat_hd',
        'id_pegawai',
        'no_sk',
        'tgl_sk',
        'id_jenis_hukuman',
        'id_sub_jenis_hukuman',
        'tmt',
        'tmt_akhir',
        'pejabat_ttd_sk',
        'scan_sk',
        'status_spv',
        'alasan_hukdis_id',
        'golongan_id',
        'golongan_lama',
        'hukdis_yg_diberhentikan_id',
        'keterangan',
        'masa_bulan',
        'masa_tahun',
        'nomor_pp',
        'no_sk_pembatalan',
        'tgl_sk_pembatalan'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_riwayat_hd', $data['id_riwayat_hd'])
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
