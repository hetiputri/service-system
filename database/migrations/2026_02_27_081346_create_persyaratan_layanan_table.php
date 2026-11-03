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
        Schema::create('persyaratan_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('layanan_id');

            $table->string('nama_persyaratan');
            $table->string('label')->nullable();
            $table->string('tipe')->nullable();
            $table->boolean('wajib')->default(true);
            $table->string('metode_validasi')->nullable();
            $table->string('sumber')->nullable();
            $table->string('is_active')->default(true);
            $table->string('penyedia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persyaratan_layanan');
    }
};
