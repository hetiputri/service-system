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
     * Hasil pengisian tbl_pegawai.id_pegawai_bkn (SIMPEG) dari PNS ID di file import.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->table('siasn_import_log', function (Blueprint $table) {
            $table->unsignedInteger('id_bkn_diisi')->default(0)->after('gagal');
            $table->unsignedInteger('id_bkn_berbeda')->default(0)->after('id_bkn_diisi');
            $table->unsignedInteger('id_bkn_dilewati')->default(0)->after('id_bkn_berbeda');
            // contoh NIP yang id_pegawai_bkn-nya berbeda atau dilewati (json)
            $table->text('detail_id_bkn')->nullable()->after('kolom_tidak_dikenal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->table('siasn_import_log', function (Blueprint $table) {
            $table->dropColumn(['id_bkn_diisi', 'id_bkn_berbeda', 'id_bkn_dilewati', 'detail_id_bkn']);
        });
    }
};
