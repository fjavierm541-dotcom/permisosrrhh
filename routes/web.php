<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\PeriodoVacacionesController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DepartamentoMuniController;
use App\Http\Controllers\DocumentoEmpleado;
use App\Http\Controllers\CalendarioController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SolicitudCompensatorioController;
    

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


//Ruta para menu de permisos 
Route::get('/permisos/menu', function () {
    return view('permisos.menu');
})->name('permisos.menu');


//Permisos laboral
Route::get('/permisos', [PermisoController::class, 'index'])
    ->name('permisos.index');
Route::get('/permisos/crear', [PermisoController::class, 'create'])
    ->name('permisos.create');
Route::post('/permisos', [PermisoController::class, 'store'])
    ->name('permisos.store');
    Route::get('permisos/{id}/aprobar', [PermisoController::class, 'aprobar'])
    ->name('permisos.aprobar');

Route::post('/permisos/{id}/aprobar', [PermisoController::class, 'aprobar'])->name('permisos.aprobar');
Route::post('/permisos/{id}/rechazar', [PermisoController::class, 'rechazar'])->name('permisos.rechazar');

//Imprimir listado de permisos por mes 
Route::get('/permisos/imprimir-mes', [PermisoController::class, 'imprimirMes'])
    ->name('permisos.imprimir.mes');
    
//permiso permiso al enviar la solicitud 
Route::get('/permisos/{id}/imprimir', [PermisoController::class, 'imprimir'])
    ->name('permisos.imprimir');








//periodos 
Route::get('periodos/create', [PeriodoVacacionesController::class, 'create'])
    ->name('periodos.create');

Route::post('periodos/store', [PeriodoVacacionesController::class, 'store'])
    ->name('periodos.store');

//reactivar periodos 
Route::post('/periodos/reactivar', [PeriodoVacacionesController::class, 'reactivar'])
    ->name('periodos.reactivar');



// crear/registrar nuevos empleados en el sistema

//estado de empleado activo o inactivo
Route::post('/empleados/{dni}/cambiar-estado', [EmpleadoController::class, 'cambiarEstado'])
    ->where('dni', '.*')
    ->name('empleados.cambiarEstado');
    

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
    ->where('dni', '.*')
    ->name('empleados.verRegistro');

    Route::get('/empleados/{dni}/imprimir', [EmpleadoController::class, 'verRegistroImprimir'])
    ->where('dni', '.*')
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



//CALENDAR

Route::get('/calendario', [CalendarioController::class,'index'])->name('calendario.index');

Route::get('/calendario/create', [CalendarioController::class,'create'])->name('calendario.create');

Route::post('/calendario/store', [CalendarioController::class,'store'])->name('calendario.store');

Route::get('/calendario/{id}/edit', [CalendarioController::class,'edit'])->name('calendario.edit');

Route::put('/calendario/{id}', [CalendarioController::class,'update'])->name('calendario.update');

Route::get('/calendario/eventos', [CalendarioController::class,'eventos']);

//
Route::get('/calendario/dia', [CalendarioController::class,'dia']);
//eliminar
Route::delete('/calendario/{id}', [CalendarioController::class,'destroy'])->name('calendario.destroy');
//importar feriados nacional del sig año
Route::get('/calendario/importar-feriados/{year}', [CalendarioController::class,'importarFeriados'])
    ->name('calendario.importar');





    //LOGIN
    Route::get('/login', function () {
    return view('auth.login');
});



// COMPENSATORIOS 
Route::post('/compensatorios/solicitudes', [SolicitudCompensatorioController::class, 'store'])
    ->name('compensatorios.solicitudes.store');

Route::get('/compensatorios/solicitudes/create', [SolicitudCompensatorioController::class, 'create'])
    ->name('compensatorios.solicitudes.create');

    //imprimir compensatorio por mes 
    Route::get('/compensatorios/solicitudes/imprimir-mes', [SolicitudCompensatorioController::class, 'imprimirMes'])
    ->name('compensatorios.solicitudes.imprimir.mes');

    //imprimir compensatorio individual pendiente
    Route::get(
    '/compensatorios/solicitudes/{id}/imprimir',
    [SolicitudCompensatorioController::class, 'imprimir']
)->name('compensatorios.solicitudes.imprimir');

//agregar solicitud por depto específico 
Route::get('/empleados/por-departamento/{id}', function ($id) {
    return DB::table('empleados')
        ->where('departamento_funcional_id', $id)
        ->select(
            'DNI',
            DB::raw("CONCAT(primer_nombre, ' ', primer_apellido) as nombre")
        )
        ->get();
});

//rechazar o aprobar solicitudes 
Route::get('/compensatorios/solicitudes', [SolicitudCompensatorioController::class, 'index'])
    ->name('compensatorios.solicitudes.index');
//vista detalle
    Route::get('/compensatorios/solicitudes/{id}', [SolicitudCompensatorioController::class, 'show'])
    ->name('compensatorios.solicitudes.show');


    Route::post('/compensatorios/{id}/aprobar', [SolicitudCompensatorioController::class, 'aprobar'])
    ->name('compensatorios.solicitudes.aprobar');

Route::post('/compensatorios/{id}/rechazar', [SolicitudCompensatorioController::class, 'rechazar'])
    ->name('compensatorios.solicitudes.rechazar');