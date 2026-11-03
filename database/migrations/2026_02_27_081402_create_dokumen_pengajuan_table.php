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
        Schema::create('dokumen_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_layanan_id')
                  ->constrained('pengajuan_layanan')
                  ->cascadeOnDelete();
    
            $table->foreignId('persyaratan_layanan_id')
                  ->constrained('persyaratan_layanan');
    
            $table->string('file_path')->nullable();
            $table->string('status_validasi')->nullable();
            $table->text('catatan_verifikator')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_pengajuan');
    }
};
