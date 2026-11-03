<div x-show="currentStep === 2 && status_akhir && status_akhir != 'selesai' && status_akhir != 'diajukan'" x-transition>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <h2 class="text-lg font-semibold mb-6 border-b pb-2">
                Pengisian Formulir Cuti
            </h2>
            <div>
                <h4 class='mt-5'>Pejabat Berwenang Pemberi Cuti</h4>
                <input type="hidden" name="id_detail" x-model="formCuti.id_detail">
                <div class="relative w-full">
                    <div @click="openKaopd = !openKaopd"
                        class="rounded-xl w-full border px-5 py-2 cursor-pointer bg-white flex justify-between items-center gap-3">
                        <span class="min-w-0 truncate" x-text="selected ? selected.nama_pegawai+ ' - ' +selected.nip : 'Pilih Atasan'"></span>
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div x-show="openKaopd" @click.outside="openKaopd=false"
                        class="absolute left-0 right-0 mt-1 w-full max-w-full bg-white border rounded shadow z-10">
                        <!-- Search input -->
                        <!-- <input type="text"
                        x-model="search"
                        placeholder="Search..."
                        class="w-120 px-3 py-2 border-b focus:outline-none"> -->

                        <!-- Options -->
                        <ul class="max-h-40 overflow-y-auto w-full">
                            <template x-for="item in kepala_opd" :key="item.id_pegawai">
                                <li @click="selectkaopd(item)"
                                    class="hover:bg-gray-100 w-full cursor-pointer text-sm px-5 py-2 bg-white flex justify-between"
                                    x-text="item.nip+ ' - ' +item.nama_pegawai">
                                </li>
                            </template>

                            <template x-if="filtered().length === 0">
                                <li class="px-3 py-2 text-gray-500">No results found</li>
                            </template>
                        </ul>
                    </div>
                </div>
                <!-- Hidden input for form submit -->
                <input type="hidden" name="kaopd_id" x-model="formCuti.selected.id_pegawai">


                <!--Atasan Langsung-->
                <h4 class='mt-5'>Atasan Langsung</h4>
                <div class="relative w-full">
                    <div @click="openAtasan = !openAtasan"
                        class="rounded-xl w-full border px-5 py-2 cursor-pointer bg-white flex justify-between items-center gap-3">
                        <span class="min-w-0 truncate" x-text="selectedAt ? selectedAt.nama_pegawai+ ' - ' +selectedAt.nip : 'Pilih Atasan Langsung'"></span>
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div x-show="openAtasan" @click.outside="openAtasan=false"
                        class="absolute left-0 right-0 mt-1 w-full max-w-full bg-white border rounded shadow z-10">
                        <!-- Search input -->
                        <!-- <input type="text"
                        x-model="searchAt"
                        placeholder="Search..."
                        class="w-120 px-3 py-2 border-b focus:outline-none"> -->

                        <!-- Options -->
                        <ul class="max-h-40 overflow-y-auto w-full">
                            <template x-for="item in atasan_langsung" :key="item.id_pegawai">
                                <li @click="selectAtasan(item)"
                                    class="hover:bg-gray-100 w-full cursor-pointer text-sm px-5 py-2 bg-white flex justify-between"
                                    x-text="item.nip+ ' - ' +item.nama_pegawai">
                                </li>
                            </template>

                            <template x-if="filteredAtasan().length === 0">
                                <li class="px-3 py-2 text-gray-500">No results found</li>
                            </template>
                        </ul>
                    </div>
                </div>
                <!-- Hidden input for form submit -->
                <input type="hidden" name="atasan_id" x-model="formCuti.selectedAt.id_pegawai">


                <h4 class='mt-5'>Tanggal Permohonan</h4>
                <input
                    type="date"
                    x-model="formCuti.tanggal"
                    readonly
                    class="border p-2 w-full rounded-lg">

                <div x-data="{ dariTanggal: '' }" class="space-y-2">
                    <h4 class='mt-5'>Dari Tanggal</h4>
                    <input
                        type="date"
                        x-ref="datepicker"
                        x-model="formCuti.dariTanggal"
                        x-init="
                                flatpickr($refs.datepicker, {
                                    minDate: today,
                                    dateFormat: 'Y-m-d'
                                })
                            "
                        class="border p-2 w-full rounded-lg">
                </div>

                <div x-data="{ sampaiTanggal: '' }" class="space-y-2">
                    <h4 class='mt-5'>Sampai Tanggal</h4>
                    <input
                        type="date"
                        x-ref="datepicker"
                        x-model="formCuti.sampaiTanggal"
                        @change="updateSampaiTanggal()"
                        x-init="
                                flatpickr($refs.datepicker, {
                                    minDate: today,
                                    dateFormat: 'Y-m-d'
                                })
                            "
                        class="border p-2 w-full rounded-lg">
                </div>

                <h4 class='mt-5 mb-5'>Selama</h4>
                <div class="flex items-center">
                    <input
                        type="number"
                        x-model="formCuti.haricuti"
                        :readonly="formCuti.satuan_waktu === 'Hari Kalender'"
                        class="border p-2 w-100 rounded-lg">
                    <span class="border p-2 rounded-lg bg-gray-100 whitespace-nowrap" x-text="formCuti.satuan_waktu"></span>
                    <input
                        type="hidden"
                        x-model="formCuti.satuan_waktu"
                        class="border p-2 w-100 rounded-lg">
                </div>
            </div>
        </div>


        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <h2 class="text-lg font-semibold mb-6 border-b pb-2">
                Keterangan
            </h2>
            <h4 class='mt-5'>Alasan Cuti</h4>
            <textarea
                x-model="formCuti.deskripsi"
                class="border p-2 w-full rounded-lg"
                rows="4"></textarea>

            <h4 class='mt-5'>Nomor Telepon Selama Cuti</h4>
            <input
                type="text"
                x-model="formCuti.nomorTelepon"
                class="border p-2 w-full rounded-lg">

            <h4 class='mt-5'>Alamat Selama Cuti</h4>
            <textarea
                x-model="formCuti.alamat"
                class="border p-2 w-full rounded-lg"
                rows="4"></textarea>
        </div>

    </div>
    <div class="mt-8 flex justify-between items-center w-full">
        <button
            @click="prevStep()"
            class="ml-6 px-6 py-2 bg-gray-300 rounded-xl text-center">
            Sebelumnya
        </button>

        <button
            @click="nextFromStep(0, 2)"
            class="px-6 py-2 bg-blue-600 text-white text-right rounded-xl hover:bg-blue-700 disabled:opacity-40"
            :disabled="!formCuti.selectedAt || !formCuti.dariTanggal || !formCuti.sampaiTanggal || !formCuti.haricuti || !formCuti.deskripsi || !formCuti.nomorTelepon || !formCuti.alamat">
            Lanjut
        </button>
    </div>
</div>