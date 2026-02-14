<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\PeriodoVacacionesSistema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        $empleados = Empleado::all();

        $rojos = 0;
        $amarillos = 0;
        $verdes = 0;

        foreach ($empleados as $empleado) {

            $periodo = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
                ->where('estado', 'activo')
                ->whereRaw('(dias_otorgados - dias_usados) > 0')
                ->orderBy('fecha_vencimiento', 'asc')
                ->first();

            if ($periodo) {

                $diasRestantes = $hoy->diffInDays($periodo->fecha_vencimiento, false);

                if ($diasRestantes <= 90) {
                    $rojos++;
                } elseif ($diasRestantes <= 180) {
                    $amarillos++;
                } else {
                    $verdes++;
                }

            } else {
                $verdes++;
            }
        }

        return view('dashboard.index', compact(
            'rojos',
            'amarillos',
            'verdes'
        ));
    }
}
