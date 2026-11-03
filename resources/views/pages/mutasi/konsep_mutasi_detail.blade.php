@extends('layouts.app')


@section('title', 'Konsep Mutasi Detail')
{{-- @php
    dd($mutasiStruktural);
@endphp --}}
@section('content')
    <div class="flex flex-col gap-[24px] w-full" x-data="konsepDetail(@js($mutasiStruktural))">
        <div class="w-full rounded-[16px] bg-white px-[24px] py-[20px] flex flex-col gap-[24px]">
            <div class="flex w-full justify-between items-center">
                <p class="font-semibold text-[24px]">Informasi Konsep</p>

                <x-ui.button
                    class="text-[12px] !bg-[#1570EF] hover:!bg-[#175CD3] disabled:cursor-not-allowed disabled:opacity-50
                        disabled:!bg-[#1570EF]"
                    size="sm" x-bind:disabled="mutasi_struktural.mutasi_struktural_detail"
                    @click="penetapanJabatan()">Tambah
                    Jabatan</x-ui.button>

            </div>
            <div class="max-w-xl bg-white rounded-[8px] space-y-3 text-[12px]">

                <div class="grid grid-cols-[180px_20px_1fr]">
                    <div class="font-semibold">Nama</div>
                    <div class="text-center">:</div>
                    <div>{{ $mutasiStruktural['nama_konsep'] }}</div>
                </div>

                <div class="grid grid-cols-[180px_20px_1fr]">
                    <div class="font-semibold">Tanggal</div>
                    <div class="text-center">:</div>
                    <div>{{ $mutasiStruktural['tanggal_konsep'] }}</div>
                </div>

                <div class="grid grid-cols-[180px_20px_1fr]">
                    <div class="font-semibold">Dibuat Oleh</div>
                    <div class="text-center">:</div>
                    <div>{{ $mutasiStruktural->pembuat->nama_pegawai }}</div>
                </div>
                <div class="grid grid-cols-[180px_20px_1fr]">
                    <div class="font-semibold">Status</div>
                    <div class="text-center">:</div>
                    <div>
                        <span class="px-[10px] py-[2px] text-xs rounded-[999px]"
                            :class="mutasi_struktural.status_konsep == 0 ? 'bg-[#F2F4F7] text-[#475467]' : (mutasi_struktural
                                .status_konsep == 1 ? 'bg-[#ECFDF3] text[#039855]' : 'bg-[#FEF3F2] text-[#D92D20]')">
                            {{ $mutasiStruktural->status_konsep->label() }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
        <div class="w-full rounded-[16px] bg-white px-[24px] py-[20px] flex flex-col gap-[24px]">
            <div class=" min-w-0 overflow-x-auto" id="tableContainer">
                <table id="jabatanTable" class="row-border" style="font-size: 12px">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Jabatan</th>
                            <th>Nama Pegawai</th>
                            <th>Jabatan Lama</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <x-ui.modal x-data="{ open: false }" @open-delete-modal.window="open = true"
            @close-delete-modal.window="open = false" :isOpen="false" class="max-w-[500px] overflow-y-auto">
            <div class="relative w-full rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                <div class="flex flex-col gap-[24px] mt-[40px]">
                    <p x-text="'Apakah anda yakin ingin menghapus penetapan jabatan ini?'"></p>
                    <div class="flex justify-end gap-[8px]">
                        <x-ui.button x-bind:disabled="loading" size="sm" variant="outline"
                            @click="hapus_jabatan()">Lanjut</x-ui.button>
                        <x-ui.button x-bind:disabled="loading" size="sm" variant="outline"
                            @click="$dispatch('close-delete-modal')">Kembali</x-ui.button>
                    </div>
                </div>
        </x-ui.modal>
    </div>
@endsection

@push('scripts')
    <script>
        function konsepDetail(mutasi_struktural) {
            return {
                jabatan_table: null,
                mutasi_struktural,
                loading: false,
                languageConfiguration: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    loadingRecords: "Memuat...",
                    processing: "Memproses...",
                    emptyTable: "Tidak ada data dalam tabel"
                },
                init() {
                    this.jabatan_table = new DataTable('#jabatanTable', {
                        pageLength: 5,
                        lengthChange: false,
                        processing: true,
                        serverSide: false,
                        searching: false,
                        paging: false,
                        info: false,
                        ordering: false,
                        data: [],
                        columns: [{
                                data: null,
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row, meta) {
                                    return meta.row + 1;
                                }
                            },
                            {
                                data: 'jabatan_baru',
                                name: 'jabatan_baru'
                            },
                            {
                                data: 'nama_pegawai',
                                name: 'nama_pegawai',
                            },

                            {
                                data: 'jabatan_lama',
                                name: 'jabatan_lama',
                            },

                            {
                                data: null,
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row, meta) {
                                    return `
                                    <div class="flex gap-[8px] justify-center">
                                            <x-ui.button size='sm' class="!py-[5px] !px-[12px] w-fit bg-blue-500 hover:bg-blue-600 text-white text-[12px]" @click="penetapanJabatan()">Edit</x-ui.button>
                                            <x-ui.button size='sm' class="!py-[5px] !px-[12px] w-fit bg-red-500 hover:bg-red-600 text-white text-[12px]" @click="$dispatch('open-delete-modal')">Hapus</x-ui.button>
                                            </div>
                                    `;
                                }
                            },

                        ],
                        columnDefs: [{
                                targets: 0,
                                className: 'whitespace-normal break-words w-10',
                            },
                            {
                                targets: 4,
                                className: 'whitespace-normal break-words w-50',
                            }
                        ],
                        language: this.languageConfiguration
                    })
                    console.log(mutasi_struktural)
                    data = []
                    data.push({
                        jabatan_baru: mutasi_struktural.mutasi_struktural_detail.ke_jabatan.jabatan,
                        nama_pegawai: mutasi_struktural.mutasi_struktural_detail.pegawai.nama_pegawai,
                        jabatan_lama: mutasi_struktural.mutasi_struktural_detail.dari_jabatan.jabatan,
                    })
                    this.jabatan_table.clear();
                    this.jabatan_table.rows.add(data);
                    this.jabatan_table.draw();
                },

                penetapanJabatan() {
                    window.location.href = `{{ url('/') }}/mutasi/konsep/${mutasi_struktural.id}/jabatan`
                },

                async hapus_jabatan() {
                    try {
                        this.loading = true
                        const res = await axios.delete(
                            `{{ url('/') }}/mutasi/konsep/${mutasi_struktural.id}/jabatan`)
                        if (res.data.redirect) {
                            window.location.href = res.data.redirect;
                        }
                    } finally {
                        this.loading = false
                    }
                }
            }
        }
    </script>
@endpush
