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
            $table->string('no_sertifikat')->nullable()->after('nama_keahlian');
            $table->string('sertifikat')->nullable()->after('no_sertifikat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keahlian_pegawai', function (Blueprint $table) {
            $table->dropColumn(['no_sertifikat','sertifikat']);
        });
    }
};
