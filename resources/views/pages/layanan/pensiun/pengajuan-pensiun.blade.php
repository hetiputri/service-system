@extends('layouts.app')
@section('title', 'Pengajuan Pensiun')
@section('content')
<div x-data="pensiunForm()" x-init="init()">
    <div class="bg-white p-8 rounded-2xl shadow">
        <h2 class="text-lg font-semibold mb-6">
            Pengajuan Pensiun
        </h2>

        <div class="bg-gray-50 border rounded-xl p-6 mb-6">

            <div class="relative">

                <!-- BASE LINE -->
                <div class="absolute top-4 left-0 w-full h-[3px] bg-gray-200"></div>
                <!-- PROGRESS LINE -->
                <div class="absolute top-4 left-0 h-[3px] bg-green-500 transition-all duration-500" :style="'width:' + t.progress + '%'"></div>
                <!-- :style="'width:' + (currentStep / (steps.length - 1) * 100) + '%'" -->

                <div class="relative flex justify-between">

                    <template x-for="(step,index) in steps" :key="index">

                        <div class="flex flex-col items-center w-32 text-center">

                            <!-- STEP CIRCLE -->
                            <div class="w-9 h-9 flex items-center justify-center transition rounded-full border text-sm font-semibold bg-white z-10"
                                :class="{
                                'bg-green-500 text-gray border-green-500': index < currentStep,
                                'bg-blue-500 text-gray border-blue-500': currentStep === index,
                                'bg-gray-200 text-gray-500 border-gray-300': index > currentStep
                            }">

                                <!-- kalau sudah lewat → checklist -->
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


        <div class="bg-white border rounded-xl p-8 shadow-sm">

            @if(($pengajuan?->current_step ?? 1) === 1)
            {{-- STEP 1 --}}
            @include('pages.layanan.pensiun.steps.step-jenis')

            {{-- STEP 2 --}}
            @include('pages.layanan.pensiun.steps.step-data-pegawai')

            {{-- STEP 3 --}}
            @include('pages.layanan.pensiun.steps.step-dpcp')

            {{-- STEP 4 --}}
            @include('pages.layanan.pensiun.steps.step-dokumen')

            {{-- STEP 5 --}}
            @include('pages.layanan.pensiun.steps.step-review')
            @endif

            {{-- STEP 6 --}}
            @include('pages.layanan.pensiun.steps.step-final')
        </div>
    </div>
</div>
<script>
    // console.log();
    function pensiunForm() {
        return {
            eligibility: @json($eligibility),
            step2Checked: false,
            previewOpen: false,
            reviewChecked: false,
            loading: false,
            pengajuanId: @json($pengajuanId),
            previewUrl: null,
            uploadProgress: 0,
            ttdMode: 'basah', // basah | elektronik
            stepUser: @json($stepUser),
            alamatPensiun: '',
            tmt_pensiun: @json($tmt_pensiun),
            nama_ttd: '',
            init() {

                // let saved = localStorage.getItem('pensiun_step')

                // if(saved){
                //     this.currentStep = Math.max(this.currentStep, parseInt(saved))
                // }
                this.maxStep = this.currentStep
                this.steps.forEach((step, index) => {
                    if (index < this.currentStep) {
                        step.completed = true
                    }
                })

                // watcher untuk perubahan step
                this.$watch('currentStep', value => {
                    if (value === 3) {
                        this.loadDokumen()
                    }
                })

                // HANDLE RELOAD PAGE
                if (this.currentStep >= 3) {
                    this.loadDokumen()
                }

            },
            uploadProgress: 0,
            uploading: {},
            // currentStep: {{ ($pengajuan->current_step ?? 1) - 1 }},
            currentStep: @json($currentStep),
            status_akhir: @json($status_akhir),
            selectedJenis: @json($selectedJenis),
            maxStep: @json($maxStep),
            steps: [{
                    label: 'Pilih Jenis Pensiun'
                },
                {
                    label: 'Tinjau Profil'
                },
                {
                    label: 'DPCP'
                },
                {
                    label: 'Unggah Dokumen'
                },
                {
                    label: 'Finalisasi'
                }
            ],

            form: {
                nip: '',
                nama: '',
                jabatan: '',
                unit: ''
            },
            dokumenList: [],

            jenisPensiun: @json($jenisPensiun),
            fileExisting: @json($filePengajuan),

            isDisabled(type) {
                if (type === 1) return !this.eligibility.eligible_bup
                if (type === 2) return !this.eligibility.eligible_aps
                // if (type === 'PensiunBUP') return !this.eligibility.eligible_bup
                // if (type === 'PensiunAPS') return !this.eligibility.eligible_aps
                return false
            },


            getReason(type) {
                if (type === 1) return this.eligibility.reason_bup
                if (type === 2) return this.eligibility.reason_aps
                return null
            },

            getJenisName(id) {
                let item = this.jenisPensiun.find(j => j.id == id)
                return item ? item.nama_layanan : ''
            },

            allRequiredUploaded() {
                return this.dokumenList
                    .filter(doc => doc.wajib)
                    .every(doc => this.fileExisting[doc.id] || doc.uploaded)
            },

            loadDokumen() {
                if (!this.pengajuanId) return

                // if(this.dokumenList.length > 0) return
                fetch("{{ route('pengajuan.pensiun.persyaratan') }}?pengajuan_id=" + this.pengajuanId)

                    .then(res => res.json())
                    .then(data => {
                        // console.log("data output");
                        // console.log(data.data);
                        if (data.success) {
                            this.dokumenList = data.data
                        }

                    })
            },

            previewFile(url) {

                if (!url.startsWith('bkpsdm')) {
                    // console.log('bknbkpsdm:'+url);
                    // url = '/'+url
                    this.previewUrl = url
                } else {

                    this.previewUrl = "https://" + url
                    // console.log('bkpsdm:'+url);
                }
                this.previewOpen = true
            },
            uploadDokumen(persyaratanId) {
                console.log('upload start', persyaratanId)
                SwalHelper.loadingToast('Mengirim...')
                // console.log("upload :", event,persyaratanId)
                let file = event.target.files[0]

                if (!file) return
                // this.uploading[persyaratanId] = true
                this.uploading = {
                    ...this.uploading,
                    [persyaratanId]: true
                }
                let formData = new FormData()

                formData.append('file', file)
                formData.append('persyaratan_id', persyaratanId)
                formData.append('pengajuan_id', this.pengajuanId)
                // console.log('id syarat pengajuan');
                // console.log(selectedJenis)
                // console.log(pengajuan_id)

                fetch("{{ route('pengajuan.pensiun.upload.dokumen') }}", {

                        method: 'POST',

                        credentials: 'same-origin',

                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },

                        body: formData

                    })
                    .then(res => res.json())
                    .then(data => {

                        if (data.success) {
                            console.log("Path data");
                            console.log(data.path);
                            // route('dokumen.preview', $doc->id)
                            // this.fileExisting[persyaratanId]=data.path
                            // this.doc.file_url=data.file_url
                            this.fileExisting[persyaratanId] = data.file_url


                            console.log(window.showToast)
                            SwalHelper.toast('Upload berhasil')
                        } else {
                            SwalHelper.errorToast(data.message || 'Terjadi kesalahan ❌')
                            // SwalHelper.errorToast('Upload gagal ❌')
                        }
                        console.log('uploading state', this.uploading)
                        // event.target.value = ''

                    })
                    .catch(err => {

                        Swal.close()

                        console.log('ERROR FULL:', err) // debug

                        SwalHelper.errorToast(
                            err.message || 'Terjadi kesalahan ❌'
                        )

                    })
                    .finally(() => {
                        this.uploading[persyaratanId] = false
                    })
            },


            selectJenis(type) {
                if (this.isDisabled(type)) return
                this.selectedJenis = type
            },
            completeStep() {
                this.steps[this.currentStep].completed = true
                // localStorage.setItem('pensiun_step', this.currentStep)
                // console.log('step sekarag:'+this.currentStep++)
                this.currentStep++
                this.maxStep = Math.max(this.maxStep, this.currentStep)
            },

            updateStep(step) {
                let others = {};
                let data = {};
                if (step == 3) {
                    let others = {
                        nama_signature: this.nama_ttd,
                        tmt_pensiun: this.tmt_pensiun
                    };
                    data = {
                        id: this.pengajuanId,
                        step: step + 1, // simpan sebagai 1-based ke DB
                        ...others,
                    };
                } else {
                    data = {
                        id: this.pengajuanId,
                        step: step + 1 // simpan sebagai 1-based ke DB
                    };
                }

                fetch('{{ url("/pengajuan/pensiun/update-step") }}', {
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
                        //this.pengajuanId = data.data.pengajuan_id
                        if (step == 2) {
                            location.reload()
                        }
                        this.completeStep()
                    })
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

            nextFromStep1() {

                if (!this.selectedJenis) {
                    alert('Silakan pilih jenis pensiun terlebih dahulu')
                    return
                }

                fetch('{{ url("pengajuan/pensiun/set-jenis") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            jenis_pensiun: this.selectedJenis,
                            id: this.pengajuanId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {

                        // simpan id pengajuan baru
                        this.pengajuanId = data.pengajuan_id

                        this.completeStep()
                    })
            },

            nextFromStep2() {
                if (!this.step2Checked) {
                    alert('Silakan ceklis konfirmasi terlebih dahulu')
                    return
                }
                //this.completeStep()
                this.updateStep(2)
            },

            nextFromStep3() {
                this.updateStep(3)
                //this.completeStep()
            },

            prevStep() {
                if (this.currentStep > 0) {
                    this.currentStep--
                }
            },


            submitPengajuan() {
                SwalHelper.loadingToast('Sedang Mengirim...')

                fetch('{{ url("/pengajuan/pensiun/submit") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: this.pengajuanId,
                            nama_signature: this.nama_ttd,
                            tmt_pensiun: this.tmt_pensiun
                        })
                    })
                    .then(async res => {
                        let data = await res.json().catch(() => ({}))

                        // ❌ kalau HTTP error (500, 422, dll)
                        if (!res.ok) {
                            throw new Error(data.message || 'Terjadi kesalahan server ❌')
                        }

                        return data
                    })
                    .then(data => {
                        if (data.success) {
                            SwalHelper.toast('Berhasil Submit')
                            window.location.reload()
                        } else {
                            // ❌ error dari backend
                            SwalHelper.errorToast(data.message || 'Gagal submit ❌')
                        }
                    })
                    .catch(err => {
                        console.log(err)
                        SwalHelper.errorToast(err.message || 'Koneksi bermasalah ❌')
                    })
            }
        }
    }

    let canvas = document.getElementById('signature-pad')
    let ctx = canvas.getContext('2d')

    let drawing = false

    canvas.addEventListener('mousedown', () => drawing = true)
    canvas.addEventListener('mouseup', () => drawing = false)
    canvas.addEventListener('mousemove', draw)

    function draw(e) {

        if (!drawing) return

        ctx.lineWidth = 2
        ctx.lineCap = 'round'

        ctx.lineTo(e.offsetX, e.offsetY)
        ctx.stroke()

        ctx.beginPath()
        ctx.moveTo(e.offsetX, e.offsetY)

    }


    function dpcpComponent() {

        return {
            alamatPensiun: @json($alamatPensiun),
            nama_ttd: @json($nama_ttd),
            signatureBase64: @json($signature),
            ttd_sarana: '',
            tmt_pensiun: @json($tmt_pensiun),

            init() {
                this.initCanvas()

                if (this.signatureBase64) {
                    this.loadSignature()
                }
            },

            initCanvas() {
                window.canvas = document.getElementById('signature-pad')
                window.ctx = canvas.getContext('2d')
            },

            loadSignature() {
                let img = new Image()
                img.onload = () => {
                    ctx.clearRect(0, 0, canvas.width, canvas.height)
                    ctx.drawImage(img, 0, 0)
                }
                img.src = this.signatureBase64
            },

            clearSignature() {
                ctx.clearRect(0, 0, canvas.width, canvas.height)
                ctx.beginPath()
                this.signatureBase64 = ''
            },

            refreshFromApi() {

                SwalHelper.loadingToast('Loading...')

                let ttd = this.nama_ttd
                let alamat = this.alamatPensiun
                let iframe = this.$refs.iframeDpcp
                let check = this.ttd_sarana
                let tmt = this.tmt_pensiun

                // if (!iframe) {
                //     console.log('iframe belum ada')
                //     return
                // }

                let baseUrl = "{{ route('pensiun.preview.dpcp', ['id' => session('pegawai')['id_pegawai']]) }}"; //iframe.data - url; //@json($baseUrl); //config('app.url'); //iframe.dataset.url.loadingToast('Mengambil data terbaru...')

                // 🔥 tampilkan loading


                // 🔥 trigger reload iframe
                let url = baseUrl + `?use_api=1&al=` + alamat + `&tmt=` + tmt + `&ch=` + check + `&ttd=` + ttd + `&t=` + new Date().getTime()
                //url = iframe.src;
                // 🔥 tunggu iframe selesai load
                // iframe.onload = () => {
                //     SwalHelper.close()
                //     showToast('Data terupdate berhasil ditampilkan', 'success')
                // }
                SwalHelper.close()
                $('#previewFrameDpcp').attr('src', url);
            },

            submitDpcp() {
                // 🔥 tampilkan loading
                SwalHelper.loadingToast('Load...')

                if (!this.alamatPensiun) {
                    alert('Alamat wajib diisi')
                    return
                }

                let base64 = canvas.toDataURL("image/png")

                fetch('{{ url("/pengajuan/pensiun/save-dpcp-signature") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            signature: base64,
                            alamat_sesudah_pensiun: this.alamatPensiun,
                            nama_signature: this.nama_ttd,
                            tmt_pensiun: this.tmt_pensiun,
                            id_pengajuan: @json($pengajuanId)
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // alert('DPCP berhasil ditandatangani')
                            SwalHelper.close()
                            showToast('Data terupdate berhasil diupdate', 'success')
                            //location.reload()
                            let url = "{{ route('pensiun.preview.dpcp', ['id' => session('pegawai')['id_pegawai']]) }}"; //iframe.data - url; //@json($baseUrl); //config('app.url'); //iframe.dataset.url.loadingToast('Mengambil data terbaru...')
                            $('#previewFrameDpcp').attr('src', url);
                        }
                    })
            }
        }
    }
</script>
@endsection