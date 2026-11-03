@extends('layouts.app')

@section('title', 'Detail Pegawai - Integrasi SIASN')

@section('content')

@php
$data = $pegawai ? ($pegawai['simpeg'] ?? $pegawai['siasn']) : null;
// sebab tombol sync nonaktif untuk seluruh pegawai (null = boleh)
$alasanKeSiasn = !\App\Http\Controllers\Integrasi\IntegrasiController::SYNC_KE_SIASN_AKTIF ? 'Sync ke SIASN belum diaktifkan'
    : (!$pegawai ? null : (!$pegawai['siasn'] ? 'Pegawai belum terdaftar di SIASN' : (!$pegawai['simpeg'] ? 'Pegawai belum ada di SIMPEG' : null)));
$alasanKeSimpeg = !$pegawai ? null : (!$pegawai['simpeg'] ? 'Pegawai belum ada di SIMPEG, tidak bisa di-sync' : (!$pegawai['siasn'] ? 'Tidak ada data SIASN' : null));
// data yang bisa dipilih untuk di-sync: ke SIMPEG baru data pribadi (IntegrasiSiasnService::KOLOM_SYNC_SIMPEG)
$bisaSync = fn($key) => in_array($key, $kolomSyncSimpeg, true) || \App\Http\Controllers\Integrasi\IntegrasiController::SYNC_KE_SIASN_AKTIF;
if ($pegawai && !$pegawai['simpeg']) {
    $sumber = ['label' => 'Hanya ada di SIASN', 'class' => 'bg-red-100 text-red-700'];
} elseif ($pegawai && !$pegawai['siasn']) {
    $sumber = ['label' => 'Hanya ada di SIMPEG', 'class' => 'bg-red-100 text-red-700'];
} else {
    $sumber = ['label' => 'Ada di SIMPEG & SIASN', 'class' => 'bg-emerald-50 text-emerald-700'];
}
// baris yang berbeda, dipakai untuk tombol sync massal
$barisBerbeda = collect($kelompok)->flatten(1)->where('berbeda', true)->filter(fn($item) => $bisaSync($item['key']))
    ->map(fn($item) => collect($item)->only(['key', 'label', 'simpeg', 'siasn']))->values();
@endphp

<div class="bg-white border rounded-2xl shadow-sm p-5 mb-6">
    <div class="flex flex-col justify-between gap-3 lg:flex-row lg:items-end">
        <div>
            <a href="{{ route('integrasi.siasn.dashboard') }}" class="text-sm font-semibold text-blue-600 hover:underline">&larr; Kembali ke Dashboard Integrasi</a>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Perbandingan Data Pegawai</h1>
            <p class="mt-1 text-sm text-slate-500">Data SIMPEG dibandingkan dengan data SIASN BKN per atribut.</p>
        </div>
        @include('pages.integrasi.partials.cari-nip')
    </div>
</div>

@if (session('success'))
<div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-800">{{ session('error') }}</div>
@endif
@if ($errors->any())
<div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
    @foreach ($errors->all() as $pesan)
    <p>{{ $pesan }}</p>
    @endforeach
</div>
@endif

@if ($adaSimulasi)
<div class="mb-6 flex flex-col justify-between gap-2 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 sm:flex-row sm:items-center">
    <p><strong>Mode simulasi.</strong> Hasil sync hanya tersimpan di sesi login Anda dan belum dikirim ke SIASN maupun SIMPEG.</p>
    @if ($bolehUpdate)
    <form method="POST" action="{{ route('integrasi.siasn.reset') }}">
        @csrf
        <button type="submit" class="shrink-0 rounded-lg border border-amber-300 bg-white px-3 py-1 text-xs font-semibold text-amber-800 hover:bg-amber-100">Reset simulasi</button>
    </form>
    @endif
</div>
@endif

@if (!$pegawai)
<div class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">
    @if ($nip)
    <p class="text-lg font-semibold text-slate-800">Pegawai dengan NIP <span class="font-mono">{{ $nip }}</span> tidak ditemukan</p>
    <p class="mt-1 text-sm text-slate-500">NIP tidak ada di SIMPEG maupun di hasil penarikan data SIASN.</p>
    @else
    <p class="text-lg font-semibold text-slate-800">Masukkan NIP untuk melihat perbandingan data pegawai</p>
    @endif
</div>
@else

<div x-data="{
        modal: false,
        arah: '',
        items: [],
        buka(arah, items) { this.arah = arah; this.items = items; this.modal = true; },
        get tujuan() { return this.arah === 'siasn' ? 'SIASN' : 'SIMPEG'; },
        get asal() { return this.arah === 'siasn' ? 'SIMPEG' : 'SIASN'; },
        nilai(item, sisi) { return item[sisi] ?? '(tidak ada data)'; },
        semuaBaris: @js($barisBerbeda),
        dipilih: [],
        terpilih() { return this.semuaBaris.filter(item => this.dipilih.includes(item.key)); },
        pilihSemua(centang) { this.dipilih = centang ? this.semuaBaris.map(item => item.key) : []; },
        // kosong = tombol sync boleh dipakai
        alasanSync(arah) {
            if (!this.dipilih.length) return 'Centang data yang akan di-sync terlebih dahulu';
            const sumber = arah === 'siasn' ? 'simpeg' : 'siasn';
            if (this.terpilih().some(item => item[sumber] === null)) return 'Ada data terpilih yang kosong di ' + sumber.toUpperCase();
            return '';
        },
    }">

    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-xl font-bold text-slate-900">{{ $data['nama'] }}</p>
                <p class="font-mono text-sm text-slate-600">{{ $nip }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ $data['nilai']['jabatan'] ?? '-' }} &middot; {{ $data['unit_kerja'] ?? '-' }}</p>
            </div>
            <div class="flex flex-col items-start gap-2 sm:items-end">
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $sumber['class'] }}">{{ $sumber['label'] }}</span>
                <p class="text-sm text-slate-600">
                    <strong class="text-2xl {{ count($berbeda) ? 'text-red-600' : 'text-emerald-600' }}">{{ count($berbeda) }}</strong>
                    dari {{ $jumlahAtribut }} data berbeda
                </p>
            </div>
        </div>
    </div>

    @if (!$pegawai['simpeg'] || !$pegawai['siasn'])
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
        @if (!$pegawai['simpeg'])
        <p class="font-semibold">Pegawai ini terdaftar di SIASN BKN tetapi belum ada di SIMPEG.</p>
        <p class="mt-1 text-red-700">Periksa apakah pegawai baru, pindah masuk, atau NIP-nya tercatat berbeda di SIMPEG.</p>
        <p class="mt-1 font-semibold">Data pegawai ini tidak bisa di-sync ke SIMPEG karena pegawainya belum ada di SIMPEG. Tambahkan pegawai melalui proses SIMPEG terlebih dahulu.</p>
        @else
        <p class="font-semibold">Pegawai ini terdaftar di SIMPEG tetapi tidak ditemukan di SIASN BKN.</p>
        <p class="mt-1 text-red-700">Periksa apakah pegawai sudah pensiun, pindah keluar, atau NIP-nya salah di SIMPEG. Penambahan ASN ke SIASN tidak bisa dilakukan dari halaman ini.</p>
        @endif
    </div>
    @elseif (count($berbeda))
    <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm">
        <p class="font-semibold text-amber-800">Data yang perlu dicocokkan:</p>
        <div class="mt-2 flex flex-wrap gap-1">
            @foreach ($berbeda as $item)
            <span class="rounded-full bg-white px-2 py-0.5 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">{{ $item['label'] }}</span>
            @endforeach
        </div>
    </div>
    @else
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
        Semua data pegawai ini sudah sama antara SIMPEG dan SIASN.
    </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" x-data="{ hanyaBeda: false }">
        <div class="flex flex-col justify-between gap-3 lg:flex-row lg:items-center">
            <h2 class="text-lg font-bold text-slate-900">Rincian Per Data</h2>
            <div class="flex flex-wrap items-center gap-3">
                <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" x-model="hanyaBeda" class="rounded border-slate-300">
                    Tampilkan yang berbeda saja
                </label>
            </div>
        </div>

        @php $pakaiCentang = $bolehUpdate && $barisBerbeda->isNotEmpty(); @endphp
        @if ($pakaiCentang)
        <div class="mt-3 rounded-xl bg-slate-50 p-3 text-xs text-slate-600">
            <p class="font-semibold text-slate-700">Centang data yang akan di-sync, lalu pilih arahnya di judul kolom:</p>
            <div class="mt-1 grid gap-1 sm:grid-cols-2">
                <p><span class="font-semibold text-blue-700">Sync ke SIASN &rarr;</span>: data <strong>SIMPEG yang benar</strong>, nilainya dikirim untuk memperbarui SIASN.@unless (\App\Http\Controllers\Integrasi\IntegrasiController::SYNC_KE_SIASN_AKTIF) <em>(sementara belum diaktifkan)</em>@endunless</p>
                <p><span class="font-semibold text-emerald-700">&larr; Sync ke SIMPEG</span>: data <strong>SIASN yang benar</strong>, nilainya <strong>langsung disimpan</strong> ke SIMPEG (tbl_pegawai).</p>
            </div>
            <p class="mt-1">Saat ini yang bisa di-sync ke SIMPEG hanya <strong>Data Pribadi</strong> (nama, tempat lahir, tanggal lahir, jenis kelamin, agama). Data lain tetap ditampilkan untuk dicocokkan.</p>
        </div>
        @endif

        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-wide text-slate-500">
                        @if ($pakaiCentang)
                        <th class="w-8 py-2 pr-2">
                            <input type="checkbox" title="Pilih semua data yang berbeda" class="rounded border-slate-300"
                                :checked="dipilih.length === semuaBaris.length"
                                :indeterminate="dipilih.length > 0 && dipilih.length < semuaBaris.length"
                                @change="pilihSemua($event.target.checked)">
                        </th>
                        @endif
                        <th class="w-1/6 py-2 pr-3">Data</th>
                        <th class="py-2 px-3">
                            <div class="flex items-center justify-between gap-2">
                                <span>SIMPEG</span>
                                @if ($pakaiCentang)
                                @include('pages.integrasi.partials.tombol-sync', ['arah' => 'siasn', 'alasan' => $alasanKeSiasn, 'label' => 'Sync ke SIASN'])
                                @endif
                            </div>
                        </th>
                        <th class="border-l border-slate-200 py-2 px-3">
                            <div class="flex items-center justify-between gap-2">
                                <span>SIASN</span>
                                @if ($pakaiCentang)
                                @include('pages.integrasi.partials.tombol-sync', ['arah' => 'simpeg', 'alasan' => $alasanKeSimpeg, 'label' => 'Sync ke SIMPEG'])
                                @endif
                            </div>
                        </th>
                        <th class="py-2 pl-3 text-right">Status</th>
                    </tr>
                </thead>
                @foreach ($kelompok as $judul => $baris)
                <tbody>
                    <tr x-show="!hanyaBeda || {{ collect($baris)->contains('berbeda', true) ? 'true' : 'false' }}">
                        <td colspan="{{ $pakaiCentang ? 5 : 4 }}" class="bg-slate-50 py-2 px-3 text-xs font-bold uppercase tracking-wide text-slate-600">{{ $judul }}</td>
                    </tr>
                    @foreach ($baris as $item)
                    @if ($pakaiCentang && $item['berbeda'] && $bisaSync($item['key']))
                    <tr class="border-b border-slate-100" :class="dipilih.includes('{{ $item['key'] }}') ? 'bg-blue-50' : 'bg-red-50/60'"
                    @else
                    <tr class="border-b border-slate-100 {{ $item['berbeda'] ? 'bg-red-50/60' : '' }}"
                    @endif
                        @if (!$item['berbeda']) x-show="!hanyaBeda" @endif>
                        @if ($pakaiCentang)
                        <td class="py-2 pr-2">
                            @if ($item['berbeda'] && $bisaSync($item['key']))
                            <input type="checkbox" id="pilih-{{ $item['key'] }}" value="{{ $item['key'] }}" x-model="dipilih" class="rounded border-slate-300">
                            @endif
                        </td>
                        @endif
                        <td class="py-2 pr-3 font-medium text-slate-700">
                            @if ($pakaiCentang && $item['berbeda'] && $bisaSync($item['key']))
                            <label for="pilih-{{ $item['key'] }}" class="cursor-pointer">{{ $item['label'] }}</label>
                            @else
                            {{ $item['label'] }}
                            @endif
                        </td>
                        <td class="py-2 px-3 text-slate-800">
                            {!! $item['simpeg'] !== null ? e($item['simpeg']) : '<span class="italic text-slate-400">Tidak ada data</span>' !!}
                        </td>
                        <td class="border-l border-slate-200 py-2 px-3 {{ $item['berbeda'] && $item['siasn'] !== null ? 'font-semibold text-red-700' : 'text-slate-800' }}">
                            {!! $item['siasn'] !== null ? e($item['siasn']) : '<span class="italic font-normal text-slate-400">Tidak ada data</span>' !!}
                        </td>
                        <td class="py-2 pl-3 text-right">
                            @if ($item['berbeda'])
                            <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700">Berbeda</span>
                            @else
                            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700">Sesuai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @endforeach
            </table>
        </div>
    </div>

    {{-- riwayat sync SIASN -> SIMPEG pegawai ini (siasn_sync_log) --}}
    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900">Riwayat Sync</h2>
        <p class="mt-1 mb-4 text-xs text-slate-500">Perubahan data pegawai ini di SIMPEG yang dilakukan lewat Sync ke SIMPEG, terbaru di atas.</p>
        @if ($riwayatTersedia)
        @include('pages.integrasi.partials.riwayat-sync-tabel', ['nip' => $nip])
        @else
        <p class="rounded-xl bg-amber-50 p-4 text-sm text-amber-800">Tabel <code>siasn_sync_log</code> belum ada di database SIASN. Jalankan migration riwayat sync terlebih dahulu.</p>
        @endif
    </div>

    @if ($bolehUpdate)
    {{-- konfirmasi sebelum sync --}}
    <div x-show="modal" x-transition.opacity style="display: none"
        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl" @click.outside="modal = false">
            <h3 class="text-lg font-bold text-slate-900">Konfirmasi Sync <span x-text="tujuan"></span></h3>
            <p class="mt-1 text-sm text-slate-600">
                Data di <strong x-text="tujuan"></strong> akan diganti dengan data dari <strong x-text="asal"></strong>
                untuk pegawai <strong>{{ $data['nama'] }}</strong> ({{ $nip }}).
            </p>
            <p class="mt-2 rounded-lg bg-amber-50 p-2 text-xs text-amber-800" x-show="arah === 'simpeg'">
                Perubahan <strong>langsung disimpan</strong> ke database SIMPEG (tbl_pegawai) dan dicatat di riwayat sync.
            </p>

            <div class="mt-4 max-h-80 overflow-y-auto rounded-xl border border-slate-200">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-slate-50">
                        <tr class="text-left text-xs uppercase tracking-wide text-slate-500">
                            <th class="py-2 px-3">Data</th>
                            <th class="py-2 px-3">Nilai sekarang di <span x-text="tujuan"></span></th>
                            <th class="py-2 px-3">Nilai baru</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="item in items" :key="item.key">
                            <tr class="border-t border-slate-100">
                                <td class="py-2 px-3 font-medium text-slate-700" x-text="item.label"></td>
                                <td class="py-2 px-3 text-slate-500 line-through" x-text="nilai(item, arah)"></td>
                                <td class="py-2 px-3 font-semibold text-emerald-700" x-text="nilai(item, arah === 'siasn' ? 'simpeg' : 'siasn')"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <form method="POST" action="{{ route('integrasi.siasn.update') }}" class="mt-5 flex justify-end gap-2">
                @csrf
                <input type="hidden" name="nip" value="{{ $nip }}">
                <input type="hidden" name="arah" :value="arah">
                <template x-for="item in items" :key="item.key">
                    <input type="hidden" name="atribut[]" :value="item.key">
                </template>
                <button type="button" @click="modal = false"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                <button type="submit"
                    class="rounded-lg px-4 py-2 text-sm font-semibold text-white"
                    :class="arah === 'siasn' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-emerald-600 hover:bg-emerald-700'">
                    Ya, Sync <span x-text="tujuan"></span>
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endif

@endsection
