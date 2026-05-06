<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empleado;
use App\Models\PeriodoVacacionesSistema;
use App\Models\MovimientoPermisoSistema;
use Carbon\Carbon;

////Correr este comando para prueba manual de Generación automática de Vacaciones 
// php artisan vacaciones:generar-automaticas

class GenerarVacacionesAutomaticas extends Command
{
    protected $signature = 'vacaciones:generar-automaticas';

    protected $description = 'Genera automáticamente períodos de vacaciones según la fecha de nombramiento del empleado.';

    public function handle()
    {
        $hoy = Carbon::today();

        $empleados = Empleado::whereNotNull('fecha_nombramiento')->get();

        foreach ($empleados as $empleado) {

            $fechaNombramiento = Carbon::parse($empleado->fecha_nombramiento);

            // Si aún no cumple ni 1 año, no genera vacaciones
            if ($fechaNombramiento->diffInYears($hoy) < 1) {
                continue;
            }

            $aniosCumplidos = $fechaNombramiento->diffInYears($hoy);

            // Aniversario laboral de este año cumplido
            $fechaInicioPeriodo = $fechaNombramiento->copy()->addYears($aniosCumplidos);

            // Solo generar cuando ya llegó exactamente o pasó su aniversario
            if ($hoy->lt($fechaInicioPeriodo)) {
                continue;
            }

            // Evitar duplicados por empleado + año laboral
            $yaExiste = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
                ->where('anio_laboral', $aniosCumplidos)
                ->exists();

            if ($yaExiste) {
                continue;
            }

            $diasOtorgados = $this->calcularDiasVacaciones($aniosCumplidos);

            $periodo = PeriodoVacacionesSistema::create([
                'dni_empleado' => $empleado->DNI,
                'anio_laboral' => $aniosCumplidos,
                'dias_otorgados' => $diasOtorgados,
                'dias_usados' => 0,
                'dias_restantes' => $diasOtorgados,
                'fecha_inicio_periodo' => $fechaInicioPeriodo->toDateString(),
                'fecha_vencimiento' => $fechaInicioPeriodo->copy()->addYears(3)->toDateString(),
                'extension_hasta' => null,
                'estado' => 'activo',
            ]);

            MovimientoPermisoSistema::create([
                'dni_empleado' => $empleado->DNI,
                'periodo_id' => $periodo->id,
                'permiso_id' => null,
                'categoria' => 'vacaciones',
                'tipo_movimiento' => 'generado',
                'dias_afectados' => $diasOtorgados,
                'horas_afectadas' => 0,
                'descripcion' => 'Se generó automáticamente el período vacacional del año laboral '
                    . $aniosCumplidos
                    . ' con '
                    . $diasOtorgados
                    . ' días asignados por cumplimiento de '
                    . $aniosCumplidos
                    . ' año(s) laborales.',
            ]);

            $this->info("Vacaciones generadas para {$empleado->DNI} - Año laboral {$aniosCumplidos}");
        }

        $this->info('Proceso de generación automática de vacaciones finalizado.');

        return Command::SUCCESS;
    }

    private function calcularDiasVacaciones(int $anios): int
    {
        return match (true) {
            $anios === 1 => 12,
            $anios === 2 => 15,
            $anios === 3 => 18,
            $anios === 4 => 22,
            $anios === 5 => 26,
            $anios >= 6 => 30,
            default => 0,
        };
    }
}