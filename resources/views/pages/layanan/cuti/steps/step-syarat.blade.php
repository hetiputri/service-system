<div x-show="currentStep === 1 && status_akhir && status_akhir != 'selesai' && status_akhir != 'diajukan'" x-transition>
    <div class="bg-white border rounded-xl p-8 shadow-sm">
        <h2 class="text-lg font-semibold mb-6">
            Persyaratan Pengajuan {{ $pilihan_jenis_cuti ? $pilihan_jenis_cuti : 'Cuti' }}
        </h2>



        <!-- <template x-for="item in syaratCuti" :key="item.id">
                <div
                    @click="selectJenis(item.id)"
                    class="border rounded-2xl p-6 transition duration-200 shadow-md"
                    :class="{
                'opacity-50 cursor-not-allowed bg-gray-100': isDisabled(item.id),
                'cursor-pointer hover:scale-105 hover:border-blue-600 hover:bg-blue-50': !isDisabled(item.id),
                'shadow-md': selectedJenis === item.id
            }">

                    <h3 class="font-semibold mb-2 text-base" x-text="item.nama_layanan"></h3>

                    <template x-if="isDisabled(item.id)">
                        <p class="text-sm text-red-500">Tidak Memenuhi Syarat</p>
                    </template>

                    <template x-if="!isDisabled(item.id)">
                        <p class="text-sm text-gray-600">
                            Memenuhi syarat pengajuan.
                        </p>
                    </template>
                </div>
            </template> -->
        <div class="bg-white border rounded-xl p-6 shadow-sm mb-5 w-full">

            <div class="overflow-x-auto">

                <table class="table w-full text-sm">
                    <thead class="text-gray-600">
                        <tr>
                            <th width='5%' class="text-left py-3">No</th>
                            <th width='15%' class="text-left pl-2">Syarat</th>
                            <th width='30%' class="text-center py-3 pl-2">Keterangan</th>
                            <th width='50%' class="text-left py-3">Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width='5%' class="text-md pl-2">1.</td>
                            <td width='15%' class="text-md pl-2">Status Kepegawaian</td>
                            <td width='30%' class="pl-2 text-center">CPNS/PNS/PPPK</td>
                            <td width='50%' class="text-left text-green-500 font-bold">Terpenuhi</td>
                        </tr>
                        <template x-if="selectedJenis==6">
                            <tr>
                                <td width='5%' class="text-md pl-2 py-2">2.</td>
                                <td width='15%' class="text-md pl-2">Masa Kerja Pegawai (Min)</td>
                                <td width='30%' class="pl-2 text-center">1 Tahun</td>
                                <td width='50%' class="text-left text-green-500 font-bold">Terpenuhi</td>
                            </tr>
                        </template>
                        <template x-if="selectedJenis==7 || selectedJenis==11">
                            <tr>
                                <td width='5%' class="text-md pl-2 py-2">2.</td>
                                <td width='15%' class="text-md pl-2">Masa Kerja Pegawai (Min)</td>
                                <td width='30%' class="pl-2 text-center">5 Tahun</td>
                                <td width='50%' class="text-left text-green-500 font-bold">Terpenuhi</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-between items-center w-full">
        <button
            @click="prevStep()"
            class="ml-6 px-6 py-2 bg-gray-300 rounded-xl text-center">
            Sebelumnya
        </button>

        <button
            @click="nextFromStep(0, 1)"
            class="px-6 py-2 bg-blue-600 text-white text-right rounded-xl hover:bg-blue-700 disabled:opacity-40"
            :disabled="!selectedJenis">
            Lanjut
        </button>
    </div>

</div>