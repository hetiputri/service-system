<?php

namespace App\Services\Integrasi;

use App\Models\SiasnSyncLogModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Menampilkan riwayat sync SIASN -> SIMPEG (siasn_sync_log di db_siasn).
 * Nilai yang tersimpan apa adanya di tbl_pegawai (mis. id_agama) diterjemahkan ke nama dari referensi SIMPEG.
 */
class RiwayatSyncService
{
    private ?array $referensi = null;

    public function tersedia(): bool
    {
        try {
            return Schema::connection('mysql_third')->hasTable('siasn_sync_log');
        } catch (Throwable $e) {
            report($e);
            return false;
        }
    }

    /**
     * Sumber data DataTables riwayat sync satu pegawai di halaman detail.
     * Pencarian: nama data, kolom SIMPEG, atau nama pengguna yang melakukan sync.
     */
    public function tabel(string $nip, string $cari, ?string $dari, ?string $sampai, int $mulai, int $jumlah, string $arah = 'desc'): array
    {
        if (!$this->tersedia()) {
            return ['total' => 0, 'terfilter' => 0, 'data' => []];
        }

        $query = SiasnSyncLogModel::query()
            ->where('nip', $nip)
            ->when($dari, fn($q) => $q->where('created_at', '>=', $dari . ' 00:00:00'))
            ->when($sampai, fn($q) => $q->where('created_at', '<=', $sampai . ' 23:59:59'));
        $total = (clone $query)->count();

        $cari = trim($cari);
        if ($cari !== '') {
            $atribut = collect(array_merge(...array_values(IntegrasiSiasnService::ATRIBUT_PEGAWAI)))
                ->filter(fn($label) => stripos($label, $cari) !== false)->keys();

            $query->where(fn($q) => $q
                ->where('nama_user', 'like', '%' . $cari . '%')
                ->orWhere('kolom', 'like', '%' . $cari . '%')
                ->orWhereIn('atribut', $atribut));
        }

        $terfilter = (clone $query)->count();
        $baris = $query->orderBy('id', $arah === 'asc' ? 'asc' : 'desc')->skip($mulai)->take($jumlah)->get();

        return ['total' => $total, 'terfilter' => $terfilter, 'data' => $this->tampilkan($baris)];
    }

    private function tampilkan($baris): array
    {
        $label = array_merge(...array_values(IntegrasiSiasnService::ATRIBUT_PEGAWAI));

        return $baris->map(fn($log) => [
            'waktu' => $log->created_at?->translatedFormat('d M Y, H:i'),
            'data' => $label[$log->atribut] ?? $log->atribut,
            'kolom' => $log->tabel . '.' . $log->kolom,
            'nilai_lama' => $this->nilaiTampil($log->kolom, $log->nilai_lama),
            'nilai_baru' => $this->nilaiTampil($log->kolom, $log->nilai_baru),
            'pengguna' => $log->nama_user,
        ])->values()->all();
    }

    // nilai kolom tbl_pegawai dalam bentuk yang bisa dibaca, mis. id_agama 3 -> "Kristen Protestan"
    private function nilaiTampil(string $kolom, $nilai): ?string
    {
        if ($nilai === null || $nilai === '') {
            return null;
        }

        $referensi = $this->referensi();
        return match ($kolom) {
            'id_agama' => $referensi['agama'][$nilai] ?? "ID {$nilai}",
            'id_jenis_kelamin' => $referensi['jenis_kelamin'][$nilai] ?? "ID {$nilai}",
            'tanggal_lahir' => preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $nilai, $m) ? "{$m[3]}-{$m[2]}-{$m[1]}" : $nilai,
            default => (string) $nilai,
        };
    }

    private function referensi(): array
    {
        if ($this->referensi === null) {
            $db = DB::connection('mysql_second');
            $this->referensi = [
                'agama' => $db->table('ref_agama')->pluck('agama', 'id_agama')->all(),
                'jenis_kelamin' => $db->table('ref_jenis_kelamin')->pluck('jenis_kelamin', 'id_jenis_kelamin')->all(),
            ];
        }

        return $this->referensi;
    }
}
