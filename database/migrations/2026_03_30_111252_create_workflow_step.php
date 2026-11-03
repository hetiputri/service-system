<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_step', function (Blueprint $table) {
            $table->id('id_step');

            $table->unsignedBigInteger('id_layanan');
            $table->string('nama_step');
            $table->integer('urutan_step');
            $table->string('role_pemroses')->nullable();
            $table->integer('sla_step_hari')->nullable();

            $table->timestamps();

            // FK ke layanan
            $table->foreign('id_layanan')
                ->references('id')
                ->on('layanan')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_step');
    }
};