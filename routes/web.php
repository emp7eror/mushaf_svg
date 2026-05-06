<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuranController;

Route::get('/', [QuranController::class, 'index'])->name('home');
Route::get('/mushaf/{mushaf}', [QuranController::class, 'reader'])->name('reader');
Route::get('/api/mushaf/{mushaf}/page/{page}', [QuranController::class, 'serveSvg'])->name('svg');
Route::get('/api/mushaf/{mushaf}/json/{page}', [QuranController::class, 'serveJson'])->name('json');
