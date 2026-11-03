<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwPmkModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_riwayat_pmk';
    protected $primaryKey = 'id_riwayat_pmk';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_riwayat_pmk',
        'id_pegawai',
        'nip',
        'pengalaman',
        'tgl_mulai',
        'tgl_selesai',
        'dinilai',
        'no_persetujuan',
        'tgl_persetujuan',
        'tmt',
        'no_sk',
        'tgl_sk',
        'pejabat_penetapan',
        'mkg_tahun',
        'mkg_bulan',
        'catatan_dari_bkd',
        'status_spv'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_riwayat_pmk', $data['id_riwayat_pmk'])
            ->where(function ($join) use ($data) {
                $join->where('id_pegawai',  $data['id_pegawai'])
                    ->orWhere('nip',  $data['nip']);
            });
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
