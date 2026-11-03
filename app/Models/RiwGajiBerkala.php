<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwGajiBerkala extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tblriwayatgaji';
    protected $primaryKey = 'id_riwayat_gaji';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_riwayat_gaji',
        'id_pegawai',
        'NIP',
        'TMTGaji',
        'NoSKGaji',
        'TglSKGaji',
        'PejabatSK',
        'tgl_sk_usulan',
        'mkg_tahun',
        'mkg_bulan',
        'Keterangan',
        'status_spv',
        'catatan_dari_bkd',
        'status_sync',
        'tanggal_sync'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_riwayat_gaji', $data['id_riwayat_gaji'])
            ->where(function ($join) use ($data) {
                $join->where('id_pegawai', $data['id_pegawai'])
                    ->orWhere('NIP', $data['NIP']);
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
