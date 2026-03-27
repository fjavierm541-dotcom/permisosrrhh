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
        ]);

        try {

            $empleado = Empleado::where('DNI', $request->dni_empleado)->first();

            if (!$empleado || !$empleado->fecha_nombramiento) {
                throw new \Exception("El empleado no tiene fecha de nombramiento registrada.");
            }

            // 🔥 CARGA ÚNICA
            $yaTieneHistorial = PeriodoVacacionesSistema::where('dni_empleado', $request->dni_empleado)->exists();

           $anios = $request->anio_laboral ?? [];
           $anios = array_unique($request->anio_laboral ?? []);
            sort($anios);

            $huecos = [];

            for ($j = 0; $j < count($anios) - 1; $j++) {
                if ($anios[$j + 1] != $anios[$j] + 1) {
                    $huecos[] = $anios[$j] + 1;
                }
            }

            if (!empty($huecos)) {
                return back()->withInput()->with('error', 'Faltan años en el historial: ' . implode(', ', $huecos));
            }

                        if ($yaTieneHistorial) {
                            throw new \Exception("Este empleado ya tiene historial registrado.");
                        }

                        DB::transaction(function () use ($request, $empleado) {

                            for ($i = 0; $i < count($request->anio_laboral); $i++) {

                                $anioLaboral = $request->anio_laboral[$i];
                                $anioActual = now()->year;
            $anioIngreso = \Carbon\Carbon::parse($empleado->fecha_nombramiento)->year;

            // ❌ No años futuros
            if ($anioLaboral > $anioActual) {
                throw new \Exception("No puedes registrar años futuros.");
            }

            // ❌ No antes del ingreso
            if ($anioLaboral < $anioIngreso) {
                throw new \Exception("El año laboral no puede ser menor al año de ingreso del empleado.");
            }
                    $otorgados = $request->dias_otorgados[$i];
                    $usados = $request->dias_usados[$i] ?? 0;

                    if ($usados > $otorgados) {
                        throw new \Exception("Los días usados no pueden ser mayores que los otorgados.");
                    }

                    // 🔥 BLOQUEAR DUPLICADOS
                    $existe = PeriodoVacacionesSistema::where('dni_empleado', $request->dni_empleado)
                        ->where('anio_laboral', $anioLaboral)
                        ->exists();

                    if ($existe) {
                        throw new \Exception("El año laboral {$anioLaboral} ya está registrado para este empleado.");
                    }

                    // 🔥 FECHA INICIO (ANIVERSARIO)
                    $fechaInicio = Carbon::parse($empleado->fecha_nombramiento)
                        ->setYear($anioLaboral);

                    // 🔥 VENCIMIENTO (3 AÑOS)
                    $fechaVencimiento = $fechaInicio->copy()->addYears(3);

                    // 🔥 ESTADO AUTOMÁTICO (MEJOR UX)
                    $estado = $fechaVencimiento->isPast() ? 'vencido' : 'activo';

                    PeriodoVacacionesSistema::create([
                        'dni_empleado' => $request->dni_empleado,
                        'anio_laboral' => $anioLaboral,
                        'dias_otorgados' => $otorgados,
                        'dias_usados' => $usados,
                        'fecha_inicio_periodo' => $fechaInicio,
                        'fecha_vencimiento' => $fechaVencimiento,
                        'estado' => $estado
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

    //reactivar periodos
public function reactivar(Request $request)
{
    $request->validate([
        'periodo_id' => 'required|exists:periodos_vacaciones_sistema,id',
        'motivo' => 'required|string|max:500',
        'documento' => 'nullable|file|max:2048'
    ]);

    try {

        $periodo = PeriodoVacacionesSistema::findOrFail($request->periodo_id);

        // 🔥 validar que esté vencido
        if ($periodo->estado !== 'vencido') {
            throw new \Exception("Solo se pueden reactivar períodos vencidos.");
        }

        // 🔥 extender 1 año desde hoy o desde vencimiento
        $nuevaFecha = \Carbon\Carbon::parse($periodo->fecha_vencimiento)->addYear();

        // 🔥 actualizar período
        $periodo->update([
            'estado' => 'extendido',
            'extension_hasta' => $nuevaFecha
        ]);

        // 🔥 guardar documento (opcional)
        if ($request->hasFile('documento')) {
            $ruta = $request->file('documento')->store('reactivaciones', 'public');
        }

        // 🔥 (opcional futuro) guardar en historial
        // puedes crear tabla luego si quieres auditoría

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }

    return back()->with('success', 'Período reactivado correctamente.');
}

}