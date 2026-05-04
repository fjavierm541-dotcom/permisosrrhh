<?php

namespace App\Http\Controllers;
use App\Models\SolicitudCompensatorio; 
use App\Models\Compensatorio; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class SolicitudCompensatorioController extends Controller
{
    /**
     * 🟢 Mostrar formulario de creación
     */
    public function create()
    {
        $departamentos = DB::table('departamentos_muni')
            ->where('activo', 1)
            ->get();

        return view('compensatorios.create', compact('departamentos'));
    }







    /**
     * 🟢 Guardar solicitud
     */
public function store(Request $request)
{
    $request->validate([
        'departamento_id' => 'required|integer',
        'fecha_trabajada' => 'required|date',
        'empleados' => 'required|array|min:1',
        'empleados.*' => 'required|string',
        'descripcion' => 'nullable|string',
        'justificacion' => 'nullable|string',
    ]);

    $hoy = Carbon::today();
    $fecha = Carbon::parse($request->fecha_trabajada);
    $esTardio = $fecha->lt($hoy);

    if ($esTardio && empty($request->justificacion)) {
        return back()
            ->withErrors([
                'justificacion' => 'Debe ingresar una justificación para registros tardíos'
            ])
            ->withInput();
    }

    $solicitud = SolicitudCompensatorio::create([
        'departamento_id' => $request->departamento_id,
        'fecha_trabajada' => $request->fecha_trabajada,
        'descripcion' => $request->descripcion,
        'estado' => 'pendiente',
        'es_registro_tardio' => $esTardio,
        'justificacion' => $request->justificacion,
        'creado_por' => auth()->id() ?? 1
    ]);

    foreach ($request->empleados as $dni) {
        if (!empty($dni)) {
            $solicitud->empleados()->create([
                'dni_empleado' => $dni
            ]);
        }
    }

    return redirect()
        ->route('compensatorios.solicitudes.index')
        ->with('success', 'Solicitud creada correctamente.');
}






public function index()
{
    $solicitudes = SolicitudCompensatorio::with([
        'empleados.empleado',
        'departamento'
    ])
    ->orderByRaw("
        CASE 
            WHEN estado = 'pendiente' THEN 0
            WHEN estado = 'aprobado' THEN 1
            WHEN estado = 'rechazado' THEN 2
            ELSE 3
        END
    ")
    ->latest()
    ->paginate(15);

    return view('compensatorios.index', compact('solicitudes'));
}








    public function show($id)
{
    $solicitud = SolicitudCompensatorio::with([
        'empleados.empleado',
        'departamento'
    ])->findOrFail($id);

    return view('compensatorios.show', compact('solicitud'));
}





public function aprobar(Request $request, $id)
{
    $solicitud = SolicitudCompensatorio::with('empleados')->findOrFail($id);

    $request->validate([
        'dias_aprobados' => 'required|integer|min:1'
    ]);

    $solicitud->update([
        'estado' => 'aprobado',
        'dias_aprobados' => $request->dias_aprobados,
        'aprobado_por' => auth()->id() ?? 1
    ]);

    foreach ($solicitud->empleados as $emp) {

        Compensatorio::create([
            'dni_empleado' => $emp->dni_empleado,
            'dias_otorgados' => $request->dias_aprobados,
            'dias_disponibles' => $request->dias_aprobados,
            'fecha_origen' => $solicitud->fecha_trabajada,
            'fecha_vencimiento' => Carbon::parse($solicitud->fecha_trabajada)->addYears(3),
            'estado' => 'activo',
            'origen' => 'solicitud',
            'referencia_id' => $solicitud->id
        ]);

        DB::table('movimientos_permisos_sistema')->insert([
            'dni_empleado' => $emp->dni_empleado,
            'permiso_id' => null,
            'periodo_id' => null,
            'categoria' => 'compensatorio',
            'tipo_movimiento' => 'asignacion',
            'dias_afectados' => $request->dias_aprobados,
            'horas_afectadas' => 0,
            'descripcion' => 'Asignación de ' . $request->dias_aprobados . ' día(s) compensatorio(s) por solicitud #' . $solicitud->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    return redirect()
        ->route('compensatorios.solicitudes.index')
        ->with('success', 'Solicitud aprobada y compensatorios generados.');
}




public function rechazar(Request $request, $id)
{
    $request->validate([
        'motivo_rechazo' => 'required|string|max:1000'
    ]);

    $solicitud = SolicitudCompensatorio::with('empleados')->findOrFail($id);

    $solicitud->update([
        'estado' => 'rechazado',
        'aprobado_por' => auth()->id() ?? 1
    ]);

    foreach ($solicitud->empleados as $emp) {
        DB::table('movimientos_permisos_sistema')->insert([
            'dni_empleado' => $emp->dni_empleado,
            'permiso_id' => null,
            'periodo_id' => null,
            'categoria' => 'compensatorio',
            'tipo_movimiento' => 'rechazo',
            'dias_afectados' => 0,
            'horas_afectadas' => 0,
            'descripcion' => 'Solicitud #' . $solicitud->id . ' rechazada. Motivo: ' . $request->motivo_rechazo,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    return redirect()
        ->route('compensatorios.solicitudes.index')
        ->with('success', 'Solicitud rechazada correctamente.');
}


}