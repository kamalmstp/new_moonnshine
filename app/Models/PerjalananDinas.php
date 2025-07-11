<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerjalananDinas extends Model
{
    protected $table = 'perjalanan_dinas';

    protected $guarded = [];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class);
    }
}
