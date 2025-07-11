<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeahlianPegawai extends Model
{
    protected $table = 'keahlian_pegawai';

    protected $guarded = [];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class);
    }
}
