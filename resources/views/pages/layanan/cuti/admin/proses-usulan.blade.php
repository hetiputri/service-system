@extends('layouts.app')
@section('title', 'Pengajuan Cuti')
@section('content')

<div class="max-w-7xl mx-auto p-3">
    <!-- Card Container -->
    <div x-data="search()">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-2">

            <!-- Header -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    Filter Data Pegawai
                </h2>


                <!-- Search Section -->

                <div class="mt-6">
                    <!-- Filter Utama -->
                    <div class="flex flex-col md:flex-row gap-2 items-stretch md:items-center">

                        <div class="flex-2">
                            <input type="text" x-model="form.nip"
                                placeholder="NIP"
                                class="w-full pl-2 pr-1 mx-auto py-2 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs font-bold placeholder:font-bold">
                        </div>

                        <div class="flex-2">
                            <input type="text"
                                placeholder="Nama Pegawai" x-model="form.nama_pegawai"
                                class="w-full pl-2 pr-3 mx-auto py-2 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs font-bold placeholder:font-bold">
                        </div>

                        <div class="flex-2">
                            <x-tom-select class="" type="skpd" label-field="skpd" value-field="id_skpd" name="id_skpd" x-model="form.id_skpd" placeholder="Pilih SKPD" />
                        </div>

                        <div class="flex-1 flex gap-2">
                            <button type="button"
                                @click='caridata()'
                                class="flex w-auto whitespace-nowrap items-center gap-2 px-6 py-2 border border-blue-500 text-blue-500 rounded-lg text-xs">
                                <img src="icons/search.svg" width='20%' />
                                <span>
                                    Cari Data
                                </span>
                            </button>

                        </div>
                    </div>
                    <template x-if="searchError">
                        <p class="mt-3 text-sm text-red-600" role="alert" x-text="searchError"></p>
                    </template>
                </div>
            </div>
        </div>
        <div class="mt-3 bg-white rounded-xl shadow-sm border border-gray-200">

            <!-- Header -->
            <div class="p-6 border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    Daftar usulan Cuti
                </h2>
            </div>
            <div class="flex items-center gap-2 pl-6">

                <!-- BACK -->
                <a href="{{ url('proses-cuti/pegawai/'.$id) }}"
                    class="px-4 py-2 text-sm bg-green-300 hover:bg-green-300 rounded-lg transition">
                    ← Kembali
                </a>
            </div>

            <!-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-5"> -->
            <div class="pl-6 pb-6 pr-6 border-b border-gray-200">
                <!--table-->
                <!-- <div class="table-responsive p-3"> -->
                <div class="overflow-x-auto border mt-3 border-gray-200 rounded-lg">
                    <div class="flex justify-between items-center p-4 border-b bg-gray-50">

                        <div class="text-xs text-gray-600">
                            Show
                            <select x-model="perPage" @change="currentPage = 1" class="border rounded px-2 py-1 text-xs">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            entries
                        </div>

                        <div>
                            <input type="text"
                                x-model="tableSearch"
                                placeholder="Search..."
                                class="border px-3 py-1 text-xs rounded w-48">
                        </div>
                    </div>
                    <!-- <table class="table align-items-center mb-0" id="myTable" width='100%'>
                        <thead>
                            <tr>
                                <td width="5%" class='font-bold'>No</td>
                                <td width='18%' class='font-bold'>No. Tiket</td>
                                <td width='18%' class='font-bold'>Tanggal Pengajuan</td>
                                <td width='10%' class='font-bold'>Nama</td>
                                <td width='20%' class='font-bold'>NIP</td>
                                <td width='10%' class='font-bold'>OPD</td>
                                <td width='10%' class='font-bold'>&nbsp;</td>
                                <td width='10%' class='font-bold'>&nbsp;</td>
                                <td width='10%' class='font-bold'>&nbsp;</td>
                            </tr>
                        </thead> -->
                    <table class="min-w-full text-xs text-left">
                        <thead class="bg-gray-100 text-gray-600 uppercase">
                            <tr>
                                <th class="px-2 py-4 text-center">No</th>
                                <th class="px-2 py-2 text-center">No. Tiket</th>
                                <th class="px-2 py-2 text-center">Tanggal Pengajuan</th>
                                <th class="px-2 py-2 text-center">Nama</th>
                                <th class="px-2 py-2 text-center">NIP</th>
                                <th class="px-2 py-3 text-center">OPD</th>
                                <th class="px-1 py-3">&nbsp;</th>
                                <!-- <th class="px-1 py-2">&nbsp;</th>
                                <th class="px-1 py-2">&nbsp;</th> -->

                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            <!-- <tbody> -->
                            <template x-for='(data,index) in paginatedArrays' :key=data.id>
                                <tr>
                                    <td class="text-center px-2 py-3" x-text="index+1"></td>
                                    <td class="w-30 px-4 py-3 text-center" x-text="data.nomor_tiket"></td>
                                    <td class="w-30 px-3 py-3 text-center" x-text="data.tanggal_ajuan"></td>
                                    <td class="w-40 px-4 py-6" x-text="data.nama_pegawai"></td>
                                    <td class="w-40 px-2 py-4" x-text="data.nip"></td>
                                    <td class="px-3 py-4 break-words whitespace-normal" x-text="data.skpd"></td>
                                    <td class="w-25 px-1 py-3 text-center">
                                        <button type="button"
                                            @click='detail(data.id,idProses)'
                                            class="border border-blue-500 px-3 py-1 bg-blue-500 text-white rounded text-xs items-center">
                                            <span>
                                                Detail
                                            </span>
                                        </button>
                                    </td>
                                    <!-- <td class="py-2">
                                        <button type="button"
                                            @click='terima(data.id)'
                                            class="border border-yellow-500 px-3 py-1 bg-yellow-600 text-white rounded text-xs">
                                            <span>
                                                Terima
                                            </span>
                                        </button>
                                    </td>
                                    <td class="py-2">
                                        <button type="button"
                                            @click='tolak(data.id)'
                                            class="border border-red-500 px-3 py-1 bg-red-600 text-white rounded text-xs">
                                            <span>
                                                Tolak
                                            </span>
                                        </button>
                                    </td> -->
                                </tr>
                            </template>

                        </tbody>
                    </table>
                    <!-- Pagination Controls -->
                    <div class="flex justify-between items-center p-4 border-t text-xs">

                        <div>
                            Total: <span x-text="totalData"></span> data
                        </div>

                        <div class="flex items-center gap-2">
                            <button @click="changePage(currentPage - 1)"
                                :disabled="currentPage === 1"
                                class="px-3 py-1 border rounded disabled:opacity-50">
                                Prev
                            </button>

                            <span>
                                Page <span x-text="currentPage"></span>
                                of <span x-text="totalPages"></span>
                            </span>

                            <button @click="changePage(currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                class="px-3 py-1 border rounded disabled:opacity-50">
                                Next
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function search() {
            return {
                idProses: @json($id),
                arrays: @json($arrays),
                currentPage: 1,
                tableSearch: '',
                perPage: 10,
                searchError: '',
                form: {
                    nip: '',
                    nama_pegawai: '',
                    id_skpd: '',
                },
                init() {

                    this.$watch('perPage', () => {
                        this.currentPage = 1;
                    });

                    this.$watch('tableSearch', () => {
                        this.currentPage = 1;
                    });
                },
                async caridata() {
                    this.searchError = '';

                    try {
                        const response = await fetch('{{ url("pencarian/cuti-usulan") }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.form)
                        });

                        let data = null;
                        try {
                            data = await response.json();
                        } catch {
                            // A non-JSON response is handled below.
                        }

                        if (!response.ok) {
                            throw new Error(data?.message || `Search failed (HTTP ${response.status}).`);
                        }

                        if (data?.success === false) {
                            throw new Error(data.message || 'Search failed.');
                        }

                        if (!Array.isArray(data?.data)) {
                            throw new Error('The server returned an invalid search response.');
                        }

                        this.arrays = data.data;
                        this.currentPage = 1;
                    } catch (error) {
                        console.error('Failed to search leave requests:', error);
                        this.searchError = error instanceof Error
                            ? error.message
                            : 'Unable to search. Please try again.';
                    }
                },
                get filteredResults() {
                    if (!this.tableSearch) return this.arrays;

                    const keyword = this.tableSearch.toLowerCase();

                    return this.arrays.filter(item =>
                        Object.values(item).some(value =>
                            String(value ?? '').toLowerCase().includes(keyword)
                        )
                    );
                },
                get totalData() {
                    return this.filteredResults.length;
                },
                get totalPages() {
                    return Math.ceil(this.totalData / Number(this.perPage)) || 1;
                },
                get paginatedArrays() {
                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + Number(this.perPage);
                    return this.filteredResults.slice(start, end);
                },
                changePage(page) {
                    if (page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                },
                detail(id, idProses) {
                    document.location.href = "{{ route('detail.usulan') }}?id=" + id + "&idp=" + idProses;
                },
                terima(id) {
                    //masuk ke proses utama
                },
                tolak(id) {
                    //balikkan keadaan ke current_step 1 dengan aksi 'direvisi' dengan catatan
                }



            }
        }
    </script>
</div>
@endsection