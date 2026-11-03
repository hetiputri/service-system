<div x-show="currentStep === 0 && status_akhir && status_akhir != 'selesai' && status_akhir != 'diajukan'" x-transition>
    <div class="bg-white border rounded-xl p-8 shadow-sm">
        <h2 class="text-lg font-semibold mb-6">
            Pilih Jenis Cuti
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <template x-for="item in jenisCuti" :key="item.id">
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
            </template>

        </div>
    </div>
</div>