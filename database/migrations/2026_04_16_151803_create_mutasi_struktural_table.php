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
        Schema::create('mutasi_struktural', function (Blueprint $table) {
            $table->id();
            $table->string('kode_konsep', 50)->unique()->nullable();
            $table->string('nama_konsep', 200);
            $table->date('tanggal_konsep');
            $table->text('keterangan')->nullable();
            $table->tinyInteger('status_konsep')
                ->nullable()
                ->default(0)
                ->comment('0=Belum Verifikasi, 1=Disetujui, 2=Ditolak');
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_struktural');
    }
};
