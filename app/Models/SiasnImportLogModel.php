<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Riwayat import file export SIASN ke siasn_data_utama
class SiasnImportLogModel extends Model
{
    protected $connection = 'mysql_third';
    protected $table = 'siasn_import_log';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_GAGAL = 'gagal';

    protected $fillable = [
        'nama_file',
        'path',
        'mode',
        'status',
        'total_baris',
        'berhasil',
        'gagal',
        'id_bkn_diisi',
        'id_bkn_berbeda',
        'id_bkn_dilewati',
        'detail_id_bkn',
        'dihapus',
        'pesan',
        'detail_gagal',
        'kolom_tidak_dikenal',
        'id_user',
        'nama_user',
        'mulai_at',
        'selesai_at',
    ];

    protected $casts = [
        'detail_gagal' => 'array',
        'kolom_tidak_dikenal' => 'array',
        'detail_id_bkn' => 'array',
        'mulai_at' => 'datetime',
        'selesai_at' => 'datetime',
    ];

    public $timestamps = true;

    // job memperbarui log setiap blok baris; log yang 2 jam tidak berubah dianggap macet (mis. queue worker mati)
    public function sedangBerjalan(): bool
    {
        return in_array($this->status, [self::STATUS_MENUNGGU, self::STATUS_DIPROSES], true)
            && $this->updated_at?->gt(now()->subHours(2));
    }
}
