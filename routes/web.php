<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProgramareController;
use App\Http\Controllers\AxiosController;
use App\Http\Controllers\ZiNelucratoareController;

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


Route::redirect('/', 'https://evidentapersoanelorfocsani.ro/');

Route::get('/{serviciu}/programari/adauga-programare-noua', [ProgramareController::class, 'adaugaProgramareNoua']);
Route::get('/{serviciu}/programari/adauga-programare-pasul-0', [ProgramareController::class, 'adaugaProgramarePasul0']); // Doar pentru „Casatorii oficieri”, care au 3 locatii diferite
Route::post('/{serviciu}/programari/adauga-programare-pasul-0', [ProgramareController::class, 'postadaugaProgramarePasul0']); // Doar pentru „Casatorii oficieri”, care au 3 locatii diferite
Route::get('/{serviciu}/programari/adauga-programare-pasul-1', [ProgramareController::class, 'adaugaProgramarePasul1']);
Route::post('/{serviciu}/programari/adauga-programare-pasul-1', [ProgramareController::class, 'postadaugaProgramarePasul1']);
Route::get('/{serviciu}/programari/adauga-programare-pasul-2', [ProgramareController::class, 'adaugaProgramarePasul2']);
Route::post('/{serviciu}/programari/adauga-programare-pasul-2', [ProgramareController::class, 'postAdaugaProgramarePasul2']);
Route::get('/{serviciu}/programari/adauga-programare-pasul-3', [ProgramareController::class, 'adaugaProgramarePasul3']);
Route::post('/{serviciu}/programari/adauga-programare-pasul-3', [ProgramareController::class, 'postAdaugaProgramarePasul3']);
Route::get('/{serviciu}/programari/adauga-programare-pasul-4', [ProgramareController::class, 'adaugaProgramarePasul4']);

Route::get('/{serviciu}/programari/sterge-programare-pasul-1/{cheie_unica}', [ProgramareController::class, 'stergeProgramarePasul1']);
Route::delete('/{serviciu}/programari/sterge-programare-pasul-1/{cheie_unica}', [ProgramareController::class, 'postStergeProgramarePasul1']);
Route::get('/{serviciu}/programari/sterge-programare-pasul-2/{cheie_unica}', [ProgramareController::class, 'stergeProgramarePasul2']);

// Extras date cu Axios
Route::get('/{serviciu}/programari/axios', [ProgramareController::class, 'axios']);

Route::get('/axios/trimitere-cod-validare-email', [AxiosController::class, 'trimitereCodValidareEmail']);

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
    Route::view('/acasa', 'acasa');

    Route::get('/{serviciu}/programari/afisare-saptamanal', [ProgramareController::class, 'afisare_saptamanal']);
    Route::get('/{serviciu}/programari/afisare-zilnic', [ProgramareController::class, 'afisare_zilnic']);
    Route::get('/{serviciu}/programari/export/{data}/{view_type}', [ProgramareController::class, 'PdfExportPeZi']);

    Route::resource('/{serviciu}/programari', ProgramareController::class,  ['parameters' => ['programari' => 'programare']]);
    Route::resource('/{serviciu}/zile-nelucratoare', ZiNelucratoareController::class,  ['parameters' => ['zile-nelucratoare' => 'zi_nelucratoare']]);


    // Route::get('/adaugare-zile-si-ore-casatorii-oficieri', function(){
    //     $data = [
    //         // Sediu
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'09:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'09:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'09:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'09:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'10:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'10:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'10:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'10:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'11:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'11:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'11:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'11:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'12:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'12:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'12:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'12:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'13:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'13:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'13:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'13:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'14:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'14:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'14:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'14:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'15:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'15:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'15:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'15:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'09:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'09:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'09:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'09:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'10:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'10:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'10:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'10:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'11:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'11:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'11:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'11:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'12:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'12:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'12:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'12:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'13:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'13:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'13:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'13:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'14:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'14:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'14:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'14:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'15:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'15:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'15:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'15:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'09:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'09:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'09:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'09:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'10:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'10:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'10:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'10:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'11:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'11:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'11:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'11:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'12:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'12:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'12:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'12:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'13:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'13:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'13:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'13:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'14:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'14:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'14:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'14:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'15:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'15:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'15:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'15:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'09:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'09:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'09:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'09:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'10:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'10:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'10:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'10:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'11:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'11:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'11:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'11:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'12:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'12:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'12:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'12:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'13:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'13:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'13:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'13:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'14:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'14:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'14:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'14:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'15:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'15:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'15:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'15:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'09:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'09:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'09:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'09:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'10:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'10:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'10:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'10:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'11:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'11:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'11:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'11:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'12:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'12:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'12:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'12:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'13:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'13:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'13:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'13:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'14:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'14:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'14:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'14:45:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'15:00:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'15:15:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'15:30:00', 'serviciu'=>4],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'15:45:00', 'serviciu'=>4],

    //         // Foisor
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'16:00:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'16:15:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'16:30:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'16:45:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'17:00:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'17:15:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'17:30:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'17:45:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'18:00:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'18:15:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'18:30:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'18:45:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'16:00:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'16:15:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'16:30:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'16:45:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'17:00:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'17:15:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'17:30:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'17:45:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'18:00:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'18:15:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'18:30:00', 'serviciu'=>5],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'18:45:00', 'serviciu'=>5],

    //         // Teatru
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'09:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'09:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'09:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'09:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'10:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'10:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'10:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'10:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'11:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'11:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'11:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'11:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'12:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'12:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'12:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'12:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'13:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'13:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'13:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'13:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'14:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'14:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'14:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'1', 'ora'=>'14:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'09:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'09:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'09:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'09:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'10:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'10:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'10:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'10:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'11:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'11:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'11:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'11:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'12:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'12:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'12:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'12:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'13:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'13:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'13:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'13:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'14:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'14:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'14:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'2', 'ora'=>'14:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'09:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'09:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'09:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'09:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'10:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'10:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'10:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'10:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'11:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'11:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'11:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'11:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'12:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'12:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'12:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'12:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'13:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'13:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'13:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'13:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'14:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'14:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'14:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'3', 'ora'=>'14:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'09:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'09:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'09:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'09:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'10:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'10:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'10:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'10:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'11:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'11:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'11:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'11:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'12:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'12:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'12:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'12:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'13:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'13:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'13:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'13:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'14:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'14:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'14:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'4', 'ora'=>'14:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'09:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'09:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'09:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'09:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'10:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'10:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'10:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'10:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'11:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'11:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'11:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'11:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'12:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'12:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'12:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'12:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'13:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'13:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'13:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'13:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'14:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'14:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'14:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'5', 'ora'=>'14:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'09:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'09:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'09:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'09:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'10:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'10:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'10:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'10:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'11:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'11:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'11:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'11:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'12:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'12:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'12:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'12:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'13:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'13:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'13:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'6', 'ora'=>'13:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'09:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'09:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'09:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'09:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'10:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'10:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'10:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'10:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'11:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'11:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'11:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'11:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'12:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'12:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'12:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'12:45:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'13:00:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'13:15:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'13:30:00', 'serviciu'=>6],
    //         ['ziua_din_saptamana'=>'7', 'ora'=>'13:45:00', 'serviciu'=>6],
    //         //...
    //     ];
    //     App\Models\ProgramareOraDeProgram::insert($data);
    //     return 'Gata';
    // });
});


