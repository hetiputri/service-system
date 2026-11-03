<!-- STEP 5 -->
<div x-show="currentStep === 4 && status_akhir && status_akhir != 'selesai' && status_akhir != 'diajukan'">

    <h2 class="text-xl font-semibold mb-6">
        Review Pengajuan
    </h2>

    <!-- INFO PENGAJUAN -->
    <div class="bg-gray-50 border rounded-xl p-6 mb-6">

        <h3 class="font-semibold mb-4">
            Informasi Pengajuan
        </h3>

        <div class="grid grid-cols-2 gap-4 text-sm">

            <div>
                <div class="text-gray-500">Jenis Pensiun</div>
                <div class="font-semibold" x-text="getJenisName(selectedJenis)"></div>
            </div>

            <div>
                <div class="text-gray-500">Tanggal Pengajuan</div>
                <div class="font-semibold">
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>

        </div>

    </div>


    <!-- DATA PEGAWAI -->
    <div class="bg-white border rounded-xl p-6 mb-6">

        <h3 class="font-semibold mb-4">
            Data Pegawai
        </h3>

        <div class="grid grid-cols-2 gap-4 text-sm">

            <div>
                <div class="text-gray-500">NIP</div>
                <div class="font-semibold">{{ $dataPribadi['nip'] }}</div>
            </div>

            <div>
                <div class="text-gray-500">Nama</div>
                <div class="font-semibold">{{ $dataPribadi['nama_pegawai'] }}</div>
            </div>

            <div>
                <div class="text-gray-500">Jabatan</div>
                <div class="font-semibold">{{ $dataPribadi['jabatan'] }}</div>
            </div>

            <div>
                <div class="text-gray-500">Unit Kerja</div>
                <div class="font-semibold">{{ $dataPribadi['unit_kerja'] }}</div>
            </div>

            <div>
                <div class="text-gray-500">Pangkat</div>
                <div class="font-semibold">
                    {{ $dataPribadi['pangkat'] }} ({{ $dataPribadi['gol_ruang'] }})
                </div>
            </div>

            <!-- <div>
<div class="text-gray-500">Masa Kerja</div>
<div class="font-semibold">
{{ $dataPribadi['mk_tahun'] }} Tahun {{ $dataPribadi['mk_bulan'] }} Bulan
</div>
</div> -->

        </div>

    </div>


    <!-- STATUS DOKUMEN -->
    <div class="bg-white border rounded-xl p-6 mb-6">

        <h3 class="font-semibold mb-4">
            Status Dokumen
        </h3>

        <div class="space-y-2 text-sm">

            <template x-for="doc in dokumenList">

                <div class="flex justify-between border-b py-2">

                    <div class="flex items-center gap-2">

                        <span x-text="doc.label"></span>

                        <template x-if="doc.wajib">
                            <span class="text-red-500">*</span>
                        </template>

                    </div>

                    <span
                        class="text-xs px-2 py-1 rounded"
                        :class="fileExisting[doc.id] || doc.uploaded
? 'bg-green-100 text-green-700'
: 'bg-red-100 text-red-600'"
                        x-text="fileExisting[doc.id] || doc.uploaded
? 'Lengkap'
: 'Belum Upload'"></span>

                </div>

            </template>

        </div>

    </div>


    <!-- WARNING -->
    <div class="bg-yellow-50 border border-yellow-200 text-sm p-4 rounded-lg mb-6">
        ⚠ Setelah pengajuan dikirim, data tidak dapat diubah kembali.
    </div>


    <!-- KONFIRMASI -->
    <div class="border-t pt-4">

        <label class="flex items-start gap-3 cursor-pointer">

            <input
                type="checkbox"
                x-model="reviewChecked"
                class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded">

            <span class="text-sm text-gray-700">
                Saya menyatakan bahwa seluruh data dan dokumen yang saya kirimkan
                adalah <b>benar dan dapat dipertanggungjawabkan</b>.
            </span>

        </label>

    </div>


    <!-- BUTTON -->
    <div class="mt-8 flex justify-between">

        <button
            @click="prevStep()"
            class="px-6 py-2 bg-gray-300 rounded-xl">
            Kembali
        </button>

        <button
            @click="submitPengajuan()"
            :disabled="!reviewChecked"
            :class="!reviewChecked 
? 'bg-gray-400 cursor-not-allowed'
: 'bg-green-600 hover:bg-green-700'"
            class="px-6 py-2 text-black rounded-xl">
            Submit Pengajuan
        </button>

    </div>

</div>