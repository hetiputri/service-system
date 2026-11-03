<?php

namespace App\Repositories\RolePermission;

interface RolePermitRepo
{
    /**
     * Get data by ID and jenis_data
     * @param $id_pegawai
     * @param $jenis_data
     * @return mixed
     */

    public function pengguna();
    public function caripengguna($nama, $nip);
    public function getPengguna($id_user);
    public function read($jenis);
    public function create($jenis, $data, $data_alias);
    public function update($jenis, $id, $data, $data_alias);
    public function deleted($jenis, $id);
    public function assignRolePermitToUser($id_user, $listrole, $listpermit);
    public function assignRoleToUser($id_user, $roleName);
    public function assignPermitToUser($id_user, $permitName);
    public function cekRole($id_user);
    public function cekRolePermit($roleName);
    public function assignPermitToRole($roleName, $permissionName);
    public function allRolewithPermission();
}
