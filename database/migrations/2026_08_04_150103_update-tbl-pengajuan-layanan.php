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
        Schema::table('pengajuan_layanan', function (Blueprint $table) {
            $table->integer('id_pangkat')->nullable()->after('pegawai_id');
            $table->integer('id_jabatan')->nullable()->after('id_pangkat');
            $table->integer('id_skpd')->nullable()->after('id_jabatan');
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
