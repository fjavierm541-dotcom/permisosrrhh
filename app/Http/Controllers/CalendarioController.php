<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarioDia;
use Illuminate\Support\Facades\DB;



class CalendarioController extends Controller
{

    public function index()
    {
        return view('calendario.index');
    }

    public function create()
    {
        $departamentos = DB::table('departamentos_muni')->get();

        return view('calendario.create', compact('departamentos'));
    }

    public function edit($id)
{
    $dia = CalendarioDia::findOrFail($id);

    $departamentos = DB::table('departamentos_muni')->get();

    return view('calendario.edit', compact('dia','departamentos'));
}

    
    


    public function store(Request $request)
{
    $request->validate([
        'titulo' => [
            'required',
            'string',
            'max:150',
            'regex:/^[\pL\pN\s\-]+$/u'
        ],
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        'origen' => 'required|in:nacional,local',
        'tipo_afectacion' => 'required|in:no_laborable,descuento',
        'descripcion' => 'required|string|max:500'
    ],[
        'titulo.regex' => 'El título no debe contener caracteres especiales.',
        'fecha_fin.after_or_equal' => 'La fecha fin no puede ser menor que la fecha inicio.'
    ]);

    $inicio = $request->fecha_inicio;
    $fin = $request->fecha_fin ?? $inicio;

    // validar solapamiento
    $existe = CalendarioDia::where(function($q) use ($inicio, $fin){

    $q->where(function($q2) use ($inicio, $fin){

        $q2->where('fecha_inicio','<=',$fin)
           ->whereRaw('IFNULL(fecha_fin, fecha_inicio) >= ?', [$inicio]);

    });

})->exists();

    if($existe){
        return back()->withErrors([
            'fecha_inicio' => 'Ya existe un feriado en ese rango.'
        ])->withInput();
    }

    $dia = CalendarioDia::create([
        'titulo' => $request->titulo,
        'fecha_inicio' => $inicio,
        'fecha_fin' => $request->fecha_fin,
        'origen' => $request->origen,
        'tipo_afectacion' => $request->tipo_afectacion,
        'descripcion' => $request->descripcion
    ]);

    // guardar excepciones
    if($request->has('departamentos')){
        foreach($request->departamentos as $dep){
            DB::table('calendario_excepciones')->insert([
                'calendario_dia_id' => $dia->id,
                'departamento_id' => $dep,
                'tipo' => 'trabaja'
            ]);
        }
    }

    return redirect()->route('calendario.index')
        ->with('success','Feriado agregado correctamente');
}







public function update(Request $request, $id)
{
    $request->validate([
        'titulo' => [
            'required',
            'string',
            'max:150',
            'regex:/^[\pL\pN\s\-]+$/u'
        ],
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        'origen' => 'required|in:nacional,local',
        'tipo_afectacion' => 'required|in:no_laborable,descuento',
        'descripcion' => 'required|string|max:500'
    ],[
        'titulo.regex' => 'El título no debe contener caracteres especiales.',
        'fecha_fin.after_or_equal' => 'La fecha fin no puede ser menor que la fecha inicio.'
    ]);

    $inicio = $request->fecha_inicio;
    $fin = $request->fecha_fin ?? $inicio;

    // validar solapamiento
 $existe = CalendarioDia::where('id','!=',$id)
    ->where(function($q) use ($inicio, $fin){

        $q->where(function($q2) use ($inicio, $fin){

            $q2->where('fecha_inicio','<=',$fin)
               ->whereRaw('IFNULL(fecha_fin, fecha_inicio) >= ?', [$inicio]);

        });

    })->exists();

    if($existe){
        return back()->withErrors([
            'fecha_inicio' => 'Este rango se cruza con otro feriado.'
        ])->withInput();
    }

    $dia = CalendarioDia::findOrFail($id);

    $dia->update([
        'titulo' => $request->titulo,
        'fecha_inicio' => $inicio,
        'fecha_fin' => $request->fecha_fin,
        'origen' => $request->origen,
        'tipo_afectacion' => $request->tipo_afectacion,
        'descripcion' => $request->descripcion
    ]);

    // 🔥 limpiar excepciones
    DB::table('calendario_excepciones')
        ->where('calendario_dia_id',$id)
        ->delete();

    // 🔥 insertar nuevas
    if($request->has('departamentos')){
        foreach($request->departamentos as $dep){
            DB::table('calendario_excepciones')->insert([
                'calendario_dia_id'=>$id,
                'departamento_id'=>$dep,
                'tipo'=>'trabaja',
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
        }
    }

    return redirect()->route('calendario.index')
        ->with('success','Actualizado correctamente');
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

    
    
public function importarFeriados(Request $request)
{
    $year = $request->year ?? date('Y');

    $feriadosClaves = [
        "$year-01-01",
        "$year-05-01",
        "$year-09-15",
        "$year-12-25"
    ];

    $existentes = CalendarioDia::whereIn('fecha_inicio', $feriadosClaves)
                    ->where('origen','nacional')
                    ->count();

    if($existentes >= count($feriadosClaves)){
        return response()->json([
            'status' => 'exists'
        ]);
    }

    $feriados = [
        ['titulo'=>'Año Nuevo','fecha_inicio'=>"$year-01-01"],
        ['titulo'=>'Día del Trabajador','fecha_inicio'=>"$year-05-01"],
        ['titulo'=>'Independencia de Honduras','fecha_inicio'=>"$year-09-15"],
        ['titulo'=>'Navidad','fecha_inicio'=>"$year-12-25"]
    ];

    foreach($feriados as $f){

        $existe = CalendarioDia::where('fecha_inicio', $f['fecha_inicio'])
                    ->where('origen','nacional')
                    ->exists();

        if(!$existe){
            CalendarioDia::create([
                'titulo'=>$f['titulo'],
                'fecha_inicio'=>$f['fecha_inicio'],
                'origen'=>'nacional',
                'tipo_afectacion'=>'no_laborable',
                'descripcion'=>'Feriado nacional'
            ]);
        }

    }

    return response()->json([
        'status'=>'ok'
    ]);
}


public function dia(Request $request)
{
    $fecha = $request->fecha;

    return CalendarioDia::where(function($q) use ($fecha){

        $q->where('fecha_inicio','<=',$fecha)
          ->whereRaw('IFNULL(fecha_fin, fecha_inicio) >= ?', [$fecha]);

    })->get();
}


public function destroy($id)
{
    CalendarioDia::destroy($id);

    return redirect()->route('calendario.index')
        ->with('success','Feriado eliminado correctamente');
}


}

