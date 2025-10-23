<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pangkatGolongan() { return $this->belongsTo(PangkatGolongan::class); }
    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class); }

    public function riwayatPendidikan() { return $this->hasMany(RiwayatPendidikan::class); }
    public function riwayatJabatan() { return $this->hasMany(RiwayatJabatan::class); }
    public function riwayatPangkat() { return $this->hasMany(RiwayatPangkat::class); }
    public function pelatihan() { return $this->hasMany(Pelatihan::class); }
    public function keahlian() { return $this->hasMany(KeahlianPegawai::class); }
    public function kompetensiGuru() { return $this->hasMany(KompetensiGuru::class); }
    public function cuti() { return $this->hasMany(Cuti::class); }
    public function mutasi() { return $this->hasMany(Mutasi::class); }
    public function pensiun() { return $this->hasMany(Pensiun::class); }
    public function perjalananDinas() { return $this->hasMany(PerjalananDinas::class); }
    public function arsipDokumen() { return $this->hasMany(ArsipDokumen::class); }
    public function disposisi() { return $this->hasMany(Disposisi::class); }
}
