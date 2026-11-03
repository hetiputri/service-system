<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dpcp', function (Blueprint $table) {

            $table->text('signature_base64')->nullable()->after('status_pengajuan');
            $table->string('signature_file')->nullable()->after('signature_base64');

            $table->integer('signature_x')->nullable()->after('signature_file');
            $table->integer('signature_y')->nullable()->after('signature_x');

            $table->timestamp('signed_at')->nullable()->after('signature_y');

        });
    }

    public function down(): void
    {
        Schema::table('dpcp', function (Blueprint $table) {

            $table->dropColumn([
                'signature_base64',
                'signature_file',
                'signature_x',
                'signature_y',
                'signed_at',
            ]);

        });
    }
};
