<div x-show="currentStep === 4   && status_akhir && status_akhir != 'selesai' && status_akhir != 'diajukan'" x-transition>
    <div class="grid grid-cols-1 gap-4">
        <div class="bg-white border rounded-xl p-6 shadow-sm mb-3">
            <div class="bg-white border rounded-2xl shadow-sm p-5 mb-6">
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

            <div class="bg-white border rounded-xl p-6 shadow-sm mb-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-500 text-xs mb-1">Tanggal Pengajuan</p>

                        <div class="flex items-center gap-2">
                            <p class="font-semibold text-gray-800">
                                {{ $pengajuan && $pengajuan->status_akhir=='draft' ? tglindonesia(now()) : ($pengajuan ? tglindonesia($pengajuan->tanggal_pengajuan):'') }}
                            </p>
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
                            {{ $cutiDetail?->lama_cuti ?? '' }} {{ $cutiDetail?->satuan_waktu ?? '' }}
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

                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-500 text-xs mb-1">Status</p>
                        <p class="font-semibold text-gray-800">
                            {{ $pengajuan->status_akhir ?? ''}}
                        </p>
                    </div>

                    @if($pengajuan?->status_akhir=='Revisi')
                    <div class="bg-red-100 rounded-xl p-4">
                        <p class="text-gray-500 text-xs mb-1">Catatan Penolakan</p>
                        <p class="font-bold text-red-800">
                            {{ $catatan ?? ''}}
                        </p>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        <div class=" bg-white border rounded-xl p-6 shadow-sm">
            <div class="bg-white border rounded-xl p-6 shadow-sm mb-5">
                <!-- HEADER -->
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold mb-4">Upload Syarat Dokumen</h3>
                </div>
                <div class="overflow-x-auto">

                    <table class="w-full text-sm">
                        <thead class="text-gray-600">
                            <tr>
                                <th class="text-left py-3 w-10">No</th>
                                <th class="text-left py-3">Nama Berkas</th>
                                <th class="text-left py-3 w-72">&nbsp;</th>
                                <th class="text-left py-3 w-72">&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td width='5%' class="text-md p-4">1.</td>
                                <td width='35%' class="text-md p-4">Surat Pengantar (format pdf)*</td>
                                <td width='30%' class="pl-3"><button type='button' @click="uploadPengantar()" class="border-2 border-green-500 bg-green-500 text-white px-3 rounded-lg">Upload</button></td>
                                <td width='35%'>
                                    <template x-if="doc_sp_skpd">
                                        <button type='button' class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700" @click="openDoc('{{ $pengajuanId }}', 17)">Lihat File</button>
                                    </template>
                                </td>
                            </tr>
                            <tr>
                                <td width='5%' class="text-md p-4">2.</td>
                                <td width='35%' class="text-md p-4">Formulir Cuti (format pdf)*</td>
                                <td width='30%' class="pl-3"><button type='button' @click="uploadFormulir()" class="border-2 border-green-500 bg-green-500 text-white px-3 rounded-lg">Upload</button></td>
                                <td width='35%'>
                                    <template x-if="doc_form_cuti">
                                        <button type='button' class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700" @click="openDoc('{{ $pengajuanId }}', 18)">Lihat File</button>
                                    </template>
                                </td>
                            </tr>
                            <tr>
                                <td width='5%' class="text-md p-4">3.</td>
                                <td width='35%' class="text-md p-4">Dokumen Pendukung Lain (format pdf)<span x-text="dokumen_wajib"></span></td>
                                <td width='30%' class="pl-3"><button type='button' @click="uploadPendukung()" class="border-2 border-green-500 bg-green-500 text-white px-3 rounded-lg">Upload</button></td>
                                <td width='35%'>
                                    <template x-if="doc_pendukung">
                                        <button type='button' class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700" @click="openDoc('{{ $pengajuanId }}', 19)">Lihat File</button>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class='text-xs pt-5'>* Wajib Dilampirkan dengan ukuran file maksimum 500kB</p>
                    <template x-if="selectedJenis==6">
                        <p class='text-xs'>Note: Cuti Tahunan yang diajukan dengan TMT < 7 hari dari Tanggal Pengajuan harus melampirkan Dokumen Pendukung</p>
                    </template>
                </div>
            </div>
            <div class="pt-2 mt-2 flex gap-2 justify-end">
                <button type="button"
                    @click="hapus(' {{ $pengajuanId }}')"
                    class="flex w-auto whitespace-nowrap bg-red-500 text-center gap-2 px-4 py-2 border border-red-500 text-blue-500 rounded-lg text-md">
                    <span class="text-md text-white font-bold text-center">
                        Batalkan Usulan
                    </span>

                </button>
                <!-- icon by Icons8 -->
                <button type="button"
                    @click="ajukanCuti('{{ $pengajuanId }}')"
                    class="flex w-auto whitespace-nowrap items-center bg-blue-500 gap-2 px-2 py-2 border border-blue-500 text-blue-500 rounded-lg text-md">
                    <span class="text-md text-white font-bold text-center">
                        Ajukan Cuti
                    </span>
                </button>
            </div>

        </div>
        <div class="mt-8 flex justify-between items-center w-full">
            <button
                @click="prevStep()"
                class="ml-6 px-6 py-2 bg-gray-300 rounded-xl text-center">
                Sebelumnya
            </button>

        </div>
    </div>


    <!-- Modal Background -->
    <div
        x-show="UploadModalOpen"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        x-transition>

        <!-- Modal Content -->
        <div
            @click.away="UploadModalOpen = false"
            class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md md:max-w-2xl lg:max-w-2xl">

            <h2 class="text-xl font-bold mb-4">
                Upload Dokumen <span x-text='namaDokumen'></span>
            </h2>

            <!-- <form action="/pengajuan/cuti/upload" method="POST" enctype="multipart/form-data"> -->
            <div x-data="uploadDoc()">
                <!-- Laravel CSRF -->
                <!-- @csrf -->
                <div class="mb-4">
                    <label class="block mb-2 font-medium border-b-2">
                        Pilih File
                    </label>

                    <!-- <input
                        type="file"
                        name="dokumen"
                        class="w-full border p-2 rounded"> -->

                    <input type="file" name="file" @change="file = $event.target.files[0]"
                        class="border p-2 rounded w-full mb-2">

                    <input
                        type="hidden"
                        name="idPeng"
                        x-model="idPeng">
                    <input
                        type="hidden"
                        name="doc_id"
                        x-model="doc_id">
                </div>

                <div class="flex justify-end gap-2">

                    <button
                        type="button"
                        @click="UploadModalOpen = false"
                        class="px-4 py-2 border rounded">
                        Batal
                    </button>

                    <button
                        @click="uploadFile"
                        class="px-4 py-2 bg-green-600 text-white rounded">
                        Upload
                    </button>

                </div>
                <!-- </form> -->

            </div>
        </div>
    </div>

    <!--Modal Lihat Dokumen-->

    <div
        x-show="SeeDoc"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        x-transition>

        <!-- Modal Content -->
        <div
            @click.away="SeeDoc = false"
            class="bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl md:max-w-4xl lg:max-w-4xl">

            <h2 class="text-xl font-bold mb-4">
                Dokumen <span x-text='namaDokumen'></span>
            </h2>
            <!-- Close -->
            <button
                @click="SeeDoc = false"
                class="absolute top-2 right-2 text-red-500 text-xl">
                ✕
            </button>

            <!-- PDF Viewer -->
            <iframe
                :src="fileUrl"
                class="w-full h-full rounded">
            </iframe>

        </div>
    </div>
</div>