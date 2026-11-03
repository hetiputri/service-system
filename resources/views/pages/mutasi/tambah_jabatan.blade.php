@extends('layouts.app')

@section('title', 'Tambah Jabatan')
@section('content')
    <div class="w-full rounded-[16px] bg-white px-[24px] py-[20px] flex flex-col gap-[24px]" x-data="tambahJabatan(@js($mutasi_struktural))">
        <div class="flex w-full justify-between items-center">
            <p class="font-semibold text-[24px]">Penetapan Jabatan Pegawai</p>
        </div>
        <div class="max-w-2xl bg-white rounded-[8px] space-y-6 text-[12px]">
            <div class="grid grid-cols-[180px_20px_1fr] items-center">
                <div class="font-semibold">Nama Jabatan</div>
                <div class="text-center">:</div>
                <div class="min-h-[30px]  flex gap-[8px]">
                    <div class="border border-1 shadow-xs w-[500px] rounded-[8px] flex items-center py-[5px] px-[8px]"
                        @click="$dispatch('open-pegawai-modal')">
                        <p x-text="jabatan_dipilih.jabatan ?? 'Pilih Jabatan'"></p>
                    </div>
                    <x-ui.button class="text-[12px] !min-h-[30px] !p-0 !px-[8px] w-fit whitespace-nowrap"
                        @click="$dispatch('open-jabatan-modal')">Pilih Jabatan</x-ui.button>
                </div>
            </div>
            <x-ui.modal x-data="{ open: false }" @open-jabatan-modal.window="open = true"
                @close-jabatan-modal.window="open = false" :isOpen="false"
                class="max-w-[1000px] max-h-[80vh] overflow-y-auto">
                <div class="relative w-full rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                    <p class="text-[16px] font-semibold">Pilih Jabatan</p>
                    <div class="flex flex-col gap-[16px] mt-[30px]">
                        @php
                            $skpdOptions = $skpd->pluck('skpd', 'kode_skpd');
                        @endphp
                        <x-input-text label='Nama Jabatan' x-model='jabatan_modal.nama_jabatan' />
                        <x-select name="status_jabatan" label="Status Jabatan" :options="$status_jabatan"
                            x-model="jabatan_modal.status_jabatan" />
                        <x-select name="skpdOptionse" :options="$skpdOptions" label="OPD" x-model="jabatan_modal.kode_skpd" />
                        <div class="flex justify-center">
                            <x-ui.button class="w-fit disabled:cursor-not-allowed" size="sm" @click="cariJabatan()"
                                x-bind:disabled="(!jabatan_modal.kode_skpd && !jabatan_modal.nama_jabatan && !jabatan_modal
                                    .status_jabatan) || loading">Cari
                                Data</x-ui.button>
                        </div>
                    </div>

                    <div class=" min-w-0 overflow-x-auto mt-[24px]">
                        <table id="jabatanTable" class="display" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Jabatan</th>
                                    <th>Status Jabatan</th>
                                    <th>Nama Pejabat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </x-ui.modal>

            <div class="grid grid-cols-[180px_20px_1fr] items-center">
                <div class="font-semibold">Nama Pegawai</div>
                <div class="text-center">:</div>
                <div class="flex h-[30px] gap-[8px]">
                    <div class="border border-1 shadow-xs w-[500px] rounded-[8px] flex items-center py-[5px] px-[8px]"
                        @click="$dispatch('open-pegawai-modal')">
                        <p x-text="pegawai_dipilih.nama_pegawai ?? 'Pilih Pegawai'"></p>
                    </div>
                    <x-ui.button class="text-[12px] !h-full !p-0 !px-[8px] w-fit whitespace-nowrap"
                        @click="$dispatch('open-pegawai-modal')">Pilih Pegawai</x-ui.button>
                </div>
            </div>
            <x-ui.modal x-data="{ open: false }" @open-pegawai-modal.window="open = true" :isOpen="false"
                @close-pegawai-modal.window="open = false" class="max-w-[1000px] max-h-[80vh] overflow-y-auto">
                <div class="relative w-full rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                    <p class="text-[16px] font-semibold">Pilih Pegawai</p>
                    <div class="flex flex-col gap-[16px] mt-[30px]">
                        <x-input-text label='Nama Pegawai' x-model='pegawai_modal.nama_pegawai' />
                        <x-input-text label='NIP' x-model='pegawai_modal.nip' />
                        <x-select :options="$skpdOptions" label="OPD" x-model="pegawai_modal.kode_skpd" />
                        <div class="flex justify-center">
                            <x-ui.button class="w-fit" size="sm" @click="cariPegawai()"
                                x-bind:disabled="(!pegawai_modal.nip && !pegawai_modal.nama_pegawai && !pegawai_modal.kode_skpd) ||
                                loading">Cari
                                Data</x-ui.button>
                        </div>
                    </div>
                    <div class=" min-w-0 overflow-x-auto mt-[24px]">
                        <table id="pegawaiTable" class="display" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Pegawai</th>
                                    <th>Perangkat Daerah</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </x-ui.modal>
            <x-ui.button size="sm"
                class="text-[12px] bg-green-500 hover:bg-green-600 w-fit disabled:bg-green-500 disabled:opacity-50 hover:cursor-pointer disabled:cursor-not-allowed"
                @click="submit()"
                x-bind:disabled="!jabatan_dipilih.id_jabatan || !pegawai_dipilih.id_pegawai">Selanjutnya</x-ui.button>
        </div>
    </div>


    @push('scripts')
        <script>
            function tambahJabatan(mutasi_struktural) {
                return {
                    mutasi_struktural,
                    jabatan_dipilih: {
                        id_jabatan: null,
                        jabatan: null,
                    },

                    pegawai_dipilih: {
                        id_pegawai: null,
                        nama_pegawai: null,
                        id_jabatan: null,
                        jabatan: null,
                    },

                    jabatan_table: null,
                    pegawai_table: null,

                    jabatan_modal: {
                        nama_jabatan: null,
                        status_jabatan: null,
                        kode_skpd: null,
                        jabatan_list: null,
                    },

                    pegawai_modal: {
                        nama_pegawai: null,
                        nip: null,
                        kode_skpd: null,
                        pegawai_list: null,
                    },

                    loading: false,
                    error: null,
                    validationError: null,

                    languageConfiguration: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        zeroRecords: "Data tidak ditemukan",
                        info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                        infoEmpty: "Tidak ada data tersedia",
                        infoFiltered: "(difilter dari _MAX_ total data)",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        },
                        loadingRecords: "Memuat...",
                        processing: "Memproses...",
                        emptyTable: "Tidak ada data dalam tabel"
                    },

                    init() {
                        this.$watch('loading', (val) => {
                            if (val) {
                                SwalHelper.loadingToast('Sedang Memproses...')
                            } else if (!val) {
                                SwalHelper.close()
                            }
                        })

                        this.$watch('error', (val) => {
                            if (val) {
                                SwalHelper.errorToast(this.error)
                                this.error = null
                            }
                        })

                        this.jabatan_table = new DataTable('#jabatanTable', {
                            pageLength: 5,
                            lengthChange: false,
                            processing: true,
                            serverSide: false,
                            searching: false,
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
                                    data: 'jabatan',
                                    name: 'jabatan',
                                },
                                {
                                    data: 'status_jabatan',
                                    name: 'status_jabatan',
                                },
                                {
                                    data: 'nama_pejabat',
                                    name: 'nama_pejabat'
                                },
                                {
                                    data: null,
                                    orderable: false,
                                    searchable: false,
                                    render: function(data, type, row, meta) {
                                        return `
                                            <x-ui.button size='sm' class="!py-[5px] !px-[12px] w-fit bg-blue-500 hover:bg-blue-600 text-white text-[12px]" @click="pilihJabatan(${meta.row})">Pilih</x-ui.button>
                                    `;
                                    }
                                },

                            ],
                            columnDefs: [{
                                    targets: '_all',
                                    className: 'whitespace-normal break-words align-middle',
                                },
                                {
                                    targets: 0,
                                    className: 'whitespace-normal break-words align-middle w-10',
                                },
                                {
                                    targets: 1,
                                    className: 'whitespace-normal break-words align-middle w-[500px]',
                                }
                            ],
                            language: this.languageConfiguration
                        })

                        this.pegawai_table = new DataTable('#pegawaiTable', {
                            pageLength: 5,
                            lengthChange: false,
                            processing: true,
                            serverSide: false,
                            searching: false,
                            scrollX: true,
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
                                    data: 'nama_pegawai',
                                    name: 'nama_pegawai',
                                },
                                {
                                    data: 'skpd',
                                    name: 'skpd',
                                },
                                {
                                    data: null,
                                    orderable: false,
                                    searchable: false,
                                    render: function(data, type, row, meta) {
                                        return `
                                            <x-ui.button size='sm' class="!py-[5px] !px-[12px] w-fit bg-blue-500 hover:bg-blue-600 text-white text-[12px]" @click="pilihPegawai(${meta.row})">Pilih</x-ui.button>
                                    `;
                                    }
                                },

                            ],
                            columnDefs: [{
                                    targets: '_all',
                                    className: 'whitespace-normal break-words align-middle',
                                },
                                {
                                    targets: 0,
                                    className: 'whitespace-normal break-words align-middle w-10',
                                },
                                {
                                    targets: 1,
                                    className: 'whitespace-normal break-words align-middle w-[500px]',
                                }
                            ],
                            language: this.languageConfiguration
                        })

                        if (mutasi_struktural.mutasi_struktural_detail) {
                            console.log(this.mutasi_struktural.mutasi_struktural_detail)
                            this.jabatan_dipilih.id_jabatan = this.mutasi_struktural.mutasi_struktural_detail.ke_id_jabatan
                            this.jabatan_dipilih.jabatan = this.mutasi_struktural.mutasi_struktural_detail.ke_jabatan.jabatan

                            this.pegawai_dipilih.id_pegawai = this.mutasi_struktural.mutasi_struktural_detail.id_pegawai
                            this.pegawai_dipilih.nama_pegawai = this.mutasi_struktural.mutasi_struktural_detail.pegawai
                                .nama_pegawai
                            this.pegawai_dipilih.id_jabatan = this.mutasi_struktural.mutasi_struktural_detail.dari_id_jabatan
                            this.pegawai_dipilih.jabatan = this.mutasi_struktural.mutasi_struktural_detail.dari_jabatan.jabatan
                        }
                    },

                    async cariJabatan() {
                        try {
                            this.loading = true
                            if (this.jabatan_modal
                                .kode_skpd || this.jabatan_modal.nama_jabatan || this.jabatan_modal.status_jabatan) {
                                const {
                                    jabatan_list,
                                    ...cleanData
                                } = this.jabatan_modal;

                                searchForm = cleanData
                                const res = await axios.get('{{ url('/') }}/jabatan', {
                                    params: cleanData
                                })
                                this.jabatan_table.clear();
                                this.jabatan_table.rows.add(res.data);
                                this.jabatan_table.draw();
                                this.jabatan_modal.jabatan_list = res.data
                            } else {
                                this.error = `Mohon isi salah satu isian`
                            }
                        } finally {
                            this.loading = false
                        }
                    },

                    async cariPegawai() {
                        try {
                            this.loading = true
                            if (this.pegawai_modal
                                .kode_skpd || this.pegawai_modal.nama_pegawai || this.pegawai_modal.nip) {
                                const {
                                    pegawai_list,
                                    ...cleanData
                                } = this.pegawai_modal;

                                searchForm = cleanData
                                const res = await axios.get('{{ url('/') }}/pegawai', {
                                    params: cleanData
                                })

                                this.pegawai_table.clear();
                                this.pegawai_table.rows.add(res.data);
                                this.pegawai_table.draw();
                                this.pegawai_modal.pegawai_list = res.data
                            } else {
                                this.error = `Mohon isi salah satu isian`
                            }
                        } finally {
                            this.loading = false
                        }
                    },

                    pilihJabatan(idx) {
                        this.jabatan_dipilih.id_jabatan = this.jabatan_modal.jabatan_list[idx].id_jabatan
                        this.jabatan_dipilih.jabatan = this.jabatan_modal.jabatan_list[idx].jabatan
                        this.$dispatch('close-jabatan-modal')
                    },

                    pilihPegawai(idx) {
                        this.pegawai_dipilih.id_pegawai = this.pegawai_modal.pegawai_list[idx].id_pegawai
                        this.pegawai_dipilih.nama_pegawai = this.pegawai_modal.pegawai_list[idx].nama_pegawai
                        this.pegawai_dipilih.id_jabatan = this.pegawai_modal.pegawai_list[idx].id_jabatan
                        this.pegawai_dipilih.jabatan = this.pegawai_modal.pegawai_list[idx].jabatan
                        this.$dispatch('close-pegawai-modal')
                    },

                    async submit() {
                        try {
                            this.loading = true
                            if (this.jabatan_dipilih
                                .id_jabatan || this.pegawai_dipilih.id_pegawai) {
                                req_body = {
                                    id_pegawai: String(this.pegawai_dipilih.id_pegawai),
                                    dari_id_jabatan: String(this.pegawai_dipilih.id_jabatan),
                                    ke_id_jabatan: String(this.jabatan_dipilih.id_jabatan),
                                }
                                const res = await axios.post(
                                    `{{ url('/') }}/mutasi/konsep/${mutasi_struktural.id}/jabatan`, req_body)
                                console.log(res.data)
                                if (res.data.redirect) {
                                    window.location.href = res.data.redirect;
                                }
                            } else {
                                this.error = `Mohon isi salah satu isian`
                            }
                        } finally {
                            this.loading = false
                        }
                    }
                }
            }
        </script>
    @endpush
@endsection
