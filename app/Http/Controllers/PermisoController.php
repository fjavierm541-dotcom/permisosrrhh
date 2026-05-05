<?php

namespace App\Http\Controllers;

use App\Models\PermisoSistema;
use App\Models\Empleado;
use App\Models\TipoPermisoSistema;
use App\Models\EstadoPermisoSistema;
use Illuminate\Http\Request;
use App\Models\DiasAcumuladosSistema;
use App\Models\PeriodoVacacionesSistema;
use App\Models\MovimientoPermisoSistema;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PermisoController extends Controller
{
    /* ==========================
       LISTAR PERMISOS
    ========================== */
   public function index()
{
    $permisos = PermisoSistema::with(['empleado','tipo','estado'])
        ->orderByRaw("
            CASE 
                WHEN estado_permiso_id = (
                    SELECT id FROM estados_permiso_sistema 
                    WHERE LOWER(nombre) = 'pendiente' 
                    LIMIT 1
                ) THEN 0
                ELSE 1
            END
        ")
        ->latest()
        ->paginate(15);

    return view('permisos.index', compact('permisos'));
}

    /* ==========================
       FORMULARIO CREAR
    ========================== */
    public function create()
    {
        $empleados = Empleado::all();
        $tipos = TipoPermisoSistema::where('activo', 1)->get();

        return view('permisos.create', compact('empleados', 'tipos'));
    }

    /* ==========================
       GUARDAR PERMISO
    ========================== */
    public function store(Request $request)
    {
        $request->validate([
            'dni_empleado' => 'required',
            'tipo_permiso_id' => 'required',
            'fecha_inicio' => 'required|date',
        ]);

        $permiso = PermisoSistema::create([
            'dni_empleado' => $request->dni_empleado,
            'modalidad' => $request->modalidad,
            'tipo_permiso_id' => $request->tipo_permiso_id,
            'estado_permiso_id' => 1,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'horas' => $request->horas ?? 0,
            'motivo' => $request->motivo,
        ]);

        return redirect()
            ->route('permisos.index')
            ->with('permiso_imprimir', $permiso->id);
    }




//imprimir por mes
public function imprimirMes(Request $request)
{
    $mes = $request->get('mes', now()->format('m'));
    $anio = $request->get('anio', now()->format('Y'));

    $permisos = PermisoSistema::with(['empleado', 'tipo', 'estado'])
        ->whereMonth('fecha_inicio', $mes)
        ->whereYear('fecha_inicio', $anio)
        ->orderBy('fecha_inicio', 'asc')
        ->get();

    return view('permisos.imprimir-mes', compact('permisos', 'mes', 'anio'));
}


    
    /* ==========================
       APROBAR PERMISOS (FIFO REAL)
    ========================== */
public function aprobar($id)
{
    $permiso = PermisoSistema::with(['tipo','estado'])->findOrFail($id);

    if (strtolower($permiso->estado->nombre) !== 'pendiente') {
        return back()->with('error', 'Este permiso ya fue procesado.');
    }

    $estadoAprobado = EstadoPermisoSistema::whereRaw('LOWER(nombre) = ?', ['aprobado'])->firstOrFail();

    $tipoNombre = strtolower($permiso->tipo->nombre);

    // 🔥 CALCULAR HORAS → DÍAS
    $horasARestar = 0;

    switch ($permiso->modalidad) {
        case 'horas':
            $horasARestar = $permiso->horas ?? 0;
            break;

        case 'medio_dia':
            $horasARestar = 4;
            break;

        case 'un_dia':
            $horasARestar = 8;
            break;

        case 'varios_dias':
            $dias = Carbon::parse($permiso->fecha_inicio)
                ->diffInDays(Carbon::parse($permiso->fecha_fin)) + 1;
            $horasARestar = $dias * 8;
            break;
    }

    $horasSolicitadas = $horasARestar;

    /**
     * 🔵 TIPOS QUE NO RESTAN
     */
    if (!$this->tipoRestaDias($tipoNombre)) {

    $permiso->estado_permiso_id = $estadoAprobado->id;
    $permiso->save();

    MovimientoPermisoSistema::create([
        'dni_empleado' => $permiso->dni_empleado,
        'periodo_id' => null,
        'permiso_id' => $permiso->id,
        'categoria' => $tipoNombre,
        'tipo_movimiento' => 'aprobacion_permiso',
        'dias_afectados' => 0,
        'horas_afectadas' => 0,
        'descripcion' => 'Permiso aprobado sin afectar saldo (' . $permiso->tipo->nombre . ').',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect()->route('permisos.index')
        ->with('success', 'Permiso aprobado sin afectar saldo.');
}

    /**
     * 🔴 VALIDAR SALDO TOTAL
     */
    $totalCompensatorios = DB::table('compensatorios_sistema')
        ->where('dni_empleado', $permiso->dni_empleado)
        ->where('estado', 'activo')
        ->sum('dias_disponibles');

    $totalVacaciones = DB::table('periodos_vacaciones_sistema')
        ->where('dni_empleado', $permiso->dni_empleado)
        ->whereIn('estado', ['activo','extendido'])
        ->selectRaw('SUM(dias_otorgados - dias_usados) as total')
        ->value('total') ?? 0;

    $saldoHoras = DB::table('horas_acumuladas_sistema')
    ->where('dni_empleado', $permiso->dni_empleado)
    ->where('estado', 'activo')
    ->selectRaw('SUM(horas_otorgadas - horas_usadas) as total')
    ->value('total') ?? 0;

$totalDisponibleHoras = $saldoHoras + (($totalCompensatorios + $totalVacaciones) * 8);

if ($horasSolicitadas > $totalDisponibleHoras) {
    return back()->with('error', 'No hay saldo suficiente para cubrir las horas solicitadas.');
}

    /**
     * 🔥 FIFO REAL
     */
    if (in_array($permiso->modalidad, ['horas', 'medio_dia'])) {

    $this->consumirHorasFIFO(
        $permiso->dni_empleado,
        $horasSolicitadas,
        $permiso->id,
        $tipoNombre
    );

} else {

    $diasARestar = $horasSolicitadas / 8;

    $this->consumirDiasFIFO(
        $permiso->dni_empleado,
        $diasARestar,
        $permiso->id,
        $tipoNombre
    );
}

    /**
     * ✅ APROBAR
     */
    $permiso->estado_permiso_id = $estadoAprobado->id;
    $permiso->save();

    return redirect()->route('permisos.index')
        ->with('success', 'Permiso aprobado correctamente.');
}











private function consumirHorasFIFO($dniEmpleado, $horasSolicitadas, $permisoId, $tipoNombre)
{
    $horasPendientes = $horasSolicitadas;

    /**
     * 1. CONSUMIR HORAS ACUMULADAS FIFO
     */
    $horasAcumuladas = DB::table('horas_acumuladas_sistema')
        ->where('dni_empleado', $dniEmpleado)
        ->where('estado', 'activo')
        ->whereRaw('(horas_otorgadas - horas_usadas) > 0')
        ->orderBy('fecha_vencimiento')
        ->orderBy('fecha_origen')
        ->get();

    foreach ($horasAcumuladas as $hora) {

        if ($horasPendientes <= 0) {
            break;
        }

        $horasDisponibles = $hora->horas_otorgadas - $hora->horas_usadas;

        $horasAConsumir = min($horasDisponibles, $horasPendientes);

        $nuevasHorasUsadas = $hora->horas_usadas + $horasAConsumir;

        DB::table('horas_acumuladas_sistema')
            ->where('id', $hora->id)
            ->update([
                'horas_usadas' => $nuevasHorasUsadas,
                'estado' => $nuevasHorasUsadas >= $hora->horas_otorgadas ? 'agotado' : 'activo',
                'updated_at' => now(),
            ]);

        DB::table('movimientos_permisos_sistema')->insert([
            'dni_empleado' => $dniEmpleado,
            'permiso_id' => $permisoId,
            'periodo_id' => null,
            'categoria' => 'horas',
            'tipo_movimiento' => 'consumo',
            'dias_afectados' => 0,
            'horas_afectadas' => $horasAConsumir,
            'descripcion' => 'Consumo de ' . $horasAConsumir . ' horas acumuladas por permiso de ' . $tipoNombre . '.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $horasPendientes -= $horasAConsumir;
    }

    if ($horasPendientes <= 0) {
        return;
    }

    /**
     * 2. CONSUMIR COMPENSATORIOS FIFO
     */
    $compensatorios = DB::table('compensatorios_sistema')
        ->where('dni_empleado', $dniEmpleado)
        ->where('estado', 'activo')
        ->where('dias_disponibles', '>', 0)
        ->orderBy('fecha_vencimiento')
        ->orderBy('fecha_origen')
        ->get();

    foreach ($compensatorios as $compensatorio) {

        if ($horasPendientes <= 0) {
            break;
        }

        /**
         * Se toma 1 día completo
         */
        $nuevoDisponible = $compensatorio->dias_disponibles - 1;

        DB::table('compensatorios_sistema')
            ->where('id', $compensatorio->id)
            ->update([
                'dias_disponibles' => $nuevoDisponible,
                'estado' => $nuevoDisponible <= 0 ? 'agotado' : 'activo',
                'updated_at' => now(),
            ]);

        $horasDelDia = 8;
        $horasAConsumir = min($horasDelDia, $horasPendientes);
        $sobrante = $horasDelDia - $horasAConsumir;

        /**
         * Si sobran horas, se guardan como bolsa nueva
         */
        if ($sobrante > 0) {
            DB::table('horas_acumuladas_sistema')->insert([
                'dni_empleado' => $dniEmpleado,
                'horas_otorgadas' => $sobrante,
                'horas_usadas' => 0,
                'fecha_origen' => $compensatorio->fecha_origen,
                'fecha_vencimiento' => $compensatorio->fecha_vencimiento,
                'estado' => 'activo',
                'origen' => 'compensatorio',
                'referencia_id' => $compensatorio->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('movimientos_permisos_sistema')->insert([
            'dni_empleado' => $dniEmpleado,
            'permiso_id' => $permisoId,
            'periodo_id' => null,
            'categoria' => 'compensatorio',
            'tipo_movimiento' => 'consumo_horas',
            'dias_afectados' => 1,
            'horas_afectadas' => $horasAConsumir,
            'descripcion' => 'Consumo por horas. Se convirtió 1 día compensatorio en 8 horas. Se consumieron '
                . $horasAConsumir . ' horas y sobraron ' . $sobrante . ' horas.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $horasPendientes -= $horasAConsumir;
    }

    if ($horasPendientes <= 0) {
        return;
    }

    /**
     * 3. CONSUMIR VACACIONES FIFO
     */
    $periodos = DB::table('periodos_vacaciones_sistema')
        ->where('dni_empleado', $dniEmpleado)
        ->whereIn('estado', ['activo', 'extendido'])
        ->whereRaw('(dias_otorgados - dias_usados) > 0')
        ->orderByRaw('COALESCE(extension_hasta, fecha_vencimiento) ASC')
        ->orderBy('fecha_inicio_periodo')
        ->get();

    foreach ($periodos as $periodo) {

        if ($horasPendientes <= 0) {
            break;
        }

        /**
         * Se toma 1 día completo
         */
        DB::table('periodos_vacaciones_sistema')
            ->where('id', $periodo->id)
            ->update([
                'dias_usados' => $periodo->dias_usados + 1,
                'dias_restantes' => max(0, $periodo->dias_restantes - 1),
                'updated_at' => now(),
            ]);

        $horasDelDia = 8;
        $horasAConsumir = min($horasDelDia, $horasPendientes);
        $sobrante = $horasDelDia - $horasAConsumir;

        /**
         * Si sobran horas, se guardan como bolsa nueva
         */
        if ($sobrante > 0) {
            DB::table('horas_acumuladas_sistema')->insert([
                'dni_empleado' => $dniEmpleado,
                'horas_otorgadas' => $sobrante,
                'horas_usadas' => 0,
                'fecha_origen' => $periodo->fecha_inicio_periodo,
                'fecha_vencimiento' => $periodo->extension_hasta ?? $periodo->fecha_vencimiento,
                'estado' => 'activo',
                'origen' => 'vacaciones',
                'referencia_id' => $periodo->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('movimientos_permisos_sistema')->insert([
            'dni_empleado' => $dniEmpleado,
            'permiso_id' => $permisoId,
            'periodo_id' => $periodo->id,
            'categoria' => 'vacaciones',
            'tipo_movimiento' => 'consumo_horas',
            'dias_afectados' => 1,
            'horas_afectadas' => $horasAConsumir,
            'descripcion' => 'Consumo por horas. Se convirtió 1 día de vacaciones en 8 horas. Se consumieron '
                . $horasAConsumir . ' horas y sobraron ' . $sobrante . ' horas.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $horasPendientes -= $horasAConsumir;
    }
}










    /* ==========================
       RECHAZAR
    ========================== */
   public function rechazar(Request $request, $id)
{
    $request->validate([
        'motivo_rechazo' => 'required|string|max:500'
    ], [
        'motivo_rechazo.required' => 'Debe ingresar el motivo del rechazo.',
        'motivo_rechazo.max' => 'El motivo no puede exceder 500 caracteres.'
    ]);

    $permiso = PermisoSistema::with(['estado', 'tipo'])->findOrFail($id);

    if (strtolower($permiso->estado->nombre) !== 'pendiente') {
        return back()->with('error', 'Este permiso ya fue procesado.');
    }

    $estadoRechazado = EstadoPermisoSistema::whereRaw('LOWER(nombre) = ?', ['rechazado'])->firstOrFail();

    $permiso->estado_permiso_id = $estadoRechazado->id;
    $permiso->save();

    MovimientoPermisoSistema::create([
        'dni_empleado' => $permiso->dni_empleado,
        'periodo_id' => null,
        'permiso_id' => $permiso->id,
        'categoria' => strtolower($permiso->tipo->nombre ?? 'permiso'),
        'tipo_movimiento' => 'rechazo_permiso',
        'dias_afectados' => 0,
        'horas_afectadas' => 0,
        'descripcion' => 'Permiso rechazado: ' . $request->motivo_rechazo,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return back()->with('success', 'Permiso rechazado correctamente.');
}






    
    /* ==========================
       FIFO REAL
    ========================== */
   private function consumirDiasFIFO($dni, $diasSolicitados, $permisoId, $tipoNombre)
{
    $diasRestantes = $diasSolicitados;

    /**
     * 🔥 1. COMPENSATORIOS
     */
    $compensatorios = DB::table('compensatorios_sistema')
        ->where('dni_empleado', $dni)
        ->where('estado', 'activo')
        ->where('dias_disponibles', '>', 0)
        ->orderBy('fecha_origen', 'asc')
        ->get();

    foreach ($compensatorios as $comp) {

        if ($diasRestantes <= 0) break;

        $usar = min($comp->dias_disponibles, $diasRestantes);

        DB::table('compensatorios_sistema')
            ->where('id', $comp->id)
            ->update([
                'dias_disponibles' => $comp->dias_disponibles - $usar,
                'estado' => ($comp->dias_disponibles - $usar) <= 0 ? 'agotado' : 'activo'
            ]);

        DB::table('movimientos_permisos_sistema')->insert([
            'dni_empleado' => $dni,
            'permiso_id' => $permisoId,
            'categoria' => $tipoNombre,
            'tipo_movimiento' => 'consumo',
            'dias_afectados' => $usar,
            'horas_afectadas' => 0,
            'descripcion' => 'Descuento de ' . $usar . ' día(s) por permiso (' . ucfirst($tipoNombre) . ')',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $diasRestantes -= $usar;
    }

    /**
     * 🔥 2. VACACIONES
     */
    if ($diasRestantes > 0) {

        $periodos = DB::table('periodos_vacaciones_sistema')
            ->where('dni_empleado', $dni)
            ->whereIn('estado', ['activo','extendido'])
            ->whereRaw('(dias_otorgados - dias_usados) > 0')
            ->orderBy('fecha_inicio_periodo', 'asc')
            ->get();

        foreach ($periodos as $p) {

            if ($diasRestantes <= 0) break;

            $disponible = $p->dias_otorgados - $p->dias_usados;
            $usar = min($disponible, $diasRestantes);

            DB::table('periodos_vacaciones_sistema')
                ->where('id', $p->id)
                ->increment('dias_usados', $usar);

            DB::table('movimientos_permisos_sistema')->insert([
                'dni_empleado' => $dni,
                'permiso_id' => $permisoId,
                'periodo_id' => $p->id,
                'categoria' => $tipoNombre,
                'tipo_movimiento' => 'consumo',
                'dias_afectados' => $usar,
                'horas_afectadas' => 0,
                'descripcion' => 'Descuento de ' . $usar . ' día(s) por permiso (' . ucfirst($tipoNombre) . ')',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $diasRestantes -= $usar;
        }
    }

    return $diasRestantes;
}

private function tipoRestaDias($tipoNombre)
{
    $tipo = strtolower($tipoNombre);

    return str_contains($tipo, 'vacacion') ||
           str_contains($tipo, 'compensatorio') ||
           str_contains($tipo, 'personal');
}
}