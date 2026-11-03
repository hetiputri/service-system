@extends('layouts.app')
@section('title', 'Proses Pengajuan Cuti')
@section('content')

<div x-data="mainForm()" x-init="init()">
    <div class="bg-white p-8 rounded-2xl shadow mb-6">
        <h2 class="text-lg font-semibold mb-8">
            Proses Data Pengajuan Cuti
        </h2>
        <div class="flex justify-start mb-3 ">
            <button type=" button"
                @click='add_proses()'
                class="flex w-40 whitespace-nowrap items-center gap-2 px-4 py-2 border border-black text-black-500 rounded-lg text-xs">
                <img src='{{ asset("icons/plus.svg") }}' width='15%' /><span>Tambah Proses</span>
            </button>
        </div>

        <div
            x-show="addProses"
            style="background-color:rgb(0,0,0,0.5);display:none"
            class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50">
            <div
                class="bg-white rounded-xl shadow-lg p-6 left-6 mt-2 w-200"
                :class="addProses ? 'bg-white rounded-xl shadow-lg p-6 ms-4 w-200' : ''">

                <h2 class="text-lg font-bold mb-4">Tambah Proses Kerja Cuti</h2>
                <div class="flex items-center gap-3">
                    <label class="w-80 pb-5">Nama Proses Kerja</label>
                    <input
                        type="text"
                        name="adminname"
                        placeholder="Masukkan Nama Proses Kerja"
                        class="w-full border rounded p-2 mb-4"
                        x-model="formProses.adminName"
                        required>
                </div>

                <div class="flex items-center gap-3">
                    <label class="w-80 pb-5">Tanggal Proses Kerja</label>
                    <input
                        type="date"
                        class="w-full border rounded p-2 mb-4"
                        x-ref="datepicker"
                        x-model="formProses.tanggalKerja"
                        x-init="
                                flatpickr($refs.datepicker, {                                   
                                    dateFormat: 'Y-m-d'
                                })
                            "
                        required>
                </div>

                <div class="flex gap-2">
                    <button type="button"
                        @click="simpanProses()"
                        class="border border-green-500 px-3 py-1 bg-green-600 text-white rounded text-xs">
                        Simpan
                    </button>

                    <button
                        type="button"
                        @click="addProses=false"
                        class="border border-gray-400 px-3 py-1 bg-gray-400 text-white rounded text-xs">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>

        <table class="table align-items-center mb-0 mt-3" id="myTable" width='100%'>
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th width="5%" class='px-2 py-4'>No</th>
                    <th width='20%' class='px-2 py-4 text-center'>Tanggal Proses Kerja</th>
                    <th width='20%' class='px-2 py-4 text-center'>Nama Proses Kerja</th>
                    <th width='20%' class='px-2 py-4 text-center'>&nbsp;</th>
                    <th width='10%' class='px-2 py-4 text-center'>&nbsp;</th>
                    <!-- <td width='10%' class='px-2 py-4 text-center'>&nbsp;</td> -->
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($arrays as $index => $data)
                <tr>
                    <td class="text-center px-2 py-3">{{ $index + 1 }}</td>
                    <td class="w-30 px-4 py-3">{{ $data->tgl_kerja }}</td>
                    <td class="w-30 px-3 py-3">{{ $data->nama_admin }}</td>
                    <td class="w-40 px-4 py-6 text-center">
                        <button type="button"
                            @click='dataPegawai({{ $data->id }})'
                            class="border border-blue-500 px-3 py-1 bg-blue-600 text-white rounded text-xs">
                            <span>
                                Data Pegawai
                            </span>
                        </button>
                    </td>
                    <!-- <td class="w-40 px-4 py-6 text-center">
                        @if($data->scan_sk!='')
                        <button type="button"
                            @click='lihatSK({{ $data->id }})'
                            class="border border-green-500 px-3 py-1 bg-green-500 text-white rounded text-xs">
                            <span>
                                Lihat SK
                            </span>
                        </button>
                        @else
                        <button type="button"
                            @click='uploadSK({{ $data->id }})'
                            class="border border-yellow-500 px-3 py-1 bg-yellow-500 text-white rounded text-xs">
                            <span>
                                Upload SK
                            </span>
                        </button>
                        @endif
                    </td> -->
                    <td class="w-25 px-1 py-3 text-center">
                        @if($data->jumlah==0)
                        <button type="button"
                            @click='hapus({{ $data->id }})'
                            class="border border-red-500 px-3 py-1 bg-red-500 text-white rounded text-xs">
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
        <!-- Modal Background -->
        <div
            x-show="UploadModalOpen"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-[999]"
            x-transition>

            <!-- Modal Content -->
            <div
                @click.away="UploadModalOpen = false"
                class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md md:max-w-2xl lg:max-w-2xl">

                <h2 class="text-xl font-bold mb-4">
                    Upload Surat SK Persetujuan Cuti
                </h2>
                <div x-data="uploadDoc()">
                    <div class="mb-4">
                        <label class="block mb-2 font-medium border-b-2">
                            Pilih File
                        </label>

                        <input type="file" name="file" @change="file = $event.target.files[0]"
                            class="border p-2 rounded w-full mb-2">

                        <input
                            type="hidden"
                            name="idProses"
                            x-model="idProses">
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

        <!-- Preview File-->
        <div x-show="previewOpen" class="fixed inset-0 bg-black/60 flex items-center justify-center z-999">

            <div class="bg-white p-4 rounded-xl w-[80%] h-[80%] relative">

                <button @click="previewOpen=false" class="absolute top-3 right-3">
                    ✕
                </button>

                <iframe
                    :src="previewUrl"
                    class="w-full h-full rounded-lg">
                </iframe>

            </div>

        </div>
    </div>
    <script>
        function mainForm() {
            return {
                addProses: false,
                idProses: 0,
                UploadModalOpen: false,
                previewOpen: false,
                previewUrl: null,
                formProses: {
                    adminName: '',
                    tanggalKerja: '',
                },
                init() {
                    $('#myTable').DataTable();
                },
                add_proses() {
                    this.addProses = true;
                },
                simpanProses() {
                    fetch('{{ url("proses-cuti/tambah") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.formProses)
                        })
                        .then(res => res.json())
                        .then(data => {
                            // simpan id pengajuan baru
                            if (data.success == true) {
                                //alert('Proses kerja cuti berhasil ditambahkan');
                                Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: data.message,
                                        buttons: true,
                                        dangerMode: true,
                                    })
                                    .then((willOk) => {
                                        if (willOk) {
                                            location.reload();
                                        }
                                    });
                            }
                        })
                },
                dataPegawai(id) {
                    window.location.href = `{{ url('proses-cuti/pegawai') }}/${id}`;
                },
                uploadSK(id) {
                    this.idProses = id;
                    this.UploadModalOpen = true;
                },
                lihatSK(id) {
                    let fileurl = `{{ route('cuti.lihat-sk') }}?id=${id}&v=${Date.now()}`;
                    this.previewUrl = fileurl;
                    this.previewOpen = true;
                },
                hapus(id) {
                    Swal.fire({
                        title: 'Konfirmasi',
                        html: "Anda ingin menghapus Proses Kerja ini?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ url('proses-utama-cuti/hapus') }}/${id}`, {
                                    method: 'GET',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success == true) {
                                        Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil!',
                                                text: data.message,
                                                buttons: true,
                                                dangerMode: true,
                                            })
                                            .then((willOk) => {
                                                if (willOk) {
                                                    location.reload();
                                                }
                                            });
                                    } else {
                                        alert(data.message);
                                    }
                                });
                        }
                    });
                }
            }
        }

        function uploadDoc() {
            return {
                file: null,
                async uploadFile() {

                    let formData = new FormData();

                    // Append integers
                    formData.append('idProses', this.idProses);
                    formData.append('file', this.file);

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