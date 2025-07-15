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
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->string('tema')->nullable()->after('nama_pelatihan');
            $table->string('tempat_pelatihan')->nullable()->after('penyelenggara');
            $table->date('tanggal_mulai')->nullable()->after('tahun');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            $table->date('surat_tugas')->nullable()->after('tanggal_selesai');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->dropColumn(['tema', 'tempat_pelatihan','tanggal_mulai','tanggal_selesai','surat_tugas']);
        });
    }
};
