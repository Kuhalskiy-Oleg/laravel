<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImgController;

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


Route::get('/', [ImgController::class, 'index'])->name('index');

Route::post('/upload-file', [ImgController::class, 'uploadImg'])->name('uploadImg');


