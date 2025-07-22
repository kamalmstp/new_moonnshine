<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cuti extends Model
{
    protected $table = 'cuti';

    protected $guarded = [];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class);
    }

    public function getLamaCutiHariAttribute(): ?int
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $startDate = Carbon::parse($this->tanggal_mulai);
            $endDate = Carbon::parse($this->tanggal_selesai);
            return $startDate->diffInDays($endDate) + 1;
        }

        return null;
    }

    public function surat()
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_id');
    }
}
