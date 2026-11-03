@extends('layouts.app')
@section('title', 'Pengajuan Cuti')
@section('content')
<div class="max-w-7xl mx-auto p-3">
    <!-- Card Container -->

    <div x-data="detail()">
        <div class="bg-white border rounded-2xl shadow-sm p-5 mb-6">

            <!-- TOP: Breadcrumb -->
            <div class="text-sm text-gray-500 mb-3">
                <span class="hover:text-blue-600 cursor-pointer">Dashboard</span>
                <span class="mx-1">/</span>
                <span class="hover:text-blue-600 cursor-pointer">Pengajuan Cuti</span>
                <span class="mx-1">/</span>
                <span class="text-gray-700 font-medium">Detail</span>
            </div>

            <!-- MAIN HEADER -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <!-- LEFT -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">
                        Detail Pengajuan Cuti
                    </h2>
                </div>


            </div>
        </div>

        <!-- <div class="grid grid-cols-1 gap-4"> -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 mb-6 mt-5">
            <div class="bg-white border rounded-xl p-6 shadow-sm mb-5">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">
                    Informasi Pengajuan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

                    <!-- Nomor Tiket -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-500 text-xs mb-1">Nomor Tiket</p>
                        <p class="font-semibold text-gray-800 tracking-wide">
                            {{ $pengajuan?->nomor_tiket ?? '' }}
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-500 text-xs mb-1">Tanggal Pengajuan</p>

                        <div class="flex items-center gap-2">
                            <p class="font-semibold text-gray-800">
                                {{ $pengajuan ? tglindonesia($pengajuan->tanggal_pengajuan) : ''  }}
                            </p>

                            <span class="text-xs px-2 py-1 rounded-full font-medium">
                                {{ $pengajuan->status_akhir }}
                            </span>
                        </div>
                    </div>

                    <!-- Layanan -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-500 text-xs mb-1">Layanan</p>
                        <p class="font-semibold text-gray-800">
                            {{ $cutiDetail ? $cutiDetail->nama_layanan : '' }}
                        </p>
                    </div>
                    <!-- Layanan -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-500 text-xs mb-1">Lama Cuti</p>
                        <p class="font-semibold text-gray-800">
                            {{ $cutiDetail?->lama_cuti ?? '' }} Hari Kerja
                        </p>
                    </div>

                    <!-- Layanan -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-500 text-xs mb-1">Waktu</p>
                        <p class="font-semibold text-gray-800">
                            {{ $cutiDetail ? tglindonesia($cutiDetail->tgl_mulai).' - '.tglindonesia($cutiDetail->tgl_selesai) : '' }}
                        </p>
                    </div>

                    <!-- Layanan -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-500 text-xs mb-1">Alasan Cuti</p>
                        <p class="font-semibold text-gray-800">
                            {{ $cutiDetail?->deskripsi ?? '' }}
                        </p>
                    </div>

                </div>
            </div>
            <!-- DATA PEGAWAI -->
            <!-- <div class="bg-white border rounded-xl p-4 mb-6 mt-5"> -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 mb-6 mt-5">

                <!-- HEADER -->
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold">Data Pegawai</h3>

                    <!-- BUTTON LIHAT DETAIL
                    <a
                        href="{{ route('pegawai.profile', $pengajuan->pegawai_id) }}"
                        target="_blank"
                        class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                        👁 Lihat Detail
                    </a> -->

                </div>

                <!-- CONTENT -->
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500">Nama</div>
                        <div class="font-semibold">
                            {{ $dataPegawai?->nama_pegawai ?? '' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-gray-500">NIP</div>
                        <div class="font-semibold">
                            {{ $dataPegawai?->nip ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-gray-500">SKPD</div>
                        <div class="font-semibold">
                            {{ $dataPegawai?->skpd ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 mb-6 mt-5">

                <!-- HEADER -->
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold mb-4">Syarat Dokumen</h3>
                </div>
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
                            <tr>
                                <td width='5%' class="text-md p-4">1.</td>
                                <td width='35%' class="text-md p-4">Surat Pengantar (format pdf)</td>
                                <td width='60%'>
                                    <template x-if="doc_sp_skpd">
                                        <button type='button' class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700" @click="openDoc('{{ $pengajuan->id }}', 17)">Lihat File</button>
                                    </template>
                                </td>
                            </tr>
                            <tr>
                                <td width='5%' class="text-md p-4">2.</td>
                                <td width='35%' class="text-md p-4">Formulir Cuti (format pdf)</td>
                                <td width='60%'>
                                    <template x-if="doc_form_cuti">
                                        <button type='button' class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700" @click="openDoc('{{ $pengajuan->id }}', 18)">Lihat File</button>
                                    </template>
                                </td>
                            </tr>
                            <tr>
                                <td width='5%' class="text-md p-4">3.</td>
                                <td width='35%' class="text-md p-4">Dokumen Pendukung Lain (format pdf)</td>
                                <td width='60%'>
                                    <template x-if="doc_pendukung">
                                        <button type='button' class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700" @click="openDoc('{{ $pengajuan->id }}', 19)">Lihat File</button>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RIGHT ACTION -->
            <div class="flex items-center gap-2 justify-end">

                <!-- BACK -->
                <a href="{{ route('proses.cuti') }}?id={{ $idProses }}"
                    class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                    ← Kembali
                </a>
                <button
                    @click="riwayat('{{ $pengajuan->id }}')"
                    class="px-4 py-2 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
                    Riwayat
                </button>
                <!-- ACTION BUTTON -->
                <button
                    @click="terima('{{ $pengajuan->id }}')"
                    class="px-4 py-2 text-sm bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                    Terima
                </button>
                <button
                    @click="tolak('{{ $pengajuan->id }}')"
                    class="px-4 py-2 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition">
                    Tolak
                </button>
            </div>
        </div>
        <div x-show="previewOpen" class="fixed inset-0 bg-black/60 flex items-center justify-center z-999">

            <div class="bg-white p-4 rounded-xl w-[80%] h-[80%] relative">

                <button @click="previewOpen=false" class="absolute bg-white px-2 py-2 top-3 right-3">
                    ✕
                </button>

                <iframe
                    :src="previewUrl"
                    class="w-full h-full rounded-lg">
                </iframe>

            </div>

        </div>

        <!-- Modal Background -->
        <div
            x-show="catatan"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-999"
            x-transition>

            <!-- Modal Content -->
            <div
                @click.away="catatan = false"
                class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md md:max-w-2xl lg:max-w-2xl">

                <h2 class="text-xl font-bold mb-4">
                    Catatan Penolakan<span x-text='namaDokumen'></span>
                </h2>

                <input
                    type="hidden"
                    name="idPeng"
                    x-model="idPengajuan">

                <textarea
                    rows='4'
                    class="border border-gray-500 w-full rounded-lg p-4"
                    name="catatan_text"
                    x-model="catatan_text"></textarea>


                <div class="flex justify-end gap-2">

                    <button
                        type="button"
                        @click="catatan = false"
                        class="px-4 py-2 border rounded">
                        Batal
                    </button>

                    <button
                        @click="saveCatatan()"
                        class="px-4 py-2 bg-green-600 text-white rounded">
                        Simpan
                    </button>

                </div>

            </div>


        </div>

        <!-- Modal Background -->
        <div
            x-show="showDaftarModal"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-999"
            x-transition>
            <div
                @click.away="showDaftarModal = false"
                class="bg-white rounded-2xl shadow-xl w-full max-w-4xl p-6 mx-4 overflow-y-auto max-h-120 overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-semibold">Riwayat Cuti Pegawai</h2>
                    </div>
                    <button type="button" @click="showDaftarModal = false" class="text-gray-500 hover:text-gray-900">✕</button>
                </div>

                <div class="overflow-x-auto my-4">
                    <table class="w-full text-sm text-left border border-gray-100 border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Jenis Cuti</th>
                                <th class="px-4 py-3">Lama (Hari)</th>
                                <th class="px-4 py-3">Dari/Sampai</th>
                                <th class="px-4 py-3">No SK</th>
                                <th class="px-4 py-3">tanggal SK</th>
                                <th class="px-4 py-3">Pejabat Penandatangan</th>

                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(items, idx) in riwayat_cuti_baru" :key="items.id">
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-3" x-text="idx + 1"></td>
                                    <td class="px-4 py-3" x-text="items.jenis_cuti"></td>
                                    <td class="px-4 py-3" x-text="items.lama"></td>
                                    <td class="px-4 py-3" x-text="items.dari+' s.d '+items.sampai"></td>
                                    <td class="px-4 py-3" x-text="items.nomor_sk"></td>
                                    <td class="px-4 py-3" x-text="items.tanggal_sk"></td>
                                    <td class="px-4 py-3" x-text="items.pejabat_sk"></td>
                                </tr>
                            </template>

                            <template x-for="(item, index) in riwayat_cuti" :key="item.id_riwayat_cuti">
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-3" x-text="riwayat_cuti_baru.length + index + 1"></td>
                                    <td class="px-4 py-3" x-text="item.jenis_cuti"></td>
                                    <td class="px-4 py-3" x-text="item.lama"></td>
                                    <td class="px-4 py-3" x-text="item.dari+' s.d '+item.sampai"></td>
                                    <td class="px-4 py-3" x-text="item.nomor_sk"></td>
                                    <td class="px-4 py-3" x-text="item.tanggal_sk"></td>
                                    <td class="px-4 py-3" x-text="item.pejabat_sk"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function detail() {
        return {
            idProses: @json($idProses),
            catatan: false,
            idPengajuan: 0,
            doc_pendukung: @json($doc_pendukung),
            doc_sp_skpd: @json($doc_sp_skpd),
            doc_form_cuti: @json($doc_form_cuti),
            previewOpen: false,
            previewUrl: null,
            catatan_text: '',
            showDaftarModal: false,
            riwayat_cuti_baru: @json($riwayat_cuti_baru),
            riwayat_cuti: @json($riwayat_cuti),
            openDoc(pengajuanId, DocId) {
                let fileurl = `{{ route('cuti.lihat-upload') }}?pengajuanId=${pengajuanId}&docs_id=${DocId}`;
                this.previewUrl = fileurl;
                this.previewOpen = true;
            },
            tolak(id) {
                this.idPengajuan = id;
                this.catatan = true;
            },
            riwayat(id) {
                this.showDaftarModal = true;

            },
            terima(id) {
                fetch('{{ url("proses-cuti/pegawai-tambah") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id_usulan: id,
                            id_proses: this.idProses
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        // simpan id pengajuan baru
                        if (data.success == true) {
                            //alert(data.message);
                            document.location.href = "{{ url('/proses-cuti/pegawai') }}/" + this.idProses;
                        } else {
                            alert(data.message);
                        }
                    })
            },
            async saveCatatan() {
                try {
                    const response = await fetch("{{ url('/cuti/usulan-tolak') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            pengajuanId: this.idPengajuan,
                            catatan_text: this.catatan_text
                        })
                    });

                    const data = await response.json();
                    this.results = data;
                    if (data.success == true) {
                        Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                buttons: true,
                                dangerMode: true,
                            })
                            .then((willOk) => {
                                if (willOk) {
                                    document.location.href = "{{ route('proses.cuti') }}?id=" + this.idProses;
                                }
                            });
                    }
                    //alert(data.message);

                } catch (error) {
                    console.error(error);
                    alert("Terjadi kesalahan jaringan");
                }


            }
        }
    }
</script>
@endsection