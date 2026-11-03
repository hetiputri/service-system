@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')


<div class="max-w-7xl mx-auto p-4">
    <div x-data="{ add_role: false,add_permit:false,reset_role:false }" class="transition-all duration-200">
        <!-- Card Container -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">

            <!-- Header -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    Pengaturan Akses dan Permission Aplikasi Simpeg
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kategori Akses dan Permission
                </p>
            </div>

            <div class="p-6">
                <div class="mt-3 mb-3">

                    @if(session('message'))
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 3000)"
                        class="bg-green-200 p-3 mb-3 text-sm">
                        {{ session('message') }}
                    </div>
                    @endif
                    <div x-data="usersComponent()">
                        <button type="button" @click='addrole()' class="mb-3 px-5 py-2 bg-gray-600 text-white rounded-lg">
                            <span>Tambah Kategori Akses</span>
                        </button>
                        <div
                            x-show="add_role"
                            style="background-color:rgb(0,0,0,0.5);display:none"
                            class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50">
                            <div
                                class="bg-white rounded-xl shadow-lg p-6 left-6 mt-2 w-200"
                                :class="add_role ? 'bg-white rounded-xl shadow-lg p-6 ms-4 w-200' : ''"
                                @click.outside="add_role = false">
                                <!-- Form -->
                                <!-- <form id="tambahRole" method="POST" action="{{ route('role.tambah.edit') }}">
                                @csrf -->
                                <!-- <div x-data="addRoles()"> -->
                                <h2 class="text-lg font-bold mb-4" x-text="headerTextRole"></h2>
                                <div class="flex items-center gap-3">
                                    <label class="w-80 pb-5">Nama Role</label>
                                    <input
                                        type="text"
                                        name="rolename"
                                        placeholder="Masukkan Nama Role"
                                        class="w-full border rounded p-2 mb-4"
                                        x-model="rolename"
                                        required>
                                </div>

                                <div class="flex items-center gap-3">
                                    <label class="w-80 pb-5">Nama Role Alias</label>
                                    <input
                                        type="text"
                                        name="rolenamealias"
                                        placeholder="Masukkan Nama Alias Role"
                                        class="w-full border rounded p-2 mb-4"
                                        x-model="rolenamealias"
                                        required>
                                </div>

                                <input
                                    type="hidden"
                                    name="roleid"
                                    x-model="roleid"
                                    :value=0>

                                <div class="flex gap-2">
                                    <button type="button"
                                        @click="simpanrole()"
                                        class="bg-green-500 text-white px-4 py-2 rounded">
                                        Simpan
                                    </button>

                                    <button
                                        type="button"
                                        @click="add_role=false"
                                        class="bg-gray-300 px-4 py-2 rounded">
                                        Batalkan
                                    </button>
                                </div>
                                <!-- </div> -->
                                <!-- </form> -->
                            </div>
                        </div>

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


                            <table class="min-w-full text-xs text-left">
                                <thead class="bg-gray-100 text-gray-600 uppercase">
                                    <tr>
                                        <th class="px-4 py-3">No</th>
                                        <th class="px-4 py-3">Kategori Akses (Nama)</th>
                                        <th class="px-4 py-3">Kategori Akses (Alias)</th>
                                        <th class="px-2 py-3">&nbsp;</th>
                                        <th class="px-2 py-3">&nbsp;</th>
                                        <th class="px-2 py-3">&nbsp;</th>

                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    <!-- Loop Data -->
                                    <template x-for="(role,index) in paginatedResults" :key="role.id">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3"
                                                x-text="(currentPageRole - 1) * perPage + index + 1"></td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col">
                                                    <span class="font-semibold text-gray-800"
                                                        x-text="role.name ?? '-'"></span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col">
                                                    <span class="font-semibold text-gray-800"
                                                        x-text="role.alias ?? '-'"></span>
                                                </div>
                                            </td>

                                            <td class="px-4 py-3">
                                                <button @click="edit_role(role.name)"
                                                    class="border px-3 py-1 bg-white-800 border-blue-500 text-blue-500 rounded text-xs">
                                                    Edit
                                                </button>
                                            </td>
                                            <td class="px-4 py-3">
                                                <button @click="reset_role(role)"
                                                    class="px-3 py-1 bg-blue-600 text-white rounded text-xs">
                                                    Atur Permission
                                                </button>
                                            </td>
                                            <td class="px-4 py-3">
                                                <button @click="hapus_role(role.id,index)"
                                                    class="px-3 py-1 bg-red-500 text-white rounded text-xs">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>

                            <div
                                x-show="showReset" x-transition
                                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50"
                                style="display:none">
                                <div
                                    class="bg-white rounded-xl shadow-lg p-6 ms-4 w-200 overflow-y-auto max-h-120"
                                    :class="reset_role ? 'bg-white rounded-xl shadow-lg p-6 border-1 ms-4 w-200 overflow-y-auto max-h-120' : ''"
                                    @click.outside="showReset = false">
                                    <!-- Form -->
                                    <!-- <form method="POST" action="{{ route('assign.permission.role') }}">
                                        @csrf -->
                                    <div x-data="formRoles()">
                                        <div class="border-1 p-3">
                                            <input type="hidden"
                                                name="rolename"
                                                x-model="selected.name">

                                            <p>Nama Kategori Akses : <!--<span class="bold" x-text="selected.name"></span>
                                                (Alias:--><span class="bold" x-text="selected.alias"></span></p>
                                        </div>

                                        <!-- List of checkboxes -->
                                        <div class="border-1 p-3 rounded mt-3">
                                            <template x-for="permit in permissions" :key="permit.name">
                                                <div class="mb-2">
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox"
                                                            name="permission_name[]"
                                                            :value="permit.name"
                                                            x-model="permits">
                                                        <!-- <span class="ml-2"
                                                            x-text="permit.name"></span> -->
                                                        <span class="ml-2"
                                                            x-text="permit.alias"></span>
                                                    </label>
                                                </div>
                                            </template>
                                            <!-- </div> -->
                                        </div>

                                        <div class="flex gap-2 mt-4">
                                            <button type="button"
                                                @click="save()"
                                                class="bg-green-500 text-white px-4 py-2 rounded">
                                                Simpan
                                            </button>
                                            <button type="button"
                                                @click="showReset=false"
                                                class="bg-gray-300 px-4 py-2 rounded">
                                                Batalkan
                                            </button>
                                        </div>
                                    </div>
                                    <!-- </form> -->
                                </div>
                            </div>

                            <!-- <div
                                x-show="editRole"
                                style="background-color:rgb(0,0,0,0.5);display:none"
                                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50">
                                <div
                                    class="bg-white rounded-xl shadow-lg p-6 left-6 mt-2 w-200"
                                    @click.outside="editRole = false">
                                    <!-- Form
                                    <form method="POST" action="{{ route('role.edit') }}">
                                        @csrf
                                        <h2 class="text-lg font-bold mb-4">Edit Kategori Akses</h2>
                                        <input
                                            type="text"
                                            name="rolename"
                                            placeholder="Enter name"
                                            class="w-full border rounded p-2 mb-4"
                                            x-model="rolename"
                                            required>

                                        <input
                                            type="hidden"
                                            name="roleid"
                                            x-model="roleid"
                                            required>

                                        <div class="flex gap-2">
                                            <button
                                                type="submit"
                                                class="bg-green-500 text-white px-4 py-2 rounded">
                                                Simpan
                                            </button>

                                            <button
                                                type="button"
                                                @click="editRole=false"
                                                class="bg-gray-300 px-4 py-2 rounded">
                                                Batalkan
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div> -->

                            <!-- Pagination Controls -->
                            <div class="flex justify-between items-center p-4 border-t text-xs">

                                <div>
                                    Total: <span x-text="totalDataRole"></span> data
                                </div>

                                <div class="flex items-center gap-2">
                                    <button @click="changePageRole(currentPageRole - 1)"
                                        :disabled="currentPageRole === 1"
                                        class="px-3 py-1 border rounded disabled:opacity-50">
                                        Prev
                                    </button>

                                    <span>
                                        Page <span x-text="currentPageRole"></span>
                                        of <span x-text="totalPagesRole"></span>
                                    </span>

                                    <button @click="changePageRole(currentPageRole + 1)"
                                        :disabled="currentPageRole === totalPagesRole"
                                        class="px-3 py-1 border rounded disabled:opacity-50">
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- ============PERMISSION======================================= -->

                <div x-data="permitComponent()">
                    <div class="p-6 mt-6">
                        <button type="button" @click='add_permit()' class="mb-3 px-5 py-2 bg-gray-600 text-white rounded-lg">
                            <span>Tambah Kategori Permission</span>
                        </button>

                        <div
                            x-show="addPermit" style="background-color:rgb(0,0,0,0.5);display:none"
                            class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50">

                            <div
                                class="bg-white rounded-xl shadow-lg p-6 ms-4 w-200"
                                @click.outside="addPermit = false">
                                <!-- Form -->
                                <!-- <form method="POST" action="{{ route('permission.tambah.edit') }}">
                                    @csrf -->
                                <h2 class="text-lg font-bold mb-4" x-text="headerText"></h2>
                                <div class="flex items-center gap-3">
                                    <label for="permitname" class="w-80 pb-5">Nama Permission</label>
                                    <input
                                        type="text"
                                        name="permission_name"
                                        placeholder="Masukkan Nama Permission"
                                        class="w-full border rounded p-2 mb-4"
                                        x-model="permission_name"
                                        required>
                                </div>

                                <div class="flex items-center gap-3">
                                    <label for="permitname" class="w-80 pb-5">Nama Permission Alias</label>
                                    <input
                                        type="text"
                                        name="permission_namealias"
                                        placeholder="Masukkan Nama Alias Permission"
                                        class="w-full border rounded p-2 mb-4"
                                        x-model="permission_namealias"
                                        required>
                                </div>

                                <input
                                    type="hidden"
                                    name="permission_id"
                                    x-model="permission_id"
                                    :value=0>

                                <div class="flex gap-2">
                                    <button type="button"
                                        @click="simpanpermit()"
                                        class="bg-green-500 text-white px-4 py-2 rounded">
                                        Simpan
                                    </button>

                                    <button
                                        type="button"
                                        @click="addPermit=false"
                                        class="bg-gray-300 px-4 py-2 rounded">
                                        Batalkan
                                    </button>
                                </div>

                                <!-- </form> -->
                            </div>
                        </div>

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
                                        x-model="tableSearch2"
                                        placeholder="Search..."
                                        class="border px-3 py-1 text-xs rounded w-48">
                                </div>


                            </div>


                            <table class=" min-w-full text-xs text-left">
                                <thead class="bg-gray-100 text-gray-600 uppercase">
                                    <tr>
                                        <th class="px-4 py-3">No</th>
                                        <th class="px-4 py-3">Kategori Permission (Nama)</th>
                                        <th class="px-4 py-3">Kategori Permission (Alias)</th>
                                        <th class="px-4 py-3">&nbsp;</th>
                                        <th class="px-4 py-3">&nbsp;</th>

                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    <!-- Loop Data -->
                                    <template x-for="(permission,index) in paginatedResultsPermit" :key="permission.id">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3"
                                                x-text="(currentPagePermit - 1) * perPage + index + 1"></td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col">
                                                    <span class="font-semibold text-gray-800"
                                                        x-text="permission.name ?? '-'"></span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col">
                                                    <span class="font-semibold text-gray-800"
                                                        x-text="permission.alias ?? '-'"></span>
                                                </div>
                                            </td>

                                            <td class="px-4 py-3">
                                                <button type="button"
                                                    @click="edit_permission(permission)"
                                                    class="border px-3 py-1 bg-white text-blue-500 border-blue-500 rounded text-xs hover:bg-blue-700">
                                                    Edit
                                                </button>
                                            </td>
                                            <td class="px-4 py-3">
                                                <button @click="hapus_permission(permission.id,index)"
                                                    class="px-3 py-1 bg-red-500 text-white rounded text-xs">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    </template>


                                </tbody>
                            </table>
                            <!-- Pagination Controls -->
                            <div class="flex justify-between items-center p-4 border-t text-xs">

                                <div>
                                    Total: <span x-text="totalDataPermit"></span> data
                                </div>

                                <div class="flex items-center gap-2">
                                    <button @click="changePagePermit(currentPagePermit - 1)"
                                        :disabled="currentPagePermit === 1"
                                        class="px-3 py-1 border rounded disabled:opacity-50">
                                        Prev
                                    </button>

                                    <span>
                                        Page <span x-text="currentPagePermit"></span>
                                        of <span x-text="totalPagesPermit"></span>
                                    </span>

                                    <button @click="changePagePermit(currentPagePermit + 1)"
                                        :disabled="currentPagePermit === totalPagesPermit"
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
</div>

<!-- Toggle Script -->
<script>
    function usersComponent() {
        return {
            currentPageRole: 1,
            perPage: 10,
            roles: @json($roles),
            permissions: @json($permissions),
            permits: [], // current role permissions
            tableSearch: '',
            selected: {},
            init() {
                this.$watch('perPage', () => {
                    this.currentPageRole = 1;
                });

                this.$watch('tableSearch', () => {
                    this.currentPageRole = 1;
                });
            },
            addrole() {
                this.headerTextRole = 'Masukkan Kategori Role';
                this.roleid = 0;
                this.rolename = '';
                this.rolenamealias = '';
                this.add_role = true;
            },
            hapus_role(roleid, index) {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah anda yakin ingin menghapus Akses/Role ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('/role-hapus') }}/${roleid}`)
                            .then(
                                res => {
                                    if (res.ok) {
                                        // Remove from local array to update menu/table
                                        //this.roles.splice(index, 1);
                                        location.reload();
                                        console.log('Item deleted');
                                    } else {
                                        console.error('Failed to delete item');
                                    }
                                })
                            .catch(err => console.error('Error:', err));
                    }
                });
            },

            edit_role(rolename) {
                fetch(`{{ url('/role/permissionlist/') }}/${rolename}`)
                    .then(res => res.json())
                    .then(data => {
                        this.headerTextRole = 'Edit Kategori Role';
                        this.rolename = data.Role;
                        this.roleid = data.Role_id;
                        this.rolenamealias = data.RoleAlias;
                        this.add_role = true;
                    });
            },
            close() {
                this.add_role = false;
            },
            simpanrole() {
                fetch(`{{ url('/role-tambah-edit') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            rolename: this.rolename,
                            rolenamealias: this.rolenamealias,
                            roleid: this.roleid
                        })
                    })
                    .then(response => response.json()) // parse JSON response
                    .then(data => {
                        console.log('Response from controller:', data);

                        // you can show success message
                        if (data.success == false) {
                            //alert(data.message);
                            Swal.fire({
                                    title: 'Konfirmasi',
                                    icon: 'question',
                                    text: data.message,
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((willOk) => {
                                    if (willOk) {
                                        //location.reload();
                                    }
                                });
                        } else {
                            // alert(data.message);
                            this.close();
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((willOk) => {
                                    if (willOk) {
                                        location.reload();
                                    }
                                });
                            //this.close();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Harap Melengkapi Seluruh Data');
                        // Swal.fire({
                        //         title: 'Konfirmasi',
                        //         icon: 'warning',
                        //         text: 'Harap Melengkapi Seluruh Data yang diminta!',
                        //         buttons: true,
                        //         dangerMode: true,
                        //     })
                        //     .then((willOk) => {
                        //         if (willOk) {
                        //             this.add_role = true;
                        //         }
                        //     });


                    });
            },
            reset_role(row) {
                //Fetch existing permissions for this role from backend

                fetch(`{{ url('/role/permissionlist/') }}/${row.name}`)
                    .then(res => res.json())
                    .then(data => {
                        // data = array of permission names
                        this.permits = data.Permission;
                        this.selected = row;
                        this.showReset = true;
                    });
            },

            get filteredResults() {
                if (!this.tableSearch) return this.roles;

                const keyword = this.tableSearch.toLowerCase();

                return this.roles.filter(item =>
                    Object.values(item).some(value =>
                        String(value ?? '').toLowerCase().includes(keyword)
                    )
                );
            },

            get totalDataRole() {
                return this.filteredResults.length;
            },

            get totalPagesRole() {
                return Math.ceil(this.totalDataRole / Number(this.perPage)) || 1;
            },

            get paginatedResults() {
                const start = (this.currentPageRole - 1) * this.perPage;
                const end = start + Number(this.perPage);
                return this.filteredResults.slice(start, end);
            },
            changePageRole(page) {
                if (page >= 1 && page <= this.totalPagesRole) {
                    this.currentPageRole = page;
                }
            }
        }
    }

    function permitComponent() {
        return {
            currentPagePermit: 1,
            perPage: 10,
            permissions: @json($permissions),
            tableSearch2: '',
            init() {
                this.$watch('perPage', () => {
                    this.currentPagePermit = 1;
                });

                this.$watch('tableSearch2', () => {
                    this.currentPagePermit = 1;
                });
            },
            add_permit() {
                this.headerText = 'Masukkan Kategori Permission';
                this.permission_id = 0;
                this.permission_name = '';
                this.permission_namealias = '';
                this.addPermit = true;
            },
            edit_permission(permission) {
                this.headerText = 'Edit Kategori Permission';
                this.permission_id = permission.id;
                this.permission_name = permission.name;
                this.permission_namealias = permission.alias;
                this.addPermit = true;

            },
            closepermit() {
                this.addPermit = false;
            },
            hapus_permission(permissionid, index) {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah anda yakin ingin menghapus Permission ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('/permission-hapus') }}/${permissionid}`)
                            .then(
                                res => {
                                    if (res.ok) {
                                        //this.permissions.splice(index, 1);
                                        location.reload();
                                        console.log('Item deleted');
                                    } else {
                                        console.error('Failed to delete item');
                                    }
                                })
                            .catch(err => console.error('Error:', err));
                    }
                });
                // if (confirm('Apakah anda yakin ingin menghapus Permission ini?')) {

                // }
            },
            simpanpermit() {
                fetch(`{{ url('/permission-tambah-edit') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            permission_id: this.permission_id,
                            permission_name: this.permission_name,
                            permission_namealias: this.permission_namealias
                        })
                    })
                    .then(response => response.json()) // parse JSON response
                    .then(data => {
                        console.log('Response from controller:', data);

                        // you can show success message
                        if (data.success == false) {
                            //alert(data.message);
                            Swal.fire({
                                    title: 'Konfirmasi',
                                    icon: 'question',
                                    text: data.message,
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((willOk) => {
                                    if (willOk) {
                                        //location.reload();
                                    }
                                });
                        } else {
                            // alert(data.message);
                            this.closepermit();
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((willOk) => {
                                    if (willOk) {
                                        location.reload();
                                    }
                                });
                            //this.close();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Harap Melengkapi Seluruh Data');
                        // Swal.fire({
                        //         title: 'Konfirmasi',
                        //         icon: 'warning',
                        //         text: 'Harap Melengkapi Seluruh Data yang diminta!',
                        //         buttons: true,
                        //         dangerMode: true,
                        //     })
                        //     .then((willOk) => {
                        //         if (willOk) {
                        //             this.add_role = true;
                        //         }
                        //     });


                    });
            },
            get filteredResultsPermit() {
                if (!this.tableSearch2) return this.permissions;

                const keyword = this.tableSearch2.toLowerCase();

                return this.permissions.filter(item =>
                    Object.values(item).some(value =>
                        String(value ?? '').toLowerCase().includes(keyword)
                    )
                );
            },

            get totalDataPermit() {
                return this.filteredResultsPermit.length;
            },

            get totalPagesPermit() {
                return Math.ceil(this.totalDataPermit / Number(this.perPage)) || 1;
            },

            get paginatedResultsPermit() {
                const start = (this.currentPagePermit - 1) * this.perPage;
                const end = start + Number(this.perPage);
                return this.filteredResultsPermit.slice(start, end);
            },

            changePagePermit(page) {
                if (page >= 1 && page <= this.totalPagesPermit) {
                    this.currentPagePermit = page;
                }
            }


        }
    }

    function formRoles() {
        return {
            close() {
                this.showReset = false;
            },
            save() {
                fetch(`{{ url('/roletambahpermission') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            permission_name: this.permits,
                            rolename: this.selected.name
                        })
                    })
                    .then(response => response.json()) // parse JSON response
                    .then(data => {
                        console.log('Response from controller:', data);

                        // you can show success message
                        if (data.success == false) {
                            alert(data.message);
                        } else {
                            // alert(data.message);
                            this.close();
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((willOk) => {
                                    if (willOk) {
                                        //location.reload();
                                    }
                                });
                            //this.close();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Harap dipilih Nama Permission');
                    });
            },

        }
    }
</script>


@endsection