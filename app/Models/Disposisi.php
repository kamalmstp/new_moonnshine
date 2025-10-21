<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    protected $table = 'disposisi';

    protected $guarded = [];

    public function surat_masuk() {
        return $this->belongsTo(SuratMasuk::class);
    }
}
