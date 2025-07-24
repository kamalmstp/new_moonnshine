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
        Schema::table('keahlian_pegawai', function (Blueprint $table) {
            $table->string('modul')->nullable()->after('sertifikat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keahlian_pegawai', function (Blueprint $table) {
            $table->dropColumn(['modul']);
        });
    }
};
