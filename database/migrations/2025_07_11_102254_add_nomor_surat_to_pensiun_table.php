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
        Schema::table('pensiun', function (Blueprint $table) {
            $table->date('tanggal_pensiun')->nullable()->after('tanggal_usulan');
            $table->string('status_pengajuan')->default('diproses')->after('tanggal_pensiun');
            $table->text('keterangan')->nullable()->after('status_pengajuan');
            $table->string('nomor_surat')->nullable()->after('keterangan');
            $table->boolean('arsipkan')->default(false)->after('sk_pensiun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pensiun', function (Blueprint $table) {
            $table->dropColumn(['tanggal_pensiun','status_pengajuan','keterangan','nomor_surat', 'aripkan']);
        });
    }
};
