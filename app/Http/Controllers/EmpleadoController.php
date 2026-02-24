<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\DiasAcumuladosSistema;
use App\Models\PeriodoVacacionesSistema;
use Carbon\Carbon;
use App\Models\MovimientoPermisoSistema;
use Barryvdh\DomPDF\Facade\Pdf;


class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    // 🔄 Marcar vencidos automáticamente
    PeriodoVacacionesSistema::where('estado', 'activo')
        ->where(function ($query) {
            $query->whereNull('extension_hasta')
                  ->whereDate('fecha_vencimiento', '<', Carbon::today());
        })
        ->orWhere(function ($query) {
            $query->whereNotNull('extension_hasta')
                  ->whereDate('extension_hasta', '<', Carbon::today());
        })
        ->update(['estado' => 'vencido']);

    $empleados = Empleado::all(); // ⚠ Traemos todos primero

    foreach ($empleados as $empleado) {

        // ===== DÍAS DISPONIBLES =====
        $periodos = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
            ->where('estado', 'activo')
            ->get();

        $totalDiasDisponibles = 0;

        foreach ($periodos as $periodo) {
            $otorgados = (int) $periodo->dias_otorgados;
            $usados = (int) ($periodo->dias_usados ?? 0);
            $restantes = max(0, $otorgados - $usados);
            $totalDiasDisponibles += $restantes;
        }

        $empleado->dias_disponibles = $totalDiasDisponibles;

        $acumulado = DiasAcumuladosSistema::where('dni_empleado', $empleado->DNI)->first();
        $empleado->horas_disponibles = $acumulado->horas_acumuladas ?? 0;

        // ===== SEMÁFORO =====
        $periodoProximo = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
            ->where('estado', 'activo')
            ->whereRaw('(dias_otorgados - dias_usados) > 0')
            ->orderByRaw('COALESCE(extension_hasta, fecha_vencimiento) asc')
            ->first();

        if ($periodoProximo) {

            $fechaReferencia = $periodoProximo->extension_hasta
                ?? $periodoProximo->fecha_vencimiento;

            $diasRestantes = Carbon::today()->diffInDays($fechaReferencia, false);

            if ($diasRestantes > 180) {
                $empleado->semaforo = 'verde';
            } elseif ($diasRestantes > 90) {
                $empleado->semaforo = 'amarillo';
            } else {
                $empleado->semaforo = 'rojo';
            }

        } else {
            $empleado->semaforo = 'verde';
        }
    }

    // 🔍 FILTRAR POR COLOR SI SE ENVÍA
    if ($request->estado) {
        $empleados = $empleados->where('semaforo', $request->estado);
    }

    // 🔢 PAGINACIÓN MANUAL
    $page = $request->get('page', 1);
    $perPage = 15;

    $empleados = new \Illuminate\Pagination\LengthAwarePaginator(
        $empleados->forPage($page, $perPage),
        $empleados->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('empleados.index', compact('empleados'));
}







public function generarVacaciones()
{
    $hoy = Carbon::today();

    $empleados = Empleado::all();

    foreach ($empleados as $empleado) {

        if (!$empleado->fecha_nombramiento) continue;

        $fechaIngreso = Carbon::parse($empleado->fecha_nombramiento);

        // ¿Hoy cumple aniversario?
        if ($fechaIngreso->month != $hoy->month ||
            $fechaIngreso->day != $hoy->day) {
            continue;
        }

        $aniosCumplidos = $fechaIngreso->diffInYears($hoy);

        if ($aniosCumplidos < 1) continue;

        // Tabla legal
        if ($aniosCumplidos == 1) $dias = 12;
        elseif ($aniosCumplidos == 2) $dias = 15;
        elseif ($aniosCumplidos == 3) $dias = 18;
        elseif ($aniosCumplidos == 4) $dias = 22;
        elseif ($aniosCumplidos == 5) $dias = 26;
        else $dias = 30;

        // Evitar duplicados
        $existe = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
            ->where('anio_laboral', $aniosCumplidos)
            ->exists();

        if ($existe) continue;

        PeriodoVacacionesSistema::create([
            'dni_empleado' => $empleado->DNI,
            'anio_laboral' => $aniosCumplidos,
            'dias_otorgados' => $dias,
            'dias_usados' => 0,
            'fecha_inicio_periodo' => $hoy,
            'fecha_vencimiento' => $hoy->copy()->addYears(2),
            'estado' => 'activo'
        ]);
    }

    return back()->with('success', 'Proceso de generación ejecutado correctamente.');
}





    /**
     *CREAR EMPLEADOS
     */
    public function create()
{
    return view('empleados.create');
    
}

    /**
     * GUARDAR INFORMACIÓN DE LOS EMPLEADOS
     */
public function store(Request $request)
{
    $request->validate([

    // NOMBRES (solo letras y espacios)
    'primer_nombre' => ['required','regex:/^[\pL\s]+$/u','max:50'],
    'segundo_nombre' => ['nullable','regex:/^[\pL\s]+$/u','max:50'],
    'primer_apellido' => ['required','regex:/^[\pL\s]+$/u','max:50'],
    'segundo_apellido' => ['nullable','regex:/^[\pL\s]+$/u','max:50'],

    // DNI
    'DNI' => ['required','unique:empleados,DNI','regex:/^[0-9]{13}$/'],

    // SEXO
    'sexo' => 'required|in:Masculino,Femenino',

    // ESTADO CIVIL
    'estado_civil' => 'nullable|in:Soltero(a),Casado(a),Unión Libre,Divorciado(a),Viudo(a)',

    // TIPO SANGRE
    'tipo_sangre' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',

    // TELÉFONOS
    'telefono_celular' => ['nullable','regex:/^(3|8|9)[0-9]{7}$/'],
    'telefono_fijo' => ['nullable','regex:/^2[0-9]{7}$/'],

    // DIRECCIÓN
    'direccion_domicilio' => 'nullable|string|max:255',

    // SALARIO
    'salario_inicial' => ['nullable','regex:/^L\.?\s?[0-9]{1,3}(,[0-9]{3})*(\.[0-9]{2})?$/'],

], [

    // NOMBRES
    'primer_nombre.required' => 'El primer nombre es obligatorio.',
    'primer_nombre.regex' => 'El nombre no debe aceptar números ni caracteres especiales.',
    'primer_nombre.max' => 'El nombre no debe exceder 50 caracteres.',

    'segundo_nombre.regex' => 'El segundo nombre no debe aceptar números.',
    'segundo_nombre.max' => 'Máximo 50 caracteres.',

    'primer_apellido.required' => 'El primer apellido es obligatorio.',
    'primer_apellido.regex' => 'El apellido no debe aceptar números.',
    'primer_apellido.max' => 'Máximo 50 caracteres.',

    'segundo_apellido.regex' => 'El segundo apellido no debe aceptar números.',
    'segundo_apellido.max' => 'Máximo 50 caracteres.',

    // DNI
    'DNI.required' => 'El DNI es obligatorio.',
    'DNI.unique' => 'Este DNI ya está registrado.',
    'DNI.regex' => 'El DNI debe contener exactamente 13 números.',

    // SEXO
    'sexo.required' => 'Debe seleccionar el sexo.',

    // TELÉFONOS
    'telefono_celular.regex' => 'El celular debe iniciar con 3, 8 o 9 y tener 8 dígitos.',
    'telefono_fijo.regex' => 'El teléfono fijo debe iniciar con 2 y tener 8 dígitos.',

    // SALARIO
    'salario_inicial.regex' => 'El salario debe tener formato: L. 12,000.00',

]);

    $data = $request->all();
    $data['usuario_crea'] = auth()->user()->name ?? 'Sistema';

    // Crear empleado primero
    $empleado = Empleado::create($data);

    // ================================
    // Guardar documentos en tabla aparte
    // ================================

    $documentos = [
        'copia_dni' => 'Copia DNI',
        'acuerdo' => 'Acuerdo',
        'nota_traslado' => 'Nota Traslado'
    ];

    foreach ($documentos as $campo => $tipo) {

        if ($request->hasFile($campo)) {

            $ruta = $request->file($campo)
                ->store("empleados/{$empleado->DNI}/documentos", 'public');

            DocumentoEmpleado::create([
                'dni_empleado' => $empleado->DNI,
                'tipo_documento' => $tipo,
                'ruta_archivo' => $ruta
            ]);
        }
    }

    return redirect()
        ->route('empleados.index')
        ->with('success', 'Empleado creado correctamente.');
}







    /**
     * Display the specified resource.
     */
public function show($dni)
{
    $empleado = Empleado::where('DNI', $dni)->firstOrFail();

    // Períodos activos
    $periodosActivos = PeriodoVacacionesSistema::where('dni_empleado', $dni)
        ->where('estado', 'activo')
        ->orderByDesc('anio_laboral')
        ->get();

    // Períodos vencidos
    $periodosVencidos = PeriodoVacacionesSistema::where('dni_empleado', $dni)
        ->where('estado', 'vencido')
        ->orderByDesc('anio_laboral')
        ->get();

    // Movimientos
    $movimientos = MovimientoPermisoSistema::where('dni_empleado', $dni)
        ->orderByDesc('created_at')
        ->get();

    // Calcular días disponibles SOLO activos
    $totalDiasDisponibles = $periodosActivos->sum(function ($periodo) {
        return max(0, $periodo->dias_otorgados - $periodo->dias_usados);
    });

    return view('empleados.show', compact(
        'empleado',
        'periodosActivos',
        'periodosVencidos',
        'movimientos',
        'totalDiasDisponibles'
    ));
}



public function reporte($dni)
{
    $empleado = Empleado::where('DNI', $dni)->firstOrFail();

    $periodosActivos = PeriodoVacacionesSistema::where('dni_empleado', $dni)
        ->where('estado', 'activo')
        ->orderByDesc('anio_laboral')
        ->get();

    $periodosVencidos = PeriodoVacacionesSistema::where('dni_empleado', $dni)
        ->where('estado', 'vencido')
        ->orderByDesc('anio_laboral')
        ->get();

    $movimientos = MovimientoPermisoSistema::where('dni_empleado', $dni)
        ->orderByDesc('created_at')
        ->get();

    $totalDiasDisponibles = 0;

    foreach ($periodosActivos as $periodo) {
        $totalDiasDisponibles += max(0, $periodo->dias_otorgados - $periodo->dias_usados);
    }

    // 🔥 IMPORTANTE: usar timezone correcto
    $fechaGeneracion = Carbon::now('America/Tegucigalpa')
        ->locale('es')
        ->translatedFormat('d \d\e F \d\e\l Y H:i');

    $pdf = Pdf::loadView('empleados.reporte', compact(
        'empleado',
        'periodosActivos',
        'periodosVencidos',
        'movimientos',
        'totalDiasDisponibles',
        'fechaGeneracion'
    ));

    $pdf->setPaper('a4', 'portrait');

    // 🔥 Renderizar primero para que calcule páginas
    $pdf->render();

    return $pdf->stream('reporte_empleado_'.$empleado->DNI.'.pdf', [
        'Attachment' => false // 👈 abre en nueva pestaña
    ]);
}





public function expediente($dni)
{
    $empleado = Empleado::with('documentos')
        ->where('DNI', $dni)
        ->firstOrFail();

    return view('empleados.expediente', compact('empleado'));
}



public function verRegistro($dni)
{
    $empleado = Empleado::with('documentos')
        ->where('DNI', $dni)
        ->firstOrFail();

    return view('empleados.verRegistro', compact('empleado'));
}


public function imprimirRegistro($dni)
{
    $empleado = Empleado::with('documentos')
        ->where('DNI', $dni)
        ->firstOrFail();

    return view('empleados.verRegistroImprimir', compact('empleado'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    


}
