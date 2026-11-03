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
        Schema::create('pengajuan_layanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')->constrained('layanan');
            $table->foreignId('pegawai_id')->constrained('pegawai');
            $table->string('id_jenis_layanan')->nullable();
            $table->string('data_pribadi')->nullable();
            $table->string('riwayat_keluarga')->nullable();
            $table->string('data_cpns')->nullable();
            $table->string('data_pns')->nullable();
            $table->string('riwayat_pangkat')->nullable();
            $table->string('riwayat_PMK')->nullable();
    
            $table->dateTime('tanggal_pengajuan')->useCurrent();
            $table->string('status_akhir')->nullable();
            $table->integer('current_step')->nullable();
            $table->integer('form_step')->nullable();
            $table->string('nomor_tiket')->nullable()->index();
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_layanan');
    }
};
