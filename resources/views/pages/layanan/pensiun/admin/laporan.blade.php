@extends('layouts.app')
@section('title','Laporan Pensiun')

@section('content')

<div x-data="search()">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-2">

        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Filter Data Pencarian
                    </h2>
                </div>
            </div>

            <div class="mt-6">
                <form action="{{ route('rekap.pensiun') }}" method="POST" class="space-y-4">
                    @csrf


                    <div class="rounded-2xl border border-gray-200 bg-gray-50/70 p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="rounded-lg bg-white p-2 shadow-sm">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-md font-semibold text-gray-700">Tanggal Pengajuan</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-md font-medium text-gray-600">Dari Tanggal</label>
                                <input type="date" name="dariTanggal" x-ref="pengajuanFrom" x-model="form.tanggalPengajuanFrom" x-init="flatpickr($refs.pengajuanFrom, { dateFormat: 'Y-m-d' })" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="mb-1 block text-md font-medium text-gray-600">Sampai Tanggal</label>
                                <input type="date" name="sampaiTanggal" x-ref="pengajuanTo" x-model="form.tanggalPengajuanTo" x-init="flatpickr($refs.pengajuanTo, { dateFormat: 'Y-m-d' })" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-gray-50/70 p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="rounded-lg bg-white p-2 shadow-sm">
                                <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-md font-semibold text-gray-700">TMT Pensiun</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-md font-medium text-gray-600">Dari Tanggal</label>
                                <input type="date" name="tmt_pensiun_from" x-ref="tmtFrom" x-model="form.tmtPensiunFrom" x-init="flatpickr($refs.tmtFrom, { dateFormat: 'Y-m-d' })" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="mb-1 block text-md font-medium text-gray-600">Sampai Tanggal</label>
                                <input type="date" name="tmt_pensiun_to" x-ref="tmtTo" x-model="form.tmtPensiunTo" x-init="flatpickr($refs.tmtTo, { dateFormat: 'Y-m-d' })" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                            </div>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Jenis Pensiun</label>
                            <input type="hidden" name="selectedAt[id]" x-model="form.selectedAt.id">
                            <div class="relative w-full">
                                <div @click="openJenis = !openJenis"
                                    class="rounded-lg border border-gray-300 px-3 py-2 cursor-pointer bg-white flex justify-between items-center text-sm">
                                    <span x-text="selectedAt ? selectedAt.nama_layanan : 'Pilih Jenis Pensiun'"></span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <div x-show="openJenis"
                                    @click.outside="openJenis = false"
                                    class="absolute top-full left-0 mt-1 w-full bg-white border rounded-xl shadow-lg z-10">
                                    <ul class="max-h-40 overflow-y-auto">
                                        <template x-for="item in jenis_pensiun" :key="item.id">
                                            <li @click="selectJenis(item)" class="hover:bg-gray-100 cursor-pointer text-sm px-3 py-2" x-text="item.nama_layanan"></li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Status Akhir Usulan</label>
                            <input type="hidden" name="selectedStat[nama_status]" x-model="form.selectedStat.nama_status">
                            <div class="relative w-full">
                                <div @click="openStat = !openStat"
                                    class="rounded-lg border border-gray-300 px-3 py-2 cursor-pointer bg-white flex justify-between items-center text-sm">
                                    <span x-text="selectedStat ? selectedStat.status : 'Pilih Status'"></span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <div x-show="openStat"
                                    @click.outside="openStat = false"
                                    class="absolute top-full left-0 mt-1 w-full bg-white border rounded-xl shadow-lg z-10">
                                    <ul class="max-h-40 overflow-y-auto">
                                        <template x-for="item in Status" :key="item.nama_status">
                                            <li @click="selectStat(item)" class="hover:bg-gray-100 cursor-pointer text-sm px-3 py-2" x-text="item.status"></li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div> -->

                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Dalam Tahapan Proses</label>
                            <input type="hidden" name="selectedFase[urutan_step]" x-model="form.selectedFase.urutan_step">
                            <div class="relative w-full">
                                <div @click="openFase = !openFase"
                                    class="rounded-lg border border-gray-300 px-3 py-2 cursor-pointer bg-white flex justify-between items-center text-sm">
                                    <span x-text="selectedFase ? selectedFase.nama_step : 'Pilih Tahapan'"></span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <div x-show="openFase"
                                    @click.outside="openFase = false"
                                    class="absolute top-full left-0 mt-1 w-full bg-white border rounded-xl shadow-lg z-10">
                                    <ul class="max-h-40 overflow-y-auto">
                                        <template x-for="item in Fase" :key="item.urutan_step">
                                            <li @click="selectFase(item)" class="hover:bg-gray-100 cursor-pointer text-sm px-3 py-2" x-text="item.nama_step"></li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-end gap-2 pt-2">
                        <button type="button" @click="resetFilters()" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
                            Reset
                        </button>
                        <button type="submit" class="flex items-center justify-center gap-2 rounded-lg border border-blue-500 bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Cari Data</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="mt-3 bg-white rounded-xl shadow-sm border border-gray-200">

        <!-- Header -->
        <div class="p-6 border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    Laporan Rekap Proses Pensiun
                </h2>
                <button type="button" @click="exportExcel()"
                    class="inline-flex items-center gap-2 bg-blue-500 text-white rounded-lg px-4 py-2 text-xs hover:bg-blue-600">
                    <img src="{{ asset('icons/download.svg') }}" width="16" alt="Download" />
                    <span>Download Excel</span>
                </button>
            </div>
        </div>

        <!-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-5"> -->
        <div class="pl-6 pb-6 pr-6 border-b border-gray-200">
            <!--table-->
            <!-- <div class="table-responsive p-3"> -->
            <div class="overflow-x-auto border mt-3 border-gray-200 rounded-lg">

                <table class="table align-items-center mb-0 mt-3" id="myTable" width='100%'>
                    <thead class="bg-gray-200 text-gray-600">
                        <tr>
                            <th class="px-2 py-4 text-center">No</th>
                            <th class="px-2 py-2 text-center">No. Tiket<br>Tanggal Pengajuan</th>
                            <th class="px-2 py-2 text-center">Nama/NIP</th>
                            <th class="px-2 py-3 text-center">Pangkat/<br>Gol Ruang</th>
                            <th class="px-2 py-3 text-center">Unit Kerja</th>
                            <th class="px-2 py-3 text-center">Jenis Pensiun</th>
                            <th class="px-2 py-3 text-center">TMT Pensiun</th>
                            <th class="px-2 py-3 text-center">Tahapan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($rekap_pensiun) && count($rekap_pensiun) > 0)
                        @foreach($rekap_pensiun as $index => $item)
                        <tr>
                            <td class="px-2 py-3 text-center">{{ $index + 1 }}</td>
                            <td class="px-2 py-3">
                                <div>{{ $item->nomor_tiket }}</div>
                                <small>{{ $item->tgl_ajuan}}</small>
                            </td>
                            <td class="px-2 py-3">
                                <div>{{ $item->nama_pegawai }}</div>
                                <small>{{ str_replace(' ','',$item->nip) }}</small>
                            </td>
                            <td class="px-2 py-3 text-center">{{ $item->pangkat ?? '' }} ({{ $item->gol_ruang ?? '' }})</td>
                            <td class="px-2 py-3 text-center">{{ $item->skpd ?? '' }}</td>
                            <td class="px-2 py-3 text-center">{{ $item->nama_layanan ?? '' }}</td>
                            <td class="px-2 py-3 text-center">{{ $item->tmt_pensiun }}</td>
                            <td class="px-2 py-3 text-center">{{ $item->nama_step }} {{ strtolower($item->stats) }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function search() {
            return {
                jenis_pensiun: @json($jenis_pensiun),
                selectedAt: @json($selected),
                selectedStat: @json($selectedStat),
                selectedFase: @json($selectedFase),
                openJenis: false,
                openStat: false,
                openFase: false,
                Status: @json($status),
                Fase: @json($fase),
                form: {
                    tanggalPengajuanFrom: @json($mulai_tanggal ?? ''),
                    tanggalPengajuanTo: @json($sampai_tanggal ?? ''),
                    tmtPensiunFrom: @json($tmt_pensiun_from ?? ''),
                    tmtPensiunTo: @json($tmt_pensiun_to ?? ''),
                    selectedAt: @json($selected ?? null),
                    selectedStat: @json($selectedStat ?? null),
                    selectedFase: @json($selectedFase ?? null)
                },
                init() {
                    if (this.form.selectedAt && this.form.selectedAt.id) {
                        const selected = this.jenis_pensiun.find(item => item.id == this.form.selectedAt.id);
                        if (selected) {
                            this.selectedAt = selected;
                        }
                    }
                    if (this.form.selectedStat && this.form.selectedStat.nama_status) {
                        const selectStat = this.Status.find(item => item.nama_status == this.form.selectedStat.nama_status);
                        if (selectStat) {
                            this.selectedStat = selectStat;
                        }
                    }
                    if (this.form.selectedFase && this.form.selectedFase.urutan_step) {
                        const selectFase = this.Fase.find(item => item.urutan_step == this.form.selectedFase.urutan_step);
                        if (selectFase) {
                            this.selectedFase = selectFase;
                        }
                    }
                    $('#myTable').DataTable({
                        // dom: 'lfrtip',
                        buttons: [{
                            extend: 'excelHtml5',
                            className: 'buttons-excel',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }]
                    });

                },
                selectJenis(item) {
                    this.selectedAt = item;
                    this.form.selectedAt = item;
                    this.openJenis = false;
                },

                selectStat(item) {
                    this.selectedStat = item;
                    this.form.selectedStat = item;
                    this.openStat = false;
                },
                selectFase(item) {
                    this.selectedFase = item;
                    this.form.selectedFase = item;
                    this.openFase = false;
                },
                resetFilters() {
                    this.selectedAt = null;
                    this.selectedStat = null;
                    this.selectedFase = null;
                    this.form.tanggalPengajuanFrom = '';
                    this.form.tanggalPengajuanTo = '';
                    this.form.tmtPensiunFrom = '';
                    this.form.tmtPensiunTo = '';
                    this.form.selectedAt = null;
                    this.form.selectedStat = null;
                    this.form.selectedFase = null;
                },
                exportExcel() {
                    const table = $('#myTable').DataTable();
                    table.button('.buttons-excel').trigger();
                }
            }
        }
    </script>
</div>

@endsection