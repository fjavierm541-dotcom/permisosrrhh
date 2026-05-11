<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empleado;
use App\Models\PeriodoVacacionesSistema;
use App\Models\MovimientoPermisoSistema;
use Carbon\Carbon;

//// Correr este comando para prueba manual de generación automática de vacaciones
// php artisan vacaciones:generar-automaticas
// NO CORRER COMANDO MANUALMENTE EN PRODUCCIÓN, salvo prueba controlada.

class GenerarVacacionesAutomaticas extends Command
{
    protected $signature = 'vacaciones:generar-automaticas';

    protected $description = 'Genera automáticamente períodos de vacaciones para empleados permanentes y por contrato.';

    public function handle()
    {
        $hoy = Carbon::today();

        $empleados = Empleado::whereNotNull('fecha_nombramiento')->get();

        foreach ($empleados as $empleado) {

    $estadoEmpleado = strtolower(trim($empleado->estado_empleado ?? 'activo'));

    if ($estadoEmpleado === 'inactivo') {
        $this->warn("Empleado inactivo omitido: {$empleado->DNI}");
        continue;
    }

    $tipoEmpleado = strtolower(trim($empleado->tipo ?? ''));

    if (str_contains($tipoEmpleado, 'contrato')) {
        $this->generarVacacionesContrato($empleado, $hoy);
    } else {
        $this->generarVacacionesPermanente($empleado, $hoy);
    }
}

        $this->info('Proceso de generación automática de vacaciones finalizado.');

        return Command::SUCCESS;
    }

    private function generarVacacionesPermanente(Empleado $empleado, Carbon $hoy): void
    {
        $fechaNombramiento = Carbon::parse($empleado->fecha_nombramiento);

        if ($fechaNombramiento->diffInYears($hoy) < 1) {
            return;
        }

        $aniosCumplidos = $fechaNombramiento->diffInYears($hoy);

        $fechaInicioPeriodo = $fechaNombramiento->copy()->addYears($aniosCumplidos);

        if ($hoy->lt($fechaInicioPeriodo)) {
            return;
        }

        $yaExiste = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
            ->where('tipo_periodo', 'anual')
            ->where('numero_periodo', $aniosCumplidos)
            ->exists();

        if ($yaExiste) {
            $this->warn("Ya existe período anual para {$empleado->DNI} - Año laboral {$aniosCumplidos}");
            return;
        }

        $diasOtorgados = $this->calcularDiasVacaciones($aniosCumplidos);

        $periodo = PeriodoVacacionesSistema::create([
            'dni_empleado' => $empleado->DNI,
            'anio_laboral' => $aniosCumplidos,
            'tipo_periodo' => 'anual',
            'numero_periodo' => $aniosCumplidos,
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
            'descripcion' => 'Se generó automáticamente el período vacacional anual del año laboral '
                . $aniosCumplidos
                . ' con '
                . $diasOtorgados
                . ' días asignados por cumplimiento de '
                . $aniosCumplidos
                . ' año(s) laborales.',
        ]);

        $this->info("Vacaciones anuales generadas para {$empleado->DNI} - Año laboral {$aniosCumplidos}");
    }

    private function generarVacacionesContrato(Empleado $empleado, Carbon $hoy): void
    {
        $fechaInicioContrato = Carbon::parse($empleado->fecha_nombramiento);

        if ($empleado->fecha_fin_contrato) {
            $fechaFinContrato = Carbon::parse($empleado->fecha_fin_contrato);

            if ($hoy->gt($fechaFinContrato)) {
                return;
            }
        } else {
            $fechaFinContrato = null;
        }

        $estadoEmpleado = strtolower(trim($empleado->estado_empleado ?? ''));

        if ($estadoEmpleado === 'inactivo') {
            return;
        }

        // Mes 1 se gana desde el día de contratación.
        // Mes 2 al cumplir 1 mes, mes 3 al cumplir 2 meses, etc.
        $mesesCumplidos = $fechaInicioContrato->diffInMonths($hoy);
        $numeroPeriodo = $mesesCumplidos + 1;

        $fechaInicioPeriodo = $fechaInicioContrato->copy()->addMonths($numeroPeriodo - 1);

        if ($hoy->lt($fechaInicioPeriodo)) {
            return;
        }

        $yaExiste = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
            ->where('tipo_periodo', 'mensual')
            ->where('numero_periodo', $numeroPeriodo)
            ->exists();

        if ($yaExiste) {
            $this->warn("Ya existe período mensual para {$empleado->DNI} - Mes laboral {$numeroPeriodo}");
            return;
        }

        $fechaVencimiento = $fechaFinContrato
            ? $fechaFinContrato->toDateString()
            : $fechaInicioPeriodo->copy()->addYears(3)->toDateString();

        $periodo = PeriodoVacacionesSistema::create([
            'dni_empleado' => $empleado->DNI,

            // Para contratos no representa años reales.
            // Lo dejamos en 0 para no mezclar con permanentes.
            'anio_laboral' => 0,

            'tipo_periodo' => 'mensual',
            'numero_periodo' => $numeroPeriodo,
            'dias_otorgados' => 1,
            'dias_usados' => 0,
            'dias_restantes' => 1,
            'fecha_inicio_periodo' => $fechaInicioPeriodo->toDateString(),
            'fecha_vencimiento' => $fechaVencimiento,
            'extension_hasta' => null,
            'estado' => 'activo',
        ]);

        MovimientoPermisoSistema::create([
            'dni_empleado' => $empleado->DNI,
            'periodo_id' => $periodo->id,
            'permiso_id' => null,
            'categoria' => 'vacaciones',
            'tipo_movimiento' => 'generado',
            'dias_afectados' => 1,
            'horas_afectadas' => 0,
            'descripcion' => $numeroPeriodo === 1
                ? 'Se generó automáticamente 1 día de vacaciones por inicio de contrato.'
                : 'Se generó automáticamente 1 día de vacaciones por cumplimiento de '
                    . ($numeroPeriodo - 1)
                    . ' mes(es) laborales como empleado por contrato.',
        ]);

        $this->info("Vacación mensual generada para {$empleado->DNI} - Mes laboral {$numeroPeriodo}");
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