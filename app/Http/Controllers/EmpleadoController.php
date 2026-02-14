<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\DiasAcumuladosSistema;
use App\Models\PeriodoVacacionesSistema;
use Carbon\Carbon;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    // 🔄 Marcar vencidos automáticamente (si no tienen extensión válida)
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

    $empleados = Empleado::paginate(15);


    foreach ($empleados as $empleado) {

        // ✅ SOLO PERÍODOS ACTIVOS
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

        // 🔎 Buscar período activo más próximo a vencer
        $periodoProximoAVencer = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
            ->where('estado', 'activo')
            ->whereRaw('(dias_otorgados - dias_usados) > 0')
            ->orderByRaw('COALESCE(extension_hasta, fecha_vencimiento) asc')
            ->first();

        if ($periodoProximoAVencer) {

            $fechaReferencia = $periodoProximoAVencer->extension_hasta
                ?? $periodoProximoAVencer->fecha_vencimiento;

            $diasRestantes = Carbon::today()->diffInDays($fechaReferencia, false);

            if ($diasRestantes > 180) {
                $empleado->semaforo = 'verde';
            } elseif ($diasRestantes > 90) {
                $empleado->semaforo = 'amarillo';
            } elseif ($diasRestantes >= 0) {
                $empleado->semaforo = 'rojo';
            } else {
                $empleado->semaforo = 'rojo';
            }

        } else {
            $empleado->semaforo = 'verde';
        }
    }

    // 🔍 FILTRO POR ESTADO
    if ($request->estado) {
        $empleados = $empleados->where('semaforo', $request->estado);
    }

    return view('empleados.index', compact('empleados'));
}






public function generarVacaciones()
{
    $hoy = Carbon::today();
    $anioActual = $hoy->year;

    $empleados = Empleado::all();

    foreach ($empleados as $empleado) {

        if (!$empleado->fecha_nombramiento) continue;

        $fechaIngreso = Carbon::parse($empleado->fecha_nombramiento);

        // Calcular años cumplidos
        $aniosCumplidos = $fechaIngreso->diffInYears($hoy);

        if ($aniosCumplidos < 1) continue; // Aún no cumple año

        // Determinar días según tabla
        $dias = 0;

        if ($aniosCumplidos == 1) $dias = 12;
        elseif ($aniosCumplidos == 2) $dias = 15;
        elseif ($aniosCumplidos == 3) $dias = 18;
        elseif ($aniosCumplidos == 4) $dias = 22;
        elseif ($aniosCumplidos == 5) $dias = 26;
        elseif ($aniosCumplidos >= 6) $dias = 30;

        // Verificar si ya existe período este año
        $existe = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
            ->where('anio_laboral', $anioActual)
            ->exists();

        if ($existe) continue;

        PeriodoVacacionesSistema::create([
            'dni_empleado' => $empleado->DNI,
            'anio_laboral' => $anioActual,
            'dias_otorgados' => $dias,
            'dias_usados' => 0,
            'fecha_inicio_periodo' => $hoy,
            'fecha_vencimiento' => $hoy->copy()->addYears(2),
            'estado' => 'activo'
        ]);
    }

    return back()->with('success', 'Vacaciones generadas correctamente para el año actual.');
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
    public function show(string $id)
    {
        //
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
