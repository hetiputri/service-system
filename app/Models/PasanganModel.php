<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasanganModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_pasangan';
    protected $primaryKey = 'id_pasangan';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_pasangan',
        'id_pegawai',
        'status_pernikahan',
        'pasangan_ke',
        'id_pekerjaan',
        'status_hidup',
        'nama_pasangan',
        'tempat_lahir',
        'tgl_lahir',
        'id_jenis_kelamin',
        'id_agama',
        'id_pendidikan',
        'pekerjaan',
        'no_telp',
        'nik_pasangan',
        'no_akta_nikah',
        'tgl_nikah',
        'no_akta_cerai',
        'tgl_cerai',
        'no_akta_meninggal',
        'tgl_meninggal',
        'alamat',
        'rt',
        'rw',
        'id_provinsi',
        'id_kabupaten',
        'id_kecamatan',
        'id_kelurahan',
        'kode_pos',
        'surat_nikah',
        'kartu_keluarga',
        'karisu',
        'askes',
        'akta_meninggal',
        'akta_cerai',
        'status_supervisi'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_pasangan', $data['id_pasangan'])
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
