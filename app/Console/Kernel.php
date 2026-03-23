<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\Models\CalendarioDia;

class Kernel extends ConsoleKernel
{
    /**
     * ESTA FUNCIÓN HARÁ QUE EL SISTEMA VERIFIQUE A LA MEDIA ANOCHE SI ESE DÍA HAY FERIADOS EN EL CALENDARIO
     * QUE RESTEN AUTOMATICAMENTE DÍAS A LOS EMPLEADOS. EJEMPLO: Lunes y martes de Semena Santa son 
     * días que se descuentan automaticamente de las vacaciones de los empleados.
     * ✔ busca calendario
     * ✔ revisa excepciones
     * ✔ descuenta SOLO a los que deben
     * ✔ guarda historial
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {

        $hoy = date('Y-m-d');

        // 🔹 obtener días activos hoy
        $dias = CalendarioDia::where(function($q) use ($hoy){

            $q->where('fecha_inicio','<=',$hoy)
              ->whereRaw('IFNULL(fecha_fin, fecha_inicio) >= ?', [$hoy]);

        })
        ->where('tipo_afectacion','descuento')
        ->get();

        foreach($dias as $dia){

            // 🔹 departamentos que trabajan (NO se descuentan)
            $excepciones = DB::table('calendario_excepciones')
                ->where('calendario_dia_id',$dia->id)
                ->pluck('departamento_id');

            // 🔹 empleados a descontar
            $empleados = DB::table('empleados')
                ->whereNotIn('departamento_funcional_id', $excepciones)
                ->get();

            foreach($empleados as $emp){

                // 🔥 evitar duplicados
                $yaDescontado = DB::table('historial_descuentos')
                    ->where('dni_empleado',$emp->DNI)
                    ->where('fecha',$hoy)
                    ->where('calendario_dia_id',$dia->id)
                    ->exists();

                if(!$yaDescontado){

                    // 🔹 restar día
                    DB::table('dias_acumulados_sistema')
                        ->where('dni_empleado',$emp->DNI)
                        ->decrement('dias_disponibles', 1);

                    // 🔹 guardar historial
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

    })->dailyAt('00:00'); // 🔥 SOLO A MEDIANOCHE
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
