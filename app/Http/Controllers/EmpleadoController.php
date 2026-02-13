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
    public function index()
{
    $empleados = Empleado::all();

    foreach ($empleados as $empleado) {

        // =========================
        // CALCULAR DÍAS DISPONIBLES DESDE PERIODOS
        // =========================

        $periodos = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)->get();

        $totalDiasDisponibles = 0;

        foreach ($periodos as $periodo) {

            $restantes = $periodo->dias_otorgados - $periodo->dias_usados;

            // Nunca permitir negativos
            if ($restantes > 0) {
                $totalDiasDisponibles += $restantes;
            }
        }

        $empleado->dias_disponibles = $totalDiasDisponibles;

        // Por ahora no usamos horas
        $empleado->horas_disponibles = 0;

        // =========================
        // CALCULAR SEMÁFORO
        // =========================

        $periodoProximoAVencer = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
            ->whereRaw('(dias_otorgados - dias_usados) > 0')
            ->orderBy('fecha_vencimiento', 'asc')
            ->first();

        if ($periodoProximoAVencer) {

            $diasRestantes = Carbon::now()
                ->diffInDays($periodoProximoAVencer->fecha_vencimiento, false);

            if ($diasRestantes > 180) {
                $empleado->semaforo = 'verde';
            } elseif ($diasRestantes > 90) {
                $empleado->semaforo = 'amarillo';
            } elseif ($diasRestantes >= 0) {
                $empleado->semaforo = 'rojo';
            } else {
                // Ya vencido
                $empleado->semaforo = 'rojo';
            }

        } else {
            // No tiene periodos activos
            $empleado->semaforo = 'verde';
        }
    }

    return view('empleados.index', compact('empleados'));
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
