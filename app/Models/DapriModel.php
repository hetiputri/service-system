<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DapriModel extends Model
{
    protected $connection = 'mysql_second';
    protected $table = 'tbl_pegawai';
    protected $primaryKey = 'id_pegawai';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_pegawai',
        'nip',
        'nip_lama',
        'nama_pegawai',
        'ktp',
        'taspen',
        'no_karpeg',
        'no_askes',
        'foto',
        'id_status_kepegawaian',
        'id_jenis_kepegawaian',
        'id_kedudukan_kepegawaian',
        'id_instansi',
        'id_satuan_organisasi',
        'id_pangkat',
        'id_tipe_jabatan',
        'id_jabatan',
        'gelar_depan',
        'gelar_belakang',
        'id_golongan_darah',
        'id_jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'rt',
        'rw',
        'marga',
        'id_suku',
        'id_agama',
        'id_provinsi',
        'id_kabupaten',
        'id_kecamatan',
        'id_kelurahan',
        'kode_pos',
        'no_telp',
        'no_hp',
        'email',
        'nama_ayah',
        'nama_ibu',
        'id_status_perkawinan',
        'npwp',
        'tinggi',
        'berat',
        'id_rambut',
        'id_bentuk_muka',
        'id_warna_kulit',
        'ciri_khas',
        'cacat_tubuh',
        'hobi',
        'tipe_pegawai',
        'status_supervisi',
        'posisi_data',
        'id_pembuat',
        'status',
        'catatan',
        'id_pendidikan',
        'id_jenjang_jabfung',
        'id_pegawai_bkn',
        'id_pengadaan',
        'id_hukuman',
        'jam_jp'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_pegawai', $data['id_pegawai']);
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
