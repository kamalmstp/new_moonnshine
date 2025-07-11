<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('arsip_dokumen', function (Blueprint $table) {
            $table->enum('jenis_dokumen', [
                'Surat Masuk',
                'Surat Keluar',
                'Cuti',
                'Mutasi',
                'Pensiun',
                'Perjalanan Dinas',
                'Lainnya',
            ])->after('pegawai_id')->nullable();
            $table->string('nomor_surat')->nullable()->after('jenis_dokumen');
            $table->date('tanggal_surat')->nullable()->after('nomor_surat');
            $table->text('perihal')->nullable()->after('tanggal_surat');
            $table->string('qr_code')->nullable()->after('file_path');
        });
    }

    public function down(): void
    {
        Schema::table('arsip_dokumen', function (Blueprint $table) {
            $table->dropColumn(['jenis_dokumen', 'nomor_surat', 'tanggal_surat', 'perihal', 'qr_code']);
        });
    }
};