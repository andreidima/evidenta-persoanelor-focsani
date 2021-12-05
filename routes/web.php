<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProgramareController;

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

Route::view('/acasa', 'acasa');

Route::redirect('/', 'https://evidentapersoanelorfocsani.ro/');

Route::get('/{serviciu}/programari/adauga-programare-noua', [ProgramareController::class, 'adaugaProgramareNoua']);
Route::get('/{serviciu}/programari/adauga-programare-pasul-1', [ProgramareController::class, 'adaugaProgramarePasul1']);
Route::post('/{serviciu}/programari/adauga-programare-pasul-1', [ProgramareController::class, 'postadaugaProgramarePasul1']);
Route::get('/{serviciu}/programari/adauga-programare-pasul-2', [ProgramareController::class, 'adaugaProgramarePasul2']);
Route::post('/{serviciu}/programari/adauga-programare-pasul-2', [ProgramareController::class, 'postAdaugaProgramarePasul2']);
Route::get('/{serviciu}/programari/adauga-programare-pasul-3', [ProgramareController::class, 'adaugaProgramarePasul3']);
Route::post('/{serviciu}/programari/adauga-programare-pasul-3', [ProgramareController::class, 'postAdaugaProgramarePasul3']);
Route::get('/{serviciu}/programari/adauga-programare-pasul-4', [ProgramareController::class, 'adaugaProgramarePasul4']);

// Extras date cu Axios
Route::get('/{serviciu}/programari/axios', [ProgramareController::class, 'axios']);

Route::get('/{serviciu}/programari/axios2', function () {
    $ore_disponibile = DB::table('programari_ore_de_program')
                    ->select(DB::raw('DATE_FORMAT(ora, "%H:%i") as ora'))
                    ->where('ziua_din_saptamana', '=', \Carbon\Carbon::today()->dayOfWeekIso)
                    ->orderBy('ora')
                    ->pluck('ora')
                    ->all();

    dd($ore_disponibile);
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/{serviciu}/programari/afisare-saptamanal', [ProgramareController::class, 'afisare_saptamanal']);
    Route::get('/{serviciu}/programari/afisare-zilnic', [ProgramareController::class, 'afisare_zilnic']);
    Route::get('/{serviciu}/programari/export/{data}/{view_type}', [ProgramareController::class, 'PdfExportPeZi']);

    Route::resource('/{serviciu}/programari', ProgramareController::class,  ['parameters' => ['programari' => 'programare']]);
});
