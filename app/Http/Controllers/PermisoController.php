<?php

namespace App\Http\Controllers;

use App\Models\PermisoSistema;
use App\Models\Empleado;
use App\Models\TipoPermisoSistema;
use App\Models\EstadoPermisoSistema;
use Illuminate\Http\Request;
use App\Models\DiasAcumuladosSistema;

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
    $permiso = PermisoSistema::with(['tipo'])->findOrFail($id);

    // Buscar estado aprobado seguro
    $estadoAprobado = EstadoPermisoSistema::whereRaw('LOWER(nombre) = ?', ['aprobado'])->first();

    if (!$estadoAprobado) {
        return back()->with('error', 'No existe el estado Aprobado en la base de datos.');
    }

    // Buscar acumulado
    $acumulado = DiasAcumuladosSistema::firstOrCreate(
        ['dni_empleado' => $permiso->dni_empleado],
        [
            'dias_vacacionales' => 0,
            'dias_compensatorios' => 0,
            'horas_acumuladas' => 0
        ]
    );

    // ===== SOLO SI RESTA DIAS =====
    if ($permiso->tipo->resta_dias) {

        $totalHoras = ($acumulado->dias_vacacionales * 8) + $acumulado->horas_acumuladas;

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
                $dias = \Carbon\Carbon::parse($permiso->fecha_inicio)
                    ->diffInDays(\Carbon\Carbon::parse($permiso->fecha_fin)) + 1;
                $horasARestar = $dias * 8;
                break;
        }

        // Nunca permitir negativo
        if ($horasARestar > $totalHoras) {
            return back()->with('error', 'Saldo insuficiente de vacaciones.');
        }

        $totalHoras -= $horasARestar;

        $acumulado->dias_vacacionales = floor($totalHoras / 8);
        $acumulado->horas_acumuladas = $totalHoras % 8;
    }

    // ===== COMPENSATORIOS =====
    if (strtolower($permiso->tipo->nombre) == 'compensatorio') {

        if ($acumulado->dias_compensatorios <= 0) {
            return back()->with('error', 'Saldo compensatorio insuficiente.');
        }

        $acumulado->dias_compensatorios -= 1;
    }

    $acumulado->save();

    $permiso->estado_permiso_id = $estadoAprobado->id;
    $permiso->save();

    return redirect()->route('permisos.index')
        ->with('success', 'Permiso aprobado correctamente.');
}








public function rechazar($id)
{
    $permiso = PermisoSistema::findOrFail($id);

    // Buscar estado Rechazado
    $estadoRechazado = EstadoPermisoSistema::where('nombre', 'Rechazado')->first();

    $permiso->estado_permiso_id = $estadoRechazado->id;
    $permiso->save();

    return redirect()->route('permisos.index')
        ->with('success', 'Permiso rechazado correctamente.');
}


}