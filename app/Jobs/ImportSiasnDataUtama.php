<?php

namespace App\Jobs;

use App\Imports\SiasnDataUtamaImport;
use App\Models\SiasnImportLogModel;
use App\Services\Integrasi\IntegrasiSiasnService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

// Memproses file export SIASN yang diupload dari Dashboard Integrasi (lihat IntegrasiController::importSiasn)
class ImportSiasnDataUtama implements ShouldQueue
{
    use Queueable;

    // file export seluruh ASN bisa puluhan ribu baris
    public $timeout = 3600;
    public $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $idLog)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $log = SiasnImportLogModel::find($this->idLog);
        if (!$log || $log->status !== SiasnImportLogModel::STATUS_MENUNGGU) {
            return;
        }

        // uji 22.000 baris memakai sekitar 100 MB, melewati batas bawaan 128 MB bila kolom export lengkap
        ini_set('memory_limit', '512M');

        $log->update([
            'status' => SiasnImportLogModel::STATUS_DIPROSES,
            'mulai_at' => now(),
            'total_baris' => $this->hitungBaris(Storage::disk('local')->path($log->path)),
        ]);

        $import = new SiasnDataUtamaImport($log);
        Excel::import($import, $log->path, 'local');

        $pesan = number_format($import->berhasil) . ' data berhasil diimport';
        if ($import->gagal) {
            $pesan .= ', ' . number_format($import->gagal) . ' baris gagal';
        }
        $pesan .= '. ID BKN SIMPEG (id_pegawai_bkn): ' . number_format($import->idBknDiisi) . ' diisi';
        if ($import->idBknBerbeda) {
            $pesan .= ', ' . number_format($import->idBknBerbeda) . ' berbeda (tidak diubah)';
        }
        if ($import->idBknDilewati) {
            $pesan .= ', ' . number_format($import->idBknDilewati) . ' dilewati';
        }

        $log->update([
            'status' => SiasnImportLogModel::STATUS_SELESAI,
            'berhasil' => $import->berhasil,
            'gagal' => $import->gagal,
            'id_bkn_diisi' => $import->idBknDiisi,
            'id_bkn_berbeda' => $import->idBknBerbeda,
            'id_bkn_dilewati' => $import->idBknDilewati,
            'detail_id_bkn' => $import->detailIdBkn,
            'detail_gagal' => $import->detailGagal,
            'kolom_tidak_dikenal' => $import->kolomTidakDikenal,
            'pesan' => $pesan . '.',
            'selesai_at' => now(),
        ]);

        // file berisi data pribadi ASN, tidak disimpan setelah berhasil diproses
        Storage::disk('local')->delete($log->path);
        IntegrasiSiasnService::hapusCache();
    }

    public function failed(?Throwable $exception): void
    {
        $log = SiasnImportLogModel::find($this->idLog);
        if (!$log) {
            return;
        }

        $log->update([
            'status' => SiasnImportLogModel::STATUS_GAGAL,
            'pesan' => mb_substr($exception?->getMessage() ?? 'Import gagal.', 0, 1000),
            'selesai_at' => now(),
        ]);
        Storage::disk('local')->delete($log->path);
        // sebagian blok mungkin sudah tersimpan sebelum gagal
        IntegrasiSiasnService::hapusCache();
    }

    // perkiraan jumlah baris data (tanpa judul) untuk menampilkan progres
    private function hitungBaris(string $path): int
    {
        try {
            $info = IOFactory::createReaderForFile($path)->listWorksheetInfo($path);

            return max(0, ($info[0]['totalRows'] ?? 1) - 1);
        } catch (Throwable $e) {
            return 0;
        }
    }
}
