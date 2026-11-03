@extends('layouts.app')

@section('title', 'Konsep Mutasi')


@section('content')
    <div class="w-full rounded-[16px] bg-white px-[24px] py-[20px] flex flex-col gap-[24px]"
        :class="$store.sidebar.isExpanded || $store.sidebar.isHovered ?
            'lg:max-w-[calc(100vw-(290px+48px+15px))]' : 'lg:max-w-[calc(100vw-(90px+48px+15px))]'"
        x-data="konsepMutasi(@js($konsepMutasi))">
        <div class="flex justify-between items-center">
            <p class="font-semibold text-[24px]">Konsep Mutasi</p>
            <a href="{{ url('/mutasi/konsep/tambah') }}">
                <x-ui.button size="sm" class="!bg-[#1570EF] hover:!bg-[#175CD3] text-[12px]">Tambah
                    Konsep</x-ui.button></a>
        </div>
        <div class="flex flex-col gap-[24px]">

            <div class=" min-w-0 overflow-x-auto" id="tableContainer">
                <table id="konsepTable" class="row-border" style="font-size: 12px">
                    <thead>
                        <tr>
                            <th style="text-align:center;">No.</th>
                            <th style="text-align:center;">Nama Konsep</th>
                            <th style="text-align:center;">Tanggal Konsep</th>
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
                    <p x-text="`Apakah anda yakin ingin menghapus konsep ${konsepDipilih.nama_konsep}`"></p>
                    <div class="flex justify-end gap-[8px]">
                        <x-ui.button x-bind:disabled="loading" size="sm" variant="outline"
                            @click="hapus_konsep()">Lanjut</x-ui.button>
                        <x-ui.button x-bind:disabled="loading" size="sm" variant="outline"
                            @click="$dispatch('close-delete-modal')">Kembali</x-ui.button>
                    </div>
                </div>
        </x-ui.modal>
    </div>
@endsection
@push('scripts')
    <script>
        function konsepMutasi(konsepMutasi) {
            const el = document.getElementById("tableContainer");
            const appHeader = document.getElementById("simpegHeader");
            return {
                loading: false,
                error: '',
                table: null,
                konsepMutasi,
                konsepDipilih: null,
                init() {
                    this.$watch('loading', (val) => {
                        if (val) {

                            SwalHelper.loadingToast('Sedang Memproses...')
                        } else if (!val) {
                            SwalHelper.close()
                        }
                    })

                    this.table = new DataTable('#konsepTable', {
                        processing: true,
                        serverSide: false,
                        searching: false,
                        autoWidth: false,
                        lengthMenu: [5, 10, 25, 50],
                        fixedHeader: {
                            header: true,
                            headerOffset: appHeader.offsetHeight
                        },
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
                                data: 'nama_konsep',
                                name: 'nama_konsep',
                            },
                            {
                                data: 'tanggal_konsep',
                                name: 'tanggal_konsep',
                            },
                            {
                                data: null,
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row, meta) {
                                    return `
                                        <div class="flex gap-[8px] justify-center">
                                            <x-ui.button size='sm' class="!py-[5px] !px-[12px] !bg-[#1570EF] hover:!bg-[#175CD3] text-white text-[12px]" @click="edit_konsep(${meta.row})">Edit</x-ui.button>
                                            <x-ui.button size='sm' @click="lihatDetail(${meta.row})" class="!py-[5px] !px-[12px] !bg-[#039855] text-white text-[12px] hover:!bg-[#027A48]">Lihat</x-ui.button>
                                            <x-ui.button size='sm' class="!py-[5px] !px-[12px] !bg-[#D92D20] text-white text-[12px] hover:!bg-[#B42318]" @click="pilih_konsep(${meta.row})">Hapus</x-ui.button>
                                        </div
                                    `;
                                }
                            }
                        ],
                        columnDefs: [{
                            targets: "_all",
                            className: 'dt-center',
                        }, ],
                        language: {
                            search: "Cari:",
                            lengthMenu: "_MENU_ data per halaman",
                            zeroRecords: "Data tidak ditemukan",
                            info: "Halaman _PAGE_ dari _PAGES_",
                            infoEmpty: "Tidak ada data tersedia",
                            infoFiltered: "(difilter dari _MAX_ total data)",
                            loadingRecords: "Memuat...",
                            processing: "Memproses...",
                            emptyTable: "Tidak ada data dalam tabel"
                        }
                    })

                    this.table.clear();
                    this.table.rows.add(konsepMutasi);
                    this.table.draw();
                },

                lihatDetail(idx) {
                    konsep = this.konsepMutasi[idx]
                    id_konsep = konsep.id
                    window.location.href = `{{ url('/') }}/mutasi/konsep/${id_konsep}`
                },

                pilih_konsep(idx) {
                    this.konsepDipilih = this.konsepMutasi[idx]
                    this.$dispatch('open-delete-modal')
                },

                async edit_konsep(idx) {
                    id_konsep = this.konsepMutasi[idx].id
                    window.location = `{{ url('/') }}/mutasi/konsep/${id_konsep}/ubah`
                },

                async hapus_konsep() {
                    try {
                        this.loading = true
                        const res = await axios.delete(`{{ url('/') }}/mutasi/konsep/${this.konsepDipilih.id}`)
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
