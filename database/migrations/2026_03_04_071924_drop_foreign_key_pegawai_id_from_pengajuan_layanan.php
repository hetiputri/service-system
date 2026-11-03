<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_layanan', function (Blueprint $table) {
            $table->dropForeign(['pegawai_id']);
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_layanan', function (Blueprint $table) {
            $table->foreign('pegawai_id')
                ->references('id')
                ->on('pegawai')
                ->onDelete('cascade');
        });
    }
};