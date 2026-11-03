<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    protected $disk = 'public';

    public function uploadPegawai($file, $nip, $kategori)
    {
        $filename = $this->generateFileName($kategori, $file->extension());

        $path = $file->storeAs(
            "pegawai/{$nip}/{$kategori}",
            $filename,
            $this->disk
        );

        return Storage::url($path);
    }

    public function uploadLayanan($file, $layanan, $idPengajuan, $kategori)
    {
        $filename = $this->generateFileName($kategori, $file->extension());

        $path = $file->storeAs(
            "layanan/{$layanan}/{$idPengajuan}",
            $filename,
            $this->disk
        );

        return Storage::url($path);
    }

    public function uploadTemp($file)
    {
        $filename = Str::uuid() . '.' . $file->extension();

        $path = $file->storeAs(
            "temp",
            $filename,
            $this->disk
        );

        return Storage::url($path);
    }

    private function generateFileName($prefix, $extension)
    {
        return $prefix . '_' . time() . '_' . Str::random(5) . '.' . $extension;
    }

    public function replacePegawai($file, $nip, $kategori)
    {
        $folder = "pegawai/{$nip}/{$kategori}";

        // Hapus semua file lama di folder kategori
        Storage::disk($this->disk)->deleteDirectory($folder);

        $filename = $this->generateFileName($kategori, $file->extension());

        $path = $file->storeAs(
            $folder,
            $filename,
            $this->disk
        );

        return Storage::url($path);
    }
    public function replaceLayanan($file, $layanan, $idPengajuan, $kategori)
    {
        $folder = "layanan/{$layanan}/{$idPengajuan}/{$kategori}";

        Storage::disk($this->disk)->deleteDirectory($folder);

        $filename = $this->generateFileName($kategori, $file->extension());

        $path = $file->storeAs(
            $folder,
            $filename,
            $this->disk
        );

        return Storage::url($path);
    }
    public function deleteLayananFolder($layanan, $idPengajuan)
    {
        $folder = "layanan/{$layanan}/{$idPengajuan}";

        return Storage::disk($this->disk)->deleteDirectory($folder);
    }
}