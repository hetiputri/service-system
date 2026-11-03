@php
use App\Helpers\SimpegHelper;
$pegawai = $dataPribadi;
@endphp

@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
{{-- BIODATA --}}

<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    {{-- HEADER --}}
    <div class="flex items-center justify-between border-b pb-4 mb-6">
        <h2 class="text-xl font-semibold text-gray-800"> Data Pribadi </h2>
        <div class="flex items-center gap-3">
            {{-- Segmented Control --}}

            {{-- Cetak Button --}}
            <!-- <button class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800-600 text-grey border-1 text-sm rounded-lg hover:bg-green-700">


                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"/>
                </svg>
                Cetak
            </button> -->
        </div>
    </div> {{-- CONTENT --}}
    <div class="grid grid-cols-12 gap-6"> {{-- FOTO --}}
        <div class="col-span-12 md:col-span-4 flex justify-center">
            <div class="w-full h-auto md:h-64 bg-white rounded-lg border flex items-start justify-center overflow-hidden">
                <img src="https://{{ $filePribadi['pasfoto']}}" class="w-full block  max-w-full max-h-64 object-contain">
            </div>
        </div>

        <div class="col-span-12 md:col-span-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-5 text-sm">
                {{-- Nama Lengkap --}}
                <div>
                    <div class="text-gray-500 dark:text-gray-400">Nama Lengkap</div>
                    <div class="font-semibold text-gray-800">
                        {{ nama_gelar($dataPribadi['nama_pegawai'],$dataPribadi['gelar_depan'],$dataPribadi['gelar_belakang']) }}
                    </div>
                </div>

                {{-- Agama --}}
                <div>
                    <div class="text-gray-500 dark:text-gray-400">Agama</div>
                    <div class="font-semibold">
                        {{ $dataPribadi['agama'] ?? '-' }}
                    </div>
                </div>

                {{-- NIP --}}
                <div>
                    <div class="text-gray-500 dark:text-gray-400">NIP</div>
                    <div class="font-semibold">
                        {{ $dataPribadi['nip'] }}
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <div class="text-gray-500 dark:text-gray-400">Alamat Rumah</div>
                    <div class="font-semibold">
                        {{$dataPribadi['alamat'] ? $dataPribadi['alamat'].' Kel. '.$dataPribadi['kelurahan'].' Kec. '.$dataPribadi['kecamatan'].' Kab./Kota '.$dataPribadi['kotakabupaten'].' Prov. '.$dataPribadi['provinsi'] : '-' }}
                        <!-- {{ $dataPribadi['alamat'] ?? '-' }} -->
                    </div>
                </div>

                {{-- NIK --}}
                <div class="flex items-center justify-between">

                    <div>
                        <div class="text-gray-500 dark:text-gray-400">NIK</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['ktp'] ?? '-' }}
                        </div>
                    </div>

                    <!-- <a  href="https://bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_peg_asli.php?id_pegawai={{ $id_pegawai }}&type=scan_ktp"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="
                        text-xs
                        border border-gray-300 dark:border-gray-600
                        px-3 py-1
                        rounded-lg
                        bg-white dark:bg-gray-700
                        hover:bg-gray-100 dark:hover:bg-gray-600
                        text-gray-700 dark:text-gray-200
                        transitionss"
                    >
                    Lihat
                </a> -->
                    <x-file-button :url="$filePribadi['ktp'] ?? null" theme="default" />

                </div>

                {{-- Golongan Darah --}}
                <div>
                    <div class="text-gray-500 dark:text-gray-400">Golongan Darah</div>
                    <div class="font-semibold">
                        {{ $dataPribadi['golongan_darah'] ?? '-' }}
                    </div>
                </div>

                {{-- Tempat Tanggal Lahir --}}
                <div>
                    <div class="text-gray-500 dark:text-gray-400">Tempat / Tanggal Lahir</div>
                    <div class="font-semibold">
                        {{ $dataPribadi['tempat_lahir'] }},
                        {{ $dataPribadi['tanggal_lahir'] }}
                    </div>
                </div>

                {{-- Nomor KARPEG --}}
                <div class="flex items-center justify-between">

                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Nomor KARPEG</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['no_karpeg'] ?? '-' }}
                        </div>
                    </div>
                    <x-file-button :url="$filePribadi['kartu_pegawai'] ?? null" theme="default" />
                    <!-- <a 
                    href="https://bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_peg_asli.php?id_pegawai={{ $id_pegawai}}&type=karpeg"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="
                        text-xs
                        border border-gray-300 dark:border-gray-600
                        px-3 py-1
                        rounded-lg
                        bg-white dark:bg-gray-700
                        hover:bg-gray-100 dark:hover:bg-gray-600
                        text-gray-700 dark:text-gray-200
                        transitionss"
                    >
                    Lihat
                </a> -->

                </div>

                {{-- Pangkat --}}
                <div>
                    <div class="text-gray-500 dark:text-gray-400">Pangkat / Golongan</div>
                    <div class="font-semibold">
                        {{ $dataPribadi['pangkat'] ?? '-' }} ( {{ $dataPribadi['gol_ruang'] ?? '-'}} )
                    </div>
                </div>

                {{-- Nomor BPJS --}}
                <div class="flex items-center justify-between">

                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Nomor BPJS</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['no_askes'] ?? '-' }}
                        </div>
                    </div>

                    <!-- <a 
                    href="https://bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_peg_asli.php?id_pegawai={{ $id_pegawai }}&type=askes_pribadi"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="
                        text-xs
                        border border-gray-300 dark:border-gray-600
                        px-3 py-1
                        rounded-lg
                        bg-white dark:bg-gray-700
                        hover:bg-gray-100 dark:hover:bg-gray-600
                        text-gray-700 dark:text-gray-200
                        transitionss"
                    >
                    Lihat
                </a> -->
                    <x-file-button :url="$filePribadi['bpjs'] ?? null" theme="default" />
                </div>

                {{-- Masa Kerja --}}
                <div>
                    <div class="text-gray-500 dark:text-gray-400">Masa Kerja</div>
                    <div class="font-semibold">
                        {{ $dataPribadi['mk_tahun'] ?? '-' }} Tahun {{ $dataPribadi['mk_bulan'] ?? '-' }} Bulan
                    </div>
                </div>

                {{-- Nomor TASpen --}}
                <div class="flex items-center justify-between">

                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Nomor TASPEN</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['taspen'] ?? '-' }}
                        </div>
                    </div>
                    <!-- <a 
                    href="https://bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_peg_asli.php?id_pegawai={{ $id_pegawai }}&type=scan_taspen"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="
                        text-xs
                        border border-gray-300 dark:border-gray-600
                        px-3 py-1
                        rounded-lg
                        bg-white dark:bg-gray-700
                        hover:bg-gray-100 dark:hover:bg-gray-600
                        text-gray-700 dark:text-gray-200
                        transitionss"
                    >
                    Lihat
                </a> -->
                    <x-file-button :url="$filePribadi['taspen'] ?? null" theme="default" />
                </div>

                {{-- Jabatan --}}
                <div>
                    <div class="text-gray-500 dark:text-gray-400">Jabatan</div>
                    <div class="font-semibold">
                        {{ $dataPribadi['jabatan'] ?? '-' }}
                    </div>
                </div>

                {{-- Nomor NPWP --}}
                <div class="flex items-center justify-between">

                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Nomor NPWP</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['npwp'] ?? '-' }}
                        </div>
                    </div>
                    <x-file-button :url="$filePribadi['npwp'] ?? null" theme="default" />
                    <!-- <a 
                    href="https://bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_peg_asli.php?id_pegawai={{ $id_pegawai }}&type=scan_npwp"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="
                        text-xs
                        border border-gray-300 dark:border-gray-600
                        px-3 py-1
                        rounded-lg
                        bg-white dark:bg-gray-700
                        hover:bg-gray-100 dark:hover:bg-gray-600
                        text-gray-700 dark:text-gray-200
                        transitionss"
                    >
                    Lihat
                </a> -->

                </div>

                {{-- Unit Kerja --}}
                <div>
                    <div class="text-gray-500 dark:text-gray-400">Unit Kerja</div>
                    <div class="font-semibold">
                        {{ $dataPribadi['unit_kerja'] ?? '-' }}
                    </div>
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <div class="text-gray-500 dark:text-gray-400">
                        Jenis Kelamin
                    </div>
                    <div class="font-semibold">
                        {{ $dataPribadi['jenis_kelamin'] ?? null }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- CARD SECTION BELOW --}}
@if ($dataPribadi['status_kepegawaian']!='CPNS' && $dataPribadi['status_kepegawaian']!='PPPK')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
    @else
    <div class="grid grid-cols-1 lg:grid-cols-1 mt-4">
        @endif

        @if ($dataPribadi['status_kepegawaian']!='PPPK')
        <!-- Card CPNS -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-800">
                        Pengangkatan Sebagai CPNS
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Lengkap Data Pegawai
                    </p>
                </div>
                <x-file-button :url="$dataCPNS['file_sk_cpns'] ?? null" />

                <!-- <a href="https://bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_peg_asli.php?id_pegawai={{ $id_pegawai }}&jenis=sk_cpns" target="_blank" rel="noopener noreferrer" class="text-xs border border-gray-300 dark:border-gray-600 px-3 py-1 rounded-lg bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 transitionss">
                    Lihat
            </a> -->
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                <div>
                    <p class="text-gray-500 dark:text-gray-400">Nota Persetujuan BKN</p>
                    <p class="font-medium text-gray-800">{{ $dataCPNS['no_bkn'] ?? null }}</p>
                </div>

                <div>
                    <p class="text-gray-500 dark:text-gray-400">Pangkat / Gol. Ruang</p>
                    <p class="font-medium text-gray-800">{{ $dataCPNS['gol_ruang_cpns'] ?? null}}</p>
                </div>

                <div>
                    <p class="text-gray-500 dark:text-gray-400">Pejabat Yang Menetapkan</p>
                    <p class="font-medium text-gray-800">{{ $dataCPNS['pejabat_penetapan'] ?? null}}</p>
                </div>

                <div>
                    <p class="text-gray-500 dark:text-gray-400">TMT CPNS</p>
                    <p class="font-medium text-gray-800">{{ $dataCPNS['tmt_cpns'] ?? null}}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500 dark:text-gray-400">Surat Keputusan CPNS</p>
                    <div class="flex items-center gap-3 mt-1">
                        <p class="font-medium text-gray-800">{{ $dataCPNS['no_sk'] ?? null}}</p>
                    </div>
                </div>

            </div>
        </div>
        @endif

        @if($dataPribadi['status_kepegawaian']=='PPPK' || $dataPribadi['status_kepegawaian']!='CPNS')
        <!-- Card PNS -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-800">
                        Pengangkatan Sebagai {{ $dataPribadi['status_kepegawaian']=='PPPK' ? 'PPPK' : 'PNS' }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Riwayat Karier Pegawai
                    </p>
                </div>
                <x-file-button :url="$filePNS ?? null" />
                <!-- <a href="https://bkpsdm.medan.go.id/simpeg/php/upload_data/lihat_upload_sk_peg_asli.php?id_pegawai={{ $id_pegawai }}&jenis=sk_pns" target="_blank" rel="noopener noreferrer" class=" text-xs border border-gray-300 dark:border-gray-600 px-3 py-1 rounded-lg bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200transitionss">
                    Lihat
            </a> -->
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Pejabat Yang Menetapkan</p>
                    <p class="font-medium text-gray-800">{{ $dataPNS['pejabat_penetapan'] ?? null}}</p>
                </div>

                <div>
                    <p class="text-gray-500 dark:text-gray-400">TMT</p>
                    <p class="font-medium text-gray-800">{{ $dataPNS['TMTPNS'] ?? null}}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500 dark:text-gray-400">Surat Keputusan </p>
                    <div class="flex items-center gap-3 mt-1">
                        <p class="font-medium text-gray-800">{{ $dataPNS['no_sk_pns'] ?? null}}</p>
                    </div>
                </div>

            </div>

        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-1">
        <!-- Card Riwayat Kepangkatan -->
        <div class="bg-white dark:bg-gray-800 rounded-xl  shadow-sm border border-gray-200 dark:border-gray-700 p-5 mt-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-800">
                        Riwayat Kepangkatan
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Riwayat Karier Pegawai
                    </p>
                </div>

            </div>

            <!-- Table responsive -->
            <div class="overflow-x-auto">

                <x-table>

                    <x-slot name="head">
                        <th class="p-2 text-left">No</th>
                        <th class="p-2 text-left">Pangkat</th>
                        <th class="p-2 text-left">TMT</th>
                        <th class="p-2 text-left">Pejabat</th>
                        <th class="p-2 text-left">Nomor SK</th>
                        <th class="p-2 text-left">Tanggal SK</th>
                        <th class="p-2 text-left">File</th>
                    </x-slot>

                    @forelse($riwayatPangkat as $i => $row)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                        <td class="p-2">{{ $i+1 }}</td>

                        <td class="p-2">
                            {{ $row['pangkat'] }} ({{ $row['gol_ruang'] }})
                        </td>

                        <td class="p-2">
                            {{ \Carbon\Carbon::parse($row['tmt_pangkat'])->format('d M Y') }}
                        </td>

                        <td class="p-2">
                            {{ $row['pejabat_penetapan'] }}
                        </td>

                        <td class="p-2">
                            {{ $row['no_sk'] }}
                        </td>

                        <td class="p-2">
                            {{ \Carbon\Carbon::parse($row['tgl_sk'])->format('d M Y') }}
                        </td>
                        <td class="p-2">
                            <!-- <a href="https://{{ $row['file_sk_pangkat'] }}"
                    target="_blank"
                    class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-small rounded-lg text-xs px-1 py-1.5 text-center me-2 mb-1 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                        Lihat
                    </a> -->
                            <x-file-button :url="$row['file_sk_pangkat'] ?? null" />
                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">
                            Tidak ada data
                        </td>
                    </tr>
                    @endforelse

                </x-table>
            </div>
        </div>
        <!-- Card Riwayat Jabatan & Mutasi -->
        <div class="bg-white dark:bg-gray-800 rounded-xl  shadow-sm border border-gray-200 dark:border-gray-700 p-5 mt-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-800">
                        Riwayat Jabatan & Mutasi
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Riwayat Karier Pegawai
                    </p>
                </div>
            </div>

            <!-- Table responsive -->
            <div class="overflow-x-auto">
                <x-table>

                    <x-slot name="head">
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Jabatan</th>
                        <th class="p-3 text-left">Unit Kerja</th>
                        <th class="p-3 text-left">TMT Jabatan</th>
                        <th class="p-3 text-left">Pejabat</th>
                        <th class="p-3 text-left">Nomor SK</th>
                        <th class="p-3 text-left">Tanggal SK</th>
                        <th class="p-3 text-left">File</th>
                    </x-slot>

                    @forelse($riwayatJabatan as $i => $row)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                        <td class="p-3">
                            {{ $i+1 }}
                        </td>
                        <td class="p-3">

                            <div class="font-semibold">
                                {{ $row['jabatan'] }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ $row['tipe_jabatan'] }}
                            </div>

                            @if($row['eselon'])
                            <div class="text-xs text-gray-500">
                                Eselon {{ $row['eselon'] }}
                            </div>
                            @endif

                        </td>

                        <td class="p-3">
                            {{ $row['unit_kerja'] }}
                        </td>

                        <td class="p-3">

                            {{ $row['tmt_jabatan'] != '0000-00-00'
        ? \Carbon\Carbon::parse($row['tmt_jabatan'])->format('d M Y')
        : '-' }}

                        </td>

                        <td class="p-3">
                            {{ $row['pejabat_penetapan'] }}
                        </td>

                        <td class="p-3">
                            {{ $row['no_sk'] }}
                        </td>

                        <td class="p-3">

                            {{ $row['tgl_sk'] != '0000-00-00'
        ? \Carbon\Carbon::parse($row['tgl_sk'])->format('d M Y')
        : '-' }}

                        </td>
                        <td class="p-3">

                            <!-- <a href="https://{{ $row['file_sk_jabatan'] }}"
        target="_blank"
        class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-small rounded-lg text-xs px-1 py-1.5 text-center me-2 mb-1 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
        Lihat
        </a> -->
                            <x-file-button :url="$row['file_sk_jabatan'] ?? null" />
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="8" class="text-center py-6 text-gray-500">
                            Tidak ada data riwayat jabatan
                        </td>

                    </tr>

                    @endforelse

                </x-table>

            </div>
        </div>
        {{-- ================= RIWAYAT PENDIDIKAN UMUM ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mt-6 p-5 mb-6">

            {{-- header --}}
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-800">
                        Riwayat Pendidikan Umum
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Riwayat Karier Pegawai
                    </p>
                </div>

                <!-- <button class="flex items-center gap-2 px-3 py-1.5 border rounded-lg text-sm hover:bg-gray-50">
            🖨 Cetak
        </button> -->
            </div>

            {{-- table --}}
            <div class="overflow-x-auto">
                <x-table>

                    <x-slot name="head">

                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Tingkat</th>
                        <!-- <th class="p-3 text-left">Jurusan</th> -->
                        <th class="p-3 text-left">Sekolah / Universitas</th>
                        <th class="p-3 text-left">Tempat</th>
                        <th class="p-3 text-left">Tanggal Ijazah</th>
                        <th class="p-3 text-left">Nomor Ijazah</th>
                        <th class="p-3 text-left">IPK / Nilai</th>
                        <th class="p-3 text-left">File</th>

                    </x-slot>

                    @forelse($riwayatPendidikan as $i => $row)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                        <td class="p-3">
                            {{ $i+1 }}
                        </td>

                        <td class="p-3">

                            <span class="font-semibold">
                                {{ $row['tingkat_pendidikan'] }}
                            </span>

                            <div class="text-xs text-gray-500">
                                {{ $row['detail_pendidikan'] }}
                            </div>

                        </td>

                        <!-- <td class="p-3">
        {{ $row['id_jurusan'] ?? '-'}}
        </td> -->

                        <td class="p-3">

                            <div class="font-semibold">
                                {{ $row['nama_sekolah'] }}
                            </div>

                            <div class="text-xs text-gray-500">
                                Kepala: {{ $row['kepala_sekolah'] }}
                            </div>

                        </td>

                        <td class="p-3">
                            {{ $row['tempat'] }}
                        </td>

                        <td class="p-3">

                            {{ $row['tanggal_ijazah']
        ? \Carbon\Carbon::parse($row['tanggal_ijazah'])->format('d M Y')
        : '-' }}

                        </td>

                        <td class="p-3">
                            {{ $row['no_ijazah'] }}
                        </td>

                        <td class="p-3">

                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                {{ $row['nem_ip'] }}
                            </span>

                        </td>
                        <td>
                            <!-- <a href="https://{{ $row['file_ijazah'] }}"
                        target="_blank"
                        class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-small rounded-lg text-xs px-1 py-1.5 text-center me-2 mb-1 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                        Lihat
            </a> -->
                            <x-file-button :url="$row['file_ijazah'] ?? null" />
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" class="text-center py-6 text-gray-500">
                            Tidak ada data pendidikan
                        </td>
                    </tr>

                    @endforelse

                </x-table>
            </div>

        </div>


        {{-- ================= RIWAYAT DIKLAT STRUKTURAL ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-800">
                        Riwayat Diklat Struktural
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Riwayat Karier Pegawai
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <x-table>

                    <x-slot name="head">
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Diklat</th>
                        <th class="p-3 text-left">Penyelenggara</th>
                        <th class="p-3 text-left">Tempat</th>
                        <th class="p-3 text-left">Periode</th>
                        <th class="p-3 text-left">Jam</th>
                        <th class="p-3 text-left">No Sertifikat</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">File</th>
                    </x-slot>

                    @forelse($riwayatDiklatStruktural as $i => $row)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                        <td class="p-3">
                            {{ $i+1 }}
                        </td>
                        <td class="p-3">
                            <div class="font-semibold">
                                {{ $row['diklat_struktural'] }}
                            </div>
                        </td>

                        <td class="p-3">
                            {{ $row['penyelenggara'] }}
                        </td>

                        <td class="p-3">
                            {{ $row['tempat_diklat'] }}
                        </td>

                        <td class="p-3">

                            <div>
                                {{ \Carbon\Carbon::parse($row['mulai'])->format('d M Y') }}
                            </div>

                            <div class="text-xs text-gray-500">
                                s/d
                                {{ \Carbon\Carbon::parse($row['selesai'])->format('d M Y') }}
                            </div>

                        </td>
                        <td class="p-3">
                            {{ $row['jumlah_jam'] }} JP
                        </td>

                        <td class="p-3">
                            {{ $row['no_ijazah'] }}
                        </td>

                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($row['tgl_ijazah'])->format('d M Y') }}
                        </td>
                        <td class="p-3">

                            <!-- <a href="https://{{ $row['file_sertifikat'] }}"
                target="_blank"
                class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-small rounded-lg text-xs px-1 py-1.5 text-center me-2 mb-1 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                Lihat
                </a> -->
                            <x-file-button :url="$row['file_sertifikat'] ?? null" />

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="9"
                            class="text-center py-6 text-gray-500 dark:text-gray-400">

                            Tidak ada data Diklat Struktural

                        </td>
                    </tr>

                    @endforelse

                </x-table>

            </div>

        </div>


        {{-- ================= RIWAYAT DIKLAT LAINNYA ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-800">
                        Riwayat Diklat Lainnya
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Riwayat Karier Pegawai
                    </p>
                </div>


            </div>

            <div class="overflow-x-auto">
                <x-table>

                    <x-slot name="head">
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Diklat</th>
                        <th class="p-3 text-left">Penyelenggara</th>
                        <th class="p-3 text-left">Tempat</th>
                        <th class="p-3 text-left">Periode</th>
                        <th class="p-3 text-left">Jam</th>
                        <th class="p-3 text-left">No Sertifikat</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">File</th>
                    </x-slot>

                    @forelse($riwayatDiklatLainnya as $i => $row)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                        <td class="p-3">
                            {{ $i+1 }}
                        </td>
                        <td class="p-3">
                            <div class="font-semibold">
                                {{ $row['nama_diklat'] }}
                            </div>
                        </td>

                        <td class="p-3">
                            {{ $row['penyelenggara'] }}
                        </td>

                        <td class="p-3">
                            {{ $row['tempat_diklat'] }}
                        </td>

                        <td class="p-3">

                            <div>
                                {{ \Carbon\Carbon::parse($row['mulai'])->format('d M Y') }}
                            </div>

                            <div class="text-xs text-gray-500">
                                s/d
                                {{ \Carbon\Carbon::parse($row['selesai'])->format('d M Y') }}
                            </div>

                        </td>

                        <td class="p-3">
                            {{ $row['jumlah_jam'] }} JP
                        </td>

                        <td class="p-3">
                            {{ $row['no_ijazah'] }}
                        </td>

                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($row['tgl_ijazah'])->format('d M Y') }}
                        </td>
                        <td class="p-3">

                            <!-- <a href="https://{{ $row['file_sertifikat'] }}"
        target="_blank"
        class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-small rounded-lg text-xs px-1 py-1.5 text-center me-2 mb-1 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
        Lihat
        </a> -->
                            <x-file-button :url="$row['file_sertifikat'] ?? null" />
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="9"
                            class="text-center py-6 text-gray-500 dark:text-gray-400">

                            Tidak ada data Diklat Struktural

                        </td>
                    </tr>

                    @endforelse

                </x-table>
            </div>

        </div>

        {{-- ================= GAJI BERKALA ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-800">Gaji Berkala</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Riwayat Karier Pegawai
                    </p>
                </div>

            </div>

            <div class="overflow-x-auto">
                <x-table>

                    <x-slot name="head">
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">TMT Gaji</th>
                        <th class="p-3 text-left">Nomor SK</th>
                        <th class="p-3 text-left">Tanggal SK</th>
                        <th class="p-3 text-left">Pejabat</th>
                        <th class="p-3 text-left">File</th>
                    </x-slot>

                    @forelse($riwayatGaji as $i => $row)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                        <td class="p-3">
                            {{ $i+1 }}
                        </td>

                        <td class="p-3">



                            {{ \Carbon\Carbon::parse($row['tmt_gaji'])->format('d M Y') }}



                        </td>

                        <td class="p-3 font-medium">

                            {{ $row['nomor_sk'] }}

                        </td>

                        <td class="p-3">

                            {{ \Carbon\Carbon::parse($row['tanggal_sk'])->format('d M Y') }}

                        </td>

                        <td class="p-3">

                            {{ $row['pejabat_sk'] }}

                        </td>

                        <td class="p-3">
                            <x-file-button :url="$row['file_sk'] ?? null" />
                            <!-- <a href="https://{{ $row['file_sk'] }}"
                    target="_blank"
                    class=" text-xs
                        border border-gray-300 dark:border-gray-600
                        px-3 py-1
                        rounded-lg
                        bg-white dark:bg-gray-700
                        hover:bg-gray-100 dark:hover:bg-gray-600
                        text-gray-700 dark:text-gray-200
                        transitionss">

                    Lihat

                    </a> -->

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6"
                            class="text-center py-6 text-gray-500 dark:text-gray-400">

                            Tidak ada data Gaji Berkala

                        </td>
                    </tr>

                    @endforelse

                </x-table>
            </div>

        </div>
        {{-- ================= PENGHARGAAN================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-800">Penghargaan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Riwayat Karier Pegawai
                    </p>
                </div>


            </div>

            <div class="overflow-x-auto">
                <x-table>

                    <x-slot name="head">
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Nama Penghargaan</th>
                        <th class="p-3 text-left">Tahun</th>
                        <th class="p-3 text-left">Nomor SK</th>
                        <th class="p-3 text-left">Tanggal SK</th>
                        <th class="p-3 text-left">Asal Perolehan</th>
                        <th class="p-3 text-left">File</th>
                    </x-slot>

                    @forelse($riwayatPenghargaan as $i => $row)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                        <td class="p-3">
                            {{ $i+1 }}
                        </td>

                        <td class="p-3">

                            <div class="font-semibold">
                                {{ $row['nama_penghargaan'] }}
                            </div>

                        </td>

                        <td class="p-3">
                            {{ $row['tahun'] }}
                        </td>

                        <td class="p-3 font-medium">
                            {{ $row['nomor_sk'] }}
                        </td>

                        <td class="p-3">

                            {{ \Carbon\Carbon::parse($row['tanggal_sk'])->format('d M Y') }}

                        </td>

                        <td class="p-3">
                            {{ $row['asal_perolehan'] }}
                        </td>

                        <td class="p-3">

                            <!-- <a href="https://{{ $row['file_sertifikat'] }}"
                    target="_blank"
                    class=" text-xs
                        border border-gray-300 dark:border-gray-600
                        px-3 py-1
                        rounded-lg
                        bg-white dark:bg-gray-700
                        hover:bg-gray-100 dark:hover:bg-gray-600
                        text-gray-700 dark:text-gray-200
                        transitionss">

                    Lihat

                    </a> -->
                            <x-file-button :url="$row['file_sertifikat'] ?? null" />

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="7"
                            class="text-center py-6 text-gray-500 dark:text-gray-400">

                            Tidak ada data penghargaan

                        </td>
                    </tr>

                    @endforelse

                </x-table>

            </div>

        </div>
        {{-- ================= CUTI ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-800">Cuti</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Riwayat Karier Pegawai
                    </p>
                </div>


            </div>

            <div class="overflow-x-auto">
                <x-table>

                    <x-slot name="head">

                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Jenis Cuti</th>
                        <th class="p-3 text-left">Mulai</th>
                        <th class="p-3 text-left">Sampai</th>
                        <th class="p-3 text-left">Durasi</th>
                        <th class="p-3 text-left">Nomor SK</th>
                        <th class="p-3 text-left">Tanggal SK</th>
                        <th class="p-3 text-left">File</th>

                    </x-slot>

                    @forelse($riwayatCuti as $i => $row)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                        <td class="p-3">
                            {{ $i+1 }}
                        </td>

                        <td class="p-3 font-semibold">
                            {{ $row['jenis_cuti'] }}
                        </td>

                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($row['mulai'])->format('d M Y') }}
                        </td>

                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($row['sampai'])->format('d M Y') }}
                        </td>

                        <td class="p-3">

                            @php
                            $durasi =
                            \Carbon\Carbon::parse($row['mulai'])
                            ->diffInDays(
                            \Carbon\Carbon::parse($row['sampai'])
                            ) + 1;
                            @endphp

                            {{ $durasi }} hari

                        </td>

                        <td class="p-3">
                            {{ $row['nomor_sk'] ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($row['tanggal_sk'])->format('d M Y') }}
                        </td>

                        <td class="p-3">
                            <x-file-button :url="$row['file_sk'] ?? null" />
                            <!-- @if (!$row['file_sk'])
                    -
                @else
                    
                <a href="https://{{ $row['file_sk'] }}"
                target="_blank"
                class="text-blue-600 hover:underline">

                    Lihat SK

                </a> f
                @endif-->

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8"
                            class="text-center py-6 text-gray-500 dark:text-gray-400">

                            Tidak ada data cuti

                        </td>
                    </tr>

                    @endforelse

                </x-table>
            </div>
        </div>

        {{-- ================= RIWAYAT PENYESUAIAN IJAZAH ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">

            <div class="flex items-center justify-between mb-4">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Riwayat Penyesuaian Ijazah
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Riwayat Karier Pegawai
                    </p>
                </div>
            </div>



            <x-table>
                <x-slot name="head">
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">No STLUPI</th>
                    <th class="p-3 text-left">Tanggal Sertifikat Nilai</th>
                    <th class="p-3 text-left">Pendidikan</th>
                    <th class="p-3 text-left">Program Studi</th>
                    <th class="p-3 text-left">Pejabat TTD Sertifikat</th>
                    <th class="p-3 text-left">Pejabat TTD Nilai</th>
                    <th class="p-3 text-left">File</th>
                </x-slot>

                @forelse($riwayatPI as $i => $row)

                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                    <td class="p-3">
                        {{ $i+1 }}
                    </td>

                    <td class="p-3">
                        {{ $row['no_stlupi'] ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ isset($row['tgl_sertifikat_nilai'])
                    ? \Carbon\Carbon::parse($row['tgl_sertifikat_nilai'])->translatedFormat('d F Y')
                    : '-' }}
                    </td>

                    <td class="p-3 font-medium">
                        {{ $row['pendidikan'] ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $row['program_studi'] ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $row['pejabat_ttd_sertifikat'] ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $row['pejabat_ttd_nilai'] ?? '-' }}
                    </td>

                    <td class="p-3">
                        <x-file-button :url="$row['file_sk'] ?? null" />
                        <!-- @if(!empty($row['file_sk']))
                    <a href="https://{{ $row['file_sk'] }}"
                       target="_blank"
                       class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-small rounded-lg text-xs px-1 py-1.5 text-center me-2 mb-1 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                        Lihat 
                    </a>
                @else
                    -
                @endif -->
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="8"
                        class="text-center py-6 text-gray-500 dark:text-gray-400">
                        Tidak ada data Penyesuaian Ijazah
                    </td>
                </tr>

                @endforelse

            </x-table>



        </div>

        {{-- ================= RIWAYAT PENYESUAIAN MASA KERJA ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">

            <div class="flex items-center justify-between mb-4">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Riwayat Penyesuaian Masa Kerja
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Riwayat Karier Pegawai
                    </p>
                </div>
            </div>

            <x-table>

                <x-slot name="head">
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Pengalaman</th>
                    <th class="p-3 text-left">TMT</th>
                    <th class="p-3 text-left">Nomor SK</th>
                    <th class="p-3 text-left">Tanggal SK</th>
                    <th class="p-3 text-left">Pejabat Penetap</th>
                    <th class="p-3 text-left">File</th>
                </x-slot>

                @forelse($riwayatPMK as $i => $row)

                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                    <td class="p-3">
                        {{ $i+1 }}
                    </td>

                    <td class="p-3 font-medium">
                        {{ $row['pengalaman'] ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ isset($row['tmt'])
                    ? \Carbon\Carbon::parse($row['tmt'])->translatedFormat('d F Y')
                    : '-' }}
                    </td>

                    <td class="p-3">
                        {{ $row['nomor_sk'] ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ isset($row['tanggal_sk'])
                    ? \Carbon\Carbon::parse($row['tanggal_sk'])->translatedFormat('d F Y')
                    : '-' }}
                    </td>

                    <td class="p-3">
                        {{ $row['pejabat_sk'] ?? '-' }}
                    </td>

                    <td class="p-3">
                        <x-file-button :url="$row['file_sk'] ?? null" />
                        <!-- @if(!empty($row['file_sk']))
                    <a href="https://{{ $row['file_sk'] }}"
                       target="_blank"
                       class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-small rounded-lg text-xs px-1 py-1.5 text-center me-2 mb-1 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                       class="">
                        Lihat
                    </a>
                @else
                    -
                @endif -->
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7"
                        class="text-center py-6 text-gray-500 dark:text-gray-400">
                        Tidak ada data Penyesuaian Masa Kerja
                    </td>
                </tr>

                @endforelse

            </x-table>

        </div>


        {{-- ================= HUKUMAN DISIPLIN ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">

            <div class="flex items-center justify-between mb-4">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Hukuman Disiplin
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Riwayat Karier Pegawai
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">

                <x-table>

                    <x-slot name="head">

                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Tingkat</th>
                        <th class="p-3 text-left">Jenis Hukuman</th>
                        <th class="p-3 text-left">Nomor SK</th>
                        <th class="p-3 text-left">Tanggal SK</th>
                        <th class="p-3 text-left">Periode</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">File</th>

                    </x-slot>

                    @forelse($riwayatHukdis as $i => $row)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                        <td class="p-3">
                            {{ $i+1 }}
                        </td>

                        <td class="p-3">

                            @php
                            $color = str_contains(strtolower($row['tingkat_hukdis']), 'berat')
                            ? 'red'
                            : (str_contains(strtolower($row['tingkat_hukdis']), 'sedang')
                            ? 'yellow'
                            : 'blue');
                            @endphp
                            {{ $row['tingkat_hukdis'] }}
                        </td>

                        <td class="p-3 font-medium">
                            {{ $row['jenis_hukdis'] }}
                        </td>

                        <td class="p-3">
                            {{ $row['nomor_sk'] }}
                        </td>

                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($row['tanggal_sk'])->format('d M Y') }}
                        </td>

                        <td class="p-3">

                            <div>
                                {{ \Carbon\Carbon::parse($row['tmt'])->format('d M Y') }}
                            </div>

                            <div class="text-xs text-gray-500">
                                s/d
                                {{ \Carbon\Carbon::parse($row['tmt_akhir'])->format('d M Y') }}
                            </div>

                        </td>

                        <td class="p-3">

                            @php
                            $today = now();
                            $akhir = \Carbon\Carbon::parse($row['tmt_akhir']);
                            @endphp

                            @if($today->lessThanOrEqualTo($akhir))
                            <x-ui.badge color="red">Masih Berlaku</x-ui.badge>
                            @else
                            <x-ui.badge color="green">Selesai</x-ui.badge>
                            @endif

                        </td>

                        <td class="p-3">
                            <x-file-button :url="$row['file_sk'] ?? null" />
                            <!-- <a href="https://{{ $row['file_sk'] }}"
                    target="_blank"
                    class="text-blue-600 hover:underline">
                        Lihat SK
                    </a> -->

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8"
                            class="text-center py-6 text-gray-500 dark:text-gray-400">

                            Tidak ada data Hukuman Disiplin

                        </td>
                    </tr>

                    @endforelse

                </x-table>

            </div>

        </div>


        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            {{-- ================= DATA PASANGAN ================= --}}
            @forelse($pasangan as $item)

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">
                            Data Pasangan
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Informasi Data Pasangan
                        </p>
                    </div>
                    <x-file-button :url="$item['kartu_keluarga'] ?? null" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-12 text-sm">

                    {{-- Nama --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Nama Pasangan</span>
                        <div class="font-medium">
                            {{ $item['nama_pasangan'] ?? '-' }}
                        </div>
                    </div>

                    {{-- Status Hidup --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Status Hidup</span>
                        <div class="font-medium">
                            {{ $item['status_hidup'] ?? '-' }}
                        </div>
                    </div>

                    {{-- Tempat / Tanggal Lahir --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">
                            Tempat / Tanggal Lahir
                        </span>
                        <div class="font-medium">
                            {{ $item['tempat_lahir'] ?? '-' }},
                            {{ isset($item['tgl_lahir'])
                        ? \Carbon\Carbon::parse($item['tgl_lahir'])->translatedFormat('d F Y')
                        : '-' }}
                        </div>
                    </div>

                    {{-- Tanggal Nikah --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">
                            Tanggal Nikah
                        </span>
                        <div class="font-medium">
                            {{ isset($item['tgl_nikah'])
                        ? \Carbon\Carbon::parse($item['tgl_nikah'])->translatedFormat('d F Y')
                        : '-' }}
                        </div>
                    </div>

                    {{-- Pekerjaan --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">
                            Pekerjaan
                        </span>
                        <div class="font-medium">
                            {{ $item['pekerjaan'] ?? '-' }}
                        </div>
                    </div>

                    {{-- Status Nikah --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">
                            Status Pernikahan
                        </span>
                        <div class="font-medium">
                            {{ $item['status_nikah'] ?: 'Menikah' }}
                        </div>
                    </div>

                    {{-- File Akta Nikah --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">
                            Akta Nikah
                        </span>
                        <div class="font-medium">
                            <x-file-button :url="$item['akta_nikah'] ?? null" />
                        </div>
                    </div>

                    {{-- File Akta Cerai/Akta Meninggal --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">
                            Akta Cerai/Akta Meninggal
                        </span>
                        <div class="font-medium">
                            <x-file-button :url="$item['akta_cerai'] ?? ($item['akta_meninggal'] ?? null)" />
                        </div>
                    </div>

                </div>

            </div>

            @empty
            <div class="text-center text-gray-500 py-6">
                Tidak ada data pasangan
            </div>
            @endforelse
            <?php $no = 1; ?>
            @forelse($anak as $item)

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">
                            Data Anak
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Informasi Data Anak ke-{{ $no++ }}
                        </p>
                    </div>
                    <x-file-button :url="$item['akta_lahir'] ?? null" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-12 text-sm">

                    {{-- Nama --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Nama Anak</span>
                        <div class="font-medium">
                            {{ $item['nama_anak'] ?? '-' }}
                        </div>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Jenis Kelamin</span>
                        <div class="font-medium">
                            {{ $item['jenis_kelamin'] ?? '-' }}
                        </div>
                    </div>

                    {{-- Tempat / Tanggal Lahir --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">
                            Tempat / Tanggal Lahir
                        </span>
                        <div class="font-medium">
                            {{ $item['tempat_lahir'] ?? '-' }},
                            {{ isset($item['tgl_lahir'])
                        ? \Carbon\Carbon::parse($item['tgl_lahir'])->translatedFormat('d F Y')
                        : '-' }}
                        </div>
                    </div>

                    {{-- Pendidikan --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Pendidikan</span>
                        <div class="font-medium">
                            {{ $item['pendidikan'] ?? '-' }}
                        </div>
                    </div>

                    {{-- Pekerjaan --}}
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Pekerjaan</span>
                        <div class="font-medium">
                            {{ $item['pekerjaan'] ?? '-' }}
                        </div>
                    </div>

                </div>

            </div>
            @empty

            <div class="text-center text-gray-500 py-6">
                Tidak ada data anak
            </div>

            @endforelse

        </div>
    </div>




    @endsection