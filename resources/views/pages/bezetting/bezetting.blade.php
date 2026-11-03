@extends('layouts.app')

@section('title', 'Bezetting')

@section('content')
    <div class="flex flex-col gap-[24px]" x-data="laporanBezetting(@js($skpd))">
        <div class="w-full rounded-[16px] bg-white px-[24px] py-[20px] flex flex-col gap-[24px]">
            <div class=" flex w-full">
                <p class="font-semibold text-[24px]">Laporan Bezetting</p>
            </div>
            <div class=" flex flex-col gap-[12px]">
                @php
                    $skpdOptions = $skpd->pluck('skpd', 'kode_skpd');
                    $statusKepegawaianOptions = [
                        '1' => 'CPNS/PNS',
                        '2' => 'PPPK',
                        '3' => 'CPNS/PNS & PPPK',
                    ];
                @endphp
                <x-select label="OPD" name="kode_skpd" x-model="kode_skpd" :options="$skpdOptions" class="lg:w-[calc(100vw/3)]"
                    :required="true" />
                <x-select label="Status Kepegawaian" name="status_kepegawaian" x-model="status_kepegawaian"
                    :options="$statusKepegawaianOptions" class="lg:w-[calc(100vw/3)]" :required="true" />
            </div>
            <div class="flex flex-col gap-[8px] text-sm">
                <p>Catatan :</p>
                <div class="flex gap-[5px]">
                    <p class="text-red-600">(*)</p>
                    <p class="text-grey-700">Wajib diisi</p>
                </div>

            </div>
            <div class="flex justify-end w-full gap-[10px] ">
                <x-button @click="reset">
                    <x-icon name="arrows-rotate" />
                    <p class="text-[14px]">Reset</p>
                </x-button>
                <x-button class="bg-[#ECF3FF] !border-[#465FFF]" @click="loadData()"
                    x-bind:disabled="kode_skpd === '' || loading || status_kepegawaian === ''">
                    <x-icon name="magnifying-glass" />
                    <p class="text-[14px]">Cari Data</p>
                </x-button>
            </div>
        </div>
        <div class="rounded-[16px] bg-white px-[24px] py-[20px] flex flex-col gap-[24px] max-sm:max-w-[100vw]"
            id="tableContainer"
            :class="$store.sidebar.isExpanded || $store.sidebar.isHovered ?
                'lg:max-w-[calc(100vw-(290px+48px+15px))]' : 'lg:max-w-[calc(100vw-(90px+48px+15px))]'">
            <div class=" flex justify-between">
                <div class="flex flex-col gap-[4px]">
                    <p class="font-semibold text-[24px]">Hasil Pencarian</p>
                    <p class="text-[14px]" x-text="getSkpd()">
                    </p>
                </div>
                <div class="flex gap-[8px]">
                    <x-button class="!bg-[#12B76A] text-white" x-bind:disabled="exportDisabled" @click="downloadExcel()">
                        <x-icon name="download" />
                        <p class="text-[14px]">Unduh Excel</p>
                    </x-button>

                    <x-button x-bind:disabled="exportDisabled" @click="downloadPdf()">
                        <x-icon name="print-button" />
                        <p class="text-[14px]">Cetak PDF</p>
                    </x-button>

                </div>
            </div>
            <div class="min-w-0 overflow-x-auto" id="resultContainer">
                <table id="bezettingTable" class="display" style="font-size: 12px;">
                </table>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function laporanBezetting(skpd_list) {
            const el = document.getElementById("resultContainer");
            const appHeader = document.getElementById("simpegHeader");

            return {
                kode_skpd: '',
                status_kepegawaian: '3',
                laporan: [],
                skpd_list,
                exportDisabled: true,
                loading: false,
                error: '',
                table: null,

                init() {
                    this.reloadTable();

                    this.$watch('loading', (val) => {
                        if (val) {

                            SwalHelper.loadingToast('Sedang Memproses...')
                        } else if (!val) {
                            SwalHelper.close()
                        }
                    })

                    this.$watch('laporan', val => {
                        if (val.length == 0) {
                            this.exportDisabled = true
                        } else if (val.length > 0) {
                            this.exportDisabled = false
                        }
                    })
                    this.$watch('kode_skpd', val => {
                        this.laporan = []
                        this.reloadTable()
                    })
                    this.$watch('status_kepegawaian', val => {
                        this.laporan = []
                        this.reloadTable()
                    })
                },

                reloadTable() {
                    if (this.table) {
                        this.table.destroy();
                        this.table = null;
                    }

                    document.querySelector('#bezettingTable').innerHTML =
                        this.buildThead(this.status_kepegawaian);

                    this.table = new DataTable('#bezettingTable', {
                        serverSide: false,
                        fixedHeader: {
                            header: true,
                            headerOffset: appHeader.offsetHeight
                        },
                        fixedColumns: {
                            left: 2
                        },
                        scrollX: true,
                        columns: this.getColumns(this.status_kepegawaian),
                        columnDefs: [{
                            targets: '_all',
                            className: 'whitespace-normal break-words align-middle',
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
                },

                buildThead(status = '3') {
                    const showCPNSPNS = ['1', '3'].includes(status);
                    const showPPPK = ['2', '3'].includes(status);

                    const rowspanTop = showCPNSPNS ? 4 : 3;
                    const rowspanMid = showCPNSPNS ? 3 : 2;

                    const jumlahPegawaiColspan =
                        status === '1' ? 3 :
                        status === '2' ? 2 : 4;

                    const golonganColspan =
                        status === '1' ? 18 :
                        status === '2' ? 19 : 36;

                    const golonganTitle =
                        status === '2' ?
                        'Golongan' :
                        'Pangkat dan Golongan';


                    let row1 = `
                        <tr>
                            <th rowspan="${rowspanTop}" style="text-align:center">No.</th>
                            <th rowspan="${rowspanTop}" style="text-align:center;min-width:250px">
                                SKPD
                            </th>

                            <th colspan="${jumlahPegawaiColspan}" style="text-align:center">
                                Jumlah Pegawai
                            </th>

                            <th colspan="7" style="text-align:center">
                                Pejabat Administrasi
                            </th>

                            <th colspan="${golonganColspan}" style="text-align:center">
                                ${golonganTitle}
                            </th>

                            <th colspan="10" style="text-align:center">
                                Tingkat Pendidikan
                            </th>
                        </tr>
                    `;

                    // ROW 2
                    let row2 = `<tr>`;

                    if (showCPNSPNS) row2 += `<th rowspan="${rowspanMid}" style="text-align:center">CPNS</th>`;
                    if (showCPNSPNS) row2 += `<th rowspan="${rowspanMid}" style="text-align:center">PNS</th>`;
                    if (showPPPK) row2 += `<th rowspan="${rowspanMid}" style="text-align:center">PPPK</th>`;

                    row2 += `
                        <th rowspan="${rowspanMid}" style="text-align:center">Jumlah</th>

                        <th rowspan="${rowspanMid}" style="text-align:center">II.a</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">II.b</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">III.a</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">III.b</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">IV.a</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">IV.b</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">Jumlah</th>
                    `;

                    if (showCPNSPNS) {
                        row2 += `<th colspan="17" style="text-align:center">CPNS/PNS</th>`;
                    }

                    if (showPPPK) {
                        row2 += `<th colspan="18" style="text-align:center">PPPK</th>`;
                    }

                    row2 += `
                        <th rowspan="${rowspanMid}" style="text-align:center">Jumlah</th>

                        <th rowspan="${rowspanMid}" style="text-align:center">SD</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">SMP</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">SMA</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">DI</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">DII</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">DIII</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">S1</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">S2</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">S3</th>
                        <th rowspan="${rowspanMid}" style="text-align:center">Jumlah</th>
                    `;
                    row2 += `</tr>`;

                    // ROW 3
                    let row3 = `<tr>`;

                    if (showCPNSPNS) {
                        row3 += `
                            <th colspan="4" style="text-align:center">I</th>
                            <th colspan="4" style="text-align:center">II</th>
                            <th colspan="4" style="text-align:center">III</th>
                            <th colspan="5" style="text-align:center">IV</th>
                        `;
                    }

                    if (showPPPK) {
                        const rowspanPPPK = showCPNSPNS ? 2 : 1;

                        [
                            'Tanpa Golongan',
                            'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII',
                            'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII'
                        ].forEach(v => {
                            row3 += `<th rowspan="${rowspanPPPK}" style="text-align:center">${v}</th>`;
                        });
                    }

                    row3 += `</tr>`;

                    // ROW 4 (hanya jika ada CPNS/PNS)
                    let row4 = '';

                    if (showCPNSPNS) {
                        row4 = `<tr>`;

                        [
                            'a', 'b', 'c', 'd',
                            'a', 'b', 'c', 'd',
                            'a', 'b', 'c', 'd',
                            'a', 'b', 'c', 'd', 'e'
                        ].forEach(v => {
                            row4 += `<th style="text-align:center">${v}</th>`;
                        });

                        row4 += `</tr>`;
                    }

                    return `
                        <thead>
                            ${row1}
                            ${row2}
                            ${row3}
                            ${row4}
                        </thead>
                    `;
                },

                getColumns(status = '3') {
                    let cols = [{
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'skpd_name',
                        }
                    ];

                    if (['1', '3'].includes(status)) {
                        cols.push({
                            data: 'jlh_cpns'
                        }, {
                            data: 'jlh_pns'
                        });
                    }

                    if (['2', '3'].includes(status)) {
                        cols.push({
                            data: 'jlh_pppk'
                        });
                    }

                    cols.push({
                        data: 'jlh_pegawai'
                    });

                    [
                        'eselon_2a',
                        'eselon_2b',
                        'eselon_3a',
                        'eselon_3b',
                        'eselon_4a',
                        'eselon_4b',
                        'jlh_eselon'
                    ].forEach(k => {
                        cols.push({
                            data: k
                        });
                    });

                    // golongan cpns/pns
                    if (['1', '3'].includes(status)) {
                        [
                            'gol_1a', 'gol_1b', 'gol_1c', 'gol_1d',
                            'gol_2a', 'gol_2b', 'gol_2c', 'gol_2d',
                            'gol_3a', 'gol_3b', 'gol_3c', 'gol_3d',
                            'gol_4a', 'gol_4b', 'gol_4c', 'gol_4d', 'gol_4e'
                        ].forEach(k => {
                            cols.push({
                                data: k
                            });
                        });
                    }

                    // golongan pppk
                    if (['2', '3'].includes(status)) {
                        [
                            'tanpa_golongan',
                            'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII',
                            'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII'
                        ].forEach(k => {
                            cols.push({
                                data: k
                            });
                        });
                    }

                    cols.push({
                        data: 'jlh_gol'
                    });

                    // pendidikan
                    [
                        'jlh_sd', 'jlh_smp', 'jlh_sma', 'jlh_d1', 'jlh_d2',
                        'jlh_d3', 'jlh_s1', 'jlh_s2', 'jlh_s3', 'jlh_sekolah'
                    ].forEach(k => {
                        cols.push({
                            data: k
                        });
                    });

                    return cols;
                },

                getSkpd() {
                    const item = this.skpd_list.find(p => p.kode_skpd == this.kode_skpd);
                    return item ? item.skpd : 'Tidak ditemukan';
                },


                async loadData() {
                    const offset = appHeader ? (appHeader.offsetHeight * 2) : 0;
                    const yPosition = el.getBoundingClientRect().top + window.pageYOffset - offset;
                    try {
                        this.loading = true;
                        if (this.kode_skpd) {
                            const res = await axios.get('{{ url('/kepegawaian/bezetting/data') }}', {
                                params: {
                                    kode_skpd: this.kode_skpd,
                                    status_kepegawaian: this.status_kepegawaian
                                }
                            });
                            this.laporan = res.data;
                            data = res.data;
                            const lastIndex = data.length - 1;
                            data[lastIndex].skpd_name = 'Total'
                            this.table.clear();
                            this.table.rows.add(data);
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
                    return `{{ url('/') }}/kepegawaian/bezetting/pdf?kode_skpd=${this.kode_skpd}&status_kepegawaian=${this.status_kepegawaian}`;
                },

                exportExcelUrl() {
                    return `{{ url('/') }}/kepegawaian/bezetting/excel?kode_skpd=${this.kode_skpd}&status_kepegawaian=${this.status_kepegawaian}`;
                },

                downloadExcel() {
                    window.open(this.exportExcelUrl(), '__blank')
                },

                downloadPdf() {
                    window.open(this.exportPdfUrl(), '__blank')
                },

                reset() {
                    this.kode_skpd = ''
                    this.status_kepegawaian = ''
                    this.laporan = []
                },
            }
        }
    </script>
@endpush
