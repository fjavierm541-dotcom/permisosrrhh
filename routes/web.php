<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\PeriodoVacacionesController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DepartamentoMuniController;
use App\Http\Controllers\DocumentoEmpleado;


    

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
})->name('paginas.inicio');




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




// crear/registrar nuevos empleados en el sistema
Route::get('empleados/create', [EmpleadoController::class, 'create'])
    ->name('empleados.create');
//GUARDAR EMPLEADOS
Route::post('empleados', [EmpleadoController::class, 'store'])
    ->name('empleados.store');

    // ver expediente
Route::get('empleados/{dni}/expediente', [EmpleadoController::class, 'expediente'])
    ->name('empleados.expediente');

    // ver registro de empleado individual 
Route::get('empleados/{dni}/ver-registro', [EmpleadoController::class, 'verRegistro'])
    ->name('empleados.verRegistro');

Route::get('empleados/{dni}/ver-registro/imprimir', [EmpleadoController::class, 'imprimirRegistro'])
    ->name('empleados.verRegistro.imprimir');

    Route::get('/empleados', [EmpleadoController::class, 'index'])
    ->name('empleados.index');
Route::get('/empleados/{dni}', [EmpleadoController::class, 'show'])
    ->name('empleados.show');
//imprimir reprte individual de empleado  
Route::get('/empleados/{dni}/reporte', [EmpleadoController::class, 'reporte'])
    ->name('empleados.reporte');

    // editar departamento funcional
Route::get('empleados/{dni}/funcion', [EmpleadoController::class, 'editarFuncion'])
    ->name('empleados.funcion');

// guardar departamento funcional
Route::post('empleados/{dni}/funcion', [EmpleadoController::class, 'guardarFuncion'])
    ->name('empleados.funcion.guardar');



//Generación Manual de Vacaciones Año Actual
    Route::post('/vacaciones/generar', [EmpleadoController::class, 'generarVacaciones'])
    ->name('vacaciones.generar');


    // dashboard

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');




//autenticacion de usuario
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');



//DEPTOS RUTAS
Route::resource('departamentos', DepartamentoMuniController::class); 

Route::patch('departamentos/{id}/toggle',
    [DepartamentoMuniController::class,'toggle']
)->name('departamentos.toggle'); 

//buscar en listado de deptos
Route::get('/departamentos/buscar',[DepartamentoMuniController::class,'buscar'])
->name('departamentos.buscar'); 

Route::get('/departamentos/{id}/asignar',[DepartamentoMuniController::class,'asignar'])
->name('departamentos.asignar');

Route::post('/departamentos/{id}/asignar',[DepartamentoMuniController::class,'guardarAsignacion'])
->name('departamentos.asignar.guardar');


//RUTAS AGREGAR JEFES DE DEPTOS
Route::get('/departamentos/{id}/jefe',
    [DepartamentoMuniController::class,'editarJefe']
)->name('departamentos.jefe');

Route::post('/departamentos/{id}/jefe',
    [DepartamentoMuniController::class,'guardarJefe']
)->name('departamentos.jefe.guardar');