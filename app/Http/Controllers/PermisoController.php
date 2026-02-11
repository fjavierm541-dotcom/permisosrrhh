<?php

namespace App\Http\Controllers;

use App\Models\PermisoSistema;
use App\Models\Empleado;
use App\Models\TipoPermisoSistema;
use App\Models\EstadoPermisoSistema;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /* ==========================
       LISTAR PERMISOS
    ========================== */
    public function index()
    {
        $permisos = PermisoSistema::with(['empleado', 'tipo', 'estado'])->get();

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

        PermisoSistema::create([
            'dni_empleado' => $request->dni_empleado,
            'tipo_permiso_id' => $request->tipo_permiso_id,
            'estado_permiso_id' => 1, // Pendiente
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'horas' => $request->horas ?? 0,
            'motivo' => $request->motivo,
        ]);

        return redirect()->route('permisos.index')
            ->with('success', 'Permiso registrado correctamente');
    }
}
