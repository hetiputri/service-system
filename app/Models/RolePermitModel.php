<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermitModel extends Model
{
    public function getPengguna()
    {
        $data = \DB::connection('mysql_second')->table('tbl_pengguna')
            ->Join('tbl_pegawai as b', 'b.id_pegawai', '=', 'tbl_pengguna.id_pegawai')
            ->select(
                'b.nip',
                'b.nama_pegawai',
                'b.id_pegawai',
                'tbl_pengguna.id_user'
            )
            ->where('tbl_pengguna.modul', '=', 1)
            ->where('b.id_satuan_organisasi', '>', 0);

        return $data;
    }

    public function cariPengguna($nama, $nip)
    {
        if ($nama || $nip) {
            $data = \DB::connection('mysql_second')->table('tbl_pengguna')
                ->Join('tbl_pegawai as b', 'b.id_pegawai', '=', 'tbl_pengguna.id_pegawai')
                ->select(
                    'b.nip',
                    'b.nama_pegawai',
                    'b.id_pegawai',
                    'tbl_pengguna.id_user'
                )
                ->where('tbl_pengguna.modul', 1)
                ->where(function ($query) use ($nama, $nip) {
                    if ($nama != null && $nama != '') {
                        $query->where('b.nama_pegawai', 'like', '%' . $nama . '%');
                    }
                    if ($nip) {
                        $query->where('b.nip', 'like', '%' . $nip . '%');
                    }
                });

            return $data;
        } else {
            return '';
        }
    }

    public function getUserName($id_user)
    {
        $data = \DB::connection('mysql_second')->table('tbl_pengguna as a')
            ->leftJoin('tbl_pegawai as b', 'b.id_pegawai', '=', 'a.id_pegawai')
            ->select('a.id_user', 'b.nama_pegawai', 'b.nip')
            ->where('a.id_user', $id_user);

        return $data;
    }
}

// <div
//                                 class="bg-white rounded-xl shadow-lg p-6 ms-4 w-200"
//                                 :class="detail ? 'bg-white rounded-xl shadow-lg p-6 border-1 ms-4 w-200' : ''"
//                                 @click.outside="detail = false">

//                                 <div x-data="formRoles()">
//                                     <div class="border-1 p-3">
//                                         <input type="hidden"
//                                             name="rolename"
//                                             x-model="selected.name">
//                                         <p>Hak Akses - <span class="bold" x-text="pengguna_nama"></span> (<span class="bold" x-text="pengguna_nip"></span>)</p>
//                                     </div>
//                                     <!-- List of checkboxes -->
//                                     <div class="flex flex-col md:flex-row gap-2 items-stretch md:items-center">
//                                         <template x-for="role in roles" :key="role.name">
//                                             <div class="flex-1">
//                                                 <div class="border-1 p-3 rounded-lg mt-3">
//                                                     <div class="mb-2">
//                                                         <!-- Hidden input to pass array to backend -->

//                                                         <label class="inline-flex items-center">
//                                                             <input type="checkbox"
//                                                                 name="role_name"
//                                                                 :value="role.name"
//                                                                 x-model="rolenames">
//                                                             <span class="ml-2"
//                                                                 x-text="role.name"></span>
//                                                         </label>
//                                                     </div>
//                                                 </div>
//                                             </div>
//                                             <div class="flex-4">
//                                                 <div class="border-1 p-3 rounded-lg mt-3">
//                                                     <div class="mb-2">
//                                                         <ul>
//                                                             <template x-for="item in category.items">
//                                                                 <label class="inline-flex items-center">
//                                                                     <input type="checkbox"
//                                                                         name="role_name"
//                                                                         :value="role.name"
//                                                                         x-model="rolenames">
//                                                                     <span class="ml-2"
//                                                                         x-text="role.name"></span>
//                                                                 </label>
//                                                             </template>
//                                                         </ul>

//                                                     </div>
//                                                 </div>
//                                             </div>
//                                         </template>
//                                     </div>

//                                     <div class="flex gap-2 mt-4">
//                                         <!-- <button type="submit"
//                                                 class="bg-green-500 text-white px-4 py-2 rounded">
//                                                 Submit
//                                             </button> -->
//                                         <button type="button"
//                                             @click="save()"
//                                             class="bg-green-500 text-white px-4 py-2 rounded">
//                                             Save
//                                         </button>
//                                         <button type="button"
//                                             @click="close()"
//                                             class="bg-gray-300 px-4 py-2 rounded">
//                                             Cancel
//                                         </button>
//                                     </div>
//                                 </div>
//                                 <!-- </form> -->
//                             </div>
