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
            ->latest()
            ->get();

        $acumulados = DiasAcumuladosSistema::all()
            ->keyBy('dni_empleado');

        return view('permisos.index', compact('permisos', 'acumulados'));
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

    $diasARestar = max(1, floor($horasARestar / 8));

    /**
     * 🔵 TIPOS QUE NO RESTAN
     */
    if (!$this->tipoRestaDias($tipoNombre)) {

        $permiso->estado_permiso_id = $estadoAprobado->id;
        $permiso->save();

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

    $totalDisponible = $totalCompensatorios + $totalVacaciones;

    if ($diasARestar > $totalDisponible) {
        return back()->with('error', 'No hay días disponibles suficientes.');
    }

    /**
     * 🔥 FIFO REAL
     */
    $this->consumirDiasFIFO(
        $permiso->dni_empleado,
        $diasARestar,
        $permiso->id,
        $tipoNombre
    );

    /**
     * ✅ APROBAR
     */
    $permiso->estado_permiso_id = $estadoAprobado->id;
    $permiso->save();

    return redirect()->route('permisos.index')
        ->with('success', 'Permiso aprobado correctamente.');
}





    /* ==========================
       RECHAZAR
    ========================== */
    public function rechazar(Request $request, $id)
    {
        $permiso = PermisoSistema::with('estado')->findOrFail($id);

        if (strtolower($permiso->estado->nombre) !== 'pendiente') {
            return back()->with('error', 'Este permiso ya fue procesado.');
        }

        $estadoRechazado = EstadoPermisoSistema::whereRaw('LOWER(nombre) = ?', ['rechazado'])->firstOrFail();

        $permiso->estado_permiso_id = $estadoRechazado->id;
        $permiso->save();

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