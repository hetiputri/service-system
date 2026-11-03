@extends('layouts.app')
@section('title','Detail OPD')

@section('content')
@php $dataPribadi = json_decode($pengajuan->data_pribadi); @endphp

<div class="bg-white p-6 rounded-xl shadow" x-data="{
    ...dokumenOPD({{ $pengajuan->current_step }}, '{{ $role }}'),
    tab: 'dokumen',  role: '{{ $role }}',
    revisiStep: '',
    catatan: '',
    modalType: ''
}">

    <div class="bg-white border rounded-2xl shadow-sm p-5 mb-6">

        <!-- TOP: Breadcrumb -->
        <div class="text-sm text-gray-500 mb-3">
            <span class="hover:text-blue-600 cursor-pointer">Dashboard</span>
            <span class="mx-1">/</span>
            <span class="hover:text-blue-600 cursor-pointer">Pengajuan Pensiun</span>
            <span class="mx-1">/</span>
            <span class="text-gray-700 font-medium">Detail</span>
        </div>

        <!-- MAIN HEADER -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <!-- LEFT -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Detail Pengajuan Pensiun
                </h2>
                <p class="text-sm text-gray-500">
                    Role: {{ $role }}
                </p>
            </div>

            <!-- RIGHT ACTION -->
            <div class="flex items-center gap-2">

                <!-- BACK -->
                <a href="{{ url('/admin-pensiun?role='.$role) }}"
                    class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                    ← Kembali
                </a>

                <!-- ACTION BUTTON -->
                <!-- <button
                    class="px-4 py-2 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
                    Aksi
                </button> -->

                <!-- ROLE BADGE -->
                <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-medium">
                    {{ strtoupper($role) }}
                </span>

            </div>

        </div>

    </div>

    <!-- INFO -->
    <div class="bg-gray-50 border rounded-xl p-4 mb-6 text-sm">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 mb-6 mt-5">

            <h2 class="text-lg font-semibold text-gray-700 mb-4">
                Informasi Pengajuan
            </h2>

            @php
            $status = strtolower($pengajuan->status_akhir);

            $badgeColor = match(true) {
            str_contains($status, 'selesai') => 'bg-green-100 text-green-700',
            str_contains($status, 'diajukan') => 'bg-yellow-100 text-yellow-700',
            str_contains($status, 'tolak') => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700'
            };
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

                <!-- Nomor Tiket -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-gray-500 text-xs mb-1">Nomor Tiket</p>
                    <p class="font-semibold text-gray-800 tracking-wide">
                        {{ $pengajuan->nomor_tiket }}
                    </p>
                </div>

                <!-- Status -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-gray-500 text-xs mb-1">Status</p>

                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-gray-800">
                            {{ $step->nama_step }}
                        </p>

                        <!-- <span class="text-xs px-2 py-1 rounded-full font-medium {{ $badgeColor }}">
                            {{ $pengajuan->status_akhir }}
                        </span> -->
                    </div>
                </div>

                <!-- Layanan -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-gray-500 text-xs mb-1">Layanan</p>
                    <p class="font-semibold text-gray-800">
                        {{ $pengajuan->layanan->nama_layanan ?? '-' }}
                    </p>
                </div>

            </div>

        </div>

        <!-- DATA PEGAWAI -->
        <div class="bg-white border rounded-xl p-4 mb-6 mt-5">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-semibold">Data Pegawai</h3>

                <!-- BUTTON LIHAT DETAIL -->
                @if($role == 'AdminBkdPensiun')
                <a
                    href="{{ route('pegawai.profile', $pengajuan->pegawai_id) }}"
                    target="_blank"
                    class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                    👁 Lihat Profil Pegawai
                </a>
                @endif

            </div>

            <!-- CONTENT -->
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-500">Nama</div>
                    <div class="font-semibold">
                        {{ $dataPribadi->data->nama_pegawai ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-gray-500">NIP</div>
                    <div class="font-semibold">
                        {{ $dataPribadi->data->nip ?? '-' }}
                    </div>
                </div>
            </div>

        </div>

        <div class="flex border-b mb-6 gap-2">
            <button
                @click="tab='dokumen'"
                :class="tab=='dokumen' ? 'border-blue-600 text-blue-600' : 'text-gray-500'"
                class="px-4 py-2 border-b-2 font-semibold">
                📁 Dokumen
            </button>

            @canany(['cru-surat-pidana', 'r-surat-pidana'])
            <button
                @click="tab='pidana'"
                :class="tab=='pidana' ? 'border-blue-600 text-blue-600' : 'text-gray-500'"
                class="px-4 py-2 border-b-2 font-semibold">
                ⚖️ Surat Pidana
            </button>
            @endcanany

            @canany(['cru-surat-disiplin', 'r-surat-disiplin'])
            <button
                @click="tab='disiplin'"
                :class="tab=='disiplin' ? 'border-blue-600 text-blue-600' : 'text-gray-500'"
                class="px-4 py-2 border-b-2 font-semibold">
                📄 Surat Disiplin
            </button>
            @endcanany




            @if($role == 'AdminBkdPensiun' && auth()->user()->can('cru-sk-pensiun'))
            <button
                @click="tab='sk_pensiun'"
                :class="tab=='sk_pensiun' ? 'border-blue-600 text-blue-600' : 'text-gray-500'"
                class="px-4 py-2 border-b-2 font-semibold">
                📄 Proses SK Pensiun BKPSDM
            </button>
            @endif




        </div>
        <div x-show="tab=='sk_pensiun'">

            {{-- Finalisasi --}}
            @include('pages.layanan.pensiun.admin.final')


        </div>


        @canany(['crus-sk-pensiun'])
        <div x-show="tab=='sk_pensiun'" class="mt-4">
            <div class="bg-white border rounded-xl p-4 mb-6 mt-5">
                <!-- Upload -->
                <form action="{{ route('pensiun.upload.sk') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">

                    <input type="file" name="file" class="border p-2 rounded w-full mb-3" required>

                    <!-- <div x-data="skUpload()">

                        <form @submit.prevent="uploadSk">
                            @csrf
                            <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">

                            <input type="file" x-ref="file" class="border p-2 rounded w-full mb-3" required>

                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                Upload SK Pensiun
                            </button>
                        </form>

                    </div> -->
                </form>

                <!-- Preview -->
                @if($pengajuan->sk_path)
                <div class="mt-4">
                    <button
                        @click="previewFile('{{ route('pensiun.preview.sk', $pengajuan->id) }}')"
                        class="px-3 py-1 text-xs bg-green-600 text-white rounded-lg hover:bg-green-700">
                        👁 Lihat SK Pensiun
                    </button>
                </div>
                @endif
            </div>
        </div>
        @endcanany

        @canany(['cru-surat-disiplin', 'r-surat-disiplin'])
        <div x-show="tab=='disiplin'" x-transition>
            <div class="bg-white border rounded-xl p-4 mb-6 mt-5">
                @can('cru-surat-disiplin')
                <h1 class="text-lg font-semibold">Draft Surat Disiplin</h1>

                <div class="mt-4 flex gap-4"
                    x-data="{
        disiplin_jabatan: '{{ $dataDisiplin['pejabat_jabatan'] ?? '' }}',
        disiplin_jabatan_id: '{{ $dataDisiplin['pejabat_id'] ?? '' }}',
        disiplin_nomor: '{{ $dataDisiplin['nomor'] ?? '' }}',
        ttd_sarana_dis:'',
        
     }"
                    x-init="
        $('#ttd_oleh').select2({ width: '100%' });

        $('#ttd_oleh').on('change', () => {
            let selected = $('#ttd_oleh').find(':selected');

            disiplin_jabatan = selected.data('disiplin_jabatan') || '';
            disiplin_jabatan_id = $('#ttd_oleh').val() || '';

            // 🔥 optional: auto reload preview
            loadPreview('disiplin');
        });
     ">

                    <!-- Nomor Surat -->
                    <div class="w-1/3">
                        <label class="block mb-1">Nomor Surat</label>
                        <input type="text"
                            id="nomor_surat"
                            x-model="disiplin_nomor"
                            class="border rounded-lg p-2 w-full"
                            placeholder="Input Nomor Surat">
                    </div>

                    <!-- Penandatangan -->
                    <div class="w-1/3">
                        <label class="block mb-1">Ditandatangani oleh</label>
                        <select id="ttd_oleh"
                            x-model="disiplin_jabatan_id"
                            class="border rounded-lg p-2 w-full">

                            <option value="">Pilih Penandatangan</option>

                            @foreach($pejabat as $p)
                            <option
                                value="{{ $p['id_pegawai'] }}"
                                data-disiplin_jabatan="{{ $p['nama_pegawai'] }}"
                                {{ ($dataDisiplin['pejabat_id'] ?? '') == $p['id_pegawai'] ? 'selected' : '' }}>
                                {{ $p['nama_pegawai'] }}-{{ $p['nip'] }}
                            </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- Jabatan -->
                    <div class="w-1/3">
                        <label class="block mb-1">Jabatan di Surat</label>
                        <input type="text"
                            x-model="disiplin_jabatan"
                            class="border rounded-lg p-2 w-full"
                            placeholder="Opsional Jika Bukan Jabatan Defenitif">
                    </div>
                </div>

                @if($role == 'AdminOpdPensiun')
                <div class="mb-4 mt-4">
                    <input type="checkbox"
                        id="ttd_sarana_dis"
                        name="ttd_sarana_dis"
                        x-model="ttd_sarana_dis"
                        value="1" />
                    <label>Tanda Tangan Sarana (Hapus Bagian Nama dan NIP Penandatangan)
                    </label>
                </div>
                @endif

                @if($role == 'AdminBkdPensiun' && auth()->user()->can('pemroses-pensiun-bkd'))
                <h5 class='mt-5'>Berdasarkan Surat dari OPD dengan</h5>
                <div class="mt-4 flex gap-4">

                    <div class="w-1/3" x-data="{ disiplin_nomor_disopd: '' }">
                        <label class="block mb-1">Nomor Surat</label>
                        <input type="text"
                            id="nomor_surat_disopd"
                            x-model="disiplin_nomor_disopd"
                            class="border rounded-lg p-2 w-full"
                            placeholder="Input Nomor Surat">
                    </div>

                    <div class="w-1/3" x-data="{ tanggal_surat_disopd: '' }">
                        <label class="block mb-1">Tanggal Surat</label>
                        <input type="date"
                            id="tanggal_surat_disopd"
                            x-ref="datepicker"
                            x-model="tanggal_surat_disopd"
                            x-init="
                                flatpickr($refs.datepicker, {                                    
                                    dateFormat: 'Y-m-d'
                                })
                            "
                            class="border rounded-lg p-2 w-full">
                    </div>

                    <div class="w-1/3" x-data="{ pejabat_disopd: '' }">
                        <label class="block mb-1">Pejabat Penandatangan</label>
                        <input type="text"
                            id="pejabat_disopd"
                            x-model="pejabat_disopd"
                            class="border rounded-lg p-2 w-full">
                    </div>
                </div>
                @endif

            </div>
            <!-- Button -->
            <div class="mt-4 flex gap-2">

                <button onclick="loadPreview('disiplin')"
                    class="px-4 py-2 rounded-lg text-sm border bg-gray-200 font-semibold">
                    Tampilkan Surat
                </button>

                <!-- <button onclick="simpanSurat('disiplin')"
                        class="px-4 py-2 rounded-lg text-sm border bg-blue-200 font-semibold">
                        Simpan Data
                    </button> -->
            </div>
            @endcan

            <!-- <iframe id="previewSuratFrame" 
            class="w-full h-[600px] border rounded mt-4">
        </iframe> -->

            <!-- <iframe id="previewSuratFrame" 
            class="w-full h-[800px] border rounded shadow bg-white">
        </iframe> -->
        </div>

    </div>
    @endcanany

    @canany(['cru-surat-pidana', 'r-surat-pidana'])
    <div x-show="tab=='pidana'" x-transition>
        <div class="bg-white border rounded-xl p-4 mb-6 mt-5">
            <h1 class="text-lg font-semibold">Draft Surat Pidana</h1>

            @can('cru-surat-pidana')
            <div class="mt-4 flex gap-4" x-data="{
                    pidana_jabatan: '{{ $dataPidana['pejabat_jabatan'] ?? '' }}',
                    pidana_jabatan_id: '{{ $dataPidana['pejabat_id'] ?? '' }}',
                    pidana_nomor: '{{ $dataPidana['nomor'] ?? '' }}', 
                    ttd_sarana_pid :'',
                }" x-init=" $('#id_pidana_ttd_oleh').select2();

                    $('#id_pidana_ttd_oleh').on('change', () => {
                        let selected = $('#id_pidana_ttd_oleh').find(':selected');
                        pidana_jabatan = selected.data('pidana_jabatan') || '';
                    });">

                <!-- Nomor Surat -->
                <div class="w-1/3">
                    <label class="block mb-1">Nomor Surat</label>
                    <input type="text" id="nomor_surat_pidana" x-model="pidana_nomor"
                        class="border rounded-lg p-2 w-full"
                        placeholder="Input Nomor Surat">
                </div>

                <!-- Penandatangan -->
                <div class="w-1/3">
                    <label class="block mb-1">Ditandatangani oleh</label>
                    <select id="id_pidana_ttd_oleh" x-model="pidana_jabatan_id" class="border rounded-lg p-2 w-full">
                        <option value="">Pilih Penandatangan</option>
                        @foreach($pejabat as $p)
                        <option
                            value="{{ $p['id_pegawai'] }}"
                            data-pidana_jabatan="{{ $p['nama_pegawai'] }}">
                            {{ $p['nama_pegawai'] }} - {{ $p['nip'] }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jabatan -->
                <div class="w-1/3">
                    <label class="block mb-1">Jabatan di Surat</label>
                    <input type="text" class="border rounded-lg p-2 w-full"
                        x-model="pidana_jabatan"
                        placeholder="Opsional Jika Bukan Jabatan Defenitif">
                </div>
            </div>

            @if($role == 'AdminOpdPensiun')
            <div class="mb-4 mt-4">
                <input type="checkbox"
                    id="ttd_sarana_pid"
                    name="ttd_sarana_pid"
                    x-model="ttd_sarana_pid"
                    value="1" />
                <label>Tanda Tangan Sarana (Hapus bagian Nama dan NIP Penandatangan)
                </label>
            </div>
            @endif

            @if($role == 'AdminBkdPensiun' && auth()->user()->can('pemroses-pensiun-bkd'))
            <h5 class='mt-5'>Berdasarkan Surat dari OPD dengan</h5>
            <div class="mt-4 flex gap-4">

                <div class="w-1/3" x-data="{ pidana_nomor_pidopd: '' }">
                    <label class="block mb-1">Nomor Surat</label>
                    <input type="text"
                        id="nomor_surat_pidopd"
                        x-model="pidana_nomor_pidopd"
                        class="border rounded-lg p-2 w-full"
                        placeholder="Input Nomor Surat">
                </div>

                <div class="w-1/3" x-data="{ tanggal_surat_pidopd: '' }">
                    <label class="block mb-1">Tanggal Surat</label>
                    <input type="date"
                        id="tanggal_surat_pidopd"
                        x-ref="datepicker"
                        x-model="tanggal_surat_pidopd"
                        x-init="
                                flatpickr($refs.datepicker, {                                    
                                    dateFormat: 'Y-m-d'
                                })
                            "
                        class="border rounded-lg p-2 w-full">
                </div>

                <div class="w-1/3" x-data="{ pejabat_pidopd: '' }">
                    <label class="block mb-1">Pejabat Penandatangan</label>
                    <input type="text"
                        id="pejabat_pidopd"
                        x-model="pejabat_pidopd"
                        class="border rounded-lg p-2 w-full">
                </div>
            </div>
            @endif

            <!-- <button id="btnGenerate" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                    Generate
                </button> -->
            <div class="mt-3 mb-3">
                <!-- <button type="button" id="btnGenerateSuratPidana"  class="px-4 py-2 rounded-lg text-sm border bg-gray-200 font-semibold">
                        Cek dan Generate
                    </button> -->
                <button onclick="loadPreview('pidana')" class="px-4 py-2 rounded-lg text-sm border bg-gray-200 font-semibold">Tampilkan Surat</button>
                <!-- <button onclick="simpanSurat('pidana')"
                        class="px-4 py-2 rounded-lg text-sm border bg-blue-200 font-semibold">
                        Simpan Data
                    </button> -->
            </div>
            @endcan

        </div>
    </div>
    @endcanany
    <div x-show="tab=='pidana'||tab=='disiplin'" x-transition>
        <iframe id="previewFrame" class="w-full h-[600px] mt-4"></iframe>
    </div>
    <div>

        <div x-show="tab=='dokumen'" x-transition>
            <div class="bg-white border rounded-xl p-6 mb-6">

                <h3 class="font-semibold mb-4">
                    Dokumen Pengajuan
                </h3>

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="text-gray-600">
                            <tr>
                                <th class="text-left py-3 w-10">No</th>
                                <th class="text-left py-3">Nama Berkas</th>
                                <th class="text-left py-3 w-72">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <template x-for="(doc,index) in dokumenList" :key="doc.id">

                                <template x-if="
        role !== 'AdminOpdPensiun' || 
        ['opd','pegawai'].includes((doc?.penyedia || '').toLowerCase())
    ">
                                    <tr class="hover:bg-gray-50">

                                        <td class="py-3" x-text="index+1"></td>

                                        <td class="py-3">
                                            <div class="flex items-center gap-3">

                                                <span x-text="doc.label"></span>
                                                <template x-if="doc.wajib">
                                                    <span class="text-red-500 font-bold ml-1">*</span>
                                                </template>
                                                <span
                                                    class="text-xs px-2 py-0.5 rounded-full"
                                                    :class="doc.uploaded
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-red-100 text-red-600'"
                                                    x-text="doc.uploaded
                                                    ? 'Sudah Upload'
                                                    : 'Belum Upload'">
                                                </span>

                                            </div>
                                        </td>

                                        <td class="py-3">

                                            <div class="flex items-center gap-2">

                                                <!-- VIEW -->
                                                <template x-if="doc.uploaded">
                                                    <button
                                                        @click="previewFile(doc.file_url)"
                                                        class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                        👁 Lihat
                                                    </button>
                                                </template>

                                                <template x-if="!doc.uploaded && doc?.penyedia != 'OPD' && doc?.penyedia != 'BKPSDM'">
                                                    <span class="text-gray-400 text-xs">
                                                        Tidak ada file
                                                    </span>
                                                </template>

                                                @can('cru-surat-disiplin')
                                                <template x-if="
                                                    (doc?.penyedia === 'OPD' 
                                                        && '{{ $pengajuan?->status_akhir ?? '' }}' !== 'selesai' 
                                                        && '{{ $pengajuan?->current_step ?? '' }}' == 2
                                                    ) 
                                                    || 
                                                    (doc?.penyedia === 'BKPSDM' 
                                                        && '{{ $pengajuan?->current_step ?? '' }}' == 3
                                                    )
                                                ">
                                                    <label
                                                        class="px-3 py-1 text-xs rounded-lg cursor-pointer"
                                                        :class="isLocked 
                                                            ? 'bg-gray-300 cursor-not-allowed' 
                                                            : 'bg-gray-600 text-white hover:bg-gray-700'">
                                                        ⬆ Unggah
                                                        <!-- <input
                                                                type="file"
                                                                class="hidden"
                                                                :disabled="isLocked"
                                                                @change="!isLocked && uploadDokumen(doc.id, $event)"> -->
                                                        <input
                                                            type="file"
                                                            class="hidden"
                                                            @change="uploadDokumen(doc.id, $event)">
                                                    </label>

                                                </template>
                                                @endcan
                                            </div>

                                        </td>
                                    </tr>

                                </template>
                            </template>


                        </tbody>
                    </table>
                    <p class="text-sm text-red-500 mt-4">
                        ⚠️ Dokumen bertanda * wajib diunggah sebelum melanjutkan. File yang diunggah berformat pdf dengan ukuran maksimum 2 MB
                    </p>
                </div>
            </div>

            <!-- VALIDASI & SUBMIT -->
            <div class="bg-white border rounded-xl p-6">

                <!-- WARNING -->
                <div class="bg-yellow-50 border border-yellow-200 text-sm p-4 rounded-lg mb-4">
                    <span x-text="role == 'AdminBkdPensiun' 
    ? '⚠ Pastikan seluruh dokumen telah diverifikasi dengan benar.' 
    : '⚠ Pastikan seluruh dokumen telah dicek dan file pidana & disiplin sudah diupload dengan benar.'">
                    </span>
                </div>


                @if($role=='AdminOpdPensiun' && $pengajuan->current_step == 2 )
                <!-- CHECKBOX -->
                <label class="flex items-start gap-3 cursor-pointer mb-6">
                    <input
                        type="checkbox"
                        x-model="isChecked"
                        :disabled="isLocked"
                        class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded">
                    <span class="text-sm text-gray-700">
                        Saya menyatakan bahwa:
                        <ul class="list-disc ml-5 mt-1">
                            1) Seluruh data pegawai sudah saya periksa.
                            2) Dokumen pengajuan telah lengkap.
                            3) File <b>pidana & disiplin</b> telah diupload dengan benar.
                        </ul>
                    </span>
                </label>
            </div>
            <!-- BUTTON -->
            <div class="flex justify-between mt-6">

                <!-- REVISI & TOLAK -->
                <div class="flex gap-2">
                    <button
                        @click="!isLocked && openModal('revisi')"
                        :disabled="isLocked"
                        :class="isLocked 
            ? 'bg-gray-400 cursor-not-allowed' 
            : 'bg-yellow-500 hover:bg-yellow-600'"
                        class="px-4 py-2 text-white rounded-xl">
                        ✏️ Request Revisi
                    </button>

                    <button
                        @click="!isLocked && openModal('ditolak')"
                        :disabled="isLocked"
                        :class="isLocked 
                ? 'bg-gray-400 cursor-not-allowed' 
                : 'bg-red-600 hover:bg-red-700'"
                        class="px-4 py-2 text-white rounded-xl">
                        ❌ Tolak
                    </button>
                </div>

                <!-- SUBMIT -->
                <button
                    @click="!isLocked && submitFinal()"
                    :disabled="isLocked || !canSubmit()"
                    :class="(isLocked || !canSubmit()) 
        ? 'bg-gray-400 cursor-not-allowed' 
        : 'bg-green-600 hover:bg-green-700'"
                    class="px-6 py-2 text-white rounded-xl">
                    🚀 Submit ke BKPSDM
                </button>
                @endif

                @if($role=='AdminBkdPensiun' && $pengajuan->current_step == 3 )
                <!-- CHECKBOX -->
                <label class="flex items-start gap-3 cursor-pointer mb-6">
                    <input
                        type="checkbox"
                        x-model="isChecked"
                        :disabled="isLocked"
                        class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded">
                    <span class="text-sm text-gray-700">
                        Saya menyatakan bahwa:
                        <ul class="list-disc ml-5 mt-1">
                            1) Seluruh data pegawai sudah saya periksa.
                            2) Dokumen pengajuan telah lengkap.
                        </ul>
                    </span>
                </label>
            </div>
            <!-- BUTTON -->
            <div class="flex justify-between mt-6">

                <!-- REVISI & TOLAK -->
                <div class="flex gap-2">
                    <button
                        @click="!isLocked && openModal('revisi')"
                        :disabled="isLocked"
                        :class="isLocked 
            ? 'bg-gray-400 cursor-not-allowed' 
            : 'bg-yellow-500 hover:bg-yellow-600'"
                        class="px-4 py-2 text-white rounded-xl">
                        ✏️ Request Revisi
                    </button>

                    <!-- <button
                        @click="!isLocked && openModal('ditolak')"
                        :disabled="isLocked"
                        :class="isLocked 
                ? 'bg-gray-400 cursor-not-allowed' 
                : 'bg-red-600 hover:bg-red-700'"
                        class="px-4 py-2 text-white rounded-xl">
                        ❌ Tolak
                    </button> -->

                </div>
                <!-- SUBMIT -->
                <button
                    @click="!isLocked && submitFinal()"
                    :disabled="isLocked || !canSubmit()"
                    :class="(isLocked || !canSubmit()) 
        ? 'bg-gray-400 cursor-not-allowed' 
        : 'bg-green-600 hover:bg-green-700'"
                    class="px-6 py-2 text-white rounded-xl">
                    🚀 Pengajuan Diterima BKPSDM
                </button>
                @endif
            </div>
        </div>

        <!-- PREVIEW MODAL -->
        <div x-show="previewOpen" class="fixed inset-0 bg-black/60 flex items-center justify-center z-999">

            <div
                @click.away="previewOpen = false"
                class="bg-white p-4 rounded-xl w-[80%] h-[80%] relative">

                <button @click="previewOpen=false" class="bg-white px-3 py-3 absolute top-5 right-3">
                    ✕
                </button>

                <iframe
                    :src="previewUrl"
                    class="w-full h-full rounded-lg">
                </iframe>

            </div>

        </div>

    </div>




    <!-- MODAL REVISI / TOLAK -->
    <div x-show="modalOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center z-999 px-4">

        <div class="bg-white p-6 rounded-xl ">

            <h3 class="font-semibold mb-3 text-lg" x-text="modalType == 'revisi' || modalType == 'revisi-bkn' ? 'Catatan Revisi' : 'Alasan Penolakan'"></h3>

            <textarea
                x-model="catatan"
                rows="7"
                class="w-full border rounded-lg p-3 mb-4 focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Masukkan catatan..."></textarea>

            <!-- 🔥 PILIH TUJUAN REVISI -->
            <div x-show="['revisi', 'revisi-bkn','ditolak'].includes(modalType)" class="mb-4">
                <label class="block mb-2 font-medium">Kirim revisi ke:</label>

                <label class="flex items-center gap-2 mb-2">
                    <input type="radio" value="1" x-model="revisiStep">
                    Pegawai Calon Pensiun
                </label>

                @if($role=='AdminBkdPensiun')
                <label class="flex items-center gap-2">
                    <input type="radio" value="2" x-model="revisiStep">
                    OPD
                </label>
                @endif
            </div>


            <div class="flex justify-end gap-2">
                <button
                    @click="modalOpen=false"
                    class="px-4 py-2 bg-gray-300 rounded">
                    Batal
                </button>

                <button
                    @click="submitAksi()"
                    class="px-4 py-2 text-white rounded"
                    :class="modalType=='revisi' ? 'bg-yellow-500' : 'bg-red-600'">
                    Kirim
                </button>
            </div>

        </div>

    </div>

</div>
</div>


<script>
    // pidana start


    function simpanSurat(jenis) {
        SwalHelper.loadingToast('Mengirim...')

        let nomor, ttd, jabatan;

        if (jenis === 'disiplin') {
            nomor = $('#nomor_surat').val();
            ttd = $('#ttd_oleh').val();
            jabatan = $('[x-model="disiplin_jabatan"]').val();
        } else if (jenis === 'pidana') {
            nomor = $('#nomor_surat_pidana').val();
            ttd = $('#id_pidana_ttd_oleh').val();
            jabatan = $('[x-model="pidana_jabatan"]').val();
        }

        $.post('/dokumen/store', {
                _token: '{{ csrf_token() }}',
                jenis: jenis,
                nomor_surat: nomor,
                id_ttd_oleh: ttd,
                user_id: '{{ $pengajuan->pegawai_id }}',
                pengajuan_id: '{{ $pengajuan->id }}',
                jabatan: jabatan,
                role: '{{ $role }}'
            })
            .done(function(res) {
                SwalHelper.toast('Upload berhasil')
                location.reload()
            })
            .fail(function(xhr) {
                let message = 'Terjadi kesalahan ❌';

                // ambil pesan dari Laravel kalau ada
                if (xhr.responseJSON) {
                    message = xhr.responseJSON.message ||
                        JSON.stringify(xhr.responseJSON.errors) ||
                        message;
                }

                SwalHelper.errorToast(message);
            });
    }

    function loadPreview(jenis) {

        let nomor, ttd;
        let sarana = 0;


        if (jenis === 'disiplin') {
            nomor = $('#nomor_surat').val();
            ttd = $('#ttd_oleh').val();
            jabatan = $('[x-model="disiplin_jabatan"]').val();
            sarana = $('#ttd_sarana_dis').is(':checked') ? 1 : 0;

            nomor_opd = $('#nomor_surat_disopd').val();
            tgl_opd = $('#tanggal_surat_disopd').val();
            pejabat_opd = $('#pejabat_disopd').val();
        } else {
            nomor = $('#nomor_surat_pidana').val();
            ttd = $('#id_pidana_ttd_oleh').val();
            jabatan = $('[x-model="pidana_jabatan"]').val();
            sarana = $('#ttd_sarana_pid').is(':checked') ? 1 : 0;

            nomor_opd = $('#nomor_surat_pidopd').val();
            tgl_opd = $('#tanggal_surat_pidopd').val();
            pejabat_opd = $('#pejabat_pidopd').val();
        }

        let url = `{{ url("/dokumen/generate") }}?jenis=${jenis}` +
            `&nomor_surat=${encodeURIComponent(nomor)}` +
            `&id_ttd_oleh=${encodeURIComponent(ttd)}` +
            `&jabatan=${encodeURIComponent(jabatan)}` +
            `&user_id={{ $pengajuan->pegawai_id }}` +
            `&pengajuan_id={{ $pengajuan->id }}` +
            `&role={{ $role }}` +
            `&_=${Date.now()}` +
            `&no_opd=${encodeURIComponent(nomor_opd)}` +
            `&tgl_opd=${encodeURIComponent(tgl_opd)}` +
            `&pejabat_opd=${encodeURIComponent(pejabat_opd)}` +
            `&sarana=${encodeURIComponent(sarana)}`;

        $('#previewFrame').attr('src', url);
    }

    $(document).ready(function() {

        $('#id_pidana_ttd_oleh').select2({
            width: '100%'
        });

        $('#ttd_oleh').select2({
            placeholder: "Pilih Penandatangan",
            allowClear: true,
            width: '100%'
        });

    });



    function submitProses() {

        let filePidana = document.getElementById('file_pidana').files[0]
        let fileDisiplin = document.getElementById('file_disiplin').files[0]
        let catatan = document.getElementById('catatan').value

        if (!filePidana && !fileDisiplin) {
            alert('Minimal salah satu file harus diupload')
            return
        }

        let formData = new FormData()
        formData.append('id', @json($pengajuanId))
        formData.append('file_pidana', filePidana)
        formData.append('file_disiplin', fileDisiplin)
        formData.append('catatan', catatan)

        fetch('{{ url("/admin/opd/pengajuan/proses") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    alert('Berhasil diproses')
                    location.reload()
                } else {
                    alert('Gagal proses')
                }

            })
    }

    function dokumenOPD(currentStep, role) {
        return {
            currentStep: currentStep,
            role: role,
            isLocked: currentStep > currentStep,

            dokumenList: @json($dokumenList),
            previewOpen: false,
            previewUrl: null,
            isChecked: false,
            uploading: {},
            modalOpen: false,
            modalType: null,
            catatan: '',

            init() {
                if (!this.uploading) {
                    this.uploading = {}
                }

                // ✅ auto checklist kalau sudah lewat step
                if (this.isLocked) {
                    this.isChecked = true
                }

                console.log('currentStep:', this.currentStep)
            },


            openModal(type) {
                this.modalType = type
                this.catatan = ''
                this.modalOpen = true

                // 🔥 set default biar langsung kelihatan
                if (type === 'revisi' && this.role === 'AdminBkdPensiun') {
                    this.revisiStep = '1' // Pengaju langsung kepilih
                }
            },

            isEmpty(val) {
                return val === null || val === undefined || val === ''
            },

            canSubmit() {
                // let pidanaValid =
                //     $('#nomor_surat_pidana').val() &&
                //     $('#id_pidana_ttd_oleh').val()

                // let disiplinValid =
                //     $('#nomor_surat').val() &&
                //     $('#ttd_oleh').val()
                // BKPSDM → tidak perlu cek upload
                if (this.role === 'AdminBkdPensiun') {
                    return this.isChecked
                }

                // OPD → hanya cek dokumen miliknya
                let allUploaded = this.dokumenList
                    .filter(doc =>
                        doc.wajib && ['opd', 'pegawai'].includes((doc?.penyedia || '').toLowerCase())
                    )
                    .every(doc => doc.uploaded)

                return this.isChecked && allUploaded //&& pidanaValid && disiplinValid
            },

            submitFinal() {
                role: '{{ $role }}'

                if (!this.canSubmit()) {
                    alert('Checklist belum lengkap')
                    return
                }

                if (role == 'AdminOpdPensiun') {
                    if (!confirm('Yakin ingin submit ke BKPSDM?')) {
                        return
                    }
                } else {
                    if (!confirm('Anda yakin untuk melanjutkan proses Pensiun Pegawai ini?')) {
                        return
                    }
                }
                SwalHelper.loadingToast('Sedang Mengirim...')

                fetch('{{ url("/admin-pensiun/submit-final") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        pengajuan_id: @json($pengajuanId),
                        role: '{{ $role }}',
                    })
                })
                .then(res => res.json())
                .then(data => {

                    if (data.success) {
                        // alert('Berhasil dikirim ke BKPSDM')
                        this.isLocked = true
                        SwalHelper.toast('Berhasil Submit')
                        setTimeout(() => {
                            window.location.href = `{{ url("/admin-pensiun") }}?role={{ $role }}`
                        }, 1200) // 1.2 detik biar toast kelihatan
                        // location.reload()
                    } else {
                        SwalHelper.errorToast(data.message || 'Terjadi kesalahan ❌')
                    }

                })
                .catch(err => {
                    alert('Terjadi kesalahan')
                    console.log(err)
                })
            },

            submitAksi() {

                if (!this.catatan) {
                    alert('Catatan wajib diisi')
                    return
                }

                // 🔥 VALIDASI KHUSUS BKD
                if (this.modalType == 'revisi' && this.role == 'AdminBkdPensiun') {
                    if (!this.revisiStep) {
                        alert('Pilih tujuan revisi dulu')
                        return
                    }
                }

                console.log(this.catatan);

                fetch('{{ url("/admin-pensiun/aksi") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            pengajuan_id: @json($pengajuanId),
                            aksi: this.modalType, // revisi / tolak
                            catatan: this.catatan,
                            role: this.role,
                            revisi_step: this.revisiStep // kosong kalau bukan BKD
                        })
                    })
                    .then(res => res.json())
                    .then(data => {

                        if (data.success) {
                            alert('Berhasil diproses')
                            setTimeout(() => {
                                window.location.href = `{{ url("/admin-pensiun") }}?role={{ $role }}`
                            }, 1200) // 1.2 detik biar toast kelihatan
                        } else {
                            alert('Gagal')
                        }

                    })
                    .catch(err => {
                        alert('Terjadi kesalahan')
                        console.log(err)
                    })
            },
            lanjutTahap(pengajuanId, step) {
                if (step == 4) {
                    //ubah status_akhir di pengajuan dan tambahkan workflowlog
                    fetch('{{ url("/admin-pensiun/bkd-proses") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                pengajuanId: pengajuanId,
                                role: this.role
                            })
                        })
                        .then(res => res.json())
                        .then(data => {

                            if (data.success) {
                                alert(data.message)
                                setTimeout(() => {
                                    window.location.href = '{{ url("/admin-pensiun-detail") }}/' + pengajuanId + '?role={{ $role }}'
                                }, 1200) // 1.2 detik biar toast kelihatan
                            } else {
                                alert('Gagal')
                            }

                        })
                        .catch(err => {
                            alert('Terjadi kesalahan')
                            console.log(err)
                        })

                } else if (step == 5) {
                    //buka modal dialog upload

                }

            },

            previewFile(url) {
                if (!url.startsWith('bkpsdm')) {
                    this.previewUrl = url
                } else {
                    this.previewUrl = "https://" + url
                }
                this.previewOpen = true
            },
            // openUpload() {
            //     this.tanggal_sk = '';
            //     this.tmt_pensiun = '';
            //     this.uploadFinal = true;
            // },

            uploadDokumen(id, event) {

                let file = event.target.files[0]
                // console.log(file);
                if (!file) {
                    alert('File tidak dipilih')
                    return
                }

                this.uploading[id] = true
                SwalHelper.loadingToast('Sedang Mengunggah...')
                let formData = new FormData()
                formData.append('file', file)
                formData.append('persyaratan_id', id)
                formData.append('pengajuan_id', @json($pengajuanId))

                fetch('{{ url("/admin-pensiun/dokumen/upload") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {

                        this.uploading[id] = false

                        if (data.success) {

                            // 🔥 update UI langsung tanpa reload
                            let index = this.dokumenList.findIndex(d => d.id == id)

                            if (index !== -1) {
                                this.dokumenList[index].uploaded = true
                                this.dokumenList[index].file_url = data.file_url
                            }
                            SwalHelper.toast('Upload berhasil')
                            location.reload()
                            // alert('Upload berhasil')

                        } else {
                            SwalHelper.errorToast(data.message || 'Terjadi kesalahan ❌')
                            // alert(data.message || 'Upload gagal')
                        }
                        canSubmit()
                    })
                    .catch(err => {
                        this.uploading[id] = false
                        SwalHelper.errorToast(data.message || 'Terjadi kesalahan ❌')
                        // alert('Terjadi kesalahan')
                        console.log(err)
                    })
            },
        }
    }



    function skUpload(initialPath = '') {
        return {
            skPath: initialPath,
            loading: false,
            uploadFinal: false,
            tmt_pensiun: '',
            nomor_sk: '',
            tanggal_sk: '',
            tanggal_pertek: '',
            pejabat_ttd: '',
            keterangan_jabatan: '',

            async uploadSk() {
                let fileInput = this.$refs.file;

                if (!fileInput.files.length) {
                    SwalHelper.errorToast('Pilih file dulu');
                    return;
                }

                //this.loading = true;
                SwalHelper.loadingToast('Sedang Mengirim...');

                let formData = new FormData();
                formData.append('file', fileInput.files[0]);
                formData.append('pengajuan_id', '{{ $pengajuan->id }}');
                formData.append('tmt_pensiun', this.tmt_pensiun);
                formData.append('nomor_sk', this.nomor_sk);
                formData.append('tanggal_sk', this.tanggal_sk);
                formData.append('tanggal_pertek', this.tanggal_pertek);
                formData.append('pejabat_ttd', this.pejabat_ttd);
                formData.append('keterangan_jabatan', this.keterangan_jabatan);

                try {
                    let res = await fetch("{{ route('pensiun.upload.sk') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    let data = await res.json();

                    if (res.status) {
                        SwalHelper.toast('Berhasil Upload SK');

                        // 🔥 FIX: anti cache iframe
                        this.skPath = "{{ route('pensiun.preview.sk', $pengajuan->id) }}?t=" + Date.now();

                        // reset input file
                        fileInput.value = '';
                        this.uploadFinal = false;
                        location.reload();

                    } else {
                        SwalHelper.errorToast(data.message || 'Terjadi kesalahan ❌');
                    }

                } catch (e) {
                    SwalHelper.errorToast('Terjadi kesalahan ❌');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>

@endsection