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
            $table->unique('nomor_tiket', 'pengajuan_layanan_nomor_tiket_unique');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_layanan', function (Blueprint $table) {
            $table->dropUnique('pengajuan_layanan_nomor_tiket_unique');
        });
    }
};
