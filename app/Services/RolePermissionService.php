<?php

namespace App\Services;


use App\Repositories\RolePermission\RolePermitRepo;


class RolePermissionService
{
    protected $rolePermitRepo;

    public function __construct(RolePermitRepo $rolePermitRepo)
    {
        $this->rolePermitRepo = $rolePermitRepo;
    }

    public function ada()
    {
        $data = '';
        $id = '';
        $id_user = '';
        $roleName = '';
        $permissionName = '';
        $this->rolePermitRepo->read('role');
        $this->rolePermitRepo->read('permission');
        $this->rolePermitRepo->create('permission', $data);
        $this->rolePermitRepo->create('role', $data);
        $this->rolePermitRepo->update('permission', $id, $data);
        $this->rolePermitRepo->update('role', $id, $data);
        $this->rolePermitRepo->assignRoleToUser($id_user, $roleName);
        $this->rolePermitRepo->cekRole($id_user); //cek Role yang dimili user ybs
        $this->rolePermitRepo->cekRolePermit($roleName); //cek Role memiliki permission apa saja
        $this->rolePermitRepo->assignPermitToRole($roleName, $permissionName); //beri permission ke Role
    }
}
