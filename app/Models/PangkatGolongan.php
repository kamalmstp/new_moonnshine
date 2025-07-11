<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PangkatGolongan extends Model
{
    protected $table = 'pangkat_golongan';

    protected $guarded = [];

    public function pegawai() {
        return $this->hasMany(Pegawai::class);
    }

    public function riwayatPangkat() {
        return $this->hasMany(RiwayatPangkat::class);
    }

    public function getPangkatGolonganAttribute(): string
    {
        return "{$this->nama_pangkat} / {$this->golongan}";
    }
}