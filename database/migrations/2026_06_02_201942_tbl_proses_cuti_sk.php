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
        Schema::create('proses_layanan_cuti', function (Blueprint $table) {
            $table->id();
            $table->string('nama_admin', 255);
            $table->date('tgl_kerja')->nullable();
            $table->date('tgl_input')->nullable();
            $table->string('scan_sk', 255)->nullable();
            $table->integer('id_pemroses')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proses_layanan_cuti');
    }
};
