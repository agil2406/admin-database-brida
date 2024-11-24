<?php

use App\Http\Controllers\Api\EduwisataController;
use App\Http\Controllers\Api\InovasiController;
use App\Http\Controllers\Api\InstansiController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\TipeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// kategori
Route::get('/kategoris', [KategoriController::class, 'index']);
Route::get('/kategoris/{tipe}', [KategoriController::class, 'tipeKategori']);

// instansi
Route::get('/instansis', [InstansiController::class, 'index']);
Route::get('/instansis/{slug}', [InstansiController::class, 'detail']);

// tipe atau jenis program
Route::get('/jenis-programs', [TipeController::class, 'index']);

// eduwisata
Route::get('/eduwisatas', [EduwisataController::class, 'index']);
Route::get('/eduwisatas/daerah-eduwisata', [EduwisataController::class, 'daerahEduwisata']);
Route::get('/eduwisatas/asal-lembaga', [EduwisataController::class, 'asalLembaga']);
Route::get('/eduwisatas/asal-lembaga/{label}', [EduwisataController::class, 'detailAsalLembaga']);
Route::get('/eduwisatas/daerah-lembaga', [EduwisataController::class, 'daerahLembaga']);
Route::get('/eduwisatas/daerah-lembaga/{label}', [EduwisataController::class, 'detailDaerahLembaga']);

// inovasi
Route::get('/inovasis', [InovasiController::class, 'index']);
Route::get('/daerah-inovasi', [InovasiController::class, 'daerahInovasi']);