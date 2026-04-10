<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\Models\CalendarioDia;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        /**
         * 🔹 1. ACTUALIZAR ESTADOS DE VACACIONES
         */
        $schedule->command('vacaciones:actualizar-estados')
            ->hourly()
            ->withoutOverlapping();


        /**
         * 🔥 2. CALENDARIO AUTOMÁTICO
         */
        $schedule->call(function () {

            try {

                // 🧪 PRUEBA (luego cambias a date('Y-m-d'))
                $hoy = '2026-04-14';
                // $hoy = date('Y-m-d');

                $dias = CalendarioDia::where(function ($q) use ($hoy) {
                        $q->where('fecha_inicio', '<=', $hoy)
                          ->whereRaw('IFNULL(fecha_fin, fecha_inicio) >= ?', [$hoy]);
                    })
                    ->where('tipo_afectacion', 'descuento')
                    ->get();

                foreach ($dias as $dia) {

                    // 🔥 Departamentos que SÍ trabajan
                    $excepciones = DB::table('calendario_excepciones')
                        ->where('calendario_dia_id', $dia->id)
                        ->pluck('departamento_id')
                        ->toArray();

                    /**
                     * 🔴 1. EMPLEADOS QUE NO TRABAJAN → DESCUENTO
                     */
                    $empleadosNoTrabajan = DB::table('empleados')
                        ->whereNotIn('departamento_funcional_id', $excepciones ?: [0])
                        ->get();

                    foreach ($empleadosNoTrabajan as $emp) {

                        $yaDescontado = DB::table('movimientos_permisos_sistema')
                            ->where('dni_empleado', $emp->DNI)
                            ->where('tipo_movimiento', 'descuento_calendario')
                            ->whereDate('created_at', $hoy)
                            ->where('descripcion', 'like', '%ID: ' . $dia->id . '%')
                            ->exists();

                        if ($yaDescontado) continue;

                        $periodo = DB::table('periodos_vacaciones_sistema')
                            ->where('dni_empleado', $emp->DNI)
                            ->whereIn('estado', ['activo', 'extendido'])
                            ->whereRaw('(dias_otorgados - dias_usados) > 0')
                            ->orderBy('fecha_inicio_periodo', 'asc')
                            ->first();

                        if ($periodo) {

                            DB::table('periodos_vacaciones_sistema')
                                ->where('id', $periodo->id)
                                ->increment('dias_usados', 1);

                            DB::table('movimientos_permisos_sistema')->insert([
                                'dni_empleado' => $emp->DNI,
                                'periodo_id' => $periodo->id,
                                'permiso_id' => null,
                                'categoria' => 'vacaciones',
                                'tipo_movimiento' => 'descuento_calendario',
                                'dias_afectados' => 1,
                                'horas_afectadas' => 0,
                                'descripcion' => 'Descuento por feriado: ' . $dia->titulo . ' (ID: ' . $dia->id . ')',
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }


                    /**
                     * 🟢 2. EMPLEADOS QUE SÍ TRABAJAN → COMPENSATORIO
                     */
                    $empleadosTrabajan = DB::table('empleados')
                        ->whereIn('departamento_funcional_id', $excepciones ?: [0])
                        ->get();

                    foreach ($empleadosTrabajan as $emp) {

                        $yaAsignado = DB::table('movimientos_permisos_sistema')
                            ->where('dni_empleado', $emp->DNI)
                            ->where('tipo_movimiento', 'asignacion_calendario')
                            ->whereDate('created_at', $hoy)
                            ->where('descripcion', 'like', '%ID: ' . $dia->id . '%')
                            ->exists();

                        if ($yaAsignado) continue;

                        // 🔥 Crear compensatorio
                        DB::table('compensatorios_sistema')->insert([
                            'dni_empleado' => $emp->DNI,
                            'dias_otorgados' => 1,
                            'dias_disponibles' => 1,
                            'fecha_origen' => $hoy,
                            'fecha_vencimiento' => Carbon::parse($hoy)->addYears(3),
                            'estado' => 'activo',
                            'origen' => 'calendario',
                            'referencia_id' => $dia->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        // 🔥 Movimiento
                        DB::table('movimientos_permisos_sistema')->insert([
                            'dni_empleado' => $emp->DNI,
                            'periodo_id' => null,
                            'permiso_id' => null,
                            'categoria' => 'compensatorio',
                            'tipo_movimiento' => 'asignacion_calendario',
                            'dias_afectados' => 1,
                            'horas_afectadas' => 0,
                            'descripcion' => 'Asignación por feriado trabajado: ' . $dia->titulo . ' (ID: ' . $dia->id . ')',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }

            } catch (\Throwable $e) {
                dd($e->getMessage(), $e->getFile(), $e->getLine());
            }

        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}