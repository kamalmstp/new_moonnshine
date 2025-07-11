<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KompetensiGuru extends Model
{
    protected $table = 'kompetensi_guru';

    protected $guarded = [];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class);
    }
}
