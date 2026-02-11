<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great! prueba
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/prueba', function () {
    return view('paginas.prueba');
});

Route::get('/db-test', function() {
    try {
        DB::connection()->getPdo();
        return "Conexión a la base de datos exitosa.";
    } catch (\Exception $e) {
        return "Error de conexión: " . $e->getMessage();
    }
});

Route::get('/inicio', function () {
    return view('paginas.inicio');
});

use App\Http\Controllers\PermisoController;

Route::get('/permisos', [PermisoController::class, 'index'])
    ->name('permisos.index');
Route::get('/permisos/crear', [PermisoController::class, 'create'])
    ->name('permisos.create');
Route::post('/permisos', [PermisoController::class, 'store'])
    ->name('permisos.store');
