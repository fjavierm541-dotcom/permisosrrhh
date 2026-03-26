<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\Models\CalendarioDia;

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
         * 🔹 2. SINCRONIZAR SALDO DE VACACIONES
         * ✔ SIEMPRE refleja la realidad de periodos
         * ✔ incluye activos + extendidos
         * ✔ elimina inconsistencias
         */
        $schedule->call(function () {

            $empleados = DB::table('periodos_vacaciones_sistema')
                ->distinct()
                ->pluck('dni_empleado');

            foreach ($empleados as $dni) {

                $totalPeriodos = DB::table('periodos_vacaciones_sistema')
                    ->where('dni_empleado', $dni)
                    ->whereIn('estado', ['activo', 'extendido'])
                    ->sum('dias_restantes');

                DB::table('dias_acumulados_sistema')->updateOrInsert(
                    ['dni_empleado' => $dni],
                    [
                        'dias_vacacionales' => $totalPeriodos,
                        'updated_at' => now()
                    ]
                );
            }

        })
        ->name('sincronizar_saldo_vacaciones')
        ->hourly()
        ->withoutOverlapping();




        /**
         * 🔥 3. CALENDARIO AUTOMÁTICO (NO TOCAR)
         */
        $schedule->call(function () {

            $hoy = date('Y-m-d');

            $dias = CalendarioDia::where(function($q) use ($hoy){
                $q->where('fecha_inicio','<=',$hoy)
                  ->whereRaw('IFNULL(fecha_fin, fecha_inicio) >= ?', [$hoy]);
            })
            ->where('tipo_afectacion','descuento')
            ->get();

            foreach($dias as $dia){

                $excepciones = DB::table('calendario_excepciones')
                    ->where('calendario_dia_id',$dia->id)
                    ->pluck('departamento_id');

                $empleados = DB::table('empleados')
                    ->whereNotIn('departamento_funcional_id', $excepciones)
                    ->get();

                foreach($empleados as $emp){

                    $yaDescontado = DB::table('historial_descuentos')
                        ->where('dni_empleado',$emp->DNI)
                        ->where('fecha',$hoy)
                        ->where('calendario_dia_id',$dia->id)
                        ->exists();

                    if(!$yaDescontado){

                        $registro = DB::table('dias_acumulados_sistema')
                            ->where('dni_empleado',$emp->DNI)
                            ->first();

                        if($registro){

                            if($registro->dias_vacacionales > 0){

                                DB::table('dias_acumulados_sistema')
                                    ->where('dni_empleado',$emp->DNI)
                                    ->decrement('dias_vacacionales', 1);

                            }elseif($registro->dias_compensatorios > 0){

                                DB::table('dias_acumulados_sistema')
                                    ->where('dni_empleado',$emp->DNI)
                                    ->decrement('dias_compensatorios', 1);
                            }

                            DB::table('historial_descuentos')->insert([
                                'dni_empleado' => $emp->DNI,
                                'fecha' => $hoy,
                                'calendario_dia_id' => $dia->id,
                                'tipo' => 'calendario',
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }
                }
            }

        })
        ->name('procesar_calendario_descuentos')
        ->dailyAt('00:00')
        ->withoutOverlapping();
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