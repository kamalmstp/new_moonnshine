<?php

use Illuminate\Support\Facades\Route;
use App\Models\{Cuti, Mutasi, Pensiun, PerjalananDinas};
use App\Http\Controllers\LaporanPegawaiController;
use App\Http\Controllers\SuratMasukViewController;
use App\Http\Controllers\SuratKeluarViewController;
use App\Http\Controllers\ExportController;

Route::get('/moonshine/laporan-pegawai/export/xlsx', [ExportController::class, 'exportPegawaiXlsx'])
    ->name('moonshine.laporan.pegawai.export.xlsx');
Route::get('/moonshine/laporan-pegawai/export/pdf', [ExportController::class, 'exportPegawaiPdf'])
    ->name('moonshine.laporan.pegawai.export.pdf');

Route::get('/moonshine/laporan-cuti/export/xlsx', [ExportController::class, 'exportCutiXlsx'])
    ->name('moonshine.laporan.cuti.export.xlsx');
Route::get('/moonshine/laporan-cuti/export/pdf', [ExportController::class, 'exportCutiPdf'])
    ->name('moonshine.laporan.cuti.export.pdf');

Route::get('/moonshine/laporan-mutasi/export/xlsx', [ExportController::class, 'exportMutasiXlsx'])
    ->name('moonshine.laporan.mutasi.export.xlsx');
Route::get('/moonshine/laporan-mutasi/export/pdf', [ExportController::class, 'exportMutasiPdf'])
    ->name('moonshine.laporan.mutasi.export.pdf');

Route::get('/moonshine/laporan-pensiun/export/xlsx', [ExportController::class, 'exportPensiunXlsx'])
    ->name('moonshine.laporan.pensiun.export.xlsx');
Route::get('/moonshine/laporan-pensiun/export/pdf', [ExportController::class, 'exportPensiunPdf'])
    ->name('moonshine.laporan.pensiun.export.pdf');

Route::get('/moonshine/laporan-perjalanan-dinas/export/xlsx', [ExportController::class, 'exportPerjalananDinasXlsx'])
    ->name('moonshine.laporan.perjalanan_dinas.export.xlsx');
Route::get('/moonshine/laporan-perjalanan-dinas/export/pdf', [ExportController::class, 'exportPerjalananDinasPdf'])
    ->name('moonshine.laporan.perjalanan_dinas.export.pdf');

Route::get('/moonshine/laporan-surat-masuk/export/xlsx', [ExportController::class, 'exportSuratMasukXlsx'])
    ->name('moonshine.laporan.surat_masuk.export.xlsx');
Route::get('/moonshine/laporan-surat-masuk/export/pdf', [ExportController::class, 'exportSuratMasukPdf'])
    ->name('moonshine.laporan.surat_masuk.export.pdf');

Route::get('/moonshine/laporan-surat-keluar/export/xlsx', [ExportController::class, 'exportSuratKeluarXlsx'])
    ->name('moonshine.laporan.surat_keluar.export.xlsx');
Route::get('/moonshine/laporan-surat-keluar/export/pdf', [ExportController::class, 'exportSuratKeluarPdf'])
    ->name('moonshine.laporan.surat_keluar.export.pdf');

Route::get('/moonshine/laporan-pelatihan/export/xlsx', [ExportController::class, 'exportPelatihanXlsx'])
    ->name('moonshine.laporan.pelatihan.export.xlsx');
Route::get('/moonshine/laporan-pelatihan/export/pdf', [ExportController::class, 'exportPelatihanPdf'])
    ->name('moonshine.laporan.pelatihan.export.pdf');

Route::get('/surat-masuk/{id}', [SuratMasukViewController::class, 'show'])->name('surat_masuk.view');
Route::get('/surat-masuk/{id}/download', [SuratMasukViewController::class, 'downloadFile'])->name('surat_masuk.download');
Route::get('/surat-keluar/{id}', [SuratKeluarViewController::class, 'show'])->name('surat_keluar.view');
Route::get('/surat-keluar/{id}/download', [SuratKeluarViewController::class, 'downloadFile'])->name('surat_keluar.download');


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

Route::get('/pensiun/{pensiun}/surat', function (Pensiun $pensiun) {
    return view('surat.pensiun', compact('pensiun'));
})->name('pensiun.surat');

Route::get('/perjalanan-dinas/{id}/surat', function (PerjalananDinas $perjalananDinas) {
    return view('surat.perjalanan_dinas', compact('perjalananDinas'));
})->name('perjalanan_dinas.surat');

Route::get('/', function () {
    return view('welcome');
});
