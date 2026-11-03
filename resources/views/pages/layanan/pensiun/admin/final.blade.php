    @if($pengajuan?->current_step>=4)
    @php
    if($pengajuan?->current_step>4){
    $bknVerificationLog = $pengajuan?->workflowLogs()
    ->where('workflow_log.urutan_step', 4)
    ->whereNotNull('catatan')
    ->orderByDesc('id_log')
    ->first();
    }
    $verificationStatusText = $bknVerificationLog?->catatan ?? '';
    @endphp

    <div x-data="{ verificationStatus: @json($verificationStatusText), revisionNote: '' }" class="space-y-6 mt-5">

        <div class="border rounded-xl p-5 shadow-sm
        {{ $pengajuan?->current_step!=4 ?'bg-gray-300':'bg-white'}} ">">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-white font-semibold">1</div>
                <div>
                    <h3 class="font-semibold text-lg">Tahap Verifikasi BKN</h3>
                </div>
            </div>

            <div class="grid gap-3 md:grid-cols-3">
                <button type="button"
                    @click="lanjutTahap({{ $pengajuan->id}},4)"
                    :disabled="{{ $pengajuan?->current_step != 4 ? 'true' : 'false' }}"
                    class="border bg-green-500 hover:bg-green-600 rounded-xl px-4 py-3 text-white font-medium transition disabled:opacity-30
                    ">
                    Lolos Verifikasi BKN
                </button>

                <button
                    @click="!isLocked && openModal('revisi')"
                    :disabled="{{ $pengajuan?->current_step != 4 ? 'true' : 'false' }}"
                    class="px-4 py-2 text-white rounded-xl bg-yellow-500 hover:bg-yellow-600 disabled:opacity-30">
                    Request Revisi
                </button>
            </div>


            @if($verificationStatusText!='' && $bknVerificationLog?->aksi=='diterima')
            <div class="mt-4 rounded-lg border border-dashed border-gray-300 bg-gray-50 p-3 text-sm text-gray-700">
                <p class="font-semibold text-lg">Status Verifikasi BKN : {{$bknVerificationLog?->aksi??''}}</p>
                <p class="mt-1 capitalize text-black" x-text="verificationStatus"></p>

            </div>
            @endif
        </div>

        <div
            x-data="skUpload('{{ $pengajuan->sk_path ? route('pensiun.preview.sk', $pengajuan->id) : '' }}')"
            class=" border rounded-xl p-5 shadow-sm
{{ $pengajuan?->current_step!=5 ?'bg-gray-300':'bg-white'}} ">

            <div class="flex items-center gap-3 mb-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-600 text-white font-semibold">2</div>
                <div>
                    <h3 class="font-semibold text-lg">Tahap Pembuatan SK dan Finalisasi</h3>

                </div>
            </div>
            <div class="grid gap-3 md:grid-cols-3">
                <button type="button"
                    :disabled="{{ $pengajuan?->current_step != 5 || $pengajuan?->status_akhir == 'selesai'  ? 'true' : 'false' }}"
                    @click="uploadFinal=true"
                    class="border bg-green-500 hover:bg-green-600 rounded-xl px-4 py-3 text-white font-medium transition disabled:opacity-30">
                    Upload SK Pensiun yang telah diterbitkan oleh BKN
                </button>

                <button
                    @click="!isLocked && openModal('revisi')"
                    :disabled="{{ $pengajuan?->current_step != 5 }}"
                    class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl disabled:opacity-30">
                    Request Revisi
                </button>
            </div>

            <!-- <div class="mt-4 text-sm">
                <span x-show="!skPath" class="text-red-500">❌ Belum ada file final</span>
                <span x-show="skPath" class="text-green-600">✅ File final sudah diupload</span>
            </div> -->

            @if($dataPensiun)
            <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h4 class="text-base font-semibold text-gray-800">Informasi SK Pensiun</h4>
                        <p class="text-sm text-gray-500">Data final yang telah disimpan untuk pengajuan pensiun ini.</p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700">
                        Tersimpan
                    </span>
                </div>

                <div class="grid gap-3 md:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">TMT Pensiun</div>
                        <div class="mt-1 text-sm font-medium text-gray-800">
                            {{ $dataPensiun->tmt_pensiun ? \Carbon\Carbon::parse($dataPensiun->tmt_pensiun)->translatedFormat('d F Y') : '-' }}
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Tanggal Pertek</div>
                        <div class="mt-1 text-sm font-medium text-gray-800">
                            {{ $dataPensiun->tgl_pertek ? \Carbon\Carbon::parse($dataPensiun->tgl_pertek)->translatedFormat('d F Y') : '-' }}
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Nomor SK</div>
                        <div class="mt-1 text-sm font-medium text-gray-800">
                            {{ $dataPensiun->no_sk ?? '' }}
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Tanggal SK</div>
                        <div class="mt-1 text-sm font-medium text-gray-800">
                            {{ $dataPensiun->tgl_sk ? \Carbon\Carbon::parse($dataPensiun->tgl_sk)->translatedFormat('d F Y') : '-' }}
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pejabat Penandatangan</div>
                        <div class="mt-1 text-sm font-medium text-gray-800">
                            {{ $dataPensiun->pejabat_ttd ?: '-' }}
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Keterangan Jabatan</div>
                        <div class="mt-1 text-sm font-medium text-gray-800">
                            {{ $dataPensiun->keterangan_jabatan ?: '-' }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <button type="button"
                        @click="previewFile('{{ route('pensiun.preview.sk', $pengajuan->id) }}')"
                        class="rounded-lg border border-blue-600 bg-white px-3 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-50">
                        Lihat File SK
                    </button>
                    <button type="button"
                        @click="uploadFinal=true"
                        class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                        Perbarui Data
                    </button>
                </div>
            </div>
            @else
            <div class="mt-4 rounded-xl border border-dashed border-gray-300 bg-white p-4 text-sm text-gray-500">
                Belum ada data SK pensiun yang tersimpan.
            </div>
            @endif


            <!-- Upload Final -->
            <div x-show="uploadFinal" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/60 px-4 py-6">
                <div class="relative w-full max-w-3xl rounded-2xl bg-white shadow-2xl">
                    <button @click="uploadFinal=false" class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">
                        ✕
                    </button>

                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Finalisasi SK Pensiun</h2>
                        <p class="mt-1 text-sm text-gray-500">Lengkapi data berikut dan unggah file SK pensiun.</p>
                    </div>


                    <div x-data="skUpload()">
                        <form @submit.prevent="uploadSk" class="space-y-4 p-6">
                            @csrf
                            <div class="grid gap-4 md:grid-cols-2">
                                <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">

                                <div class="flex-2">
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Nomor SK</label>
                                    <input type="text"
                                        name="nomor_sk"
                                        class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-blue-500 focus:outline-none"
                                        x-model="nomor_sk"
                                        required>
                                </div>

                                <div class="flex-2">
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Tanggal SK</label>
                                    <input type="date"
                                        name="tanggal_sk"
                                        class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-blue-500 focus:outline-none"
                                        x-ref="tanggalSkPicker"
                                        x-model="tanggal_sk"
                                        x-init="flatpickr($refs.tanggalSkPicker, { dateFormat: 'Y-m-d' })"
                                        required>
                                </div>

                                <div class="flex-2">
                                    <label class="mb-1 block text-sm font-medium text-gray-700">TMT Pensiun</label>
                                    <input type="date"
                                        name="tmt_pensiun"
                                        class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-blue-500 focus:outline-none"
                                        x-ref="tmtPensiunPicker"
                                        x-model="tmt_pensiun"
                                        x-init="flatpickr($refs.tmtPensiunPicker, { dateFormat: 'Y-m-d' })"
                                        required>
                                </div>

                                <div>
                                    <label class=" mb-1 block text-sm font-medium text-gray-700">Tanggal Pertek</label>
                                    <input type="date"
                                        name="tanggal_pertek"
                                        class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-blue-500 focus:outline-none"
                                        x-ref="tanggalPertekPicker"
                                        x-model="tanggal_pertek"
                                        x-init="flatpickr($refs.tanggalPertekPicker, { dateFormat: 'Y-m-d' })"
                                        required>
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Pejabat Penandatangan</label>
                                    <input type="text" name="pejabat_ttd" x-model="pejabat_ttd" class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-blue-500 focus:outline-none" placeholder="Contoh: Kepala BKPSDM" required>
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Keterangan Jabatan</label>
                                    <select x-model="keterangan_jabatan" class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-blue-500 focus:outline-none" required>
                                        <option value="">Pilih keterangan jabatan</option>
                                        @foreach($refKetJab['data'] as $refket)
                                        <option value="{{ data_get($refket, 'id_keterangan_jabatan') }}">
                                            {{ data_get($refket, 'keterangan_jabatan') }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-4">
                                <label class="mb-2 block text-sm font-medium text-gray-700">Upload File SK Pensiun</label>
                                <input type="file" x-ref="file" class="block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm" required>

                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 pt-4">
                                <!-- <div class="text-sm">
                                <span x-show="!skPath" class="text-red-500">❌ Belum ada file final</span>
                                <span x-show="skPath" class="text-green-600">✅ File final sudah diupload</span>
                            </div> -->

                                <div class="flex gap-2">
                                    <button type="submit"
                                        :disabled="loading || {{ $pengajuan?->current_step != 5 ? 'true' : 'false' }}"
                                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 disabled:opacity-20">
                                        <span x-show="!loading">Simpan Data dan Upload</span>
                                        <span x-show="loading">Uploading...</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
    @endif