<?php

namespace App\Helpers;

class DokumenHelper
{
    public static function resolvePersyaratanId($jenis, $request)
    {
        // $user = auth()->user();
        $role = $request->role;

        if (!auth()->user()->hasRole($role)) {
            abort(403, 'Role tidak valid');
        }

        $id = config("dokumen.$jenis.$role");

        if (!$id) {
            abort(500, "Mapping tidak ditemukan ($jenis - $role)");
        }

        return $id;
    }
}
