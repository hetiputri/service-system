<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_third';

    /**
     * Run the migrations.
     *
     * Riwayat sync data dari SIASN ke SIMPEG (tbl_pegawai): satu baris per kolom yang diubah,
     * dengan nilai lama dan baru apa adanya di tbl_pegawai supaya perubahan bisa ditelusuri/dikembalikan.
     */
    public function up(): void
    {
        if (Schema::connection($this->connection)->hasTable('siasn_sync_log')) {
            return;
        }

        Schema::connection($this->connection)->create('siasn_sync_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nip', 30)->index();
            $table->unsignedBigInteger('id_pegawai')->index(); // tbl_pegawai.id_pegawai di SIMPEG
            $table->string('id_siasn', 64)->nullable();        // siasn_data_utama.id
            $table->string('arah', 20);                         // simpeg = data SIASN disalin ke SIMPEG
            $table->string('atribut', 50);                      // key data di dashboard, mis. agama
            $table->string('tabel', 50);                        // tabel SIMPEG yang diubah
            $table->string('kolom', 50);                        // kolom SIMPEG yang diubah, mis. id_agama
            $table->text('nilai_lama')->nullable();
            $table->text('nilai_baru')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('nama_user', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('siasn_sync_log');
    }
};
