<?php

use Illuminate\Support\Facades\Route;
use App\Models\Cuti;
use App\Models\Mutasi;


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
