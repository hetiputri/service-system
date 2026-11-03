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
        Schema::table('dokumen_pengajuan', function (Blueprint $table) {
                    // data fleksibel (snapshot)
                $table->json('data_json')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_pengajuan', function (Blueprint $table) {
            $table->dropColumn('data_json');
        });
    }
};
