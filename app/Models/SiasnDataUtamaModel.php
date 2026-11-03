<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// Data utama ASN hasil penarikan API SIASN BKN
class SiasnDataUtamaModel extends Model
{
    protected $connection = 'mysql_third';
    protected $table = 'siasn_data_utama';
    // primary key = ID ASN dari SIASN (string), bukan auto increment
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    // kolom date: API SIASN mengirim format dd-mm-yyyy, disimpan sebagai Y-m-d
    public const KOLOM_TANGGAL = [
        'tglLahir',
        'tglSkPns',
        'tmtCpns',
        'tglSkCpns',
        'tmtJabatan',
        'tmtGolAkhir',
        'tmtEselon',
        'tglSuratKeteranganDokter',
        'tglSttpl',
    ];

    protected $fillable = [
        'id',
        'nipBaru',
        'nipLama',
        'nama',
        'gelarDepan',
        'gelarBelakang',
        'tempatLahir',
        'tempatLahirId',
        'tglLahir',
        'agama',
        'agamaId',
        'email',
        'emailGov',
        'nik',
        'alamat',
        'noHp',
        'noTelp',
        'jenisPegawaiId',
        'mkTahun',
        'mkBulan',
        'jenisPegawaiNama',
        'kedudukanPnsId',
        'kedudukanPnsNama',
        'statusPegawai',
        'jenisKelamin',
        'jenisIdDokumenId',
        'jenisIdDokumenNama',
        'nomorIdDocument',
        'noSeriKarpeg',
        'tkPendidikanTerakhirId',
        'tkPendidikanTerakhir',
        'pendidikanTerakhirId',
        'pendidikanTerakhirNama',
        'tahunLulus',
        'tmtPns',
        'tmtPensiun',
        'bupPensiun',
        'tglSkPns',
        'tmtCpns',
        'tglSkCpns',
        'instansiIndukId',
        'instansiIndukNama',
        'satuanKerjaIndukId',
        'satuanKerjaIndukNama',
        'kanregId',
        'kanregNama',
        'instansiKerjaId',
        'instansiKerjaNama',
        'instansiKerjaKodeCepat',
        'satuanKerjaKerjaId',
        'satuanKerjaKerjaNama',
        'unorId',
        'unorNama',
        'unorIndukId',
        'unorIndukNama',
        'jenisJabatanId',
        'jenisJabatan',
        'jabatanNama',
        'jabatanStrukturalId',
        'jabatanStrukturalNama',
        'jabatanFungsionalId',
        'jabatanFungsionalNama',
        'jabatanFungsionalUmumId',
        'jabatanFungsionalUmumNama',
        'tmtJabatan',
        'lokasiKerjaId',
        'lokasiKerja',
        'golRuangAwalId',
        'golRuangAwal',
        'golRuangAkhirId',
        'golRuangAkhir',
        'tmtGolAkhir',
        'masaKerja',
        'eselon',
        'eselonId',
        'eselonLevel',
        'tmtEselon',
        'gajiPokok',
        'kpknId',
        'kpknNama',
        'ktuaId',
        'ktuaNama',
        'taspenId',
        'taspenNama',
        'jenisKawinId',
        'statusPerkawinan',
        'statusHidup',
        'tglSuratKeteranganDokter',
        'noSuratKeteranganDokter',
        'jumlahIstriSuami',
        'jumlahAnak',
        'noSuratKeteranganBebasNarkoba',
        'tglSuratKeteranganBebasNarkoba',
        'skck',
        'tglSkck',
        'akteKelahiran',
        'akteMeninggal',
        'tglMeninggal',
        'noNpwp',
        'tglNpwp',
        'noAskes',
        'bpjs',
        'kodePos',
        'noSpmt',
        'tglSpmt',
        'noTaspen',
        'bahasa',
        'kppnId',
        'kppnNama',
        'pangkatAkhir',
        'tglSttpl',
        'nomorSttpl',
        'nomorSkCpns',
        'nomorSkPns',
        'jenjang',
        'jabatanAsn',
        'kartuAsn',
        'validNik',
        'validKk',
        'pangkatAwal',
        'asnJenjangJabatan',
        'kode_jenjang_jabatan',
        'levelJabatan',
        'tanggal_taspen',
        'tabrum2',
        'kelas_jabatan',
        'karis_karsu',
        'path',
        'genEmailAsn',
        'pertekCpnsPnsl2thNomor',
        'pertekCpnsPnsl2thTanggal',
        'nomorSuratKeteranganDokter',
        'subJftId',
        'subJftNama',
        'gelarDepanProfesi',
        'gelarBelakangProfesi',
        'gelarDepanSertifikasi',
        'gelarBelakangSertifikasi',
        'tglAwalKontrak',
        'tglAkhirKontrak',
    ];

    // tanggal ditampilkan kembali dengan format dd-mm-yyyy seperti di SIASN
    protected $casts = [
        'tglLahir' => 'date:d-m-Y',
        'tglSkPns' => 'date:d-m-Y',
        'tmtCpns' => 'date:d-m-Y',
        'tglSkCpns' => 'date:d-m-Y',
        'tmtJabatan' => 'date:d-m-Y',
        'tmtGolAkhir' => 'date:d-m-Y',
        'tmtEselon' => 'date:d-m-Y',
        'tglSuratKeteranganDokter' => 'date:d-m-Y',
        'tglSttpl' => 'date:d-m-Y',
        'mkTahun' => 'integer',
        'mkBulan' => 'integer',
        'bupPensiun' => 'integer',
        'validNik' => 'boolean',
        'validKk' => 'boolean',
    ];

    public $timestamps = true;

    /**
     * Simpan data utama dari respons API SIASN (key sama dengan nama kolom).
     * Data dicocokkan berdasarkan id (ID ASN SIASN): update jika sudah ada, create jika belum.
     */
    public static function createorupdate(array $data)
    {
        $data = self::siapkanBaris($data);
        if (!$data) {
            return 'id atau nipBaru tidak valid';
        }

        $record = self::updateOrCreate(['id' => $data['id']], $data);

        return $record->wasRecentlyCreated ? 'berhasil create' : 'berhasil update';
    }

    /**
     * Rapikan satu baris dari API atau file export SIASN supaya siap disimpan:
     * hanya kolom fillable, string kosong -> null, tanggal -> Y-m-d, ya/tidak -> boolean.
     * Mengembalikan null bila id (ID ASN SIASN) kosong atau NIP bukan 18 digit.
     */
    public static function siapkanBaris(array $data): ?array
    {
        $baris = [];
        foreach ((new self)->getFillable() as $kolom) {
            if (!array_key_exists($kolom, $data)) {
                continue;
            }
            $nilai = is_string($data[$kolom]) ? trim($data[$kolom]) : $data[$kolom];
            $baris[$kolom] = $nilai === '' ? null : $nilai;
        }

        $baris['nipBaru'] = preg_replace('/\D/', '', (string) ($baris['nipBaru'] ?? ''));
        if (empty($baris['id']) || strlen($baris['nipBaru']) !== 18) {
            return null;
        }
        $baris['id'] = (string) $baris['id'];

        foreach (self::KOLOM_TANGGAL as $kolom) {
            if (array_key_exists($kolom, $baris)) {
                $baris[$kolom] = self::tanggalDariApi($baris[$kolom]);
            }
        }
        foreach (['validNik', 'validKk'] as $kolom) {
            if (array_key_exists($kolom, $baris) && $baris[$kolom] !== null) {
                $baris[$kolom] = in_array(strtolower((string) $baris[$kolom]), ['1', 'true', 'ya', 'y', 'yes', 'valid'], true);
            }
        }
        foreach (['mkTahun', 'mkBulan', 'bupPensiun'] as $kolom) {
            if (array_key_exists($kolom, $baris)) {
                $baris[$kolom] = is_numeric($baris[$kolom]) ? (int) $baris[$kolom] : null;
            }
        }

        return $baris;
    }

    // dd-mm-yyyy, dd/mm/yyyy, Y-m-d atau nomor tanggal Excel -> Y-m-d; kosong atau tanggal tidak valid -> null
    public static function tanggalDariApi($nilai): ?string
    {
        if ($nilai instanceof \DateTimeInterface) {
            return $nilai->format('Y-m-d');
        }

        $nilai = trim((string) $nilai);
        if (is_numeric($nilai) && (float) $nilai > 0 && (float) $nilai < 100000) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $nilai)->format('Y-m-d');
        }

        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $nilai, $m)) {
            [$tahun, $bulan, $hari] = [(int) $m[1], (int) $m[2], (int) $m[3]];
        } elseif (preg_match('/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/', $nilai, $m)) {
            [$hari, $bulan, $tahun] = [(int) $m[1], (int) $m[2], (int) $m[3]];
        } else {
            return null;
        }

        return checkdate($bulan, $hari, $tahun)
            ? Carbon::createFromDate($tahun, $bulan, $hari)->format('Y-m-d')
            : null;
    }
}
