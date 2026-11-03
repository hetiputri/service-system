<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPnsModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_pns';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    protected $fillable = [
        'id',
        'id_pegawai',
        'NIP',
        'NoSK',
        'TanggalSK',
        'PejabatSK',
        'NoKetKesehatan',
        'TanggalKetKesehatan',
        'NoSTTPLPrajab',
        'TanggalSTTPLPrajab',
        'TMTPNS',
        'KodePangkat',
        'MKGTahun',
        'MKGBulan',
        'NoNota',
        'TanggalNota',
        'id_pengadaan',
        'no_sk_spj',
        'tgl_sk_spj',
        'status_spv'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_pegawai', $data['id_pegawai'])
            ->orWhere('NIP', $data['NIP']);
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
