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
        Schema::create('proses_layanan_cuti_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('id_proses')->unsigned()->nullable();
            $table->integer('pengajuan_id')->unsigned()->nullable();
            $table->date('tgl_proses')->nullable();
            $table->string('no_sk', 255)->nullable();
            $table->date('tgl_sk')->nullable();
            $table->string('pejabat_sk', 255)->nullable();
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
        Schema::dropIfExists('proses_layanan_cuti_detail');
    }
};
