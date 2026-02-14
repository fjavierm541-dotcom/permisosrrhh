<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\PeriodoVacacionesController;
use App\Http\Controllers\EmpleadoController;
    use App\Http\Controllers\DashboardController;


    

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



Route::get('/permisos', [PermisoController::class, 'index'])
    ->name('permisos.index');
Route::get('/permisos/crear', [PermisoController::class, 'create'])
    ->name('permisos.create');
Route::post('/permisos', [PermisoController::class, 'store'])
    ->name('permisos.store');



    Route::get('permisos/{id}/aprobar', [PermisoController::class, 'aprobar'])
    ->name('permisos.aprobar');
    Route::get('permisos/{id}/rechazar', [PermisoController::class, 'rechazar'])
    ->name('permisos.rechazar');







Route::get('periodos/create', [PeriodoVacacionesController::class, 'create'])
    ->name('periodos.create');

Route::post('periodos/store', [PeriodoVacacionesController::class, 'store'])
    ->name('periodos.store');





    Route::get('/empleados', [EmpleadoController::class, 'index'])
    ->name('empleados.index');

Route::get('/empleados/{dni}', [EmpleadoController::class, 'show'])
    ->name('empleados.show');



//Generación Manual de Vacaciones Año Actual
    Route::post('/vacaciones/generar', [EmpleadoController::class, 'generarVacaciones'])
    ->name('vacaciones.generar');


    // dashboard

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
