<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartamentoMuni;
use App\Models\Empleado;

class DepartamentoMuniController extends Controller
{
public function index(Request $request)
{
    $buscar = $request->buscar;

    $departamentos = DepartamentoMuni::query()

        ->when($buscar, function ($query) use ($buscar) {

            $query->where(function ($q) use ($buscar) {

                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%");

            });

        })

        ->orderBy('codigo')

        ->paginate(15)

        ->withQueryString();

    return view('departamentos.index', compact('departamentos', 'buscar'));
}



public function show($id)
{
    $departamento = DepartamentoMuni::with('empleados')->findOrFail($id);

    return view('departamentos.show', compact('departamento'));
}



    

        public function create()
    {
        $padres = DepartamentoMuni::whereNull('departamento_padre_id')->get();

        return view('departamentos.create', compact('padres'));
    }





        public function store(Request $request)
    {
        DepartamentoMuni::create([

            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'departamento_padre_id' => $request->departamento_padre_id,
            'activo' => true

        ]);

        return redirect()->route('departamentos.index')
            ->with('success','Departamento creado correctamente');
    }




        public function edit($id)
    {
        $departamento = DepartamentoMuni::findOrFail($id);

        $padres = DepartamentoMuni::whereNull('departamento_padre_id')
            ->where('id','!=',$id)
            ->get();

        return view('departamentos.edit', compact('departamento','padres'));
    }

        public function update(Request $request, $id)
    {
        $departamento = DepartamentoMuni::findOrFail($id);

        $departamento->update([

            'codigo'=>$request->codigo,
            'nombre'=>$request->nombre,
            'descripcion'=>$request->descripcion,
            'departamento_padre_id'=>$request->departamento_padre_id,
            'activo'=>$request->activo

        ]);

        return redirect()->route('departamentos.index');
    }


        public function toggle($id)
    {
        $dep = DepartamentoMuni::findOrFail($id);

        $dep->activo = !$dep->activo;

        $dep->save();

        return back();
    }




//asoiganr o agregar deptos
   public function asignar($id)
{
    $departamento = DepartamentoMuni::findOrFail($id);

    $empleados = Empleado::with('departamento')
        ->orderBy('primer_nombre')
        ->get();

    return view('departamentos.asignar', compact('departamento','empleados'));
}

public function guardarAsignacion(Request $request, $id)
{
    $empleados = $request->empleados ?? [];

    Empleado::whereIn('DNI', $empleados)
        ->update(['departamento_id' => $id]);

    return redirect()
        ->route('departamentos.show', $id)
        ->with('success','Empleados asignados correctamente');
}



//JEFES DE DEPTOS
//EDITAR JEFE
public function editarJefe($id)
{
    $departamento = DepartamentoMuni::findOrFail($id);

    $empleados = Empleado::where('departamento_id',$id)
        ->orderBy('primer_nombre')
        ->get();

    return view('departamentos.jefe', compact('departamento','empleados'));
}

//GUARDAR JEFE 
public function guardarJefe(Request $request,$id)
{
    $request->validate([
        'jefe_dni' => 'nullable|exists:empleados,DNI'
    ]);

    $empleado = Empleado::where('DNI',$request->jefe_dni)->first();

    if($empleado && $empleado->departamento_id != $id){

        return back()->withErrors([
            'jefe_dni' => 'El jefe debe pertenecer a este departamento.'
        ]);

    }

    $existe = DepartamentoMuni::where('jefe_dni',$request->jefe_dni)
        ->where('id','!=',$id)
        ->exists();

    if($existe){

        return back()->withErrors([
            'jefe_dni' => 'Este empleado ya es jefe de otro departamento.'
        ]);

    }

    $departamento = DepartamentoMuni::findOrFail($id);

    $departamento->jefe_dni = $request->jefe_dni;
    $departamento->save();

    return redirect()
        ->route('departamentos.show',$id)
        ->with('success','Jefe de departamento actualizado');
}

}


