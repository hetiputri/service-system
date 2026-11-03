<!-- STEP 3 -->
<div x-show="currentStep === 2 && status_akhir && status_akhir != 'selesai' && status_akhir != 'diajukan'" x-transition>


    <div class="relative w-full" x-data="dpcpComponent()">

        <div class="flex gap-2 mb-4 mt-4">

            <button
                @click="ttdMode='basah'"
                class="px-4 py-2 rounded-lg text-sm border"
                :class="ttdMode=='basah' ? 'bg-gray-200 font-semibold' : 'bg-white'">
                ✍️ Tanda Tangan Basah
            </button>

            <button
                @click="ttdMode='elektronik'"
                class="px-4 py-2 rounded-lg text-sm border"
                :class="ttdMode=='elektronik' ? 'bg-gray-200 font-semibold' : 'bg-white'">
                🖊️ Tanda Tangan Elektronik
            </button>

        </div>

        <!-- Alamat Setelah Pensiun -->
        <div class="mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-medium">
                        TMT Pensiun
                    </label>
                    <input type="date"
                        name="tmt_pensiun"
                        :readonly="selectedJenis === '1'"
                        class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-blue-500 focus:outline-none"
                        x-ref="tmtPensiunPicker"
                        x-model="tmt_pensiun"
                        x-init="selectedJenis != '1' ? flatpickr($refs.tmtPensiunPicker, { dateFormat: 'Y-m-d' }):''"
                        required>
                    <template x-if="selectedJenis != '1'">
                        <label class="block mb-1 text-sm">
                            *Silakan Input TMT Pensiun yang diinginkan. Gunakan tanggal meninggal PNS jika Pensiun Janda/Duda
                        </label>
                    </template>
                </div>

                @if($pengajuan && $pengajuan->layanan_id==4)
                <div>
                    <label class="block mb-1 font-medium">
                        Nama Penandatangan (untuk Pensiun Janda/Duda)
                    </label>

                    <input type="text"
                        x-model="nama_ttd"
                        class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-blue-500 focus:outline-none" />
                </div>
                @endif
            </div>

            <!-- <label class="block mb-1 font-medium">
                Alamat Setelah Pensiun
            </label> -->
            <!-- <textarea 
                    x-model="alamatPensiun"
                    id="alamatPensiun"
                    class="w-full border rounded-lg p-2"
                    rows="3"
                    placeholder="Masukkan alamat setelah pensiun"
                ></textarea> -->
            <textarea
                x-model="alamatPensiun"
                class="w-full border rounded-lg p-2 hidden"></textarea>
        </div>


        <div x-show="ttdMode=='elektronik'" class="mt-4">
            <div class="flex flex-col items-center">
                <!-- <canvas 
                id="signature-pad"
                width="400"
                height="200"
                class="border rounded-lg bg-white"
            ></canvas> -->
                <canvas
                    id="signature-pad"
                    width="400"
                    height="200"
                    class="border rounded-lg bg-white"></canvas>

                <div class="mt-2 flex gap-2 mb-4">
                    <button @click="clearSignature()" class="px-4 py-2 bg-gray-300 rounded">Reset ttd</button>
                    <button @click="submitDpcp()" class="px-4 py-2 bg-green-600 text-white rounded">Simpan & Kunci Data</button>

                </div>
            </div>

        </div>
        <div x-show="ttdMode=='basah'">
            <!-- <div class="mb-4">
                <input type="checkbox"
                    name="ttd_sarana" id="ttd_sarana" x-model="ttd_sarana" value="1" />
                <label>Tanda Tangan Sarana (Hapus bagian Nama dan NIP)
                </label>
            </div> -->
            <button
                @click="refreshFromApi()"
                class="px-4 py-2 bg-gray-200 rounded-lg text-sm mb-4">
                Tampilkan DPCP
            </button>
            <div class="p-4 bg-yellow-50 border rounded-lg text-sm text-gray-700 mb-4">
                Silakan:<br>
                1. Unduh DPCP <br>
                2. Cetak dokumen <br>
                3. Tandatangani secara manual <br>
                4. Scan dan unggah pada tahap dokumen <br>

            </div>
        </div>

        <!-- <iframe
            x-ref="iframeDpcp"
            :class="loadingIframe ? 'opacity-50 blur-sm' : ''"
            data-url="{{ route('pensiun.preview.dpcp', ['id' => session('pegawai')['id_pegawai']]) }}"
            src="{{ route('pensiun.preview.dpcp', ['id' => session('pegawai')['id_pegawai']]) }}"
            class="w-full h-[600px] border"></iframe> -->

        <iframe id="previewFrameDpcp" class="w-full h-[600px] mt-4"></iframe>


    </div>
    <div class="mt-8 flex justify-between">

        <button
            @click="prevStep()"
            class="px-6 py-2 bg-gray-300 rounded-xl">
            Kembali
        </button>
        <button
            @click="nextFromStep3()"
            class="px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
            Lanjut
        </button>
    </div>
</div>