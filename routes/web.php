<?php

use App\Http\Controllers\EgresosController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IngresarPartidasController;
use App\Http\Controllers\PresupuestosController;
use App\Http\Controllers\PartidasController;
use App\Http\Controllers\PartidasDetalleController;
use App\Http\Controllers\PresupuestoMaestroController;
use App\Http\Controllers\SalidasController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('voyager.dashboard');
    } else {
        return redirect()->route('voyager.login');
    }
});




Route::group(['prefix' => 'admin'], function () {

    Voyager::routes();
    Route::get('/', [HomeController::class, 'index'])->name('voyager.dashboard');
    Route::resource('/presupuestos', PresupuestosController::class)->names('presupuestos');
    Route::resource('/partida', PartidasController::class);
    Route::resource('/partida-detalles', PartidasDetalleController::class)->names('partida-detalles');
    // Route::resource('/ingresar-partidas', IngresarPartidasController::class)->names('ingresar-partidas');
    Route::resource('/salidas', SalidasController::class)->names('salidas');
    Route::resource('/egresos', EgresosController::class)->names('egresos');
    Route::resource('/presupuestos-maestro', PresupuestoMaestroController::class)->names('presupuestos-maestro');
});
