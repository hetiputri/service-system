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
        Schema::create('tbl_pengguna', function (Blueprint $table) {
            $table->string('id_user')->primary;
            $table->string('nama');
            $table->string('username')->unique();
            $table->integer('id_skpd')->nullable();
            $table->integer('id_pegawai')->nullable();
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
