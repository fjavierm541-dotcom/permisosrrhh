<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\PeriodoVacacionesSistema;
use Carbon\Carbon;

class PeriodoVacacionesController extends Controller
{
    public function create()
    {
        $empleados = Empleado::all();
        return view('periodos.create', compact('empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni_empleado' => 'required',
            'anio_laboral.*' => 'required|integer',
            'dias_otorgados.*' => 'required|integer',
            'fecha_inicio_periodo.*' => 'required|date'
        ]);

        for ($i = 0; $i < count($request->anio_laboral); $i++) {

            PeriodoVacacionesSistema::create([
                'dni_empleado' => $request->dni_empleado,
                'anio_laboral' => $request->anio_laboral[$i],
                'dias_otorgados' => $request->dias_otorgados[$i],
                'dias_usados' => $request->dias_usados[$i] ?? 0,
                'fecha_inicio_periodo' => $request->fecha_inicio_periodo[$i],
                'fecha_vencimiento' => Carbon::parse($request->fecha_inicio_periodo[$i])->addYears(2),
                'activo' => true,
                'extension_autorizada' => false
            ]);
        }

        return redirect()->route('periodos.create')
            ->with('success', 'Períodos registrados correctamente.');
    }
}
