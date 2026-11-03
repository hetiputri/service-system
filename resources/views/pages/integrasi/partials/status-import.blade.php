{{-- Status import terakhir. Selama berjalan, progres diambil berkala lalu halaman dimuat ulang saat selesai. --}}
@if ($berjalan)
<div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900"
    x-data="{
        persen: @js($log->total_baris > 0 ? min(100, (int) floor(($log->berhasil + $log->gagal) / $log->total_baris * 100)) : null),
        diproses: {{ $log->berhasil + $log->gagal }},
        total: {{ $log->total_baris }},
        status: @js($log->status),
        init() {
            const timer = setInterval(async () => {
                try {
                    const res = await fetch(@js(route('integrasi.siasn.import.status')), { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    if (!data) return;
                    Object.assign(this, { persen: data.persen, diproses: data.diproses, total: data.total, status: data.status });
                    if (!data.berjalan) { clearInterval(timer); window.location.reload(); }
                } catch (e) { /* coba lagi pada interval berikutnya */ }
            }, 3000);
        },
    }">
    <div class="flex items-center justify-between gap-3">
        <p>
            <strong x-text="status === 'menunggu' ? 'Menunggu antrean...' : 'Mengimport data SIASN...'"></strong>
            <span class="text-blue-700">{{ $log->nama_file }}</span>
        </p>
        <span class="shrink-0 font-semibold" x-show="total > 0">
            <span x-text="diproses.toLocaleString('en-US')"></span> / <span x-text="total.toLocaleString('en-US')"></span> baris
        </span>
    </div>
    <div class="mt-2 h-2 overflow-hidden rounded-full bg-blue-100">
        <div class="h-full rounded-full bg-blue-600 transition-all" :style="`width: ${persen ?? 5}%`"
            :class="persen === null ? 'animate-pulse' : ''"></div>
    </div>
    <p class="mt-2 text-xs text-blue-700" x-show="status === 'menunggu'">
        Bila status ini tidak berubah, pastikan queue worker berjalan (<code>php artisan queue:work</code>).
    </p>
</div>
@else
@php
// selain "selesai" berarti gagal, atau macet di menunggu/diproses (mis. queue worker berhenti)
$gagal = $log->status !== \App\Models\SiasnImportLogModel::STATUS_SELESAI;
$pesan = $log->status === \App\Models\SiasnImportLogModel::STATUS_GAGAL || !$gagal
    ? $log->pesan
    : 'Import tidak selesai, kemungkinan queue worker berhenti. Silakan upload ulang.';
@endphp
<div class="mb-6 rounded-2xl border p-4 text-sm {{ $gagal ? 'border-red-200 bg-red-50 text-red-800' : ($log->gagal ? 'border-amber-200 bg-amber-50 text-amber-800' : 'border-emerald-200 bg-emerald-50 text-emerald-800') }}"
    x-data="{ rinci: false }">
    <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
        <p>
            <strong>Import terakhir {{ $gagal ? 'gagal' : 'selesai' }}</strong>
            &middot; {{ $log->nama_file }}
            &middot; {{ ($log->selesai_at ?? $log->updated_at)->translatedFormat('d F Y, H:i') }}
            @if ($log->nama_user) &middot; oleh {{ $log->nama_user }} @endif
        </p>
        @if ($log->detail_gagal || $log->kolom_tidak_dikenal || $log->detail_id_bkn)
        <button type="button" @click="rinci = !rinci" class="shrink-0 text-xs font-semibold underline" x-text="rinci ? 'Sembunyikan rincian' : 'Lihat rincian'"></button>
        @endif
    </div>
    <p class="mt-1">{{ $pesan }}</p>

    <div x-show="rinci" style="display: none" class="mt-3 space-y-3 text-xs">
        @if ($log->kolom_tidak_dikenal)
        <div>
            <p class="font-semibold">Kolom file yang tidak dikenali (dilewati):</p>
            <p class="mt-1">{{ implode(', ', $log->kolom_tidak_dikenal) }}</p>
        </div>
        @endif
        @if ($log->detail_gagal)
        <div>
            <p class="font-semibold">Baris yang gagal{{ $log->gagal > count($log->detail_gagal) ? ' (' . count($log->detail_gagal) . ' pertama)' : '' }}:</p>
            <ul class="mt-1 max-h-48 space-y-0.5 overflow-y-auto">
                @foreach ($log->detail_gagal as $item)
                <li>Baris {{ $item['baris'] }}{{ $item['nip'] ? ' (NIP ' . $item['nip'] . ')' : '' }}: {{ $item['alasan'] }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if ($log->detail_id_bkn)
        <div>
            <p class="font-semibold">ID BKN SIMPEG (id_pegawai_bkn) yang tidak diisi{{ ($log->id_bkn_berbeda + $log->id_bkn_dilewati) > count($log->detail_id_bkn) ? ' (' . count($log->detail_id_bkn) . ' pertama)' : '' }}:</p>
            <ul class="mt-1 max-h-48 space-y-0.5 overflow-y-auto">
                @foreach ($log->detail_id_bkn as $item)
                <li>
                    {{ isset($item['nip']) ? 'NIP ' . $item['nip'] . ': ' : '' }}{{ $item['keterangan'] }}
                    @if (isset($item['id_simpeg']))
                    (SIMPEG: <span class="font-mono">{{ $item['id_simpeg'] }}</span>, SIASN: <span class="font-mono">{{ $item['id_siasn'] }}</span>)
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endif
