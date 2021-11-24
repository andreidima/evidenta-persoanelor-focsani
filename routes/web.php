<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProgramareOnlineController;

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

Auth::routes(['register' => false, 'password.request' => false, 'reset' => false]);


Route::redirect('/', 'programari-online/adauga-programare-online-noua');

Route::get('programari-online/adauga-programare-online-noua', [ProgramareOnlineController::class, 'adaugaProgramareOnlineNoua'])->name('programare_online.adauga_programare_online_noua');
Route::get('programari-online/adauga-programare-online-pasul-1', [ProgramareOnlineController::class, 'adaugaProgramareOnlinePasul1']);
Route::post('programari-online/adauga-programare-online-pasul-1', [ProgramareOnlineController::class, 'postadaugaProgramareOnlinePasul1']);
Route::get('programari-online/adauga-programare-online-pasul-2', [ProgramareOnlineController::class, 'adaugaProgramareOnlinePasul2']);
Route::post('programari-online/adauga-programare-online-pasul-2', [ProgramareOnlineController::class, 'postAdaugaProgramareOnlinePasul2']);
Route::get('programari-online/adauga-programare-online-pasul-3', [ProgramareOnlineController::class, 'adaugaProgramareOnlinePasul3']);
Route::post('programari-online/adauga-programare-online-pasul-3', [ProgramareOnlineController::class, 'postAdaugaProgramareOnlinePasul3']);
Route::get('programari-online/adauga-programare-online-pasul-4', [ProgramareOnlineController::class, 'adaugaProgramareOnlinePasul4']);
