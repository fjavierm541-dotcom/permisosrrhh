<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarioDia;

class CalendarioController extends Controller
{

    public function index()
    {
        return view('calendario.index');
    }

    public function create()
    {
        return view('calendario.create');
    }

    public function edit($id)
    {
        $dia = CalendarioDia::findOrFail($id);
        return view('calendario.edit', compact('dia'));
    }

    public function store(Request $request)
    {
        CalendarioDia::create($request->all());

        return redirect()->route('calendario.index');
    }

    public function update(Request $request, $id)
    {
        $dia = CalendarioDia::findOrFail($id);

        $dia->update($request->all());

        return redirect()->route('calendario.index');
    }

    public function eventos()
    {
        $dias = CalendarioDia::all();

        $eventos = [];

        foreach ($dias as $dia) {

            $color = '#c9a227'; // local (dorado)

            if ($dia->origen == 'nacional') {
                $color = '#dc3545'; // rojo
            }

           $eventos[] = [
                'id' => $dia->id,
                'title' => $dia->titulo,
                'start' => $dia->fecha_inicio,
                'end' => $dia->fecha_fin 
                    ? date('Y-m-d', strtotime($dia->fecha_fin . ' +1 day'))
                    : null,
                'color' => $color
            ];
        }

        return response()->json($eventos);
    }

    
    

    public function importarFeriados()
{
    $year = date('Y');

    // verificar si ya existen
    $existe = CalendarioDia::whereYear('fecha_inicio', $year)
                ->where('origen','nacional')
                ->exists();

    if($existe){
        return response()->json([
            'status' => 'exists'
        ]);
    }

    $feriados = [
        ['titulo'=>'Año Nuevo','fecha_inicio'=>"$year-01-01"],
        ['titulo'=>'Día del Trabajador','fecha_inicio'=>"$year-05-01"],
        ['titulo'=>'Independencia','fecha_inicio'=>"$year-09-15"],
        ['titulo'=>'Morazán','fecha_inicio'=>"$year-10-03"],
        ['titulo'=>'Navidad','fecha_inicio'=>"$year-12-25"]
    ];

    foreach($feriados as $f){
        CalendarioDia::create([
            'titulo'=>$f['titulo'],
            'fecha_inicio'=>$f['fecha_inicio'],
            'origen'=>'nacional'
        ]);
    }

    return response()->json([
        'status'=>'ok'
    ]);
}



public function dia(Request $request)
{
    $fecha = $request->fecha;

    return CalendarioDia::whereDate('fecha_inicio','<=',$fecha)
        ->where(function($q) use ($fecha){
            $q->whereNull('fecha_fin')
              ->orWhere('fecha_fin','>=',$fecha);
        })
        ->get();
}


public function destroy($id)
{
    CalendarioDia::destroy($id);

    return redirect()->route('calendario.index')
        ->with('success','Feriado eliminado correctamente');
}


}

