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
        Schema::create('layanan_cuti_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('pengajuan_id');
            $table->integer('kaopd_id')->nullable();
            $table->integer('atasan_langsung_id')->nullable();
            $table->integer('lama_cuti')->nullable();
            $table->string('satuan_waktu', 40)->nullable();
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_selesai')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->string('no_telepon', 30)->nullable();
            $table->longText('alamat')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
