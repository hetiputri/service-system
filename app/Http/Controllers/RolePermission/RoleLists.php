<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Repositories\RolePermission\RolePermitRepo;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;

class RoleLists extends Controller
{
    protected $rolePermission;
    public function __construct(RolePermitRepo $rolePermission)
    {
        $this->rolePermission = $rolePermission;
        //$this->middleware('permission:pengaturan-akses')->only('create', 'update', 'delete', 'Penggunatambahedit', 'PenggunaRoleUpdate', 'AssignPermissionOnRole');
    }

    public function render()
    {

        $roles = $this->rolePermission->read('role');
        $permissions = $this->rolePermission->read('permission');

        return view('pages.rolepermission.akses', compact('roles', 'permissions'));
    }

    public function renderAkun()
    {
        $roles = $this->rolePermission->read('role');
        $allpengguna = $this->rolePermission->pengguna();
        $rolepermiss = $this->rolePermission->read('rolepermiss');
        //dd($rolepermiss);
        return view('pages.rolepermission.aksespengguna', compact('roles', 'allpengguna', 'rolepermiss'));
    }

    public function createupdate(Request $request)
    {
        $request->validate([
            'rolename' => ['required', 'string', 'max:255'],
            'rolenamealias' => ['required', 'string', 'max:255'],
            'roleid' => ['required', 'integer'],
        ]);

        $data = $request->input('rolename');
        $data_id = $request->input('roleid');
        $data_alias = $request->input('rolenamealias');

        if ($data_id == 0) {
            $result = $this->rolePermission->create('role', $data, $data_alias);
        } elseif ($data_id > 0) {
            $result = $this->rolePermission->update('role', $data_id, $data, $data_alias);
        }

        return $result;

        //return redirect()->route('pengaturan.akses')->with('message', $result);
        // return redirect()->route('pengaturan.akses')->with([
        //     'swal' => [
        //         'icon' => 'success',
        //         'title' => 'Success!',
        //         'text' => 'Data saved successfully.'
        //     ]
        // ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'rolename' => ['required', 'string', 'max:255'],
            'rolenamealias' => ['required', 'string', 'max:255'],
            'roleid' => ['required', 'integer'],
        ]);

        $id = $request->input('roleid');
        $data = $request->input('rolename');
        $data_alias = $request->input('rolenamealias');
        $result = $this->rolePermission->update('role', $id, $data, $data_alias);

        return redirect()->route('pengaturan.akses')->with('message', $result);
    }

    public function delete(string $id)
    {
        $result = $this->rolePermission->deleted('role', $id);

        return response()->json([
            'message' => $result,
        ]);
    }

    public function Penggunasearch(Request $request)
    {
        $nama = $request->input('nama_pegawai');
        $nip = $request->input('nip');
        if ($nama || $nip) {
            $pengguna = $this->rolePermission->caripengguna($nama, $nip);
            return response()->json($pengguna);
        }
    }

    public function Penggunatambahedit(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'integer'],
            'rolename' => ['required_without:permission_name', 'nullable', 'array'],
            'permission_name' => ['required_without:rolename', 'nullable', 'array'],
        ]);

        $user_id = $request->input('user_id');
        $listpermission = $request->input('permission_name');
        $listrole = $request->input('rolename');
        if (empty($request->rolename) && empty($request->permission_name)) {
            return [
                'message' => 'Tidak ada role atau permission yang dipilih',
                'success' => false
            ];
        }

        $result = $this->rolePermission->assignRolePermitToUser($user_id, $listrole, $listpermission);

        return $result;
    }

    public function Penggunahapus(string $id_user)
    {
        $user = Pengguna::where('id_user', $id_user)->first();
        // Remove all roles
        $user->syncRoles([]);

        // Remove all permissions
        $user->syncPermissions([]);
    }


    public function CekRoleUser(string $id_user)
    {
        //tampilkan nama_pegawai,nip, role dibawahnya, permission dibawah role
        $dapeg = $this->rolePermission->getPengguna($id_user);

        return response()->json($dapeg);
    }

    public function AssignPermissionOnRole(Request $request)
    {
        $rolename = $request->rolename;
        $permission = $request->permission_name;
        $result = $this->rolePermission->assignPermitToRole($rolename, $permission);

        if ($result) {
            //return redirect()->route('pengaturan.akses'); //->with('message', $result);
            return response()->json($result);
        }
    }

    public function CekPermissionOnRole(Request $request)
    {
        $rolename = $request->role_name;
        $result = $this->rolePermission->cekRolePermit($rolename);
        return $result; //response()->json($result);
    }



    // public function TestRole()
    // {
    //     if (Auth::user()->hasRole('admin-master')) { for Role user
    // if (Auth::user()->can('tambah data gaji')) { for Permission user
    //$this->middleware('permission:tambah data gaji')->only('store');
    //$this->middleware('permission:tambah data gaji|proses data gaji')->only('store');
    //$user->can('edit posts'); for permission
    //         //$permission =  Auth::user()->getNamePermissionsViaRoles();
    //         $nama = Auth::user()->nama;
    //         return response()->json([
    //             'message' => 'Selamat Datang ' . $nama . '. Anda memiliki akses sebagai Admin Master',

    //         ]);
    //     } else {
    //         return response()->json([
    //             'message' => 'Anda tidak memiliki akses ke route ini',
    //         ], 403);
    //     }
    // }

    //    

    // public function process()
    // {
    //     // only users with permission can reach here
    // }
}

// public function __construct()
// {
//     $this->middleware('permission:lihat data gaji')->only('index');
//     $this->middleware('permission:tambah data gaji')->only('store');
//     $this->middleware('permission:edit data gaji')->only('update');
//     $this->middleware('permission:hapus data gaji')->only('destroy');
// }
