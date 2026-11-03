@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div x-data="search()">
    <div class="max-w-7xl mx-auto p-4">
        <!-- Card Container -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">

            <!-- Header -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    Filter Admin Pensiun
                </h2>


                <!-- Search Section -->
                <div class="mt-6">
                    <!-- Filter Utama -->
                    <form action="{{ route('pensiun.spv') }}" method="POST">
                        @csrf
                        <div class="flex flex-col md:flex-row gap-2 items-stretch md:items-center">

                            <select name="id_admin" id="id_admin" class="w-full pl-2 pr-1 mx-auto py-2 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs font-bold placeholder:font-bold">
                                <option value="">Semua Admin</option>
                                @if(isset($admin))
                                @foreach($admin as $adm)

                                <option value="{{ $adm->id_pegawai }}" {{ $adm->id_pegawai==$id_admin ? 'selected':''}}>{{ $adm->nama_pegawai }} - {{ $adm->nip }}</option>
                                @endforeach
                                @endif
                            </select>
                            <!-- <div class="flex-2">
                                <input type="text" name="nip" x-model="form.nip"
                                    placeholder="NIP"
                                    class="w-full pl-2 pr-1 mx-auto py-2 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs font-bold placeholder:font-bold">
                            </div>

                            <div class="flex-2">
                                <input type="text"
                                    placeholder="Nama Pegawai" name="nama_pegawai" x-model="form.nama_pegawai"
                                    class="w-full pl-2 pr-3 mx-auto py-2 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs font-bold placeholder:font-bold">
                            </div> -->

                            <div class="flex-1 flex gap-2">
                                <button type="submit"
                                    class="flex w-auto whitespace-nowrap items-center gap-2 px-6 py-2 border border-blue-500 text-blue-500 rounded-lg text-xs">
                                    <img src="icons/search.svg" width='20%' />
                                    <span>
                                        Cari Data
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto p-4">

        <!-- Card Container -->
        <div class="mt-3 bg-white rounded-xl shadow-sm border border-gray-200">

            <!-- Header -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    Pembagian Wilayah Kerja Pensiun
                </h2>
                @if(session('message'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="bg-green-200 p-3 mb-3 text-sm">
                    {{ session('message') }}
                </div>
                @endif

                <div class="mt-3">
                    <div class="flex justify-end ">
                        <button type=" button"
                            @click='add_user()'
                            class="flex w-35 whitespace-nowrap items-center gap-2 px-4 py-2 border border-black text-black-500 rounded-lg text-xs">
                            <img src="icons/plus.svg" width='15%' /><span>Tambah Data</span>
                        </button>
                    </div>
                    <div
                        x-show="addUsers"
                        style="background-color:rgb(0,0,0,0.5);display:none"
                        class="fixed inset-0 z-[99999] flex items-center justify-center overflow-y-auto bg-black/50">
                        <div
                            class="bg-white rounded-xl shadow-lg p-6 ms-4 w-200 overflow-y-auto max-h-120"
                            :class="addUser ? 'bg-white rounded-xl shadow-lg p-6 ms-4 w-250 overflow-y-auto' : ''"
                            @click.outside="addUsers = false">
                            <!-- Form -->

                            <div x-data="formTambah()">
                                <div class="border-1 border-blue-200 p-3 w-full rounded-lg mt-3 mb-2">
                                    <h2 class="text-lg font-bold"><span x-text="formname"></span> Wilayah Kerja Admin </h2>
                                </div>

                                <h4 class="mt-4">Nama dan NIP Pegawai</h4>
                                <!-- Selected / Placeholder -->
                                <div @click="open = !open"
                                    class="rounded-xl shadow-lg w-150 border px-5 py-2 cursor-pointer bg-white flex justify-between items-center">
                                    <span x-text="selected ? selected.nama_pegawai+ ' - ' +selected.nip : 'Pilih Pegawai'"></span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <!-- Dropdown -->
                                <div x-show="open" @click.outside="open=false"
                                    class="absolute mt-1 w-150 bg-white border rounded shadow z-10">

                                    <!-- Search input -->
                                    <input type="text"
                                        x-model="search"
                                        placeholder="Search..."
                                        class="w-150 px-3 py-2 border-b focus:outline-none">

                                    <!-- Options -->
                                    <ul class="max-h-40 overflow-y-auto w-150">
                                        <template x-for="item in filtered()" :key="item.id_pegawai">
                                            <li @click="select(item)"
                                                class="hover:bg-gray-100 w-150 cursor-pointer text-sm px-5 py-2 cursor-pointer bg-white flex justify-between"
                                                x-text="item.nama_pegawai+ ' - ' +item.nip">
                                            </li>
                                        </template>

                                        <template x-if="filtered().length === 0">
                                            <li class="px-3 py-2 text-gray-500">Tidak Ada Data</li>
                                        </template>
                                    </ul>
                                </div>
                                <!-- Hidden input for form submit -->
                                <input type="hidden" name="admin_id" x-model="idx">

                                <h4 class="mt-6">Wilayah Kerja SKPD</h4>
                                <!-- Selected / Placeholder -->
                                <div @click="openSkpd = !openSkpd"
                                    class="rounded-xl shadow-lg w-150 border px-5 py-2 cursor-pointer bg-white flex justify-between items-center">
                                    <span x-text="selectedSkpd ? selectedSkpd.skpd : 'Pilih SKPD'"></span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <!-- Dropdown -->
                                <div x-show="openSkpd" @click.outside="openSkpd=false"
                                    class="absolute mt-1 w-150 bg-white border rounded shadow z-10">

                                    <!-- Search input -->
                                    <input type="text"
                                        x-model="searchSkpd"
                                        placeholder="Search..."
                                        class="w-150 px-3 py-2 border-b focus:outline-none">

                                    <!-- Options -->
                                    <ul class="max-h-40 overflow-y-auto w-150">
                                        <template x-for="item2 in filteredSkpd()" :key="item2.id_skpd">
                                            <li @click="selectSkpd(item2)"
                                                class="hover:bg-gray-100 w-150 cursor-pointer text-sm px-5 py-2 cursor-pointer bg-white flex justify-between"
                                                x-text="item2.skpd">
                                            </li>
                                        </template>

                                        <template x-if="filteredSkpd().length === 0">
                                            <li class="px-3 py-2 text-gray-500">Tidak Ditemukan SKPD</li>
                                        </template>
                                    </ul>
                                </div>
                                <!-- Hidden input for form submit -->
                                <input type="hidden" name="kode_skpd" x-model="selected.kode_skpd">

                                <div class="flex gap-2 mt-6 mb-3">
                                    <button
                                        type="button"
                                        @click="simpan()"
                                        class="bg-green-500 text-white px-4 py-2 rounded">
                                        Simpan
                                    </button>
                                    <button
                                        type="button"
                                        @click="addUsers=false"
                                        class="bg-gray-300 px-4 py-2 rounded">
                                        Batalkan
                                    </button>
                                </div>
                            </div>
                            <!-- </form> -->
                        </div>
                    </div>



                    <div class="overflow-x-auto border mt-3 border-gray-200 rounded-lg">
                        <table id="tableAdmin" class="min-w-full text-xs text-left">
                            <thead class="bg-gray-100 text-gray-600 uppercase">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">NIP</th>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">SKPD</th>
                                    <th class="px-4 py-3">&nbsp;</th>
                                    <th class="px-4 py-3">&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">

                                @forelse($kerja as $i => $row)

                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $i+1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col">
                                            {{ str_replace(' ','',$row->nip) }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $row->nama_pegawai }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $row->skpd }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <button @click="edit({{ $row->id }})"
                                            class="border border-blue-500 px-3 py-1 bg-blue-600 text-white rounded text-xs">
                                            Edit
                                        </button>
                                    </td>
                                    <td class="px-4 py-3">
                                        <button @click="hapus({{ $row->id }})"
                                            class="border border-red-500 px-3 py-1 bg-red-500 text-white rounded text-xs">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                @empty

                                @endforelse


                            </tbody>
                        </table>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


<!-- Toggle Script -->
<script>
    function formTambah() {
        return {
            pegawai_id: '',
            kode_skpd: '',
            open: false,
            openSkpd: false,
            search: '',
            searchSkpd: '',
            options: @json($allpegawai),
            optionSkpd: @json($skpd),

            selectSkpd(item) {
                this.selectedSkpd = item
                this.openSkpd = false
                this.searchSkpd = ''
            },
            filtered() {
                return this.options.filter(o =>
                    o.nama_pegawai.toLowerCase().includes(this.search.toLowerCase())
                )
            },
            filteredSkpd() {
                return this.optionSkpd.filter(o =>
                    o.skpd.toLowerCase().includes(this.searchSkpd.toLowerCase())
                )
            },
            select(item) {
                this.selected = item
                this.open = false
                this.search = ''
            },
            async simpan() {
                try {
                    const response = await fetch(`{{ url('/spv-pensiun/assign-admin') }}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            id: this.idx,
                            pegawai: this.selected,
                            skpd: this.selectedSkpd
                        })
                    });

                    const data = await response.json();
                    if (data.success == true) {
                        this.message = data.message ?? '';
                        this.selectedSkpd = null;
                        this.selected = null;
                        this.addUsers = false;
                        //alert(this.message);
                        Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: this.message,
                                buttons: true,
                                dangerMode: true,
                            })
                            .then((willOk) => {
                                if (willOk) {
                                    location.reload();
                                }
                            });
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error(error);
                    alert("Terjadi kesalahan jaringan");
                }
            },
        }
    }


    function search() {
        return {
            form: {
                nip: '',
                nama_pegawai: '',

            },
            init() {
                $('#tableAdmin').DataTable({});
            },
            add_user() {
                this.pegawai_id = '';
                this.idx = '';
                this.selected = null;
                this.selectedSkpd = null;
                this.formname = 'Tambah';
                this.addUsers = true;
            },
            edit(id) {
                fetch(`{{ url('/spv-pensiun/data') }}/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        this.idx = data.id;
                        this.formname = 'Edit';
                        this.selected = data;
                        this.selectedSkpd = data;
                        this.addUsers = true;
                    });

            },
            hapus(id) {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Anda yakin ingin menghapus Admin dan SKPD ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('/spv-pensiun/admin-hapus') }}/${id}`)
                            .then(res => res.json())
                            .then(data => {
                                Swal.fire(
                                    'Berhasil!',
                                    data.message,
                                    'success'
                                )
                                location.reload();
                            });

                    }
                });
            },
            reset() {
                this.form.nip = '';
                this.form.nama_pegawai = '';
                document.querySelector('form').submit();
            },
        }
    }
</script>


@endsection