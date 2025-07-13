<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class LaporanPegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with(['pangkatGolongan', 'mataPelajaran'])->get();

        return view('laporan.pegawai', compact('pegawai'));
    }
}
