<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\PeriodoVacacionesSistema;
use Illuminate\Support\Facades\DB;
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
            'dni_empleado' => 'required|exists:empleados,DNI',

            'anio_laboral.*' => 'required|integer|min:1900',
            'dias_otorgados.*' => 'required|integer|min:0',
            'dias_usados.*' => 'nullable|integer|min:0',
            'fecha_inicio_periodo.*' => 'required|date'
        ]);

        try {

            DB::transaction(function () use ($request) {

                for ($i = 0; $i < count($request->anio_laboral); $i++) {

                    $otorgados = $request->dias_otorgados[$i];
                    $usados = $request->dias_usados[$i] ?? 0;

                    // 🔥 Validación lógica
                    if ($usados > $otorgados) {
                        throw new \Exception("Los días usados no pueden ser mayores que los otorgados.");
                    }

                    PeriodoVacacionesSistema::create([
                        'dni_empleado' => $request->dni_empleado,
                        'anio_laboral' => $request->anio_laboral[$i],
                        'dias_otorgados' => $otorgados,
                        'dias_usados' => $usados,
                        'fecha_inicio_periodo' => $request->fecha_inicio_periodo[$i],

                        // 🔥 NECESARIO PARA LA BD
                        'fecha_vencimiento' => Carbon::parse($request->fecha_inicio_periodo[$i])->addYears(2),

                        'estado' => 'activo'
                    ]);
                }

            });

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()->route('periodos.create')
            ->with('success', 'Historial de vacaciones registrado correctamente.');
    }
}