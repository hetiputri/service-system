<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_pidana', function (Blueprint $table) {
            $table->id();

            // RELASI
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('pengajuan_id')->nullable();
            $table->unsignedBigInteger('id_ttd_oleh')->nullable();

            // SURAT
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat')->nullable();

            // 🔹 PEJABAT
            $table->string('pejabat_nama')->nullable();
            $table->string('pejabat_nip')->nullable();
            $table->string('pejabat_pangkat')->nullable();
            $table->string('pejabat_jabatan')->nullable();

            // 🔹 PEGAWAI
            $table->string('nama')->nullable();
            $table->string('nip')->nullable();
            $table->string('pangkat')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('instansi')->nullable();

            // FILE
            $table->string('file_pdf')->nullable();

            // OPTIONAL
            $table->string('status')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_pidana');
    }
};