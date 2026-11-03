@extends('layouts.app')

@section('title', 'Dashboard Integrasi SIASN')

@section('content')

@php
$selisihTotal = $totalSimpeg - $totalSiasn;
$adaPerbedaan = $selisihTotal != 0 || $kategoriBerbeda > 0 || array_sum($jumlahSelisih) > 0;
$persenKesesuaian = $totalSiasn > 0 ? round((min($totalSimpeg, $totalSiasn) / max($totalSimpeg, $totalSiasn)) * 100, 2) : 0;
@endphp

@php $importBerjalan = $importTerakhir?->sedangBerjalan() ?? false; @endphp
<div class="bg-white border rounded-2xl shadow-sm p-5 mb-6" x-data="{ modalSync: {{ $errors->has('file') ? 'true' : 'false' }} }">
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Integrasi SIASN</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Perbandingan Jumlah ASN SIMPEG vs SIASN</h1>
            <p class="mt-1 text-sm text-slate-500">Sync data SIASN terakhir:
                <span class="font-semibold text-slate-700">{{ $tanggalTarik ? $tanggalTarik->translatedFormat('d F Y, H:i') . ' WIB' : 'belum pernah' }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="modalSync = true"
                @if ($importBerjalan || !$siasnTersedia) disabled title="{{ $importBerjalan ? 'Masih ada import yang sedang diproses' : 'Database SIASN belum bisa diakses' }}" @endif
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60">
                <x-icon name="arrows-rotate" />
                Sync Data SIASN
            </button>
        </div>
    </div>

    @include('pages.integrasi.partials.modal-sync')
</div>

@if (session('success'))
<div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-800">{{ session('error') }}</div>
@endif

@if ($importTerakhir)
@include('pages.integrasi.partials.status-import', ['log' => $importTerakhir, 'berjalan' => $importBerjalan])
@endif

@if (!$adaDataSiasn)
<div class="mb-6 flex gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4">
    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-500 text-sm font-bold text-white">i</div>
    <div class="text-sm text-amber-800">
        @if ($siasnTersedia)
        <p class="font-semibold">Data SIASN belum ada.</p>
        <p class="mt-1">Data SIMPEG sudah ditampilkan. Export data ASN dari web SIASN, lalu klik <strong>Sync Data SIASN</strong> untuk mengimport file tersebut. Perbandingan akan muncul setelah import selesai.</p>
        @else
        <p class="font-semibold">Database SIASN belum bisa diakses.</p>
        <p class="mt-1">Periksa koneksi <code>mysql_third</code> (DB_THIRD_* di .env) dan pastikan migration <code>siasn_data_utama</code> sudah dijalankan.</p>
        @endif
    </div>
</div>
@elseif ($adaPerbedaan)
<div class="mb-6 flex gap-3 rounded-2xl border border-red-200 bg-red-50 p-4">
    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-600 text-sm font-bold text-white">!</div>
    <div>
        <p class="font-semibold text-red-800">Ditemukan perbedaan data antara SIMPEG dan SIASN</p>
        <ul class="mt-1 list-disc pl-5 text-sm text-red-700">
            @if ($selisihTotal != 0)
            <li>Total ASN berselisih <strong>{{ number_format(abs($selisihTotal)) }}</strong> orang ({{ $selisihTotal > 0 ? 'SIMPEG lebih banyak' : 'SIASN lebih banyak' }}).</li>
            @endif
            @if ($kategoriBerbeda > 0)
            <li><strong>{{ $kategoriBerbeda }}</strong> kategori memiliki jumlah yang berbeda.</li>
            @endif
            @if ($jumlahSelisih['hanya_siasn'] > 0)
            <li><strong>{{ number_format($jumlahSelisih['hanya_siasn']) }}</strong> ASN tercatat di SIASN tetapi belum ada di SIMPEG.</li>
            @endif
            @if ($jumlahSelisih['hanya_simpeg'] > 0)
            <li><strong>{{ number_format($jumlahSelisih['hanya_simpeg']) }}</strong> ASN tercatat di SIMPEG tetapi tidak ditemukan di SIASN.</li>
            @endif
            @if ($jumlahSelisih['atribut_berbeda'] > 0)
            <li><strong>{{ number_format($jumlahSelisih['atribut_berbeda']) }}</strong> ASN memiliki data (golongan, jabatan, unit kerja, dll.) yang tidak sama.</li>
            @endif
        </ul>
    </div>
</div>
@else
<div class="mb-6 flex gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white">
        <x-icon name="check" />
    </div>
    <p class="self-center font-semibold text-emerald-800">Data SIMPEG dan SIASN sudah sesuai.</p>
</div>
@endif

<div class="bg-white border rounded-2xl shadow-sm">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-slate-200 border-l-4 border-l-blue-600 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total ASN SIMPEG</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalSimpeg) }}</p>
                <p class="mt-2 text-xs text-slate-500">PNS, CPNS dan PPPK aktif</p>
            </div>
            <div class="rounded-xl border border-slate-200 border-l-4 border-l-cyan-600 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total ASN SIASN</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $adaDataSiasn ? number_format($totalSiasn) : '–' }}</p>
                <p class="mt-2 text-xs text-slate-500">{{ $adaDataSiasn ? 'Hasil penarikan dari BKN' : 'Belum ada data SIASN' }}</p>
            </div>
            @if ($adaDataSiasn)
            <div class="rounded-xl border border-slate-200 border-l-4 {{ $selisihTotal != 0 ? 'border-l-red-500' : 'border-l-emerald-600' }} bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Selisih (SIMPEG − SIASN)</p>
                <p class="mt-2 text-3xl font-bold {{ $selisihTotal != 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ $selisihTotal > 0 ? '+' : '' }}{{ number_format($selisihTotal) }}</p>
                <p class="mt-2 text-xs text-slate-500">Kesesuaian jumlah {{ $persenKesesuaian }}%</p>
            </div>
            <div class="rounded-xl border border-slate-200 border-l-4 border-l-amber-500 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Kategori Berbeda</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $kategoriBerbeda }}</p>
                <p class="mt-2 text-xs text-amber-700">Perlu ditelusuri</p>
            </div>
            @else
            <div class="rounded-xl border border-slate-200 border-l-4 border-l-slate-300 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Selisih (SIMPEG − SIASN)</p>
                <p class="mt-2 text-3xl font-bold text-slate-400">–</p>
                <p class="mt-2 text-xs text-slate-500">Menunggu data SIASN</p>
            </div>
            <div class="rounded-xl border border-slate-200 border-l-4 border-l-slate-300 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Kategori Berbeda</p>
                <p class="mt-2 text-3xl font-bold text-slate-400">–</p>
                <p class="mt-2 text-xs text-slate-500">Menunggu data SIASN</p>
            </div>
            @endif
        </div>

        {{-- baris 1: kategori pendek, tinggi kartu disamakan --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            @foreach ($perbandingan as $judul => $baris)
            @include('pages.integrasi.partials.kartu-perbandingan')
            @endforeach
        </div>

        {{-- baris 2: kategori panjang, tinggi tetap dengan scroll di dalam kartu --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            @include('pages.integrasi.partials.kartu-golongan')
            @include('pages.integrasi.partials.kartu-unit-kerja')
        </div>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" x-data="{ tab: 'siasn' }"
            x-init="$watch('tab', () => $nextTick(() => window.sesuaikanTabelSiasn?.()))">
            <div class="flex flex-col justify-between gap-3 lg:flex-row lg:items-end">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Rincian Data Berbeda</h2>
                    <p class="mt-1 text-sm text-slate-500">Daftar ASN yang perlu dicocokkan antara SIMPEG dan SIASN. Klik <strong>Detail</strong> untuk melihat perbandingan per pegawai.</p>
                </div>
                @include('pages.integrasi.partials.cari-nip')
            </div>

            @if ($adaDataSiasn)
            <div class="mt-4 flex flex-wrap gap-2 border-b border-slate-200">
                <button type="button" @click="tab = 'siasn'"
                    :class="tab === 'siasn' ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="-mb-px border-b-2 px-3 py-2 text-sm font-semibold">
                    Hanya di SIASN <span class="ml-1 rounded-full bg-red-100 px-2 text-xs text-red-700">{{ number_format($jumlahSelisih['hanya_siasn']) }}</span>
                </button>
                <button type="button" @click="tab = 'simpeg'"
                    :class="tab === 'simpeg' ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="-mb-px border-b-2 px-3 py-2 text-sm font-semibold">
                    Hanya di SIMPEG <span class="ml-1 rounded-full bg-red-100 px-2 text-xs text-red-700">{{ number_format($jumlahSelisih['hanya_simpeg']) }}</span>
                </button>
                <button type="button" @click="tab = 'atribut'"
                    :class="tab === 'atribut' ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="-mb-px border-b-2 px-3 py-2 text-sm font-semibold">
                    Data Tidak Sama <span class="ml-1 rounded-full bg-red-100 px-2 text-xs text-red-700">{{ number_format($jumlahSelisih['atribut_berbeda']) }}</span>
                </button>
            </div>

            <div class="mt-4 overflow-x-auto" x-show="tab === 'siasn'">
                <p class="mb-3 text-xs text-slate-500">ASN yang terdaftar di SIASN BKN tetapi belum ada di SIMPEG.</p>
                @include('pages.integrasi.partials.tabel-pegawai', ['jenis' => 'hanyaDiSiasn'])
            </div>

            <div class="mt-4 overflow-x-auto" x-show="tab === 'simpeg'" style="display: none">
                <p class="mb-3 text-xs text-slate-500">ASN yang terdaftar di SIMPEG tetapi tidak ditemukan di SIASN BKN.</p>
                @include('pages.integrasi.partials.tabel-pegawai', ['jenis' => 'hanyaDiSimpeg'])
            </div>

            <div class="mt-4 overflow-x-auto" x-show="tab === 'atribut'" style="display: none">
                <p class="mb-3 text-xs text-slate-500">ASN yang ada di kedua sistem tetapi isian datanya berbeda.</p>
                @include('pages.integrasi.partials.tabel-pegawai', ['jenis' => 'atributBerbeda', 'denganAtribut' => true])
            </div>
            @else
            <p class="mt-4 rounded-xl bg-slate-50 p-6 text-center text-sm text-slate-500">Rincian perbedaan akan muncul setelah data SIASN diimport lewat Sync Data SIASN. Data SIMPEG per pegawai tetap bisa dilihat lewat kotak cek NIP.</p>
            @endif
        </section>

    </div>
</div>

@push('scripts')
@include('pages.integrasi.partials.script-datatable')
@endpush

@endsection
