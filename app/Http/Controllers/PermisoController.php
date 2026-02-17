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

        PermisoSistema::create([
    'dni_empleado' => $request->dni_empleado,
    'modalidad' => $request->modalidad,
    'tipo_permiso_id' => $request->tipo_permiso_id,
    'estado_permiso_id' => 1,
    'fecha_inicio' => $request->fecha_inicio,
    'fecha_fin' => $request->fecha_fin,
    'horas' => $request->horas ?? 0,
    'motivo' => $request->motivo,
]);

        return redirect()->route('permisos.index')
            ->with('success', 'Permiso registrado correctamente');
    }




    
/* ==========================
   APROBAR PERMISOS
========================== */
public function aprobar($id)
{
    $permiso = PermisoSistema::with(['tipo','estado'])->findOrFail($id);

    // 🔒 BLOQUEO SI YA FUE PROCESADO
    if (strtolower($permiso->estado->nombre) !== 'pendiente') {
        return back()->with('error', 'Este permiso ya fue procesado.');
    }

    $estadoAprobado = EstadoPermisoSistema::whereRaw('LOWER(nombre) = ?', ['aprobado'])->firstOrFail();

    // ===== SOLO SI RESTA DÍAS =====
    if ($permiso->tipo->resta_dias) {

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

        $diasARestar = floor($horasARestar / 8);
        $horasExtras = $horasARestar % 8;

        // 🔎 TRAER PERÍODOS FIFO
        $periodos = PeriodoVacacionesSistema::where('dni_empleado', $permiso->dni_empleado)
            ->where('estado', 'activo')
            ->whereRaw('(dias_otorgados - dias_usados) > 0')
            ->orderByRaw('COALESCE(extension_hasta, fecha_vencimiento) asc')
            ->get();

        $diasPendientes = $diasARestar;

        foreach ($periodos as $periodo) {

            if ($diasPendientes <= 0) break;

            $diasDisponibles = $periodo->dias_otorgados - $periodo->dias_usados;

            if ($diasDisponibles >= $diasPendientes) {

                $periodo->dias_usados += $diasPendientes;
                $diasPendientes = 0;

            } else {

                $periodo->dias_usados += $diasDisponibles;
                $diasPendientes -= $diasDisponibles;
            }

            $periodo->save();
        }

        // ❌ Si no alcanzó saldo
        if ($diasPendientes > 0) {
            return back()->with('error', 'Saldo insuficiente de vacaciones.');
        }

        // ===== HORAS ACUMULADAS =====
        $acumulado = DiasAcumuladosSistema::firstOrCreate(
            ['dni_empleado' => $permiso->dni_empleado],
            [
                'dias_vacacionales' => 0,
                'dias_compensatorios' => 0,
                'horas_acumuladas' => 0
            ]
        );

        if ($horasExtras > 0) {

            if ($acumulado->horas_acumuladas < $horasExtras) {
                return back()->with('error', 'Horas acumuladas insuficientes.');
            }

            $acumulado->horas_acumuladas -= $horasExtras;
            $acumulado->save();
        }

       // 📌 REGISTRAR MOVIMIENTO
MovimientoPermisoSistema::create([
    'dni_empleado' => $permiso->dni_empleado,
    'permiso_id' => $permiso->id,
    'tipo_movimiento' => 'aprobacion_permiso',
    'categoria' => strtolower($permiso->tipo->nombre),
    'descripcion' => 'Se aprobaron ' . $diasARestar . ' días y ' . $horasExtras . ' horas.',
    'dias_afectados' => $diasARestar,
    'horas_afectadas' => $horasExtras,
    'usuario_responsable' => auth()->user()->name ?? 'Sistema',
    'fecha' => now() // si tienes campo fecha en la tabla
]);

        
    }

    // ===== COMPENSATORIOS =====
    if (strtolower($permiso->tipo->nombre) == 'compensatorio') {

        $acumulado = DiasAcumuladosSistema::firstOrCreate(
            ['dni_empleado' => $permiso->dni_empleado],
            [
                'dias_vacacionales' => 0,
                'dias_compensatorios' => 0,
                'horas_acumuladas' => 0
            ]
        );

        if ($acumulado->dias_compensatorios <= 0) {
            return back()->with('error', 'Saldo compensatorio insuficiente.');
        }

        $acumulado->dias_compensatorios -= 1;
        $acumulado->save();

        // 📌 REGISTRAR MOVIMIENTO
        MovimientoPermisoSistema::create([
            'dni_empleado' => $permiso->dni_empleado,
            'permiso_id' => $permiso->id,
            'tipo_movimiento' => 'permiso_compensatorio',
            'descripcion' => 'Se descontó 1 día compensatorio.',
            'dias_afectados' => 1,
            'horas_afectadas' => 0,
            'usuario_responsable' => auth()->user()->name ?? 'Sistema'
        ]);
    }

    // ✅ ACTUALIZAR ESTADO
    $permiso->estado_permiso_id = $estadoAprobado->id;
    $permiso->save();

    return redirect()->route('permisos.index')
        ->with('success', 'Permiso aprobado correctamente y saldo actualizado.');
}








public function rechazar($id)
{
    $permiso = PermisoSistema::with('estado')->findOrFail($id);

    // 🚨 BLOQUEO SI YA NO ESTÁ PENDIENTE
    if (strtolower($permiso->estado->nombre) !== 'pendiente') {
        return back()->with('error', 'Este permiso ya fue procesado.');
    }

    $estadoRechazado = EstadoPermisoSistema::whereRaw('LOWER(nombre) = ?', ['rechazado'])->firstOrFail();

    $permiso->estado_permiso_id = $estadoRechazado->id;
    $permiso->save();

    return redirect()->route('permisos.index')
        ->with('success', 'Permiso rechazado correctamente.');
}



}