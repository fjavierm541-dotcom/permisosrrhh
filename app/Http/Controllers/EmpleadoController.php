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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    // 📅 Fecha y hora de generación
    $fechaGeneracion = Carbon::now()
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

    return $pdf->stream('reporte_empleado_'.$empleado->DNI.'.pdf');
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
