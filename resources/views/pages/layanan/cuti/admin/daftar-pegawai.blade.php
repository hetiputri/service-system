@extends('layouts.app')
@section('title', 'Pengajuan Cuti')
@section('content')

<div x-data="pegawaiForm()" x-init="init()">
    <div class="bg-white p-8 rounded-2xl shadow mb-6">
        <h2 class="text-lg font-semibold mb-8">
            Daftar Pegawai untuk Proses Pengajuan Cuti
        </h2>
        <div class="flex justify-between items-center mb-5 gap-4">
            <div class="flex gap-2">
                <!-- Left buttons: 2 buttons -->
                <button type="button"
                    @click='kembali()'
                    class="flex w-30 whitespace-nowrap items-center gap-2 px-3 py-1 border border-yellow-500 bg-yellow-500 text-white font-bold rounded-lg text-md">
                    <span>← Kembali</span>
                </button>
                <button type="button"
                    @click='addPegawai({{ $id }})'
                    class="flex w-50 whitespace-nowrap items-center gap-2 px-3 py-1 border border-black bg-white-500 text-black font-bold rounded-lg text-md">
                    <img src='{{ asset("icons/plus.svg") }}' width='10%' /><span>Tambah Pegawai</span>
                </button>
            </div>

            <div class="flex gap-3">
                <!-- Right buttons: 3 buttons -->
                <button type="button"
                    @click='unduhFiles({{ $id }},"form")'
                    class="flex w-auto whitespace-nowrap items-center gap-2 px-3 py-1 border border-green-500 bg-green-500 text-white rounded-lg text-md">
                    <span>Unduh Form Cuti Kumulatif</span>
                </button>
                <button type="button"
                    @click='unduhFiles({{ $id }},"sp")'
                    class="flex w-auto whitespace-nowrap items-center gap-2 px-3 py-1 border border-blue-500 bg-blue-500 text-white rounded-lg text-md">
                    <span>Unduh Surat Pengantar Kumulatif</span>
                </button>
            </div>
        </div>

        <table class="table align-items-center mb-0 mt-3" id="myTable" width='100%'>
            <thead>
                <tr>
                    <td width="5%" class='font-bold'>No</td>
                    <td width='20%' class='font-bold text-center'>Nama Pegawai/NIP</td>
                    <td width='15%' class='font-bold text-center'>Jabatan</td>
                    <td width='20%' class='font-bold text-center'>SKPD</td>
                    <td width='10%' class='font-bold text-center'>Jenis Cuti</td>
                    <td width='15%' class='font-bold text-center'>TMT</td>
                    <td width='10%' class='font-bold text-center'>Dokumen</td>
                    <td width='10%' class='font-bold text-center'>&nbsp;</td>
                    <td width='10%' class='font-bold text-center'>&nbsp;</td>

                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($dataPegawai as $index => $data)
                <tr>
                    <td class="text-center px-2 py-3">{{ $index + 1 }}</td>
                    <td class="w-30 px-4 py-3 text-center">{{ $data->nama_pegawai }}<br>{{ $data->nip }}</td>
                    <td class="w-30 px-3 py-3 text-center">{{ $data->jabatan }}</td>
                    <td class="w-30 px-3 py-3 text-center">{{ $data->skpd }}</td>
                    <td class="w-30 px-3 py-3 text-center">{{ $data->nama_layanan }}</td>
                    <td class="w-30 px-3 py-3 text-center">{{ $data->tgl_mulai }} s.d {{ $data->tgl_selesai }}</td>
                    <td class="w-40 px-4 py-6 text-center">
                        <button type="button"
                            @click='cekfile({{ $data->pengajuan_id }})'
                            class="border w-20 border-blue-500 px-3 py-1 bg-blue-500 text-white rounded text-sm items-center">
                            <span>
                                File
                            </span>
                        </button>
                    </td>
                    <td class="w-40 px-4 py-6 text-center">
                        <button type="button"
                            @click='uploadSK({{ $data->pengajuan_id }}, {{ $data->id }})'
                            class="border w-20 border-green-500 px-3 py-1 bg-green-500 text-white rounded text-sm items-center">
                            <span>
                                Upload
                            </span>
                        </button>
                    </td>
                    <td class="w-40 px-4 py-6 text-center">
                        @if($data->sk_path!='')
                        <button type="button"
                            @click='LihatSK({{ $data->pengajuan_id }})'
                            class="border w-20 border-blue-500 px-3 py-1 bg-white-500 text-blue-500 rounded text-sm items-center">
                            <span>
                                Lihat SK
                            </span>
                        </button>
                        @else
                        <button type="button"
                            @click='hapus({{ $data->pengajuan_id }},{{ $data->id }})'
                            class="border w-20 border-red-500 px-3 py-1 bg-red-500 text-white rounded text-sm items-center">
                            <span>
                                Hapus
                            </span>
                        </button>
                        @endif
                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>

        <div
            x-show="cekFile"
            style="background-color:rgb(0,0,0,0.5);display:none"
            class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50">
            <div
                class="bg-white rounded-xl shadow-lg p-6 left-6 mt-2 w-200"
                :class="cekFile ? 'bg-white rounded-xl shadow-lg p-6 ms-4 w-200' : ''">

                <!-- HEADER -->
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold mb-4">Syarat Dokumen Pengajuan Cuti</h3>
                </div>

                <table class="w-full text-sm">
                    <thead class="text-gray-600">
                        <tr>
                            <th class="text-left py-3 w-10">No</th>
                            <th class="text-left py-3">Nama Berkas</th>
                            <th class="text-left py-3 w-72">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td width='5%' class="text-md p-4">1.</td>
                            <td width='35%' class="text-md p-4">Surat Pengantar (format pdf)</td>
                            <td width='60%'>
                                <template x-if="doc_sp_skpd">
                                    <button type='button' class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700" @click="openDoc(pengajuanId, 17)">Lihat File</button>
                                </template>
                            </td>
                        </tr>
                        <tr>
                            <td width='5%' class="text-md p-4">2.</td>
                            <td width='35%' class="text-md p-4">Formulir Cuti (format pdf)</td>
                            <td width='60%'>
                                <template x-if="doc_form_cuti">
                                    <button type='button' class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700" @click="openDoc(pengajuanId, 18)">Lihat File</button>
                                </template>
                            </td>
                        </tr>
                        <tr>
                            <td width='5%' class="text-md p-4">3.</td>
                            <td width='35%' class="text-md p-4">Dokumen Pendukung Lain (format pdf)</td>
                            <td width='60%'>
                                <template x-if="doc_pendukung">
                                    <button type='button' class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700" @click="openDoc(pengajuanId, 19)">Lihat File</button>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex gap-2">
                    <button
                        type="button"
                        @click="cekFile=false"
                        class="border border-gray-400 px-3 py-1 bg-gray-400 text-white rounded text-xs">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>

        <div x-show="previewOpen" class="fixed inset-0 bg-black/60 flex items-center justify-center z-999">

            <div class="bg-white p-4 rounded-xl w-[80%] h-[80%] relative">

                <button @click="previewOpen=false" class="absolute bg-white top-3 right-3">
                    ✕
                </button>

                <iframe
                    :src="previewUrl"
                    class="w-full h-full rounded-lg">
                </iframe>

            </div>

        </div>
        <!--ModalUploadSK-->
        <div
            x-show="UploadModalOpen"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-[999]"
            x-transition>
            <div
                class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md md:max-w-2xl lg:max-w-2xl">

                <h2 class="text-xl font-bold mb-4">
                    Upload Surat SK Persetujuan Cuti Pegawai
                </h2>
                <div x-data="uploadDoc()">
                    <input type="hidden" name="id_pengajuan" x-model="id_pengajuan">
                    <div class="grid gap-4 md:grid-cols-2 mb-3">
                        <div class="flex-2">
                            <label class="mb-1 block font-medium text-gray-700">Nomor SK</label>
                            <input type="text"
                                name="nomor_sk"
                                class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-blue-500 focus:outline-none"
                                x-model="nomor_sk"
                                required>
                        </div>

                        <div class="flex-2">
                            <label class="mb-1 block font-medium text-gray-700">Tanggal SK</label>
                            <input type="date"
                                name="tanggal_sk"
                                class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-blue-500 focus:outline-none"
                                x-ref="tanggalSkPicker"
                                x-model="tanggal_sk"
                                x-init="flatpickr($refs.tanggalSkPicker, { dateFormat: 'Y-m-d' })"
                                required>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-1 mb-3">
                        <div class="flex-1">
                            <label class="mb-1 block font-medium text-gray-700">Pejabat SK</label>
                            <input type="text"
                                name="pejabat_sk"
                                class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-blue-500 focus:outline-none"
                                x-model="pejabat_sk"
                                required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-medium border-b-2">
                            Pilih File
                        </label>

                        <input type="file" name="file" @change="file = $event.target.files[0]"
                            class="border p-2 rounded w-full mb-2">

                        <input
                            type="hidden"
                            name="id_pengajuan"
                            x-model="id_pengajuan">
                    </div>

                    <div class="flex justify-end gap-2">
                        <button
                            type="button"
                            @click="UploadModalOpen = false"
                            class="px-4 py-2 border rounded">
                            Batal
                        </button>

                        <button
                            @click="uploadFile"
                            class="px-4 py-2 bg-green-600 text-white rounded">
                            Upload
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function pegawaiForm() {
            return {
                cekFile: false,
                previewOpen: false,
                previewUrl: null,
                doc_sp_skpd: '',
                doc_form_cuti: '',
                doc_pendukung: '',
                pengajuanId: 0,
                UploadModalOpen: false,
                id_pengajuan: 0,
                init() {
                    $('#myTable').DataTable();
                },
                addPegawai(id) {
                    window.location.href = `{{ url('/proses-cuti') }}?id=` + id;

                },
                kembali() {
                    window.location.href = `{{ url('/proses-utama-cuti') }}`;
                },
                openDoc(pengajuanId, DocId) {
                    let fileurl = `{{ route('cuti.lihat-upload') }}?pengajuanId=${pengajuanId}&docs_id=${DocId}`;
                    this.previewUrl = fileurl;
                    this.previewOpen = true;
                },
                cekfile(id) {
                    fetch(`{{ url('/cek-file-cuti/') }}/${id}`)
                        .then(response => response.json())
                        .then(data => {
                            this.doc_sp_skpd = data.doc_sp_skpd;
                            this.doc_form_cuti = data.doc_form_cuti;
                            this.doc_pendukung = data.doc_pendukung;
                            this.pengajuanId = id;
                            this.cekFile = true;
                        });
                    //window.location.href = `{{ url('/cek-file') }}?id=` + id;
                },
                uploadSK(pengajuanId, prosesId) {
                    this.id_pengajuan = pengajuanId;
                    this.UploadModalOpen = true;
                },
                LihatSK(id) {
                    this.previewUrl = '';
                    let fileurl = `{{ route('cuti.lihat-sk') }}?id=${id}&v=${Date.now()}`;
                    this.previewUrl = fileurl;
                    this.previewOpen = true;
                },
                hapus(pengajuan_id, id) {
                    if (confirm('Anda yakin ingin membatalkan proses pengajuan cuti pegawai bersangkutan dan mengembalikan ke tahap sebelumnya?')) {
                        fetch(`{{ url('/proses-cuti/hapus') }}/${pengajuan_id}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert(data.message);
                                    location.reload();
                                } else {
                                    alert('Gagal menghapus pegawai. Data pegawai tidak terdaftar.');
                                }
                            });
                    }
                },
                checkFile(doc, id_pengajuan) {
                    return false;
                    if (doc != '' && id_pengajuan > 0) {
                        return true;
                    }
                },
                unduhFiles(id_proses, jenis_file) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('/cuti-unduh-files') }}`;
                    form.style.display = 'none';

                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = `{{ csrf_token() }}`;
                    form.appendChild(tokenInput);

                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'id_proses';
                    idInput.value = id_proses;
                    form.appendChild(idInput);

                    const jenisInput = document.createElement('input');
                    jenisInput.type = 'hidden';
                    jenisInput.name = 'jenis_file';
                    jenisInput.value = jenis_file;
                    form.appendChild(jenisInput);

                    document.body.appendChild(form);
                    form.submit();
                    form.remove();
                },
            }
        }

        function uploadDoc() {
            return {
                file: null,
                nomor_sk: '',
                tanggal_sk: '',
                pejabat_sk: '',
                async uploadFile() {

                    let formData = new FormData();

                    // Append integers
                    formData.append('id_pengajuan', this.id_pengajuan);
                    formData.append('file', this.file);
                    formData.append('no_sk', this.nomor_sk);
                    formData.append('tgl_sk', this.tanggal_sk);
                    formData.append('pejabat_sk', this.pejabat_sk);

                    try {
                        let response = await fetch('{{ url("sk-cuti/upload") }}', {
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
</div>
@endsection