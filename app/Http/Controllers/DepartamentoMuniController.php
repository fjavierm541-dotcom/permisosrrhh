<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartamentoMuni;

class DepartamentoMuniController extends Controller
{
        public function index(Request $request)
{
    $buscar = $request->buscar;

    $departamentos = DepartamentoMuni::when($buscar, function ($query,$buscar){

        $query->where('nombre','like',"%$buscar%")
              ->orWhere('codigo','like',"%$buscar%");

    })
    ->orderBy('codigo')
    ->paginate(15)
    ->withQueryString();

    return view('departamentos.index', compact('departamentos','buscar'));
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
}


