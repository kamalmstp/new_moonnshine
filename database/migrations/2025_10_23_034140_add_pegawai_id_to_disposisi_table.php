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
        Schema::table('disposisi', function (Blueprint $table) {
            $table->string('pengirim')->nullable()->change();
            $table->string('penerima')->nullable()->change();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawai')->onDelete('set null')->after('penerima');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposisi', function (Blueprint $table) {
            $table->dropColumn(['pegawai_id']);
        });
    }
};
