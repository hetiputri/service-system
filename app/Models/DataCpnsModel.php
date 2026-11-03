<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataCpnsModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tblcpns';
    protected $primaryKey = 'id_tblcpns';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_pegawai',
        'NIP',
        'NoNota',
        'TanggalNota',
        'NoSK',
        'TanggalSK',
        'PejabatSK',
        'KodePangkat',
        'MKGTahun',
        'MKGBulan',
        'TMTCPNS',
        'KodeTkPendidikan',
        'Jurusan',
        'NamaJabatan',
        'NamaSKPD',
        'NoSPMT',
        'TglSPMT',
        'id_pendidikan_cpns',
        'id_detail_pendidikan',
        'id_pengadaan',
        'status_supervisi'
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
