<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dpcp', function (Blueprint $table) {
            $table->id();

            // relasi utama
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('pengajuan_layanan_id')->nullable();
            $table->unsignedBigInteger('jenis_pensiun_id')->nullable();

            // 🔥 snapshot data dari API
            $table->json('data_pribadi')->nullable();
            $table->json('riwayat_keluarga')->nullable();
            $table->json('data_cpns')->nullable();
            $table->json('data_pns')->nullable();

            // hasil perhitungan
            $table->date('tmt_pensiun')->nullable();

            // input user
            $table->text('alamat_sesudah_pensiun')->nullable();

            // tanda tangan
            // $table->longText('signature_base64')->nullable();
            // $table->string('signature_file')->nullable();
            // $table->timestamp('signed_at')->nullable();

            // status dokumen
            $table->enum('status_pengajuan', ['draft', 'locked'])->default('draft');

            $table->timestamps();

            // index biar cepat
            $table->index('pegawai_id');
            $table->index('pengajuan_layanan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dpcp');
    }
};
