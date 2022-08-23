<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\Controller::class, 'index'])->name('dashboard.get');
Route::get('/kabupaten/{id}', [App\Http\Controllers\Controller::class, 'kabupaten'])->name('kabupaten.json');
Route::get('getkecamatan', [App\Http\Controllers\Controller::class, 'getkecamatan'])->name('getkecamatan');
Route::get('cetakpdf', [App\Http\Controllers\Controller::class, 'cetakpdf'])->name('cetakpdf');
