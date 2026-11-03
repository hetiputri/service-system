<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwJabatanModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_riwayat_jabatan';
    protected $primaryKey = 'id_riwayat_jabatan';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_riwayat_jabatan',
        'id_pegawai',
        'id_jabatan',
        'jabatan',
        'id_tipe_jabatan',
        'id_jab_ganda',
        'id_plt',
        'id_keterangan_jabatan',
        'id_eselon',
        'id_skpd',
        'skpd',
        'id_unit',
        'sub_unit',
        'tmt',
        'tmt_akhir',
        'gol_pppk',
        'no_sk_jabatan',
        'tgl_sk_jabatan',
        'pejabat_penetapan',
        'no_sk_pelantikan',
        'tgl_sk_pelantikan',
        'pejabat_pelantik',
        'sumpah_jabatan',
        'img_sk_jabatan',
        'img_sk_pelantikan',
        'urut',
        'kedudukan',
        'jenis_jabfung',
        'jenjang_jabfung',
        'id_jenjang',
        'status_supervisi',
        'catatan_dari_bkd',
        'status_sync',
        'tanggal_sync'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_riwayat_jabatan', $data['id_riwayat_jabatan'])
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
