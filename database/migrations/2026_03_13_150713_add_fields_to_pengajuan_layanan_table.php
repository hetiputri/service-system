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
        Schema::table('pengajuan_layanan', function (Blueprint $table) {
            // $table->integer('current_step')->default(1)->after('layanan_id');
            $table->string('ttd_mode')->nullable()->after('current_step');
            $table->timestamp('submitted_at')->nullable()->after('ttd_mode');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_layanan', function (Blueprint $table) {
            $table->dropColumn([
                'current_step',
                'ttd_mode',
                'submitted_at',
            ]);
        });
    }
};
