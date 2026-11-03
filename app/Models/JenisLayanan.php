<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
{
    protected $table = 'jenis_layanan';

    protected $fillable = [
        'nama_layanan',
        'deskripsi'
    ];

    public function layanan()
    {
        return $this->hasMany(Layanan::class, 'id_jenis_layanan');
    }
}