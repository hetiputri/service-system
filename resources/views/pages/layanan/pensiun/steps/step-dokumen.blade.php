<div x-show="currentStep === 3 && status_akhir && status_akhir != 'selesai' && status_akhir != 'diajukan'" x-transition>

    <h2 class="text-lg font-semibold mb-6">
        Upload Dokumen
    </h2>

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
                                    :class="
                                        (fileExisting[doc.id] || doc.uploaded)
                                        ? 'bg-green-100 text-green-700'
                                        : (doc.wajib
                                            ? 'bg-red-100 text-red-600'
                                            : 'bg-gray-100 text-gray-500')
                                    "
                                    x-text="
                                        (fileExisting[doc.id] || doc.uploaded)
                                        ? 'Sudah Upload'
                                        : (doc.wajib
                                            ? 'Belum Upload'
                                            : (doc.ada_data === true ? 'Harap Data Riwayat Anda dilengkapi dengan File Upload ' : 'Tidak ada riwayat')
                                        )
                                    ">
                                </span>

                            </div>

                        </td>

                        <td class="py-3">

                            <div class="flex items-center gap-2">
                                <!-- VIEW -->


                                <template x-if="fileExisting[doc.id] || doc.uploaded">
                                    <button
                                        @click="previewFile(fileExisting[doc.id] ?? doc.file_url)"
                                        class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        👁 Lihat
                                    </button>
                                </template>

                                <template x-if="doc.sumber=='DIRECT_DB'">
                                    <!-- DRAG DROP -->
                                    <label class="px-3 py-1 text-xs bg-gray-600 text-white rounded-lg hover:bg-gray-700 cursor-pointer" x-show="!uploading[doc.id]">
                                        ⬆ Unggah
                                        <input
                                            type="file"
                                            class="hidden"
                                            @change="uploadDokumen(doc.id)">
                                    </label>
                                    <span x-show="uploading[doc.id]===true" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-lg">
                                        ⏳ Uploading...
                                    </span>
                                </template>

                                <!-- DELETE -->
                                <!-- <template x-if="fileExisting[doc.key]">
                                <button
                                @click="hapusDokumen(doc.key)"
                                class="px-3 py-1 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700"
                                >
                                🗑 Hapus
                                </button>
                                </template> -->
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <!-- <p x-show="!allRequiredUploaded()" class="text-sm text-red-500 mt-4"> -->
        <p class="text-sm text-red-500 mt-4">
            ⚠️ Dokumen bertanda * wajib diunggah sebelum melanjutkan. File yang diunggah berformat pdf dengan ukuran maksimum 2 MB
        </p>
    </div>

    <div class="mt-8 flex justify-between">
        <button
            @click="prevStep()"
            class="px-6 py-2 bg-gray-300 rounded-xl">
            Kembali
        </button>
        <button @click="completeStep()" :disabled="!allRequiredUploaded()" :class="!allRequiredUploaded()  ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'" class="px-6 py-2 text-white rounded-xl">
            Lanjut
        </button>
    </div>
    <div x-show="previewOpen" class="fixed inset-0 bg-black/60 flex items-center justify-center z-99999" x-transition>
        <div class="bg-white p-4 rounded-xl w-[80%] h-[80%] relative">
            <button @click="previewOpen=false" class="absolute top-3 right-3 px-2 py-2 bg-white text-gray-500">✕</button>
            <!-- Loading -->
            <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white">
                <div class="text-gray-600 animate-pulse">
                    ⏳ Memuat file...
                </div>
            </div>
            <iframe
                :src="previewUrl"
                class="w-full h-full rounded-lg"></iframe>
        </div>
    </div>

</div>