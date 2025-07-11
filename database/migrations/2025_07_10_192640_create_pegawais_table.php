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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->nullable()->unique();
            $table->string('nama_lengkap');
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('agama')->nullable();
            $table->enum('status_perkawinan', ['Menikah', 'Belum Menikah', 'Cerai'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();

            // Informasi Kepegawaian
            $table->enum('status_kepegawaian', ['PNS', 'PPPK', 'Honor Provinsi', 'Honor Sekolah'])->nullable();
            $table->enum('jabatan', ['Kepala Sekolah', 'Wakil Kepala', 'Guru', 'Tata Usaha', 'Operator', 'Satpam'])->nullable();
            $table->foreignId('pangkat_golongan_id')->nullable()->constrained('pangkat_golongan')->onDelete('set null');
            $table->date('tmt_pengangkatan')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('instansi_pendidikan')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->foreignId('mata_pelajaran_id')->nullable()->constrained('mata_pelajaran')->onDelete('set null');
            $table->integer('total_jam_mengajar')->nullable();

            // Relasi ke user login (jika diaktifkan)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Dokumen opsional
            $table->string('foto')->nullable();
            $table->string('sk_cpns')->nullable();
            $table->string('sk_pns')->nullable();
            $table->string('npwp')->nullable();
            $table->string('bpjs_kesehatan')->nullable();
            $table->string('bpjs_ketenagakerjaan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
