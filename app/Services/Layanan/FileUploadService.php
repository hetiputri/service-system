<?php

namespace App\Services\Layanan;

use Illuminate\Support\Facades\Storage;
use App\Models\DapriModel;
use App\Models\DataModel;
use Illuminate\Support\Str;

class FileUploadService
{


    public function previewFile($path, $filename = 'file.pdf')
    {
        if (!Storage::disk('private')->exists($path)) {
            abort(404);
        }

        //return Storage::disk('private')->response($path);
        return Storage::response($path);
    }
    public function uploadSk($pengajuan, $file, $prefix)
    {
        $id = $pengajuan->pegawai_id;
        $model = new DataModel();
        $nip = str_replace(' ', '', $model->getDapri($id)->value('nip'));
        $filename = $prefix . '_' . $nip . '.' . $file->getClientOriginalExtension();

        // hapus file lama kalau ada
        if ($pengajuan->sk_path && Storage::exists($pengajuan->sk_path)) {
            Storage::delete($pengajuan->sk_path);
        }

        $path = $file->storeAs('pensiun/sk/' . $nip, $filename);

        // update ke field tetap
        $pengajuan->update([
            'sk_path' => $path,
            'status_akhir' => 'selesai',
        ]);

        return $path;
    }
}
