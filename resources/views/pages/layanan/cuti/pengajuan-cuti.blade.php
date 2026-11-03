@extends('layouts.app')
@section('title', 'Pengajuan Cuti')
@section('content')

<div x-data="cutiForm()" x-init="init()">
    <div class="bg-white p-8 rounded-2xl shadow mb-6">
        <h2 class="text-lg font-semibold mb-6">
            Pengajuan Cuti
        </h2>

        @if($pengajuan?->status_akhir !== 'selesai' && $pengajuan?->status_akhir !== 'diajukan')
        <div class="bg-gray-50 border rounded-xl p-6 mb-6">

            <div class="relative">

                <!-- BASE LINE -->
                <div class="absolute top-4 left-0 w-full h-[3px] bg-gray-200"></div>
                <!-- PROGRESS LINE -->
                <div class="absolute top-4 left-0 h-[3px] bg-green-500 transition-all duration-500" :style="'width:' + t.progress + '%'"></div>
                <!-- :style="'width:' + (currentStep / (steps.length - 1) * 100) + '%'" -->

                <div class="relative flex justify-between">

                    <template x-for="(step,index) in steps" :key="index">
                        <!-- <span class="ml-2 text-sm"
                            x-text="index + 1"></span> -->

                        <div class="flex flex-col items-center w-32 text-center">

                            <!-- STEP CIRCLE -->
                            <div class="w-9 h-9 flex items-center justify-center transition rounded-full border text-sm font-semibold bg-white z-10"
                                :class="{
                                'bg-green-500 text-gray border-green-500': index < currentStep,
                                'bg-blue-500 text-gray border-blue-500': currentStep === index,
                                'bg-gray-200 text-gray-500 border-gray-300': index > currentStep
                            }">

                                <!--   kalau sudah lewat → checklist -->
                                <template x-if="index < currentStep">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </template>

                                <!-- selain itu (current + future) → angka -->
                                <template x-if="index >= currentStep">
                                    <span x-text="index + 1"></span>
                                </template>

                            </div>

                            <!-- STEP LABEL -->
                            <span
                                class="mt-2 text-xs font-medium"
                                x-text="step.label">
                            </span>


                            <!-- STATUS BADGE -->
                            <span
                                class="text-[10px] mt-1 px-2 py-0.5 rounded-full"
                                :class="statusClass(index)"
                                x-text="statusLabel(index)"></span>
                        </div>

                    </template>

                    <!-- FINAL TROPHY -->
                    <div class="flex flex-col items-center w-24 text-center">

                        <div
                            class="w-9 h-9 flex items-center justify-center text-xl rounded-full transition"
                            :class="currentStep >= steps.length 
                                ? 'bg-green-500 text-white' 
                                : 'text-gray-400'">
                            🏆
                        </div>

                        <span class="mt-2 text-xs font-medium"
                            :class="currentStep >= steps.length 
                                ? 'text-green-600 font-semibold' 
                                : 'text-gray-500'">
                            Selesai
                        </span>
                    </div>
                </div>
            </div>

        </div>
        @endif


    </div>


    {{-- STEP 1 --}}
    @include('pages.layanan.cuti.steps.step-jenis-cuti')

    {{-- STEP 2 --}}
    @include('pages.layanan.cuti.steps.step-syarat')

    {{-- STEP 3 --}}
    @include('pages.layanan.cuti.steps.step-formulir')

    {{-- STEP 4 --}}
    @include('pages.layanan.cuti.steps.step-unduh-dokumen')

    {{-- STEP 5 --}}
    @include('pages.layanan.cuti.steps.step-upload')

    <div
        x-show="showDaftarModal"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-999"
        x-transition>
        <div
            @click.away="showDaftarModal = false"
            class="bg-white rounded-2xl shadow-xl w-full max-w-4xl p-6 mx-4 overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold">Daftar Pengajuan Cuti</h2>
                    <p class="text-sm text-gray-600">Terdapat {{ $count_daftar }} pengajuan yang belum diselesaikan. Silakan periksa daftar berikut.</p>
                </div>
                <button type="button" @click="showDaftarModal = false" class="text-gray-500 hover:text-gray-900">✕</button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nomor Tiket</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal Pengajuan</th>
                            <th class="px-4 py-3">Nama Layanan</th>
                            <th class="px-4 py-3">Masa Cuti</th>
                            <th class="px-4 py-3">&nbsp;</th>

                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in daftar_pengajuan" :key="item.id">
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3" x-text="index + 1"></td>
                                <td class="px-4 py-3" x-text="item.nomor_tiket"></td>
                                <td class="px-4 py-3" x-text="item.status_akhir"></td>
                                <td class="px-4 py-3" x-text="item.tanggal_ajuan"></td>
                                <td class="px-4 py-3" x-text="item.nama_layanan"></td>
                                <td class="px-4 py-3" x-text="formatDate(item.tgl_mulai,item.tgl_selesai)"></td>
                                <td class="px-4 py-3">
                                    <button type="button" @click="lanjut(item.id)" class="px-4 py-2 bg-green-600 text-white rounded-lg">Lanjutkan</button>
                                </td>

                            </tr>
                        </template>
                        <template x-if="daftar_pengajuan.length === 0">
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">Tidak ada pengajuan tersimpan.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="mt-5 text-left">
                <button type="button" @click="showDaftarModal = false" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Buat Pengajuan Baru</button>
            </div>
        </div>
    </div>

</div>
<script>
    function cutiForm() {
        return {
            //Step 0 start dari pilih jenis cuti,1=syarat,2=formulir,3=upload dokumen, dst
            count_daftar: @json($count_daftar),
            daftar_pengajuan: @json($daftar_pengajuan),
            steps: @json($steps),
            eligibility: @json($eligibility),
            step2Checked: false,
            previewOpen: false,
            reviewChecked: false,
            loading: false,
            pengajuanId: @json($pengajuanId),
            previewUrl: null,
            uploadProgress: 0,
            ttdMode: 'basah',
            currentStep: @json($currentStep),
            jenisCuti: @json($jenisCuti),
            maxStep: @json($maxStep),
            status_akhir: @json($status_akhir),
            kepala_opd: @json($kaopd),
            atasan_langsung: @json($atasan_langsung),
            openKaopd: false,
            search: '',
            selected: @json($kaopd_data),
            openAtasan: false,
            searchAt: '',
            selectedAt: @json($atasan_data),
            today: @json($today),
            UploadModalOpen: false,
            showDaftarModal: false,
            namaDokumen: '',
            idPeng: '',
            doc_id: '',
            doc_sp_skpd: @json($doc_sp_skpd),
            doc_form_cuti: @json($doc_form_cuti),
            doc_pendukung: @json($doc_pendukung),
            SeeDoc: false,
            selectedJenis: @json($selectedJenis),
            catatan: @json($catatan),
            id_status_pegawai: @json($id_status_pegawai),
            ttdMode: 'basah',
            dokumen_wajib: @json($dokumen_wajib),
            masa_kerja: @json($masa_kerja),
            formCuti: {
                id_detail: @json($id_detail),
                selected: @json($kaopd_data),
                selectedAt: @json($atasan_data),
                tanggal: @json($today),
                haricuti: @json($haricuti),
                dariTanggal: @json($tanggal_mulai),
                sampaiTanggal: @json($tanggal_selesai),
                deskripsi: @json($deskripsi),
                nomorTelepon: @json($nomor_telepon),
                alamat: @json($alamat),
                satuan_waktu: @json($satuan_waktu),
                isHariCutiLocked: false
            },
            init() {
                this.maxStep = this.currentStep
                this.showDaftarModal = this.count_daftar > 0 && this.pengajuanId == 0;

                // Watch for changes in dariTanggal
                this.$watch('formCuti', (value) => {
                    if (value.dariTanggal && value.sampaiTanggal && value.satuan_waktu == 'Hari Kalender') {
                        const dariDate = new Date(value.dariTanggal);
                        const sampaiDate = new Date(value.sampaiTanggal);
                        const diffTime = Math.abs(sampaiDate - dariDate);
                        value.haricuti = 1 + Math.round(diffTime / (1000 * 60 * 60 * 24));
                    }
                }, {
                    deep: true
                });

            },
            ttd(style) {
                this.ttdMode = style;
            },
            filtered() {
                return this.kepala_opd.filter(o =>
                    o.nama_pegawai.toLowerCase().includes(this.search.toLowerCase())
                )
            },
            filteredAtasan() {
                return this.atasan_langsung.filter(o =>
                    o.nama_pegawai.toLowerCase().includes(this.searchAt.toLowerCase())
                )
            },
            selectkaopd(item) {
                this.selected = item
                this.formCuti.selected = item
                this.openKaopd = false
                this.search = ''
            },
            selectAtasan(item) {
                this.selectedAt = item
                this.formCuti.selectedAt = item
                this.openAtasan = false
                this.searchAt = ''
            },
            statusLabel(index) {
                if (index < this.maxStep) return 'Selesai'
                if (index === this.maxStep) return 'Dalam Proses'
                return 'Terkunci'
            },
            statusClass(index) {
                if (index < this.maxStep) return 'bg-green-100 text-green-600'
                if (index === this.maxStep) return 'bg-blue-100 text-blue-600'
                return 'bg-gray-200 text-gray-500'
            },
            selectJenis(id) {
                if (this.isDisabled(id)) { //return
                    this.selectedJenis = id;
                } else {
                    this.selectedJenis = id;
                    this.nextFromStep(id, 0);
                }
            },
            isDisabled(type) {
                const found = this.eligibility.find(item => item.id_jenis_layanan === type);
                return found ? found.disabled : false;
            },
            printFormulir() {
                id = this.pengajuanId;
                ttd = this.ttdMode;
                window.open('{{ url("pengajuan/cuti/print") }}/' + id + '/' + ttd, '_blank');
            },
            nextFromStep(idJenis, onStep) {
                //alert(idJenis);
                let data = {};
                if (onStep == 0) {
                    data = {
                        jenis_cuti: idJenis,
                        id: this.pengajuanId,
                        ongoingStep: onStep + 1
                    };
                } else if (onStep == 1 || onStep == 3) {
                    data = {
                        id: this.pengajuanId,
                        ongoingStep: onStep + 1
                    };
                } else if (onStep == 2) {
                    let others = this.formCuti;
                    data = {
                        id: this.pengajuanId,
                        ongoingStep: onStep + 1,
                        ...others
                    };
                }

                fetch('{{ url("pengajuan/cuti/addupdate") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(res => res.json())
                    .then(data => {
                        // simpan id pengajuan baru                        
                        if (onStep == 1 || onStep == 2) {
                            location.reload();
                        } else if (onStep == 0) {
                            document.location.href = "{{ route('pengajuan.cuti') }}?id=" + data.pengajuan_id;
                        }
                        this.pengajuanId = data.pengajuan_id;
                        this.currentStep++;
                        this.maxStep = Math.max(this.maxStep, this.currentStep);
                    })
            },
            prevStep() {
                if (this.currentStep > 0) {
                    this.currentStep--;
                }
            },
            // tte() {
            //     this.ttdMode = 'digital'
            // },
            uploadPengantar() {
                this.namaDokumen = 'Surat Pengantar';
                this.idPeng = this.pengajuanId;
                this.doc_id = 17;
                this.UploadModalOpen = true;
            },
            uploadFormulir() {
                this.namaDokumen = 'Formulir Cuti';
                this.idPeng = this.pengajuanId;
                this.doc_id = 18;
                this.UploadModalOpen = true;
            },
            uploadPendukung() {
                this.namaDokumen = 'Surat Sakit/Surat Pendukung Lain';
                this.idPeng = this.pengajuanId;
                this.doc_id = 19;
                this.UploadModalOpen = true;
            },
            formatDate(date1, date2) {
                if (date1 && date2) {
                    return date1 + ' s.d ' + date2;
                } else {
                    return '';
                }
            },
            openDoc(pengajuanId, DocId) {
                if (DocId == 17) {
                    this.namaDokumen = 'Surat Pengantar';
                } else if (DocId == 18) {
                    this.namaDokumen = 'Formulir Cuti';
                } else {
                    this.namaDokumen = 'Surat Sakit/Surat Pendukung Lain';
                }
                let fileurl = `{{ route('cuti.lihat-upload') }}?pengajuanId=${pengajuanId}&docs_id=${DocId}`;
                window.open(fileurl, '_blank');
            },
            ajukanCuti(id) {
                Swal.fire({
                    title: 'Konfirmasi',
                    html: "Apakah anda yakin ingin melanjutkan Usulan Cuti Ke BKPSDM?<br>Usulan yang diajukan tidak dapat diedit lagi.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('pengajuan/cuti/selesai') }}/${id}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.success == false) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Maaf, syarat belum lengkap',
                                        text: data.message
                                    });
                                } else {
                                    Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: data.message,
                                            buttons: true,
                                            dangerMode: true,
                                        })
                                        .then((willOk) => {
                                            if (willOk) {
                                                //location.reload();
                                                window.location.href = '{{ route("pengajuan.cuti") }}';
                                            }
                                        });
                                }
                            });
                    }
                });

            },
            hapus(id) {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Anda yakin ingin menghapus Seluruh Usulan Cuti ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('pengajuan/cuti/hapus') }}/${id}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.success == false) {
                                    alert(data.message);
                                } else {
                                    Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: data.message,
                                            buttons: true,
                                            dangerMode: true,
                                        })
                                        .then((willOk) => {
                                            if (willOk) {
                                                document.location.href = '{{ route("pengajuan.cuti") }}';
                                            }
                                        });
                                }
                            });
                    }
                });
            },
            lanjut(id) {
                document.location.href = "{{ route('pengajuan.cuti') }}?id=" + id;
            }
        }
    }

    function uploadDoc() {
        return {
            file: null,
            async uploadFile() {

                let formData = new FormData();

                // Append integers
                formData.append('doc_id', this.doc_id);
                formData.append('idPeng', this.idPeng);
                formData.append('file', this.file);

                try {

                    let response = await fetch('{{ url("pengajuan/cuti/upload") }}', {
                        method: 'POST',
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: formData
                    });

                    let result = await response.json();

                    this.message = result.message;
                    if (result.success == false) {
                        alert(this.message);
                    } else {
                        this.UploadModalOpen = false;
                        this.file = null;
                        SwalHelper.toast(this.message);
                        location.reload();
                    }


                } catch (error) {
                    console.error(error);
                    this.message = 'Upload failed';
                    alert(this.message);
                }
            }
        }
    }
</script>
@endsection