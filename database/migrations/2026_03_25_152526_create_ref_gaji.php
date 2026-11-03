<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_gaji', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pp');
            $table->integer('masa_kerja');
            $table->unsignedBigInteger('id_gol');
            $table->integer('gaji');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_gaji');
    }
};