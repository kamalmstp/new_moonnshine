<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    protected $table = 'cuti';

    protected $guarded = [];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class);
    }

    public function surat()
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_id');
    }
}
