<?php

namespace App\Http\Controllers;
use App\Models\SolicitudCompensatorio; 
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
        // ✅ Validación
        $request->validate([
            'departamento_id' => 'required|integer',
            'fecha_trabajada' => 'required|date',
            'empleados' => 'required|array|min:1',
            'empleados.*' => 'required|string',
            'descripcion' => 'nullable|string',
            'justificacion' => 'nullable|string',
        ]);

        // 🧠 Detectar si es tardío
        $hoy = Carbon::today();
        $fecha = Carbon::parse($request->fecha_trabajada);

        $esTardio = $fecha->lt($hoy);

        // ⚠️ Validar justificación si es tardío
        if ($esTardio && empty($request->justificacion)) {
            return back()
                ->withErrors([
                    'justificacion' => 'Debe ingresar una justificación para registros tardíos'
                ])
                ->withInput();
        }

        // 💾 Crear solicitud
        $solicitud = SolicitudCompensatorio::create([
            'departamento_id' => $request->departamento_id,
            'fecha_trabajada' => $request->fecha_trabajada,
            'descripcion' => $request->descripcion,
            'estado' => 'pendiente',
            'es_registro_tardio' => $esTardio,
            'justificacion' => $request->justificacion,
            'creado_por' => auth()->id() ?? 1
        ]);

        // 👥 Guardar empleados relacionados
        foreach ($request->empleados as $dni) {

            // evitar guardar vacíos
            if (!empty($dni)) {
                $solicitud->empleados()->create([
                    'dni_empleado' => $dni
                ]);
            }
        }

        // ✅ Respuesta
        return redirect()
            ->route('compensatorios.solicitudes.create')
            ->with('success', 'Solicitud creada correctamente');
    }






    public function index()
{
    $solicitudes = SolicitudCompensatorio::with([
        'empleados.empleado',
        'departamento'
    ])->latest()->get();

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
    $solicitud = SolicitudCompensatorio::findOrFail($id);

    $request->validate([
        'dias_aprobados' => 'required|integer|min:1'
    ]);

    $solicitud->update([
        'estado' => 'aprobado',
        'dias_aprobados' => $request->dias_aprobados,
        'aprobado_por' => auth()->id() ?? 1
    ]);

    return redirect()->back()->with('success', 'Solicitud aprobada');
}




public function rechazar($id)
{
    $solicitud = SolicitudCompensatorio::findOrFail($id);

    $solicitud->update([
        'estado' => 'rechazado',
        'aprobado_por' => auth()->id() ?? 1
    ]);

    return redirect()->back()->with('success', 'Solicitud rechazada');
}



}