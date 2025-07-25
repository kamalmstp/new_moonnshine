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
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->string('jenis_cuti'); // tahunan, sakit, melahirkan, dll
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan')->nullable();
            $table->string('status')->default('diproses'); // diproses, disetujui, ditolak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuti');
    }
};
