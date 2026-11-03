<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mutasi_struktural_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_konsep')
                ->nullable()
                ->constrained('mutasi_struktural')
                ->nullOnDelete();
            $table->integer('urutan');
            $table->unsignedBigInteger('id_pegawai');
            $table->unsignedBigInteger('dari_id_jabatan');
            $table->unsignedBigInteger('ke_id_jabatan');
            $table->string('jenis_mutasi', 30);
            $table->string('alasan', 255);
            $table->tinyInteger('status_validasi')
                ->default(0)
                ->comment('0=Belum Verifikasi, 1=Disetujui, 2=Ditolak');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_struktural_detail');
    }
};
