<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';

    protected $guarded = [];

    public function disposisi() { 
        return $this->hasMany(Disposisi::class); 
    }
}
