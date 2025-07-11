<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pensiun extends Model
{
    protected $table = 'pensiun';

    protected $guarded = [];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class);
    }
}
