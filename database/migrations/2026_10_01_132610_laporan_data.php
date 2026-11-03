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
        Schema::create('laporan_data', function (Blueprint $table) {
            $table->id();
            $table->string('nama_data')->nullable();
            $table->string('kategori')->nullable();
            $table->integer('nilai_data')->nullable();
            $table->integer('id_status')->nullable();
            $table->integer('status_aktif')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
