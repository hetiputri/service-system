<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_disiplin', function (Blueprint $table) {
            $table->id();

            // RELASI
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pengajuan_id')->nullable();

            // DATA SURAT
            $table->string('nomor_surat', 100);
            $table->date('tanggal_surat');

            // DATA PEJABAT
            $table->string('pejabat_nama');
            $table->string('pejabat_nip', 50);
            $table->string('pejabat_pangkat', 100);
            $table->string('pejabat_jabatan');

            // DATA PEGAWAI
            $table->string('nama');
            $table->string('nip', 50);
            $table->string('pangkat', 100)->nullable();
            $table->string('jabatan')->nullable();
            $table->string('instansi')->nullable();

            // FILE
            $table->string('file_pdf')->nullable();

            $table->timestamps();

            // INDEX (biar cepat 🔥)
            $table->index('user_id');
            $table->index('pengajuan_id');

            // OPTIONAL FK (kalau mau lebih strict)
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('pengajuan_id')->references('id')->on('pengajuan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_disiplin');
    }
};