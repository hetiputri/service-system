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
            $table->string('sk_path')->nullable()->after('nomor_tiket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_layanan', function (Blueprint $table) {
            $table->dropColumn('sk_path');
        });
    }
};