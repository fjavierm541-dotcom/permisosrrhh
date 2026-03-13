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
    $departamento = DepartamentoMuni::with('empleadosFuncionales')
        ->findOrFail($id);

    return view('departamentos.show', compact('departamento'));
}



    

        public function create()
    {
        $padres = DepartamentoMuni::whereNull('departamento_padre_id')->get();

        return view('departamentos.create', compact('padres'));

        
    }





        public function store(Request $request)
{

    $request->validate(

        [

            'codigo' => 'required|digits:3|unique:departamentos_muni,codigo',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
            'departamento_padre_id' => 'nullable|exists:departamentos_muni,id'

        ],

        [

            'codigo.required' => 'Debe ingresar el código del departamento.',
            'codigo.digits' => 'El código debe tener exactamente 3 dígitos.',
            'codigo.unique' => 'Este código ya está registrado.',

            'nombre.required' => 'Debe ingresar el nombre del departamento.',
            'nombre.max' => 'El nombre no puede exceder 150 caracteres.',

            'descripcion.max' => 'La descripción no puede exceder 255 caracteres.',

            'departamento_padre_id.exists' => 'El departamento padre seleccionado no es válido.'

        ]

    );


    DepartamentoMuni::create([

        'codigo' => $request->codigo,
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'departamento_padre_id' => $request->departamento_padre_id,
        'activo' => true

    ]);


    return redirect()
        ->route('departamentos.index')
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




//asignar o agregar deptos
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
    ->update(['departamento_funcional_id' => $id]);

    return redirect()
        ->route('departamentos.show', $id)
        ->with('success','Empleados asignados correctamente');
}






//JEFES DE DEPTOS
//EDITAR JEFE
public function editarJefe($id)
{
    $departamento = DepartamentoMuni::findOrFail($id);

    $empleados = Empleado::where('departamento_funcional_id',$id)
        ->orderBy('primer_nombre')
        ->get();

    return view('departamentos.jefe', compact('departamento','empleados'));
}

public function guardarJefe(Request $request,$id)
{
    $request->validate([
        'jefe_dni' => 'nullable|exists:empleados,DNI'
    ]);

    $empleado = Empleado::where('DNI',$request->jefe_dni)->first();

    // Validar que el empleado pertenezca funcionalmente al departamento
    if($empleado && $empleado->departamento_funcional_id != $id){

        return back()->withErrors([
            'jefe_dni' => 'El jefe debe pertenecer funcionalmente a este departamento.'
        ]);

    }

    // Validar que no sea jefe de otro departamento
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


