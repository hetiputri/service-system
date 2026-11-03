@extends('layouts.app')
@section('title', 'Pengajuan Cuti')
@section('content')



<div x-data="search()">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-2">

        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                Filter Data Pencarian
            </h2>


            <!-- Search Section -->

            <div class="mt-6">
                <form action="{{ route('rekap.cuti') }}" method="POST">
                    @csrf
                    <!-- Filter Utama -->
                    <div class="flex flex-col md:flex-row gap-7 items-stretch md:items-center py-2">
                        <div x-data="{ dariTanggal: '' }" class="flex-2">
                            <h4 class='mt-5'>Dari Tanggal</h4>
                            <input
                                type="date"
                                name="dariTanggal"
                                x-ref="datepicker"
                                x-model="form.dariTanggal"
                                x-init="
                                flatpickr($refs.datepicker, {
                                    dateFormat: 'Y-m-d'
                                })
                            "
                                class="border p-2 w-full rounded-lg text-xs">
                        </div>

                        <div x-data="{ sampaiTanggal: '' }" class="flex-2">
                            <h4 class='mt-5'>Sampai Tanggal</h4>
                            <input
                                type="date"
                                name="sampaiTanggal"
                                x-ref="datepicker"
                                x-model="form.sampaiTanggal"
                                x-init="
                                flatpickr($refs.datepicker, {
                                    dateFormat: 'Y-m-d'
                                })
                            "
                                class="border p-2 w-full rounded-lg text-xs">
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-7 items-stretch md:items-center py-2">
                        <div class="flex-2">
                            <h4>Jenis Cuti</h4>
                            <input type="hidden" name="selectedAt[id]" x-model="form.selectedAt.id">
                            <div class="relative w-full">
                                <div @click="openJenis = !openJenis"
                                    class="rounded-xl border px-5 py-2 cursor-pointer bg-white flex justify-between items-center text-xs">
                                    <span x-text="selectedAt ? selectedAt.nama_layanan : 'Pilih Jenis Cuti'"></span>

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <div x-show="openJenis"
                                    @click.outside="openJenis = false"
                                    class="absolute top-full left-0 mt-1 w-full bg-white border rounded-xl shadow-lg z-10">

                                    <ul class="max-h-40 overflow-y-auto">
                                        <template x-for="item in jenis_cuti" :key="item.id">
                                            <li @click="selectJenis(item)"
                                                class="hover:bg-gray-100 cursor-pointer text-sm px-5 py-2"
                                                x-text="item.nama_layanan">
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="flex-2">
                            <h4 class='mt-5'>Status Akhir Usulan</h4>
                            <input type="hidden" name="selectedStat[nama_status]" x-model="form.selectedStat.nama_status">
                            <div class="relative w-full">
                                <div @click="openStat = !openStat"
                                    class="rounded-xl border px-5 py-2 cursor-pointer bg-white flex justify-between items-center text-xs">
                                    <span x-text="selectedStat ? selectedStat.status : 'Pilih Status'"></span>

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <div x-show="openStat"
                                    @click.outside="openStat = false"
                                    class="absolute top-full left-0 mt-1 w-full bg-white border rounded-xl shadow-lg z-10">

                                    <ul class="max-h-40 overflow-y-auto">
                                        <template x-for="item in Status" :key="item.nama_status">
                                            <li @click="selectStat(item)"
                                                class="hover:bg-gray-100 cursor-pointer text-sm px-5 py-2"
                                                x-text="item.status">
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="flex-1 flex gap-2 mt-5">
                        <button type="submit"
                            class="flex w-auto whitespace-nowrap items-center gap-2 px-6 py-2 border border-blue-500 text-blue-500 rounded-lg text-xs">
                            <img src="icons/search.svg" width='20%' />
                            <span>
                                Cari Data
                            </span>
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
                    Laporan Rekap Proses Cuti
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
                            <th class="px-2 py-3 text-center">Jabatan</th>
                            <th class="px-2 py-3 text-center">OPD</th>
                            <th class="px-2 py-3 text-center">Jenis Cuti</th>
                            <th class="px-2 py-3 text-center">Rentang Cuti</th>
                            <th class="px-2 py-3 text-center">Nama Proses</th>
                            <th class="px-2 py-3 text-center">Status Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($rekap_cuti))
                        @foreach($rekap_cuti as $index => $item)
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
                            <td class="px-2 py-3 text-center">{{ $item->jabatan }}</td>
                            <td class="px-2 py-3 text-center">{{ $item->skpd ?? '' }}</td>
                            <td class="px-2 py-3 text-center">{{ $item->nama_layanan ?? '' }}</td>
                            <td class="px-2 py-3 text-center">
                                {{ $item->tgl_mulai }}@if($item->tgl_mulai && $item->tgl_selesai) s.d @endif {{ $item->tgl_selesai }}
                            </td>
                            <td class="px-2 py-3 text-center">{{ $item->nama_proses }}</td>
                            <td class="px-2 py-3 text-center">{{ strtolower($item->status_akhir) }}</td>
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
                jenis_cuti: @json($jenis_cuti),
                selectedAt: null,
                selectedStat: null,
                openJenis: false,
                openStat: false,
                Status: @json($status),
                form: {
                    dariTanggal: @json($mulai_tanggal),
                    sampaiTanggal: @json($sampai_tanggal),
                    selectedAt: @json($selected),
                    selectedStat: @json($selectedStat)
                },
                init() {
                    if (this.form.selectedAt && this.form.selectedAt.id) {
                        const selected = this.jenis_cuti.find(item => item.id == this.form.selectedAt.id);
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
                exportExcel() {
                    const table = $('#myTable').DataTable();
                    table.button('.buttons-excel').trigger();
                }
            }
        }
    </script>
</div>

@endsection