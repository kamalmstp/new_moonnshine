<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class ArsipDokumen extends Model
{
    protected $table = 'arsip_dokumen';

    protected $guarded = [];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class);
    }

}