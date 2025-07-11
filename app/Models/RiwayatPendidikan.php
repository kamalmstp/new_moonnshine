<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPendidikan extends Model
{
    protected $table = 'riwayat_pendidikan';

    protected $guarded = [];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class);
    }
}