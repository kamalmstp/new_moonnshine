<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPangkat extends Model
{
    protected $table = 'riwayat_pangkat';

    protected $guarded = [];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class);
    }

    public function pangkatGolongan(){
        return $this->belongsTo(PangkatGolongan::class);
    }
}
