<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwPenyIjazah extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_riwayat_pi';
    protected $primaryKey = 'id_riwayat_penyesuaian_ijazah';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_riwayat_penyesuaian_ijazah',
        'id_pegawai',
        'id_tingkat_pendidikan',
        'no_stlupi',
        'tgl_sertifikat_nilai',
        'program_studi',
        'pejabat_sertifikat',
        'pejabat_nilai',
        'status_spv',
        'catatan_dari_bkd'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_riwayat_penyesuaian_ijazah', $data['id_riwayat_penyesuaian_ijazah'])
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
