<div x-show="currentStep === 0 && status_akhir && status_akhir != 'selesai' && status_akhir != 'diajukan'" x-transition>

    <h2 class="text-lg font-semibold mb-6">
        Pilih Jenis Pensiun
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <template x-for="item in jenisPensiun" :key="item.id">
            <div
                @click="selectJenis(item.id)"
                class="border rounded-2xl p-6 transition duration-200"
                :class="{
                            'opacity-50 cursor-not-allowed bg-gray-100': isDisabled(item.id),
                            'cursor-pointer hover:shadow-md': !isDisabled(item.id),
                            'border-blue-600 bg-blue-50 shadow-md': selectedJenis === item.id
                        }">
                <h3 class="font-semibold mb-2 text-base" x-text="item.nama_layanan"></h3>

                <template x-if="isDisabled(item.id)">
                    <p class="text-sm text-red-500" x-text="getReason(item.id)"></p>
                </template>

                <template x-if="!isDisabled(item.id)">
                    <p class="text-sm text-gray-600">
                        Memenuhi syarat pengajuan.
                    </p>
                </template>
            </div>
        </template>

    </div>

    <div class="mt-8 text-right">
        <button
            @click="nextFromStep1()"
            class="px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 disabled:opacity-40"
            :disabled="!selectedJenis">
            Lanjut
        </button>
    </div>
</div>