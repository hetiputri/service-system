<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_third';

    // nama unit organisasi, instansi, jabatan dan pendidikan di export SIASN bisa lebih dari 255 karakter
    private const KOLOM = [
        'pendidikanTerakhirNama',
        'instansiIndukNama',
        'satuanKerjaIndukNama',
        'kanregNama',
        'instansiKerjaNama',
        'satuanKerjaKerjaNama',
        'unorNama',
        'unorIndukNama',
        'jabatanNama',
        'jabatanStrukturalNama',
        'jabatanFungsionalNama',
        'jabatanFungsionalUmumNama',
        'lokasiKerja',
        'kpknNama',
        'ktuaNama',
        'taspenNama',
        'kppnNama',
        'jabatanAsn',
        'subJftNama',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->table('siasn_data_utama', function (Blueprint $table) {
            foreach (self::KOLOM as $kolom) {
                $table->text($kolom)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Isian yang lebih dari 255 karakter akan gagal dikembalikan ke varchar(255).
     */
    public function down(): void
    {
        Schema::connection($this->connection)->table('siasn_data_utama', function (Blueprint $table) {
            foreach (self::KOLOM as $kolom) {
                $table->string($kolom, 255)->nullable()->change();
            }
        });
    }
};
