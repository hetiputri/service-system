<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_pp_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tahun_berlaku_mulai');
            $table->date('tahun_berlaku_sampai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_pp_gaji');
    }
};