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
        Schema::create('wilayah_admin_pensiun', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pegawai')->unsigned()->nullable();
            $table->integer('id_skpd')->unsigned()->nullable();
            $table->string('kode_skpd')->nullable();
            $table->timestamps();
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
