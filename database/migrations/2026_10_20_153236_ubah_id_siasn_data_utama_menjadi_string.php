<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_third';

    /**
     * Run the migrations.
     *
     * Tabel siasn_data_utama yang terlanjur dibuat dengan id auto increment + kolom sync_id:
     * id diganti menjadi ID ASN dari SIASN (string, primary key) dan sync_id dihapus.
     * Tabel yang dibuat dari migration create versi terbaru sudah benar, migration ini dilewati.
     */
    public function up(): void
    {
        $schema = Schema::connection($this->connection);
        if (!$schema->hasTable('siasn_data_utama') || !$schema->hasColumn('siasn_data_utama', 'sync_id')) {
            return;
        }

        // lepas auto increment dulu, primary key tidak bisa dihapus selama kolomnya auto increment
        $schema->table('siasn_data_utama', function (Blueprint $table) {
            $table->unsignedInteger('id')->change();
        });
        $schema->table('siasn_data_utama', function (Blueprint $table) {
            $table->dropPrimary();
        });
        $schema->table('siasn_data_utama', function (Blueprint $table) {
            $table->string('id', 64)->change();
        });

        // ID ASN yang sebelumnya disimpan di sync_id; data tanpa sync_id memakai NIP supaya primary key tetap unik
        DB::connection($this->connection)->table('siasn_data_utama')
            ->update(['id' => DB::raw('COALESCE(sync_id, nipBaru)')]);

        $schema->table('siasn_data_utama', function (Blueprint $table) {
            $table->primary('id');
            $table->dropIndex(['sync_id']);
            $table->dropColumn('sync_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * ID string tidak bisa dikembalikan menjadi angka auto increment, jadi hanya bisa dibatalkan saat tabel kosong.
     */
    public function down(): void
    {
        $schema = Schema::connection($this->connection);
        if (!$schema->hasTable('siasn_data_utama') || $schema->hasColumn('siasn_data_utama', 'sync_id')) {
            return;
        }
        if (DB::connection($this->connection)->table('siasn_data_utama')->exists()) {
            throw new RuntimeException('siasn_data_utama sudah berisi data, id string tidak bisa dikembalikan ke auto increment.');
        }

        // primary key tetap dipertahankan: MySQL mewajibkan kolom auto increment memiliki key
        $schema->table('siasn_data_utama', function (Blueprint $table) {
            $table->increments('id')->change();
            $table->string('sync_id', 64)->nullable()->index()->after('id');
        });
    }
};
