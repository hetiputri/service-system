@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')


<div class="max-w-7xl mx-auto p-4">

    <!-- Card Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                Pencarian Data Pegawai
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Informasi Lengkap Data Pegawai
            </p>
        </div>

        <!-- Search Section -->
        <div class="p-6" x-data="simpegSearch()">

            <!-- Filter Utama -->
            <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">

                <div class="flex-1">
                    <input type="text" x-model="form.nip"
                        placeholder="NIP"
                        class="w-full pl-2 pr-1 py-2.5 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs font-bold placeholder:font-bold">
                </div>

                <div class="flex-1">
                    <input type="text"
                        placeholder="Nama Pegawai" x-model="form.nama_pegawai"
                        class="w-full pl-2 pr-1 py-2.5 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs font-bold placeholder:font-bold">
                </div>

                <div class="flex-1">
                    <x-tom-select class="" type="skpd" label-field="skpd" value-field="id_skpd" name="id_skpd" x-model="form.id_skpd" placeholder="Pilih Jenis SKPD" />
                </div>

                <!-- Tombol Cari (Muncul hanya jika advanced TERTUTUP) -->
                <button type="button" x-show="!openAdvanced" @click="search()" class="px-5 py-2.5 bg-blue-600 text-white text-xs rounded-lg">
                    <span x-show="!loading">Cari Data</span>
                    <span x-show="loading">Loading...</span>
                </button>

                <!-- Toggle Button -->
                <button
                    @click="openAdvanced = !openAdvanced"
                    class="px-5 py-2.5 border border-gray-300 hover:bg-gray-100 text-gray-700 text-xs  font-medium rounded-lg flex items-center gap-2">

                    <svg class="w-4 h-4 transition-transform duration-300"
                        :class="{ 'rotate-180': openAdvanced }"
                        fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 9l-7 7-7-7" />
                    </svg>

                    <span x-text="openAdvanced ? 'Tutup Filter' : 'Selengkapnya'"></span>
                </button>

            </div>

            <!-- Advanced Filter -->
            <div x-show="openAdvanced"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="mt-6 pt-6 border-t border-gray-200">

                <h3 class="text-sm font-semibold text-gray-700 mb-4">
                    Filter Pencarian Data Pegawai
                </h3>

                <!-- Grid filter kamu tetap di sini -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- isi filter lanjutan -->

                    <!-- Jabatan Pelaksana -->
                    <x-tom-select
                        type="jabatan-pelaksana"
                        label-field="jabatan"
                        value-field="id_jabatan"
                        name="id_jabatan_pelaksana"
                        x-model="form.id_jabatan_pelaksana"
                        placeholder="Pilih jabatan pelaksana" />

                    <!-- Jenis Kelamin -->
                    <x-tom-select class=""
                        type="jenis-kelamin"
                        label-field="jenis_kelamin"
                        value-field="id_jenis_kelamin"
                        name="id_jenis_kelamin"
                        x-model="form.id_jenis_kelamin"
                        placeholder="Pilih Jenis Kelamin" />

                    <!-- Golongan -->
                    <x-tom-select class=""
                        type="pangkat"
                        label-field="pangkat"
                        value-field="id_pangkat"
                        name="id_pangkat"
                        x-model="form.id_pangkat"
                        placeholder="Pilih Pangkat/Golongan" />

                    <!-- Agama -->
                    <x-tom-select class=""
                        type="agama"
                        label-field="agama"
                        value-field="id_agama"
                        name="id_agama"
                        x-model="form.id_agama"
                        placeholder="Pilih Agama" />

                    <!-- Eselon -->
                    <x-tom-select class=""
                        type="eselon"
                        label-field="eselon"
                        value-field="id_eselon"
                        name="id_eselon"
                        x-model="form.id_eselon"
                        placeholder="Pilih eselon" />

                    <!-- Jenis Jabatan -->
                    <x-tom-select class=""
                        type="jenis-jabatan"
                        label-field="tipe_jabatan"
                        value-field="id_tipe_jabatan"
                        name="id_tipe_jabatan"
                        x-model="form.id_tipe_jabatan"
                        placeholder="Pilih Jenis Jabatan" />

                    <!-- Pendidikan -->
                    <div class="grid grid-cols-2 gap-0 w-full">
                        <x-tom-select
                            class="col-span-1 min-w-0"
                            type="status-tk-pendidikan"
                            label-field="nama_stat"
                            value-field="id_stat"
                            name="status_tk_pendidikan"
                            default-first="true"
                            x-model="form.status_tk_pendidikan" />

                        <x-tom-select
                            class="col-span-1 min-w-0"
                            type="tingkat-pendidikan"
                            label-field="tk_pendidikan"
                            value-field="kode_pendidikan"
                            name="kode_pendidikan"
                            x-model="form.tingkat_pendidikan"
                            placeholder="Pilih Tingkat Pendidikan" />
                    </div>



                    <!-- Jenjang Jabatan -->
                    <!-- <select class="w-full p1-10 pr-4 py-2.5 border border-unded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs">
                        <option>Jenjang Jabatan</option>
                    </select> -->
                    <x-tom-select class=""
                        type="jenjang-jabatan"
                        label-field="jenjang_jabatan"
                        value-field="id_jenjang_jabatan"
                        name="id_jenjang_jabatan"
                        x-model="form.id_jenjang_jabatan"
                        placeholder="Pilih Jenjang Jabatan" />

                    <!-- Nama Sekolah -->
                    <div class="grid grid-cols-1 gap-0 w-full">
                        <!-- <x-tom-select
                            class="col-span-1 min-w-0"
                            type="status-tk-pendidikan"
                            label-field="nama_stat"
                            value-field="id_stat"
                            name="status_nama_sekolah"
                            default-first="true"
                            x-model="form.status_nama_sekolah" /> -->
                        <input type="text" placeholder="Nama Sekolah" name="nama_sekolah" x-model="form.nama_sekolah"
                            class="w-full pl-2 pr-1 py-2.5 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs font-bold placeholder:font-bold">
                    </div>
                    <!-- Nama Jabatan -->
                    <!-- <select class="w-full p1-10 pr-4 py-2.5 border border-unded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs">
                        <option>Nama Jabatan Dari Semua Jabatan</option>
                    </select> -->
                    <input type="text" placeholder="Nama Jabatan dari Semua Jabatan" name="nama_jabatan" x-model="form.nama_jabatan"
                        class="w-full pl-2 pr-1 py-2.5 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs font-bold placeholder:font-bold">

                    <div class="grid grid-cols-1 gap-0 w-full">
                        <!-- <x-tom-select
                            class="col-span-1 min-w-0"
                            type="status-tk-pendidikan"
                            label-field="nama_stat"
                            value-field="id_stat"
                            name="status_jurusan"
                            default-first="true"
                            x-model="form.status_jurusan" /> -->
                        <!-- Jurusan -->
                        <input type="text" placeholder="Jurusan" name="jurusan" x-model="form.jurusan"
                            class="w-full pl-2 pr-1 py-2.5 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs font-bold placeholder:font-bold">
                    </div>
                    <!-- Jabatan Struktural -->
                    <x-tom-select
                        type="jabatan-struktural"
                        label-field="jabatan"
                        value-field="id_jabatan"
                        name="id_jabatan_struktural"
                        x-model="form.id_jabatan_struktural"
                        placeholder="Pilih Jabatan Struktural" />

                    <!-- Kedudukan PNS -->
                    <x-tom-select class=""
                        type="kedudukan-pegawai"
                        label-field="kedudukan_kepegawaian"
                        value-field="id_kedudukan_kepegawaian"
                        x-model="form.id_kedudukan_kepegawaian"
                        name="id_kedudukan_kepegawaian"
                        placeholder="Pilih Jenis Kedudukan Pegawai" />

                    <!-- Jabatan Fungsional -->
                    <x-tom-select class=""
                        type="jabatan-fungsional"
                        label-field="jabatan"
                        value-field="id_jabatan"
                        name="id_jabatan_fungsional"
                        x-model="form.id_jabatan_fungsional"
                        placeholder="Pilih Jabatan Fungsional" />

                    <!-- Status Kepegawaian -->
                    <x-tom-select class=""
                        type="status-pegawai"
                        label-field="status_kepegawaian"
                        value-field="id_status_kepegawaian"
                        name="id_status_pegawai"
                        x-model="form.id_status_pegawai"
                        placeholder="Pilih Status Kepegawaian" />

                    <!-- Status Kepegawaian -->
                    <select id="jenis_jabatan_struktural" x-model="form.jenis_jabatan_struktural" name="jenis_jabatan_struktural" class="w-full font-bold placeholder:font-bold">
                        <option value="">Pilih Jenis Jabatan Struktural</option>
                        <option value="JPT Pratama">JPT Pratama</option>
                        <option value="Administrator">Administrator</option>
                        <option value="Pengawas">Pengawas</option>
                    </select>


                </div>

                <!-- Tombol Cari (Muncul hanya jika advanced TERBUKA) -->
                <div class="mt-6 flex justify-end">
                    <button
                        x-show="openAdvanced" @click="search()"
                        x-transition
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs  font-medium rounded-lg flex items-center gap-2">
                        Cari Data
                    </button>
                </div>
            </div>
            <!-- ================= HASIL PENCARIAN ================= -->
            <div class="mt-8">

                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    Hasil Pencarian
                </h3>

                <div class="overflow-x-auto border border-gray-200 rounded-lg">
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
                    <table class="min-w-full table-fixed text-xs text-left">
                        <thead class="bg-gray-100 text-gray-600 uppercase">
                            <tr>
                                <th class="px-4 py-3 w-12">No</th>
                                <th class="px-4 py-3 w-1/5">Pegawai</th>
                                <th class="px-4 py-3">Pangkat/Gol</th>
                                <th class="px-4 py-3">OPD</th>
                                <th class="px-4 py-3 w-16">JK</th>
                                <th class="px-4 py-3 w-20">Profil</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            <!-- Loading -->
                            <tr x-show="loading">
                                <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                    Sedang memuat data...
                                </td>
                            </tr>

                            <!-- Tidak Ada Data -->
                            <tr x-show="!loading && results.length === 0">
                                <td colspan="8" class="px-4 py-6 text-center text-gray-400">
                                    Data tidak ditemukan
                                </td>
                            </tr>

                            <!-- Loop Data -->
                            <!-- <template x-for="(item, index) in results" :key="item.id_pegawai"> -->
                            <template x-for="(item, index) in paginatedResults" :key="item.id_pegawai">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3"
                                        x-text="(currentPage - 1) * perPage + index + 1"></td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-gray-800"
                                                x-text="item.nama_pegawai ?? '-'"></span>
                                            <span class="text-xs text-gray-500"
                                                x-text="item.nip ?? '-' "></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-gray-800"
                                                x-text="item.pangkat ?? '-'"></span>
                                            <span class="text-xs text-gray-500"
                                                x-text="item.gol_ruang ?? '-'"></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-gray-800"
                                                x-text="item.jabatan ?? '-'"></span>
                                            <span class="text-xs text-gray-500"
                                                x-text="item.unit_kerja ?? '-'"></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3" x-text="item.jenis_kelamin ?? '-'"></td>
                                    <td class="px-4 py-3">
                                        <a :href="`{{ url('') }}/pegawai/${item.id_pegawai}/profile`" target="_blank"
                                            class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                                            Lihat
                                        </a>
                                    </td>
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
            <!-- =================================================== -->

        </div>







        <!-- Toggle Script -->
        <script>
            new TomSelect("#jenis_jabatan_struktural", {
                create: false,
                sortField: {
                    field: "text",
                    // direction: "asc"
                },
                render: {
                    option: function(data, escape) {
                        return `<div><strong>${escape(data.text)}</strong></div>`;
                    },
                    item: function(data, escape) {
                        return `<div><strong>${escape(data.text)}</strong></div>`;
                    }
                }
            });

            function toggleFilter() {
                const mainFilter = document.getElementById('mainFilter');
                const advancedFilter = document.getElementById('advancedFilter');

                if (advancedFilter.classList.contains('hidden')) {
                    // Tampilkan advanced
                    advancedFilter.classList.remove('hidden');

                    // Sembunyikan main
                    mainFilter.classList.add('hidden');
                } else {
                    // Sembunyikan advanced
                    advancedFilter.classList.add('hidden');

                    // Tampilkan main
                    mainFilter.classList.remove('hidden');
                }
            }


            function simpegSearch() {
                return {
                    openAdvanced: false,
                    results: [],
                    loading: false,
                    tableSearch: '',
                    currentPage: 1,
                    perPage: 10,

                    form: {
                        nip: '',
                        nama_pegawai: '',
                        id_skpd: '',
                        id_jabatan_pelaksana: '',
                        id_jabatan_fungsional: '',
                        id_jenis_kelamin: '',
                        id_jenjang_jabatan: '',
                        //status_nama_sekolah: '',
                        nama_sekolah: '',
                        nama_jabatan: '',
                        //status_jurusan: '',
                        jurusan: '',
                        id_pangkat: '',
                        id_agama: '',
                        id_eselon: '',
                        tingkat_pendidikan: '',
                        status_tk_pendidikan: '1',
                        id_tipe_jabatan: '',
                        id_jabatan_struktural: '',
                        id_status_pegawai: '',
                        id_kedudukan_kepegawaian: '',
                        jenis_jabatan_struktural: '',
                    },

                    init() {
                        this.$watch('perPage', () => {
                            this.currentPage = 1;
                        });

                        this.$watch('tableSearch', () => {
                            this.currentPage = 1;
                        });
                    },

                    get filteredResults() {
                        if (!this.tableSearch) return this.results;

                        const keyword = this.tableSearch.toLowerCase();

                        return this.results.filter(item =>
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

                    get paginatedResults() {
                        const start = (this.currentPage - 1) * this.perPage;
                        const end = start + Number(this.perPage);
                        return this.filteredResults.slice(start, end);
                    },

                    async search() {
                        this.loading = true;
                        this.currentPage = 1;
                        //console.log(JSON.stringify(this.form));
                        try {
                            const response = await fetch("{{ route('simpeg.ajax.search') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify(this.form)
                            });

                            const data = await response.json();
                            this.results = data.results ?? [];

                        } catch (error) {
                            console.error(error);
                            alert("Terjadi kesalahan jaringan");
                        }

                        this.loading = false;
                    },

                    changePage(page) {
                        if (page >= 1 && page <= this.totalPages) {
                            this.currentPage = page;
                        }
                    }
                }
            }
        </script>


        @endsection