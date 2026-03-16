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



    public function eventos()
    {

        $dias = CalendarioDia::all();

        $eventos = [];

        foreach ($dias as $dia) {

            $color = '#c9a227';

            if ($dia->origen == 'nacional') {
                $color = '#dc3545';
            }

            if ($dia->origen == 'institucional') {
                $color = '#1f3a5f';
            }

            $eventos[] = [

                'id' => $dia->id,
                'title' => $dia->titulo,
                'start' => $dia->fecha_inicio,
                'end' => $dia->fecha_fin,
                'color' => $color

            ];

        }

        return response()->json($eventos);

    }



    public function store(Request $request)
    {

        CalendarioDia::create($request->all());

        return response()->json([
            'success' => true
        ]);

    }



    public function importarFeriados()
    {

        $year = date('Y');

        $feriados = [

            [
                'titulo' => 'Año Nuevo',
                'fecha_inicio' => "$year-01-01",
                'origen' => 'nacional'
            ],

            [
                'titulo' => 'Día del Trabajador',
                'fecha_inicio' => "$year-05-01",
                'origen' => 'nacional'
            ],

            [
                'titulo' => 'Independencia de Honduras',
                'fecha_inicio' => "$year-09-15",
                'origen' => 'nacional'
            ],

            [
                'titulo' => 'Nacimiento de Morazán',
                'fecha_inicio' => "$year-10-03",
                'origen' => 'nacional'
            ],

            [
                'titulo' => 'Navidad',
                'fecha_inicio' => "$year-12-25",
                'origen' => 'nacional'
            ]

        ];

        foreach ($feriados as $f) {

            CalendarioDia::create($f);

        }

        return response()->json([
            'success' => true
        ]);

    }

}
