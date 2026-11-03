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

                        <div class="flex-1 flex gap-2">
                            <button type="button"
                                @click='caridata()'
                                class="flex w-auto whitespace-nowrap items-center gap-2 px-6 py-2 border border-blue-500 text-blue-500 rounded-lg text-xs">
                                <img src="icons/search.svg" width='20%' />
                                <span>
                                    Cari Data
                                </span>
                            </button>
                            <button type="button"
                                @click='reset()'
                                class="flex w-auto whitespace-nowrap items-center gap-2 px-6 py-2 border border-black text-black-500 rounded-lg text-xs">
                                <span>Reset</span>
                            </button>
                        </div>
                    </div>
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
                    Pengaturan Akun
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
                            class="flex w-40 whitespace-nowrap items-center gap-2 px-4 py-2 border border-black text-black-500 rounded-lg text-xs">
                            <img src="icons/plus.svg" width='15%' /><span>Tambah Pengguna</span>
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
                                    <h2 class="text-lg font-bold">Tambah Hak Akses Pengguna </h2>
                                </div>

                                <h4 class="mt-4">Nama dan NIP Pengguna</h4>
                                <!-- Selected / Placeholder -->
                                <div @click="open = !open"
                                    class="rounded-xl shadow-lg w-150 border px-5 py-2 cursor-pointer bg-white flex justify-between items-center">
                                    <span x-text="selected ? selected.nama_pegawai+ ' - ' +selected.nip : 'Pilih Pengguna'"></span>
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
                                        <template x-for="item in filtered()" :key="item.id_user">
                                            <li @click="select(item)"
                                                class="hover:bg-gray-100 w-150 cursor-pointer text-sm px-5 py-2 cursor-pointer bg-white flex justify-between"
                                                x-text="item.nama_pegawai+ ' - ' +item.nip">
                                            </li>
                                        </template>

                                        <template x-if="filtered().length === 0">
                                            <li class="px-3 py-2 text-gray-500">No results found</li>
                                        </template>
                                    </ul>
                                </div>
                                <!-- Hidden input for form submit -->
                                <input type="hidden" name="pengguna_id" x-model="selected.id_user">


                                <h4 class='mt-5'>Pilihan Jenis Layanan</h4>
                                <div class="border-1 p-3 rounded-lg mt-3">
                                    <template x-for="(addrole,index) in rolepermiss" :key="index">
                                        <div class="bg-white-200 p-1">
                                            <div class="mb-2">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox"
                                                        name="rolenametambah"
                                                        :value="addrole.Roles.name"
                                                        x-model="rolenametambah">
                                                    <span class="ml-2 text-sm"
                                                        x-text="addrole.Roles.alias"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <h4 class='mt-5'>Jenis Layanan</h4>
                                <div @click="openRole = !openRole"
                                    class="rounded-xl shadow-lg w-150 border px-5 py-2 cursor-pointer bg-white flex justify-between items-center">
                                    <span x-text="selectedRole ? selectedRole.Roles.alias : 'Pilih Layanan'"></span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <!-- Dropdown -->
                                <div x-show="openRole" @click.outside="openRole=false"
                                    class="relative mt-1 w-150 bg-white border rounded shadow z-10">

                                    <!-- Search input -->
                                    <input type="text"
                                        x-model="searchRole"
                                        placeholder="Search..."
                                        class="w-150 px-3 py-2 border-b focus:outline-none">

                                    <!-- Options -->
                                    <ul class="max-h-40 overflow-y-auto w-150">
                                        <template x-for="itemrole in filteredRole()" :key="itemrole.Roles.id">
                                            <li @click="selectrole(itemrole)"
                                                class="hover:bg-gray-100 w-150 cursor-pointer text-sm px-5 py-2 cursor-pointer bg-white flex justify-between"
                                                x-text="itemrole.Roles.alias">
                                            </li>
                                        </template>

                                        <template x-if="filtered().length === 0">
                                            <li class="px-3 py-2 text-gray-500">No results found</li>
                                        </template>
                                    </ul>
                                </div>

                                <div class="border-1 p-3 rounded-lg mt-3">
                                    <!-- <div class="grid grid-cols-3 gap-4"> -->
                                    <template x-for="(permiss2,index) in selectedRole.Permissname" :key="index">
                                        <div class="bg-white-200 p-1">
                                            <div class="mb-2">
                                                <label class="items-center">
                                                    <input type="checkbox"
                                                        name="permiss2"
                                                        :value="permiss2.name"
                                                        x-model="selectedPermissions">
                                                    <span class="ml-2 text-sm"
                                                        x-text="permiss2.alias"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- </div> -->
                                </div>

                                <div class="flex gap-2 mt-2 mb-3">
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
                        <table class="min-w-full text-xs text-left">
                            <thead class="bg-gray-100 text-gray-600 uppercase">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">NIP</th>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">Role</th>
                                    <th class="px-4 py-3">Aksi</th>
                                    <th class="px-4 py-3">&nbsp;</th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                <!-- Loop Data -->
                                <template x-for="(pengguna,index) in paginatedResults" :key="pengguna.id_user">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3"
                                            x-text="(currentPage - 1) * perPage + index + 1"></td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-800"
                                                    x-text="pengguna.nip ?? '-'"></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-800"
                                                    x-text="pengguna.nama_pegawai ?? '-'"></span>
                                            </div>
                                        </td>

                                        <td class="px-4 py-3">
                                            <button @click="detail(pengguna.id_user)"
                                                class="border border-blue-500 text-blue-500 bg-white-800 px-3 py-1 rounded rounded text-xs">
                                                Detail
                                            </button>
                                        </td>
                                        <td class="px-4 py-3">
                                            <button @click="edit_role(pengguna.id_user)"
                                                class="border border-blue-500 px-3 py-1 bg-blue-600 text-white rounded text-xs">
                                                Edit
                                            </button>
                                        </td>
                                        <td class="px-4 py-3">
                                            <button @click="hapus(pengguna.id_user)"
                                                class="border border-red-500 px-3 py-1 bg-red-500 text-white rounded text-xs">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                </template>


                            </tbody>
                        </table>

                        <div
                            x-show="showEdit" x-transition
                            class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50"
                            style="display:none">
                            <div
                                class="bg-white rounded-xl shadow-lg p-6 ms-4 w-200 overflow-y-auto max-h-120"
                                :class="edit_role ? 'bg-white rounded-xl shadow-lg p-6 border-1 ms-4 w-200' : ''"
                                @click.outside="edit_role = false">

                                <div x-data="formEdit()">
                                    <div class="border-1 p-3">
                                        <input type="hidden"
                                            name="userid"
                                            x-model="user_id">
                                        <p>Ubah Hak Akses - <span class="bold" x-text="penggunanama"></span> (<span class="bold" x-text="penggunanip"></span>)</p>
                                    </div>

                                    <h4 class='mt-5'>Pilihan Jenis Layanan</h4>
                                    <div class="border-1 p-1 rounded-lg mt-1">
                                        <template x-for="(role,index) in rolepermiss" :key="index">
                                            <div class="bg-white-200 p-2">
                                                <div class="mb-2">
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox"
                                                            name="rolename"
                                                            :value="role.Roles.name"
                                                            x-model="rolename">
                                                        <span class="ml-2 text-sm"
                                                            x-text="role.Roles.alias"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <h4 class='mt-5'>Jenis Layanan</h4>
                                    <div @click="openRole = !openRole"
                                        class="rounded-xl shadow-lg w-150 border px-5 py-2 cursor-pointer bg-white flex justify-between items-center">
                                        <span x-text="selectedRole ? selectedRole.Roles.alias : 'Pilih Layanan'"></span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>

                                    <!-- Dropdown -->
                                    <div x-show="openRole" @click.outside="openRole=false"
                                        class="relative mt-1 w-150 bg-white border rounded shadow z-10">

                                        <!-- Search input -->
                                        <input type="text"
                                            x-model="searchRole"
                                            placeholder="Search..."
                                            class="w-150 px-3 py-2 border-b focus:outline-none">

                                        <!-- Options -->
                                        <ul class="max-h-40 overflow-y-auto w-150">
                                            <template x-for="itemrole in filteredRole()" :key="itemrole.Roles.id">
                                                <li @click="selectrole(itemrole)"
                                                    class="hover:bg-gray-100 w-150 cursor-pointer text-sm px-5 py-2 cursor-pointer bg-white flex justify-between"
                                                    x-text="itemrole.Roles.alias">
                                                </li>
                                            </template>

                                            <template x-if="filtered().length === 0">
                                                <li class="px-3 py-2 text-gray-500">No results found</li>
                                            </template>
                                        </ul>
                                    </div>

                                    <div class="border-1 p-3 rounded-lg mt-3">
                                        <!-- <div class="grid grid-cols-3 gap-4"> -->
                                        <template x-for="(permiss2,index) in selectedRole.Permissname" :key="index">
                                            <div class="bg-white-200 p-1">
                                                <div class="mb-2">
                                                    <label class="items-center">
                                                        <input type="checkbox"
                                                            name="permiss2"
                                                            :value="permiss2.name"
                                                            x-model="selectedPermissions">
                                                        <span class="ml-2 text-sm"
                                                            x-text="permiss2.alias"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </template>
                                        <!-- </div> -->
                                    </div>

                                    <div class="flex gap-2 mt-4">
                                        <button type="button"
                                            @click="simpan_edit()"
                                            class="bg-green-500 text-white px-4 py-2 rounded">
                                            Simpan
                                        </button>
                                        <button type="button"
                                            @click="close_edit()"
                                            class="bg-gray-300 px-4 py-2 rounded">
                                            Batalkan
                                        </button>
                                    </div>
                                </div>
                                <!-- </form> -->
                            </div>
                        </div>

                        <div
                            x-show="showDetail" x-transition
                            class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50"
                            style="display:none">
                            <div
                                class="bg-white rounded-xl shadow-lg p-6 ms-4 w-200 overflow-y-auto max-h-150"
                                :class="detail ? 'bg-white rounded-xl shadow-lg p-6 border-1 ms-4 w-200' : ''"
                                @click.outside="detail = false">

                                <div class="border-1 p-3">
                                    <input type="hidden"
                                        name="rolename"
                                        x-model="selected.name">
                                    <p>Hak Akses - <span class="bold" x-text="pengguna_nama"></span> (<span class="bold" x-text="pengguna_nip"></span>)</p>
                                </div>

                                <!-- <h4 class="mt-4">Jenis Layanan</h4> -->
                                <template x-for="(role,index) in pengguna_role" :key="index">
                                    <div class="border-1 p-2 rounded-lg mt-3">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="bg-white-200 p-2">
                                                <div class="mb-1">
                                                    <label class="inline-flex items-center">
                                                        <img src="icons/check-mark.png" width='7%' />
                                                        <span class="ml-2"
                                                            x-text="role.nama_role.alias"></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="bg-white-200 p-2">
                                                <template x-for="(perm, i) in role.nama_permission" :key="i">
                                                    <label class="flex items-center">
                                                        <!-- <img src="icons/check-mark.png" width='3%' /> -->
                                                        <span class="ml-2"
                                                            x-text="perm"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- <h4 class="mt-4">Permission dalam Layanan</h4> -->
                                <div class="border-1 p-3 rounded-lg mt-3">
                                    <template x-for="(permiss2,index) in pengguna_permission" :key="index">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div class="bg-white-200 p-2">
                                                <div class="mb-2">
                                                    <label class="inline-flex items-center">
                                                        <!-- <img src="icons/check-mark.png" width='3%' /> -->
                                                        <span class="ml-2"
                                                            x-text="permiss2.Roles.alias"></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="bg-white-200 p-2">
                                                <template x-for="(permiss,index) in permiss2.permitname" :key="index">
                                                    <div class="mb-2">
                                                        <label class="inline-flex items-center">
                                                            <img src="icons/check-mark.png" width='3%' />
                                                            <span class="ml-2"
                                                                x-text="permiss.alias"></span>
                                                        </label>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- <div class="bg-white-200 p-4 col-span-2">
                                                <template x-for="(perm, i) in role.nama_permission" :key="i">
                                                    <label class="inline-flex items-center">
                                                        <img src="icons/check-mark.png" width='3%' />
                                                        <span class="ml-2"
                                                            x-text="perm"></span>
                                                    </label>
                                                </template>
                                            </div> -->
                                        </div>

                                    </template>
                                </div>
                                <div class="flex gap-2 mt-4">
                                    <button type="button"
                                        @click="close_detail()"
                                        class="bg-success-300 px-4 py-2 rounded">
                                        Ok
                                    </button>
                                </div>
                            </div>
                        </div>
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
    </div>
</div>


<!-- Toggle Script -->
<script>
    function formTambah() {
        return {
            rolenametambah: [],
            selectedPermissions: [],
            pengguna_id: '',
            open: false,
            search: '',
            selected: null,
            options: @json($allpengguna),
            openRole: false,
            searchRole: '',
            selectedRole: null,
            optionsrole: @json($rolepermiss),
            filteredRole() {
                // return this.optionsrole.filter(o =>
                //     o.Rolename.toLowerCase().includes(this.searchRole.toLowerCase())
                // )
                return this.optionsrole.filter(o =>
                    (o.Roles?.name || "")
                    .toLowerCase()
                    .includes((this.searchRole || "").toLowerCase())
                )
            },
            selectrole(item) {
                this.selectedRole = item
                this.openRole = false
                this.searchRole = ''
            },
            filtered() {
                return this.options.filter(o =>
                    o.nama_pegawai.toLowerCase().includes(this.search.toLowerCase())
                )
            },
            select(item) {
                this.selected = item
                this.open = false
                this.search = ''
            },
            async simpan() {
                try {
                    const response = await fetch(`{{ url('/penggunatambahedit') }}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            permission_name: this.selectedPermissions || [],
                            rolename: this.rolenametambah || [],
                            user_id: this.selected.id_user
                        })
                    });

                    const data = await response.json();
                    this.message = data.message ?? '';
                    this.rolenametambah = [];
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
                                // location.reload();
                            }
                        });
                } catch (error) {
                    console.error(error);
                    alert("Terjadi kesalahan jaringan");
                }


            },

        }
    }

    function formEdit() {
        return {
            openRole: false,
            searchRole: '',
            //selectrole(selectedRole),
            optionsrole: @json($rolepermiss),
            filteredRole() {
                // return this.optionsrole.filter(o =>
                //     o.Rolename.toLowerCase().includes(this.searchRole.toLowerCase())
                // )
                return this.optionsrole.filter(o =>
                    (o.Roles?.name || "")
                    .toLowerCase()
                    .includes((this.searchRole || "").toLowerCase())
                )
            },
            selectrole(item) {
                id_user = this.user_id;
                fetch(`{{ url('/rolepadapengguna') }}/${id_user}`)
                    .then(res => res.json())
                    .then(data => {
                        this.selectedPermissions = data.permissions.map(perm => perm.name);
                    });
                this.selectedRole = item
                this.openRole = false
                this.searchRole = ''
            },


            async simpan_edit() {
                try {
                    const response = await fetch("{{ url('/penggunatambahedit') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            permission_name: this.selectedPermissions || [],
                            rolename: this.rolename || [],
                            user_id: this.user_id
                        })
                    });

                    const data = await response.json();
                    this.message = data.message ?? '';
                    //this.permissionname = [];
                    this.rolename = [];
                    this.showEdit = false;
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
                                // location.reload();
                            }
                        });
                } catch (error) {
                    console.error(error);
                    alert("Terjadi kesalahan jaringan");
                }
            },

        }
    }

    function search() {
        return {
            roles: @json($roles),
            allpengguna: @json($allpengguna),
            rolepermiss: @json($rolepermiss),
            results: [],
            pengguna_nama: '',
            pengguna_nip: '',
            pengguna_role: [],
            page: 1,
            perPage: 10,
            tableSearch: '',
            form: {
                nip: '',
                nama_pegawai: '',

            },
            init() {
                this.$watch('perPage', () => {
                    this.currentPage = 1;
                });

                this.$watch('tableSearch', () => {
                    this.currentPage = 1;
                });
            },
            reset() {
                this.form.nip = '';
                this.form.nama_pegawai = '';
                location.reload();
            },
            add_user() {
                this.permissiontambah = [];
                this.rolenametambah = [];
                this.pengguna_id = '';
                this.open = false;
                this.search = '';
                this.selected = null;
                this.addUsers = true;
            },
            detail(id_user) {
                fetch(`{{ url('/rolepadapengguna') }}/${id_user}`)
                    .then(res => res.json())
                    .then(data => {
                        this.pengguna_nama = data.pengguna.nama;
                        this.pengguna_nip = data.pengguna.nip;
                        this.pengguna_role = data.roles;
                        //this.pengguna_permission = data.permissions;
                        this.pengguna_permission = data.permitgroupedbyrole; //.map(group => group.namarolepermit);
                        this.showDetail = true;
                        console.log(this.pengguna_role);

                    });
            },
            hapus(id_user) {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah anda yakin ingin menghapus seluruh Hak Akses Pengguna ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('/penggunahapus') }}/${id_user}`)
                            .then(res => res.json())
                            .then(data => {
                                this.pengguna_nama = data.pengguna.nama;
                                this.pengguna_nip = data.pengguna.nip;
                                this.pengguna_role = data.roles;
                                this.showDetail = true;
                                console.log(this.pengguna_role);

                            });
                        Swal.fire(
                            'Berhasil!',
                            'Hapus Hak Akses Pengguna Berhasil dilakukan.',
                            'success'
                        )
                    }
                });
                // if (confirm('Apakah anda yakin ingin menghapus seluruh Hak Akses Pengguna ini?')) {

                // }
            },
            close_detail() {
                this.showDetail = false;
            },
            edit_role(id_user) {
                fetch(`{{ url('/rolepadapengguna') }}/${id_user}`)
                    .then(res => res.json())
                    .then(data => {
                        this.penggunanama = data.pengguna.nama;
                        this.user_id = data.pengguna.id_user;
                        this.penggunanip = data.pengguna.nip;
                        this.rolename = data.roles.map(role => role.nama_role.name);
                        this.selectedRole = data.permitgroupedbyrole[0];
                        this.selectedPermissions = data.permissions.map(perm => perm.name);
                        this.showEdit = true;
                        console.log(this.rolename);
                    });
            },
            close_edit() {
                this.showEdit = false;
            },

            async caridata() {
                this.loading = true;
                this.currentPage = 1;

                try {
                    const response = await fetch("{{ url('/caripengguna') }}", {
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
            changePage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                }
            }
        }
    }
</script>


@endsection