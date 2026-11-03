@extends('layouts.app')


@section('title', 'Pejabat Struktural')

@section('content')
    <div class="flex flex-col gap-[24px] w-full" x-data="pejabatData()">
        <div class="w-full rounded-[16px] bg-white px-[24px] py-[20px] flex flex-col gap-[24px]">
            <div class=" flex">
                <p class="font-semibold text-[24px]">Pejabat Struktural</p>
            </div>
            <div class=" grid grid-cols-2 gap-[24px] w-full">
                @php
                    $skpdOptions = $skpd->pluck('skpd', 'kode_skpd');
                    $agamaOptions = $agama->pluck('agama', 'id_agama');
                    $jenisKelaminOptions = $jenis_kelamin->pluck('jenis_kelamin', 'id_jenis_kelamin');
                @endphp
                <x-select name="status_jabatan" label="Status Jabatan" :options="$status_jabatan" :required="true"
                    x-model="id_status_jabatan" />
                <x-select name="jenis_jabatan" label="Jenis Jabatan" :options="$jenis_jabatan" x-model="id_jenis_jabatan" />
                <x-select label="OPD" name="kode_skpd" :required="true" :options="$skpdOptions" x-model="kode_skpd" />
                <x-select label="Agama" name="agama" :options="$agamaOptions" x-model="id_agama" />
                <x-select label="Jenis Kelamin" name="jenis_kelamin" :options="$jenisKelaminOptions" x-model="id_jenis_kelamin" />
                <x-input-text label='Jabatan Tertentu' value='' x-model='tambah_query' />
                <x-select name="urutan" label="Urut Berdasarkan" :options="$urutan" x-model="urutan" />

            </div>
            <div class="flex flex-col gap-[8px] text-sm">
                <p>Catatan :</p>
                <div class="flex gap-[5px]">
                    <p class="text-red-600">(*)</p>
                    <p class="text-grey-700">Wajib diisi</p>
                </div>

            </div>
            <div class="flex justify-end w-full gap-[10px]">
                <x-button @click="reset">
                    <x-icon name="arrows-rotate" class="w-[20px] h-[20px]" />
                    <p class="text-[14px]">Reset</p>
                </x-button>
                <x-button class="bg-[#ECF3FF] !border-[#465FFF]" @click="loadData()"
                    x-bind:disabled="kode_skpd === '' || loading || id_status_jabatan === ''">
                    <x-icon name="magnifying-glass" class="w-[20px] h-[20px]" />
                    <p class="text-[14px]">Cari Data</p>
                </x-button>
            </div>
        </div>
        <div class="rounded-[16px] bg-white px-[24px] py-[20px] flex flex-col gap-[24px] max-sm:max-w-[100vw]"
            id="tableContainer"
            :class="$store.sidebar.isExpanded || $store.sidebar.isHovered ?
                'lg:max-w-[calc(100vw-(290px+48px+15px))]' : 'lg:max-w-[calc(100vw-(90px+48px+15px))]'">
            <div class=" flex justify-between items-center">
                <p class="font-semibold text-[24px]">Hasil Pencarian</p>
                <x-button class="!bg-[#12B76A] text-white"
                    x-bind:disabled="kode_skpd === '' || loading || id_status_jabatan === ''"
                    @click="downloadCsv()">
                    <x-icon name="download" class="w-[20px] h-[20px]" />
                    <p class="text-[14px]">Unduh CSV</p>
                </x-button>
            </div>
            <div class=" min-w-0 overflow-x-auto" id="resultContainer">
                <table id="pejabatTable" class="display" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Jabatan</th>
                            <th>Eselon</th>
                            <th>OPD</th>
                            <th>Status Jabatan</th>
                            <th>NIP</th>
                            <th>Nama Lengkap</th>
                            <th>Gol Ruang</th>
                            <th>TMT Jabatan</th>
                            <th>Nomor SK Jabatan</th>
                            <th>Tanggal SK Jabatan</th>
                            <th>Lama Menjabat</th>
                            <th>TMT Pensiun</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function pejabatData() {
            const el = document.getElementById("resultContainer");
            const appHeader = document.getElementById("simpegHeader");

            return {
                id_status_jabatan: '',
                id_jenis_jabatan: '',
                kode_skpd: '',
                id_agama: '',
                id_jenis_kelamin: '',
                tambah_query: '',
                urutan: '',
                loading: false,
                error: '',
                table: null,
                init() {
                    this.$watch('loading', (val) => {
                        if (val) {

                            SwalHelper.loadingToast('Sedang Memproses...')
                        } else if (!val) {
                            SwalHelper.close()
                        }
                    })

                    this.table = new DataTable('#pejabatTable', {
                        // responsive: true,
                        processing: true,
                        serverSide: false,
                        fixedHeader: {
                            header: true,
                            headerOffset: appHeader.offsetHeight
                        },
                        fixedColumns: {
                            left: 2
                        },
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
                                data: 'jabatan',
                                name: 'jabatan',
                            },
                            {
                                data: 'eselon',
                                name: 'eselon',
                            },
                            {
                                data: 'perangkat_daerah',
                                name: 'perangkat_daerah'
                            },
                            {
                                data: 'status_jabatan',
                                name: 'status_jabatan',
                            },
                            {
                                data: 'nip',
                                render: function(data, type, row, meta) {
                                    if (type !== 'display') {
                                        return data;
                                    }
                                    return `
                                        <div class="flex flex-col gap-2">
                                            <div >
                                                ${data}
                                            </div>
                                            <a target="_blank" rel="noopener noreferrer" href="{{ url('/') }}/pegawai/${row.id_pegawai}/profile" >
                                            <button
                                                class="w-full py-1 flex gap-[5px] items-center justify-start"
                                                data-id="${row.id_pegawai}">
                                                <x-icons.profile />
                                                <p>Lihat Profile</p>
                                            </button>
                                            </a>
                                        </div>
                                    `;
                                }
                            },
                            {
                                data: 'nama_pegawai',
                                name: 'nama_pegawai',
                            },
                            {
                                data: 'gol_ruang',
                                name: 'gol_ruang',
                            },
                            {
                                data: 'tmt_jabatan',
                                name: 'tmt_jabatan',
                            },
                            {
                                data: 'no_sk_jabatan',
                                name: 'no_sk_jabatan',
                            },
                            {
                                data: 'tgl_sk_jabatan',
                                name: 'tgl_sk_jabatan',
                            },
                            {
                                data: 'lama_menjabat',
                                name: 'lama_menjabat',
                            },
                            {
                                data: 'tmt_pensiun',
                                name: 'tmt_pensiun',
                            },
                        ],
                        columnDefs: [{
                                targets: '_all',
                                className: 'whitespace-normal break-words align-middle',
                            },
                            {
                                targets: 0,
                                className: 'whitespace-normal break-words align-middle w-5',
                            },
                            {
                                targets: 1,
                                className: 'whitespace-normal break-words align-middle min-w-80',
                            },
                            {
                                targets: 3,
                                className: 'whitespace-normal break-words align-middle min-w-80',
                            },
                            {
                                targets: 5,
                                className: 'whitespace-normal break-words align-middle min-w-50',
                            },
                            {
                                targets: 6,
                                className: 'whitespace-normal break-words align-middle min-w-50',
                            },
                        ],
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
                },


                reset() {
                    this.id_status_jabatan = ''
                    this.id_jenis_jabatan = ''
                    this.kode_skpd = ''
                    this.id_agama = ''
                    this.id_jenis_kelamin = ''
                    this.tambah_query = ''
                    this.urutan = ''
                },

                async loadData() {
                    const offset = appHeader ? (appHeader.offsetHeight * 2) : 0;
                    const yPosition = el.getBoundingClientRect().top + window.pageYOffset - offset;

                    try {
                        this.loading = true;
                        if (this.kode_skpd !== '' && this.id_status_jabatan !== '') {
                            const res = await axios.get('{{ url('/kepegawaian/pejabat/data') }}', {
                                params: {
                                    id_status_jabatan: this.id_status_jabatan,
                                    id_jenis_jabatan: this.id_jenis_jabatan,
                                    kode_skpd: this.kode_skpd,
                                    id_agama: this.id_agama,
                                    id_jenis_kelamin: this.id_jenis_kelamin,
                                    tambah_query: this.tambah_query,
                                    urutan: this.urutan

                                }
                            });
                            data = res.data;

                            this.table.clear();
                            this.table.rows.add(data);
                            this.table.draw();
                            // this.table.columns.adjust();
                            // this.table.fixedHeader.adjust();
                            window.scrollTo({
                                top: yPosition,
                                behavior: "smooth"
                            });
                        } else {
                            this.error = "Pilih OPD dan Status Jabatan terlebih dahulu"
                        }
                    } finally {
                        this.loading = false;
                    }
                },

                exportCsvUrl() {
                    return `{{ url('/') }}/kepegawaian/pejabat-struktural/csv?id_status_jabatan=${this.id_status_jabatan}&id_jenis_jabatan=${this.id_jenis_jabatan}&kode_skpd=${this.kode_skpd}&id_agama=${this.id_agama}&id_jenis_kelamin=${this.id_jenis_kelamin}&tambah_query=${this.tambah_query}&urutan=${this.urutan}`;
                },

                downloadCsv() {
                    window.open(this.exportCsvUrl(), '__blank')
                },
            }
        }
    </script>
@endpush
