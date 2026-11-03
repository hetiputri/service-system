<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_log', function (Blueprint $table) {
            $table->id('id_log');

            $table->unsignedBigInteger('id_pengajuan');
            $table->unsignedBigInteger('id_step');
            $table->unsignedBigInteger('id_user_proses');

            // $table->string('aksi');
            $table->enum('aksi', ['draft', 'diajukan', 'revisi','ditolak','selesai']);
            $table->text('catatan')->nullable();
            $table->dateTime('tanggal_proses');

            $table->timestamps();

            // FK ke pengajuan_layanan
            $table->foreign('id_pengajuan')
                ->references('id')
                ->on('pengajuan_layanan')
                ->cascadeOnDelete();

            // FK ke workflow_step
            $table->foreign('id_step')
                ->references('id_step')
                ->on('workflow_step')
                ->cascadeOnDelete();

            // FK ke users
            $table->foreign('id_user_proses')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_log');
    }
};