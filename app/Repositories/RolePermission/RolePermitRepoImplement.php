<?php

namespace App\Repositories\RolePermission;


use App\Models\Pengguna;
use App\Models\RolePermitModel;
use App\Repositories\RolePermission\RolePermitRepo;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermitRepoImplement implements RolePermitRepo
{
    /**
     * Get data by ID and jenis_data
     * @param $id_pegawai
     * @param $jenis_data
     * @return mixed
     */
    protected $model;

    public function __construct(RolePermitModel $model)
    {
        $this->model = $model;
    }

    public function pengguna()
    {
        $penggunas = $this->model->getPengguna();
        if ($penggunas->count() > 0) {
            $dapeng = $penggunas->get();
        } else {
            $dapeng = [];
        }
        return $dapeng;
    }

    public function caripengguna($nama, $nip)
    {
        if ($nip != '') {
            $nip_d = str_replace(' ', '', $nip);
            if (strlen($nip_d) == 18) {
                $nip = substr($nip_d, 0, 8) . " " . substr($nip_d, 8, 6) . " " . substr($nip_d, 14, 1) . " " . substr($nip_d, 15, 3);
            }
        }
        $results = '';
        $data = $this->model->cariPengguna($nama, $nip)->get();
        $results = [
            'results' => $data
        ];

        return $results;
    }

    public function getPengguna($id_user)
    {
        //pengguna sekaligus dengan rolesnya 
        $pengguna = $this->model->getUserName($id_user);
        if ($pengguna->count() > 0) {
            $data = [];
            //Nama User dan NIP
            $pengguna = $pengguna->first();
            $pengguna = [
                'id_user' => $pengguna->id_user,
                'nama' => $pengguna->nama_pegawai,
                'nip' => $pengguna->nip,
            ];
            //1.Role yang dimiliki
            $rolepermit = Pengguna::where('id_user', $id_user)->first();
            $roles = [];
            if ($rolepermit->getRoleNames()) {
                foreach ($rolepermit->getRoleNames() as $rolename) {
                    $role = Role::where('name', '=', $rolename)->first();
                    $permission = [];
                    if ($role) {
                        foreach ($role->getPermissionNames() as $permit) {
                            $perm = Permission::where('name', '=', $permit)->first();
                            $permission[] = $perm->alias;
                        }
                        //$permission =  $role->getPermissionNames();
                    } //else {
                    //     $permission =  '';
                    // }
                    $roles[] = [
                        'nama_role' => [
                            'name' => $rolename,
                            'alias' => $role->alias
                        ],
                        'nama_permission' => $permission
                    ];
                }
            } else {
                $roles = [];
            }

            //2. Permission yang dimiliki
            $user = Pengguna::where('id_user', $id_user)->first();
            $permissionOnly = [];
            //$permits = [];
            $allRoleins = [];
            $groupedByRole = [];
            if ($user->getPermissionNames()) {
                foreach ($user->getPermissionNames() as $permitname) {
                    //1. First get a role that has this permission
                    $permiss = Permission::where('name', $permitname)->first();
                    $inRole = $permiss->roles->pluck('name')->first();
                    $inRoles = Role::where('name', $inRole)->first();

                    //2. cek Duplikasi Nama Role
                    if (!in_array($inRole, $allRoleins)) {
                        $allRoleins[] = $inRole;


                        //3. Then get all permissions that belong to that role
                        $allPermissins[$inRole] = $inRoles ? $inRoles->getPermissionNames() : [];
                        $allPermissinsDet[$inRole] = [];
                        if (!empty($allPermissins[$inRole])) {
                            foreach ($allPermissins[$inRole] as $detperm) {
                                $allperm = Permission::where('name', $detperm)->first();
                                $allPermissinsDet[$inRole][] = [
                                    'name' => $detperm,
                                    'alias' => $allperm->alias
                                ];
                            }
                        }

                        // Filter only the permissions the user has
                        $userPermsForRole[$inRole] = array_values(array_intersect($user->getPermissionNames()->toArray(), $inRoles->getPermissionNames()->toArray()));
                        $userPermsForRoleDet[$inRole] = [];
                        if (!empty($userPermsForRole[$inRole])) {
                            foreach ($userPermsForRole[$inRole] as $detail) {
                                $perms = Permission::where('name', $detail)->first();
                                $userPermsForRoleDet[$inRole][] = [
                                    'name' => $detail,
                                    'alias' => $perms->alias
                                ];
                            }
                        }
                    }
                    //$permits[] = $permiss->name;
                    $permissionOnly[] = [
                        'name' => $permitname,
                        'alias' => $permiss->alias,
                    ];
                }

                foreach ($allRoleins as $inRole) {
                    $groupedByRole[] = [
                        'Roles' => [
                            'name' => $inRoles->name,
                            'alias' => $inRoles->alias
                        ],
                        'Permissname' =>  $allPermissinsDet[$inRole],
                        'permitname' => $userPermsForRoleDet[$inRole], //$userPermsForRole[$inRole], //$permits
                    ];
                }
            }

            return [
                'pengguna' => $pengguna,
                'roles' => $roles, //role names belong to user
                'permissions' => $permissionOnly, //permission names belong to user
                'permitgroupedbyrole' => $groupedByRole
            ];
        } else {
            return [
                'message' => 'Pengguna tidak terdaftar'
            ];
        }
    }

    public function read($jenis)
    {
        $result = '';
        if ($jenis == 'permission') {
            $data = Permission::orderBy('name', 'asc')->get();
            if ($data->count() > 0) {
                //$datas = [];
                // foreach ($data as $d) {
                //     $datas[] = [
                //         'name' => trim($d->name),
                //         'id' => $d->id
                //     ];
                // }
                $result = $data;
            } else {
                $result = 'Permission tidak tersedia';
            }
        } elseif ($jenis == 'role') {
            $data = Role::orderBy('name', 'asc')->get();
            if ($data->count() > 0) {
                $result = $data;
            } else {
                $result = 'Role tidak tersedia';
            }
        } elseif ($jenis == 'rolepermiss') {
            $role = Role::get();
            if ($role->count() > 0) {
                $datas = [];
                foreach ($role as $r) {
                    $permissname =  []; //$r->getPermissionNames();
                    foreach ($r->getPermissionNames() as $permit) {
                        $perm = Permission::where('name', '=', $permit)->first();
                        $permissname[] = [
                            'name' => $perm->name,
                            'alias' => $perm->alias
                        ];
                    }
                    $datas[] = [
                        //'Rolename' => trim($r->name),
                        'Roles' => [
                            'name' => trim($r->name),
                            'id' => $r->id,
                            'alias' => trim($r->alias)
                        ],
                        'Permissname' => $permissname
                    ];
                }
                $result = $datas;
            } else {
                $result = 'Role tidak tersedia';
            }
        } else {
            $result = 'Kategori yang diinput salah';
        }
        return $result;
    }

    public function create($jenis, $data, $data_alias)
    {
        $result = [
            'success' => false,
            'message' => 'Data gagal diproses'
        ];
        if ($data != '' && $data_alias != '') {
            if ($jenis == 'permission') {
                Permission::create([
                    'name' => $data,
                    'guard_name' => 'web',
                    'alias' => $data_alias
                ]);
                //$result = 'Tambah Data Permission Berhasil';
                $result = [
                    'success' => true,
                    'message' => 'Tambah Data Permission Berhasil'
                ];
            } elseif ($jenis == 'role') {
                Role::create([
                    'name' => $data,
                    'guard_name' => 'web',
                    'alias' => $data_alias
                ]);
                //$result = 'Tambah Data Role Berhasil';
                $result = [
                    'success' => true,
                    'message' => 'Tambah Data Role Berhasil'
                ];
            }
            return $result;
        }
    }

    public function update($jenis, $id, $data, $data_alias)
    {
        $result = [
            'success' => false,
            'message' => 'Data Gagal diproses'
        ];
        if ($data != '' && $id != '' && $data_alias) {
            if ($jenis == 'permission') {
                $permission = Permission::find($id);
                if (!$permission) {
                    $result = 'Gagal, ID tidak dikenali';
                }

                $permission->update([
                    'name' => $data,
                    'guard_name' => 'web',
                    'alias' => $data_alias
                ]);
                //$result = 'Update Nama Permission berhasil';
                $result = [
                    'success' => true,
                    'message' => 'Update Nama Permission berhasil'
                ];
            } elseif ($jenis == 'role') {
                $role = Role::find($id);
                if (!$role) {
                    $result = 'Gagal, ID tidak dikenali';
                }
                $role->update([
                    'name' => $data,
                    'guard_name' => 'web',
                    'alias' => $data_alias
                ]);
                //$result = 'Update Nama Role berhasil';
                $result = [
                    'success' => true,
                    'message' => 'Update Nama Role berhasil'
                ];
            }
            return $result;
        }
    }

    public function deleted($jenis, $id)
    {
        if ($jenis == 'role') {
            $role = Role::find($id);
            if (!$role) {
                $result = 'Gagal, ID Role tidak dikenali';
            } else {
                // remove all permissions from this role
                $role->syncPermissions([]);

                // delete the role
                $role->delete();

                $result = 'Akses/Role berhasil dihapus';
            }
            return $result;
        } elseif ($jenis == 'permission') {
            $permit = Permission::find($id);
            if (!$permit) {
                $result = "Gagal, ID Permission tidak dikenali";
            } else {
                $permit->delete();
                $result = 'Permission berhasil dihapus';

                // Reset permission cache
                app()[PermissionRegistrar::class]->forgetCachedPermissions();
            }
            return $result;
        }
    }

    public function assignRolePermitToUser($id_user, $listrole, $listpermit)
    {
        $result1['success'] = false;
        $result2['success'] = false;
        if ($id_user > 0) { //$listrole && $listpermit
            $result1 = $this->assignRoleToUser($id_user, $listrole);
            $result2 = $this->assignPermitToUser($id_user, $listpermit);
        }

        if ($result1['success'] || $result2['success']) {
            $message = '';
            $message .= ' Pengguna berhasil diberikan hak akses';
            // if ($result1['success'] && $result2['success']) {
            //     $message .= ' Pengguna berhasil ' . $result1['message'] . ' dan ' . $result2['message'];

            // } else {
            //     $message .= ' Pengguna berhasil ' . $result1['message'] ? $result1['message'] : $result2['message'];
            // }
            return [
                'success' => true,
                'message' => $message,
            ];
        } else {
            return [
                'success' => false,
                'message' => $result1['message'] . ' ' . $result2['message'],
            ];
        }


        // $roles = Role::get();
        // $rolegrup = [];
        // foreach ($roles as $role) {
        //     $permitrole = $role->getPermissionNames();
        //     $conflicts = collect($listpermit)->intersect($permitrole);
        //     if ($conflicts->isNotEmpty()) {
        //         $listpermit = $conflicts;
        //     } else {
        //         $rolegrup[] = $role->name;
        //     }
        // }

        // if (count($listpermit) > 0) {
        //     $this->assignPermitToUser($id_user, $listpermit);
        // }

        // if (count($rolegrup) > 0) {
        // if (count($listrole) > 0) {
        //     $this->assignRoleToUser($id_user, $listrole);
        // }

    }

    public function assignRoleToUser($id_user, $listrole)
    {
        $pengguna = Pengguna::find($id_user);
        if ($pengguna) {
            if (empty($listrole)) {
                $pengguna->syncRoles([]);
                return [
                    'success' => true,
                    'message' => 'dibatalkan Role',
                ];
            } else {
                $pengguna->syncRoles([]);
                $pengguna->assignRole($listrole);
                return [
                    'success' => true,
                    'message' => 'diberikan Role',
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Maaf, ID Pengguna tidak valid',
            ];
        }
    }

    public function assignPermitToUser($id_user, $listpermit)
    {
        $pengguna = Pengguna::find($id_user);
        if ($pengguna) {
            if (empty($listpermit)) {
                $pengguna->syncPermissions([]);
                return [
                    'success' => true,
                    'message' => 'dibatalkan Permission',
                ];
            } else {
                $pengguna->syncPermissions([]);
                $pengguna->givePermissionTo($listpermit);
                return [
                    'success' => true,
                    'message' => 'diberikan Permission',
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Pengguna tidak tersedia di database',
            ];
        }
    }

    public function cekRole($id_user)
    {
        $pengguna = Pengguna::where('id_user', $id_user)->get();
        if ($pengguna->count() > 0) {
            $pengguna = $pengguna->first();
            if ($pengguna->getRoleNames()) {
                $data = [
                    'Pengguna' => [
                        'nama' => $pengguna->nama,
                        'nama' => $pengguna->nip,

                    ]

                ];
                return [
                    'Nama Pengguna' => $pengguna->pluck('nama')->first(),
                    'Role' => $pengguna->first()->getRoleNames()
                ];
            } else {
                return [
                    'Nama Pengguna' => $pengguna->pluck('nama')->first(),
                    'message' => 'Tidak ada Role terdaftar'
                ];
            }
        } else {
            return [

                'message' => 'Pegawai tidak terdaftar'
            ];
        }
    }

    public function cekRolePermit($roleName)
    {
        $role = Role::where('name', '=', $roleName)->first();
        if ($role) {
            return [
                'Role' => $role->name,
                'RoleAlias' => $role->alias,
                'Role_id' => $role->id,
                'Permission' => $role->getPermissionNames()->values()->toArray(),
            ];
        } else {
            return "Role tersebut tidak terdaftar";
        }
    }

    public function assignPermitToRole($roleName, $permissionName)
    {
        //$permission is on array data'
        //kasus kalau satu permission bisa ke multiple role
        $role = Role::where('name', '=', $roleName)->first();
        if ($role) {
            $role->syncPermissions([]);
            foreach ($permissionName as $permit) {
                $permission = Permission::where('name', '=', $permit)->first();
                if ($permission) {
                    $role->givePermissionTo($permissionName);
                    $data = [
                        'success' => true,
                        'message' => 'Berhasil menambahkan permission ke Role yang dipilih'
                    ];
                } else {
                    $data = [
                        'success' => false,
                        'message' => 'Maaf, Permission tidak terdaftar'
                    ];
                }
            }
        } else {
            $data = [
                'success' => false,
                'message' => 'Maaf, Role tidak terdaftar'
            ];
        }

        return $data;
    }

    // public function assignPermitToRole($roleName, $permissionName)
    // {
    //     //$permission is on array data
    //     $role = Role::where('name', '=', $roleName)->first();
    //     if ($role) {
    //         $role->syncPermissions([]);
    //         foreach ($permissionName as $permit) {
    //             $permission = Permission::where('name', '=', $permit)->first();
    //             if ($permission) {
    //                 // 1. Get permissions used by OTHER roles
    //                 $otherPermissions = Role::where('id', '!=', $role->id)
    //                     ->with('permissions')
    //                     ->get()
    //                     ->flatMap(function ($r) {
    //                         return $r->permissions->pluck('name');
    //                     })
    //                     ->unique()
    //                     ->toArray();

    //                 // 2. Check for conflicts
    //                 //$conflicts = array_intersect($permissionName, $otherPermissions);
    //                 $conflicts = collect($permissionName)->intersect($otherPermissions);
    //                 //if (!empty($conflicts)) {
    //                 if ($conflicts->isNotEmpty()) {
    //                     $data = [
    //                         'success' => false,
    //                         'message' => 'Maaf, Permission ' . $conflicts->implode(', ') . ' telah diberikan ke Role Lain'
    //                     ];
    //                 } else {
    //                     $role->givePermissionTo($permissionName);
    //                     $data = [
    //                         'success' => true,
    //                         'message' => 'berhasil menambahkan permission ke Role yang dipilih'
    //                     ];
    //                 }
    //             } else {
    //                 $data = [
    //                     'success' => false,
    //                     'message' => 'Maaf, Permission tidak terdaftar'
    //                 ];
    //             }
    //         }
    //     } else {
    //         $data = [
    //             'success' => false,
    //             'message' => 'Maaf, Role tidak terdaftar'
    //         ];
    //     }

    //     return $data;
    // }

    public function allRolewithPermission()
    {
        $dataRole = Role::get();
        foreach ($dataRole as $drole) {
        }
    }
}
