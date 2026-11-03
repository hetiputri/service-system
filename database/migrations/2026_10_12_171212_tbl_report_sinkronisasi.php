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
        Schema::create('report_sinkronisasi', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_proses')->nullable();
            $table->integer('id_data')->nullable();
            $table->string('nama_data')->nullable();
            $table->string('status')->nullable();
            $table->string('pesan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_sinkronisasi');
    }
};
