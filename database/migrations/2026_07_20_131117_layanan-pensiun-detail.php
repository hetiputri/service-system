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
        Schema::create('layanan_pensiun_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('pengajuan_id');
            $table->integer('pegawai_id');
            $table->date('tmt_pensiun')->nullable();
            $table->string('no_sk')->nullable();
            $table->date('tgl_sk')->nullable();
            $table->date('tgl_pertek')->nullable();
            $table->longText('pejabat_ttd')->nullable();
            $table->integer('id_keterangan_jabatan');
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
