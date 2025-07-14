<?php

use Illuminate\Support\Facades\Route;
use App\Models\Cuti;
use App\Models\Mutasi;
use App\Http\Controllers\LaporanPegawaiController;
use App\Http\Controllers\ExportController;

Route::get('/moonshine/laporan-pegawai/export/xlsx', [ExportController::class, 'exportPegawaiXlsx'])
    ->name('moonshine.laporan.pegawai.export.xlsx');

Route::get('/moonshine/laporan-pegawai/export/pdf', [ExportController::class, 'exportPegawaiPdf'])
    ->name('moonshine.laporan.pegawai.export.pdf');

Route::get('/laporan/pegawai', [LaporanPegawaiController::class, 'index'])->name('laporan.pegawai');

Route::get('/arsip/{id}', function ($id) {
    $arsip = \App\Models\ArsipDokumen::findOrFail($id);
    return view('arsip.detail', compact('arsip'));
})->name('arsip.show');

Route::get('/cuti/{cuti}/surat', function (Cuti $cuti) {
    return view('surat.cuti', compact('cuti'));
})->name('cuti.surat');

Route::get('/mutasi/{mutasi}/surat', function (Mutasi $mutasi) {
    return view('surat.mutasi', compact('mutasi'));
})->name('mutasi.surat');

Route::get('/', function () {
    return view('welcome');
});
