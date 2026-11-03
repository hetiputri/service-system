<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use App\Repositories\RolePermission\RolePermitRepo;
use Illuminate\Http\Request;


class PermissionLists extends Controller
{
    protected $rolePermission;
    public function __construct(RolePermitRepo $rolePermission)
    {
        $this->rolePermission = $rolePermission;
    }
    public function render()
    {
        $permission =  $this->rolePermission->read('permission');
        return response()->json($permission);
    }

    public function createupdate(Request $request)
    {
        $request->validate([
            'permission_name' => ['required', 'string', 'max:255'],
            'permission_namealias' => ['required', 'string', 'max:255'],
            'permission_id' => ['required', 'integer'],
        ]);

        $result = '';
        $data = $request->input('permission_name');
        $data_id = $request->input('permission_id');
        $data_alias = $request->input('permission_namealias');
        if ($data_id == 0) {
            $result =  $this->rolePermission->create('permission', $data, $data_alias);
        } elseif ($data_id > 0) {
            $result =  $this->rolePermission->update('permission', $data_id, $data, $data_alias);
        }

        // if ($result) {
        //     return redirect()->route('pengaturan.akses')->with('message', $result);
        // }
        return $result;
    }

    public function delete(string $id)
    {
        if ($id) {
            $result = $this->rolePermission->deleted('permission', $id);
        } else {
            $result = 'Maaf, ID tidak dikenali.';
        }

        return response()->json([
            'message' => $result,
        ]);
    }
}
