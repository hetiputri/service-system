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
     * Patokan data siasn_data_utama adalah id (ID ASN SIASN). Unique pada nipBaru membuat upsert MySQL
     * ikut mencocokkan NIP, jadi diganti menjadi index biasa. Tabel dari migration create versi terbaru sudah benar.
     */
    public function up(): void
    {
        $schema = Schema::connection($this->connection);
        if (!$schema->hasIndex('siasn_data_utama', 'siasn_data_utama_nipbaru_unique')) {
            return;
        }

        $schema->table('siasn_data_utama', function (Blueprint $table) {
            $table->dropUnique(['nipBaru']);
            $table->index('nipBaru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $schema = Schema::connection($this->connection);
        if (!$schema->hasIndex('siasn_data_utama', 'siasn_data_utama_nipbaru_index')) {
            return;
        }

        $schema->table('siasn_data_utama', function (Blueprint $table) {
            $table->dropIndex(['nipBaru']);
            $table->unique('nipBaru');
        });
    }
};
