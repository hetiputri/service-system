@extends('layouts.app')

@section('title', 'Daftar Pegawai')

@section('content')
    <div class="flex flex-col gap-[24px] w-full" x-data="daftarPegawai()">
        <div class="w-full rounded-[16px] bg-white px-[24px] py-[20px] flex flex-col gap-[24px]">
            <div class="flex">
                <p class="font-semibold text-[24px]">Daftar Pegawai</p>
            </div>
            <div class="grid grid-cols-2 gap-[24px] w-full">

                <x-input-text label='Nama' value='' x-model='nama' />

                <x-input-text label='NIP' value='' x-model='nip' />

                @php
                    $pangkatOptions = $pangkat->pluck('pangkat', 'id_pangkat');
                @endphp
                <x-select-multiple class="!p-0" label="Pangkat" :options="$pangkatOptions" x-model="pangkat" />

                @php
                    $eselonOptions = $eselon->pluck('eselon', 'id_eselon');
                    // dd($eselonOptions);
                @endphp
                <x-select-multiple class="!p-0" label="Eselon" :options="$eselonOptions" x-model="eselon" />

                @php
                    $tipeJabatanOptions = $tipe_jabatan->pluck('tipe_jabatan', 'id_tipe_jabatan');
                @endphp
                <div class="flex flex-col gap-[8px]">
                    <x-select name="kode_skpd" label="Tipe Jabatan" x-model="tipe_jabatan" :options="$tipeJabatanOptions" />
                </div>

                @php
                    $skpdOptions = $skpd->pluck('skpd', 'kode_skpd');
                @endphp
                <div class="flex flex-col gap-[8px]">
                    <x-select name="kode_skpd" label="OPD" x-model="kode_skpd" :options="$skpdOptions" :required="true" />
                </div>
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
                    <x-icon name="arrows-rotate" />
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
            <div class="flex justify-between items-center">
                <p class="font-semibold text-[24px]">Hasil Pencarian</p>
                </p>
                <x-button class="!bg-[#12B76A] text-white" x-bind:disabled="kode_skpd === ''"
                    @click="downloadCsv()">
                    <x-icon name="download" />
                    <p class="text-[14px]">Unduh CSV</p>
                </x-button>

            </div>
            <div class="min-w-0 overflow-x-auto" id="resultContainer">
                <table id="daftarPegawaiTable" class="display" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pegawai</th>
                            <th>NIP</th>
                            <th>Status Pegawai</th>
                            <th>Jenis Pengadaan PPPK</th>
                            <th>Pangkat/Gol Ruang</th>
                            <th>TMT Pangkat</th>
                            <th>No SK CPNS</th>
                            <th>Tgl SK CPNS</th>
                            <th>TMT CPNS</th>
                            <th>No SK PNS</th>
                            <th>Tgl SK PNS</th>
                            <th>TMT PNS</th>
                            <th>NIK</th>
                            <th>NPWP</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>Umur</th>
                            <th>Jenis Kelamin</th>
                            <th>Agama</th>
                            <th>Telepon</th>
                            <th>Tipe Jabatan</th>
                            <th>Eselon</th>
                            <th>Jabatan</th>
                            <th>TMT Jabatan</th>
                            <th>Jenis Jenjang Jabatan</th>
                            <th>Jenjang Jabatan</th>
                            <th>SKPD</th>
                            <th>Pendidikan Terakhir</th>
                            <th>Jurusan</th>
                            <th>Nama Sekolah</th>
                            <th>Agama</th>
                            <th>Alamat</th>
                            <th>Email</th>
                            <th>Status Pernikahan</th>
                            <th>Nama Suami/Istri</th>
                            <th>Pekerjaan Pasangan</th>
                            <th>Tanggal Nikah</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <script>
            function daftarPegawai() {
                const el = document.getElementById("resultContainer");
                const appHeader = document.getElementById("simpegHeader");
                return {
                    kode_skpd: '',
                    tipe_jabatan: '',
                    pangkat: [],
                    eselon: [],
                    nama: '',
                    nip: '',
                    laporan: [],
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

                        this.table = new DataTable('#daftarPegawaiTable', {
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
                                    data: 'nama_pegawai',
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
                                    data: 'status_kepegawaian',
                                },
                                {
                                    data: 'jenis_pengadaan_pppk',
                                },
                                {
                                    data: 'pangkat_gol_ruang',
                                },
                                {
                                    data: 'tmt_pangkat',
                                },
                                {
                                    data: 'no_sk_cpns',
                                },
                                {
                                    data: 'tgl_sk_cpns',
                                },
                                {
                                    data: 'tmt_cpns',
                                },
                                {
                                    data: 'no_sk_pns',
                                },
                                {
                                    data: 'tgl_sk_pns',
                                },
                                {
                                    data: 'tmt_pns',
                                },
                                {
                                    data: 'nik',
                                },
                                {
                                    data: 'npwp',
                                },
                                {
                                    data: 'tempat_lahir',
                                },
                                {
                                    data: 'tanggal_lahir',
                                },
                                {
                                    data: 'umur',
                                },
                                {
                                    data: 'jenis_kelamin',
                                },
                                {
                                    data: 'agama',
                                },
                                {
                                    data: 'telepon',
                                },
                                {
                                    data: 'tipe_jabatan',
                                },
                                {
                                    data: 'eselon',
                                },
                                {
                                    data: 'jabatan',
                                },
                                {
                                    data: 'tmt_jabatan',
                                },
                                {
                                    data: 'jenis_jabfung',
                                },
                                {
                                    data: 'jenjang_jabfung',
                                },
                                {
                                    data: 'skpd',
                                },
                                {
                                    data: 'pendidikan_terakhir',
                                },
                                {
                                    data: 'jurusan',
                                },
                                {
                                    data: 'nama_sekolah',
                                },
                                {
                                    data: 'agama',
                                },
                                {
                                    data: 'alamat',
                                },
                                {
                                    data: 'email',
                                },
                                {
                                    data: 'status_pernikahan',
                                },
                                {
                                    data: 'nama_pasangan',
                                },
                                {
                                    data: 'pekerjaan_pasangan',
                                },
                                {
                                    data: 'tgl_nikah',
                                },
                            ],
                            columnDefs: [{
                                    targets: '_all',
                                    className: 'whitespace-normal break-words align-middle',
                                },
                                {
                                    targets: 1,
                                    className: 'whitespace-normal break-words align-middle min-w-60',
                                },
                                {
                                    targets: 2,
                                    className: 'whitespace-normal break-words align-middle min-w-50',
                                },
                                {
                                    targets: 23,
                                    className: 'whitespace-normal break-words align-middle min-w-50',
                                },
                                {
                                    targets: 27,
                                    className: 'whitespace-normal break-words align-middle min-w-70',
                                },
                                {
                                    targets: 32,
                                    className: 'whitespace-normal break-words align-middle min-w-60',
                                }
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

                    async loadData() {
                        const offset = appHeader ? (appHeader.offsetHeight * 2) : 0;
                        const yPosition = el.getBoundingClientRect().top + window.pageYOffset - offset;
                        try {
                            this.loading = true;
                            if (this.kode_skpd) {
                                const res = await axios.get('{{ url('/kepegawaian/daftar-pegawai/data') }}', {
                                    params: {
                                        nama: this.nama,
                                        nip: this.nip,
                                        id_pangkat: this.pangkat.join(","),
                                        id_eselon: this.eselon.join(","),
                                        id_tipe_jabatan: this.tipe_jabatan,
                                        kode_skpd: this.kode_skpd,
                                    }
                                });
                                this.table.clear();
                                this.table.rows.add(res.data);
                                this.table.draw();
                                window.scrollTo({
                                    top: yPosition,
                                    behavior: "smooth"
                                });
                            } else {
                                this.error = "Pilih OPD terlebih dahulu"
                            }
                        } finally {
                            this.loading = false;
                        }
                    },

                    exportPdfUrl() {
                        return `{{ url('/') }}/kepegawaian/daftar-pegawai/pdf?nama=${this.nama}&nip=${this.nip}&id_pangkat=${this.pangkat}&id_eselon=${this.eselon}&id_tipe_jabatan=${this.tipe_jabatan}&kode_skpd=${this.kode_skpd}`;
                    },

                    exportExcelUrl() {
                        return `{{ url('/') }}/kepegawaian/daftar-pegawai/excel?nama=${this.nama}&nip=${this.nip}&id_pangkat=${this.pangkat}&id_eselon=${this.eselon}&id_tipe_jabatan=${this.tipe_jabatan}&kode_skpd=${this.kode_skpd}`;
                    },

                    exportCsvUrl() {
                        return `{{ url('/') }}/kepegawaian/daftar-pegawai/csv?nama=${this.nama}&nip=${this.nip}&id_pangkat=${this.pangkat}&id_eselon=${this.eselon}&id_tipe_jabatan=${this.tipe_jabatan}&kode_skpd=${this.kode_skpd}`;
                    },

                    downloadExcel() {
                        window.open(this.exportExcelUrl(), '__blank')
                    },

                    downloadPdf() {
                        window.open(this.exportPdfUrl(), '__blank')
                    },

                    downloadCsv() {
                        window.open(this.exportCsvUrl(), '__blank')
                    },

                    reset() {
                        this.kode_skpd = ''
                        this.tipe_jabatan = ''
                        this.pangkat = []
                        this.eselon = []
                        this.nama = ''
                        this.nip = ''
                        this.laporan = []
                    },
                }
            }
        </script>
    @endsection
