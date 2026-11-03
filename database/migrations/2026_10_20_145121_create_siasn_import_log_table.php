<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database SIASN, bersama tabel siasn_data_utama
    protected $connection = 'mysql_third';

    /**
     * Run the migrations.
     *
     * Riwayat import file export SIASN (CSV/Excel) ke siasn_data_utama yang diproses lewat queue.
     */
    public function up(): void
    {
        if (Schema::connection($this->connection)->hasTable('siasn_import_log')) {
            return;
        }

        Schema::connection($this->connection)->create('siasn_import_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_file', 255);
            $table->string('path', 255);
            // perbarui = upsert per NIP, ganti = upsert lalu hapus data lama yang tidak ada di file
            $table->string('mode', 20)->default('perbarui');
            // menunggu, diproses, selesai, gagal
            $table->string('status', 20)->default('menunggu');
            $table->unsignedInteger('total_baris')->default(0);
            $table->unsignedInteger('berhasil')->default(0);
            $table->unsignedInteger('gagal')->default(0);
            $table->unsignedInteger('dihapus')->default(0);
            $table->text('pesan')->nullable();
            // contoh baris yang gagal dan kolom file yang tidak dikenali (json)
            $table->text('detail_gagal')->nullable();
            $table->text('kolom_tidak_dikenal')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('nama_user', 255)->nullable();
            $table->timestamp('mulai_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('siasn_import_log');
    }
};
