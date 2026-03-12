<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\DiasAcumuladosSistema;
use App\Models\PeriodoVacacionesSistema;
use Carbon\Carbon;
use App\Models\MovimientoPermisoSistema;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DepartamentoMuni;
use App\Models\DocumentoEmpleado;


class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{

    // 🔄 Marcar vencidos automáticamente
    PeriodoVacacionesSistema::where('estado', 'activo')
        ->where(function ($query) {
            $query->whereNull('extension_hasta')
                  ->whereDate('fecha_vencimiento', '<', Carbon::today());
        })
        ->orWhere(function ($query) {
            $query->whereNotNull('extension_hasta')
                  ->whereDate('extension_hasta', '<', Carbon::today());
        })
        ->update(['estado' => 'vencido']);


    $empleados = Empleado::all();


    foreach ($empleados as $empleado) {

        // ===== DÍAS DISPONIBLES =====
        $periodos = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
            ->where('estado', 'activo')
            ->get();

        $totalDiasDisponibles = 0;

        foreach ($periodos as $periodo) {
            $otorgados = (int) $periodo->dias_otorgados;
            $usados = (int) ($periodo->dias_usados ?? 0);
            $restantes = max(0, $otorgados - $usados);
            $totalDiasDisponibles += $restantes;
        }

        $empleado->dias_disponibles = $totalDiasDisponibles;

        $acumulado = DiasAcumuladosSistema::where('dni_empleado', $empleado->DNI)->first();
        $empleado->horas_disponibles = $acumulado->horas_acumuladas ?? 0;


        // ===== SEMÁFORO =====
        $periodoProximo = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
            ->where('estado', 'activo')
            ->whereRaw('(dias_otorgados - dias_usados) > 0')
            ->orderByRaw('COALESCE(extension_hasta, fecha_vencimiento) asc')
            ->first();

        if ($periodoProximo) {

            $fechaReferencia = $periodoProximo->extension_hasta
                ?? $periodoProximo->fecha_vencimiento;

            $diasRestantes = Carbon::today()->diffInDays($fechaReferencia, false);

            if ($diasRestantes > 180) {
                $empleado->semaforo = 'verde';
            } elseif ($diasRestantes > 90) {
                $empleado->semaforo = 'amarillo';
            } else {
                $empleado->semaforo = 'rojo';
            }

        } else {
            $empleado->semaforo = 'verde';
        }
    }


    // 🔍 BUSCADOR
    if ($request->filled('buscar')) {

        $buscar = strtolower($request->buscar);

        $empleados = $empleados->filter(function ($empleado) use ($buscar) {

            $nombreCompleto = strtolower(
                $empleado->primer_nombre . ' ' .
                $empleado->segundo_nombre . ' ' .
                $empleado->primer_apellido . ' ' .
                $empleado->segundo_apellido
            );

            return str_contains($nombreCompleto, $buscar)
                || str_contains(strtolower($empleado->DNI), $buscar);
        });
    }


    // 🔍 FILTRO POR SEXO
    if ($request->sexo) {
        $empleados = $empleados->where('sexo', $request->sexo);
    }


    // 🔍 FILTRO POR SEMÁFORO
    if ($request->estado) {
        $empleados = $empleados->where('semaforo', $request->estado);
    }


    // 🔢 PAGINACIÓN MANUAL
    $page = $request->get('page', 1);
    $perPage = 15;

    $empleados = new \Illuminate\Pagination\LengthAwarePaginator(
        $empleados->forPage($page, $perPage),
        $empleados->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('empleados.index', compact('empleados'));
}







public function generarVacaciones()
{
    $hoy = Carbon::today();

    $empleados = Empleado::all();

    foreach ($empleados as $empleado) {

        if (!$empleado->fecha_nombramiento) continue;

        $fechaIngreso = Carbon::parse($empleado->fecha_nombramiento);

        // ¿Hoy cumple aniversario?
        if ($fechaIngreso->month != $hoy->month ||
            $fechaIngreso->day != $hoy->day) {
            continue;
        }

        $aniosCumplidos = $fechaIngreso->diffInYears($hoy);

        if ($aniosCumplidos < 1) continue;

        // Tabla legal
        if ($aniosCumplidos == 1) $dias = 12;
        elseif ($aniosCumplidos == 2) $dias = 15;
        elseif ($aniosCumplidos == 3) $dias = 18;
        elseif ($aniosCumplidos == 4) $dias = 22;
        elseif ($aniosCumplidos == 5) $dias = 26;
        else $dias = 30;

        // Evitar duplicados
        $existe = PeriodoVacacionesSistema::where('dni_empleado', $empleado->DNI)
            ->where('anio_laboral', $aniosCumplidos)
            ->exists();

        if ($existe) continue;

        PeriodoVacacionesSistema::create([
            'dni_empleado' => $empleado->DNI,
            'anio_laboral' => $aniosCumplidos,
            'dias_otorgados' => $dias,
            'dias_usados' => 0,
            'fecha_inicio_periodo' => $hoy,
            'fecha_vencimiento' => $hoy->copy()->addYears(2),
            'estado' => 'activo'
        ]);
    }

    return back()->with('success', 'Proceso de generación ejecutado correctamente.');
}





    /**
     *CREAR EMPLEADOS
     
   */

public function create()
{

$departamentos = DepartamentoMuni::orderBy('codigo')->get();

return view('empleados.create', compact('departamentos'));

}







public function store(Request $request)
{

$request->merge([
    'DNI' => preg_replace('/\D+/', '', (string) $request->input('DNI', '')),
    'RTN' => preg_replace('/\D+/', '', (string) $request->input('RTN', '')),

]);

for ($i = 1; $i <= 7; $i++) {

    $request->merge([
        "nombre_beneficiario$i" => $request->input("nombre_beneficiario$i") ?: 'Vacío',
        "porcentaje_beneficiario$i" => $request->input("porcentaje_beneficiario$i") ?: 0,
        "parentezco_beneficiario$i" => $request->input("parentezco_beneficiario$i") ?: 'Vacío',
        "DNI_beneficiario$i" => $request->input("DNI_beneficiario$i") ?: '0000-0000-00000'
    ]);

}

$request->validate([

    // DATOS GENERALES
    'primer_nombre' => ['required','regex:/^[\pL\s]+$/u','max:50'],
    'segundo_nombre' => ['nullable','regex:/^[\pL\s]+$/u','max:50'],
    'primer_apellido' => ['required','regex:/^[\pL\s]+$/u','max:50'],
    'segundo_apellido' => ['nullable','regex:/^[\pL\s]+$/u','max:50'],
    'codigo' => ['required','regex:/^[0-9]{1,4}$/'],
    'DNI' => ['required','unique:empleados,DNI','regex:/^[0-9]{13}$/'],
    'RTN' => ['required','regex:/^[0-9]{14}$/'],
    'sexo' => 'required|in:Masculino,Femenino',

    'estado_civil' => 'required|in:Soltero(a),Casado(a),Unión Libre,Divorciado(a),Viudo(a)',
    'nacionalidad' => ['required','regex:/^[\pL\s]+$/u','max:50'],
    'tipo_sangre' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
    
    //INFORMACION DE CONTACTO 
    'telefono_celular' => ['required','regex:/^(3|8|9)[0-9]{7}$/'],
    'telefono_fijo' => ['nullable','regex:/^2[0-9]{7}$/'],
    'direccion_domicilio' => 'required|string|max:255',
    'referencia_domicilio' => 'required|string|max:255', 

    // EDUCACIÓN
    'nivel_educativo' => 'required|in:Nivel Primario,Nivel Secundario,Nivel Superior,Postgrado',
    
    //CONTACTOS DE EMERGENCIA
    'nombre_contacto1' => ['required','regex:/^[\pL\s]+$/u','max:50'],
    'telefono_contacto1' => ['required','regex:/^[0-9]{8}$/'],
    'parentezco_contacto1' => 'required|in:Padre,Madre,Hermano(a),Abuelo(a),Tío(a),Primo(a),Esposo(a),Pareja,Hijo(a),Amigo(a),Vecino(a),Otro',
    'nombre_contacto2' => ['required','regex:/^[\pL\s]+$/u','max:50'],
    'telefono_contacto2' => ['required','regex:/^[0-9]{8}$/'],
    'parentezco_contacto2' => 'required|in:Padre,Madre,Hermano(a),Abuelo(a),Tío(a),Primo(a),Esposo(a),Pareja,Hijo(a),Amigo(a),Vecino(a),Otro',

    //BENEFICIARIOS

    'nombre_beneficiario1' => 'nullable|string|max:100',
    'porcentaje_beneficiario1' => 'nullable|numeric|min:0|max:100',
    'parentezco_beneficiario1' => 'nullable|string|max:50',
    'DNI_beneficiario1' => ['nullable','regex:/^[0-9]{4}-[0-9]{4}-[0-9]{5}$/'],

    'nombre_beneficiario2' => 'nullable|string|max:100',
    'porcentaje_beneficiario2' => 'nullable|numeric|min:0|max:100',
    'parentezco_beneficiario2' => 'nullable|string|max:50',
    'DNI_beneficiario2' => ['nullable','regex:/^[0-9]{4}-[0-9]{4}-[0-9]{5}$/'],

    'nombre_beneficiario3' => 'nullable|string|max:100',
    'porcentaje_beneficiario3' => 'nullable|numeric|min:0|max:100',
    'parentezco_beneficiario3' => 'nullable|string|max:50',
    'DNI_beneficiario3' => ['nullable','regex:/^[0-9]{4}-[0-9]{4}-[0-9]{5}$/'],

    'nombre_beneficiario4' => 'nullable|string|max:100',
    'porcentaje_beneficiario4' => 'nullable|numeric|min:0|max:100',
    'parentezco_beneficiario4' => 'nullable|string|max:50',
    'DNI_beneficiario4' => ['nullable','regex:/^[0-9]{4}-[0-9]{4}-[0-9]{5}$/'],

    'nombre_beneficiario5' => 'nullable|string|max:100',
    'porcentaje_beneficiario5' => 'nullable|numeric|min:0|max:100',
    'parentezco_beneficiario5' => 'nullable|string|max:50',
    'DNI_beneficiario5' => ['nullable','regex:/^[0-9]{4}-[0-9]{4}-[0-9]{5}$/'],

    'nombre_beneficiario6' => 'nullable|string|max:100',
    'porcentaje_beneficiario6' => 'nullable|numeric|min:0|max:100',
    'parentezco_beneficiario6' => 'nullable|string|max:50',
    'DNI_beneficiario6' => ['nullable','regex:/^[0-9]{4}-[0-9]{4}-[0-9]{5}$/'],

    'nombre_beneficiario7' => 'nullable|string|max:100',
    'porcentaje_beneficiario7' => 'nullable|numeric|min:0|max:100',
    'parentezco_beneficiario7' => 'nullable|string|max:50',
    'DNI_beneficiario7' => ['nullable','regex:/^[0-9]{4}-[0-9]{4}-[0-9]{5}$/'],

    // INFORMACIÓN LABORAL
    'puesto' => ['required','regex:/^[\pL\s]+$/u','min:3','max:20'],
    'fecha_nombramiento' => ['required','date','before_or_equal:today'],
    'tipo' => ['required','in:Acuerdo,Contrato'],
    'salario_inicial' => ['required','regex:/^L\.?\s?[0-9]{1,3}(,[0-9]{3})*(\.[0-9]{2})?$/'],
    'departamento_id' => 'required|exists:departamentos_muni,id',

    // DOCUMENTOS
    'copia_dni' => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
    'acuerdo' => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
    'nota_traslado' => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],

    
], [

    //DATOS GENERALES
    'codigo.required' => 'El código es obligatorio.',
    'codigo.regex' => 'El código debe contener entre 1 y 4 dígitos.',
    
    'primer_nombre.required' => 'El primer nombre es obligatorio.',
    'primer_nombre.regex' => 'El nombre no debe aceptar números ni caracteres especiales.',
    'primer_nombre.max' => 'El nombre no debe exceder 50 caracteres.',

    'segundo_nombre.regex' => 'El segundo nombre no debe aceptar números.',
    'segundo_nombre.max' => 'Máximo 50 caracteres.',

    'primer_apellido.required' => 'El primer apellido es obligatorio.',
    'primer_apellido.regex' => 'El apellido no debe aceptar números.',
    'primer_apellido.max' => 'Máximo 50 caracteres.',

    'segundo_apellido.regex' => 'El segundo apellido no debe aceptar números.',
    'segundo_apellido.max' => 'Máximo 50 caracteres.',

    'DNI.required' => 'El DNI es obligatorio.',
    'DNI.unique' => 'Este DNI ya está registrado.',
    'DNI.regex' => 'El DNI debe contener exactamente 13 números.',
    'RTN.required' => 'El RTN es obligatorio.',
    'RTN.regex' => 'El RTN debe contener exactamente 14 números.',

    'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
    'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser futura.',
    'fecha_nacimiento.date' => 'La fecha de nacimiento no es válida.',

    'sexo.required' => 'Debe seleccionar el sexo.',

    'telefono_celular.regex' => 'El celular del empleado debe iniciar con 3, 8 o 9 y tener 8 dígitos.',
    'telefono_celular.required' => 'El teléfono celular es obligatorio.',
    'telefono_fijo.regex' => 'El teléfono fijo del empleado debe iniciar con 2 y tener 8 dígitos.',

    'salario_inicial.regex' => 'El salario debe tener formato: L. 12,000.00',
    'salario_inicial.required' => 'Ingresar el salario es obligatori.',
    'referencia_domicilio.required' => 'La referencia del domicilio es obligatoria.',
    'direccion_domicilio.required' => 'La dirección es obligatoria.',

    //CONTACTOS DE EMERGENCIA
    'nombre_contacto1.required' => 'El nombre del primer contacto es obligatorio.',
    'nombre_contacto1.regex' => 'El nombre del primer contacto solo puede contener letras y espacios.',
    'nombre_contacto1.max' => 'El nombre del primer contacto no puede tener más de 50 caracteres.',
    'telefono_contacto1.required' => 'El teléfono del primer contacto es obligatorio.',
    'telefono_contacto1.regex' => 'El teléfono del primer contacto debe contener exactamente 8 números.',
    'parentezco_contacto1.required' => 'Debe seleccionar el parentesco del primer contacto.',
    'parentezco_contacto1.in' => 'El parentesco del primer contacto no es válido.',
    'nombre_contacto2.required' => 'El nombre del segundo contacto es obligatorio.',
    'nombre_contacto2.regex' => 'El nombre del segundo contacto solo puede contener letras y espacios.',
    'nombre_contacto2.max' => 'El nombre del segundo contacto no puede tener más de 50 caracteres.',
    'telefono_contacto2.required' => 'El teléfono del segundo contacto es obligatorio.',
    'telefono_contacto2.regex' => 'El teléfono del segundo contacto debe contener exactamente 8 números.',
    'parentezco_contacto2.required' => 'Debe seleccionar el parentesco del segundo contacto.',
    'parentezco_contacto2.in' => 'El parentesco del segundo contacto no es válido.',

    // BENEFICIARIOS
    'DNI_beneficiario1.digits' => 'El DNI del beneficiario debe tener 13 números.',
    'DNI_beneficiario2.digits' => 'El DNI del beneficiario debe tener 13 números.',
    'DNI_beneficiario3.digits' => 'El DNI del beneficiario debe tener 13 números.',
    'DNI_beneficiario4.digits' => 'El DNI del beneficiario debe tener 13 números.',
    'DNI_beneficiario5.digits' => 'El DNI del beneficiario debe tener 13 números.',
    'DNI_beneficiario6.digits' => 'El DNI del beneficiario debe tener 13 números.',
    'DNI_beneficiario7.digits' => 'El DNI del beneficiario debe tener 13 números.',

    // PUESTO
        'puesto.required' => 'El ingreso del puesto es obligatorio.',
        'puesto.regex' => 'El puesto solo debe contener letras.',
        'puesto.min' => 'El puesto debe tener al menos 3 caracteres.',
        'puesto.max' => 'El puesto no debe superar 20 caracteres.',

        // FECHA
        'fecha_nombramiento.required' => 'Debe ingresar la fecha de nombramiento.',
        'fecha_nombramiento.date' => 'Debe ingresar una fecha válida.',
        'fecha_nombramiento.before_or_equal' => 'La fecha de nombramiento no puede ser futura.',

        // TIPO
        'tipo.required' => 'Debe seleccionar el tipo de nombramiento.',
        'tipo.in' => 'El tipo de nombramientoi seleccionado no es válido.',

        // SALARIO
        'salario_inicial.required' => 'El ingreso del salario es obligatorio.',
        'salario_inicial.regex' => 'El salario debe tener formato: L. 12,000.00',

        // DOCUMENTOS
        'copia_dni.mimes' => 'La copia del DNI debe ser PDF o imagen.',
        'acuerdo.mimes' => 'El acuerdo o contrato debe ser PDF o imagen.',
        'nota_traslado.mimes' => 'La nota de traslado debe ser PDF o imagen.',

        'copia_dni.max' => 'El archivo no debe superar 5MB.',
        'acuerdo.max' => 'El archivo no debe superar 5MB.',
        'nota_traslado.max' => 'El archivo no debe superar 5MB.',


]);


 $fechaNacimiento = \Carbon\Carbon::parse($request->fecha_nacimiento);
    $data['anio_nacimiento'] = $fechaNacimiento->format('Y');
    $data['mes_nacimiento'] = $fechaNacimiento->format('m');
    $data['dia_nacimiento'] = $fechaNacimiento->format('d');

    //SALARIO
    if ($request->filled('salario_inicial')) {
    $salario = $request->salario_inicial;
    // quitar L. y espacios
    $salario = str_replace('L.', '', $salario);
    $salario = str_replace(' ', '', $salario);
    // quitar comas de miles
    $salario = str_replace(',', '', $salario);

    $request->merge([
        'salario_inicial' => $salario
    ]);
}

    $data = $request->all();
    $data['usuario_crea'] = auth()->user()->name ?? 'Sistema';;

    // Crear empleado primero
    $empleado = Empleado::create($data);

    // ================================
    // Guardar documentos en tabla aparte
    // ================================

    $documentos = [
        'copia_dni' => 'Copia DNI',
        'acuerdo' => 'Acuerdo',
        'nota_traslado' => 'Nota Traslado'
    ];

    foreach ($documentos as $campo => $tipo) {

        if ($request->hasFile($campo)) {

            $ruta = $request->file($campo)
                ->store("empleados/{$empleado->DNI}/documentos", 'public');

            DocumentoEmpleado::create([
                'dni_empleado' => $empleado->DNI,
                'tipo_documento' => $tipo,
                'ruta_archivo' => $ruta
            ]);
        }
    }

    return redirect()
        ->route('empleados.index')
        ->with('success', 'Empleado creado correctamente.');
}






public function show($dni)
{
    $empleado = Empleado::where('DNI', $dni)->firstOrFail();

    // Períodos activos
    $periodosActivos = PeriodoVacacionesSistema::where('dni_empleado', $dni)
        ->where('estado', 'activo')
        ->orderByDesc('anio_laboral')
        ->get();

    // Períodos vencidos
    $periodosVencidos = PeriodoVacacionesSistema::where('dni_empleado', $dni)
        ->where('estado', 'vencido')
        ->orderByDesc('anio_laboral')
        ->get();

    // Movimientos
    $movimientos = MovimientoPermisoSistema::where('dni_empleado', $dni)
        ->orderByDesc('created_at')
        ->get();

    // Calcular días disponibles SOLO activos
    $totalDiasDisponibles = $periodosActivos->sum(function ($periodo) {
        return max(0, $periodo->dias_otorgados - $periodo->dias_usados);
    });

    return view('empleados.show', compact(
        'empleado',
        'periodosActivos',
        'periodosVencidos',
        'movimientos',
        'totalDiasDisponibles'
    ));
}



public function reporte($dni)
{
    $empleado = Empleado::where('DNI', $dni)->firstOrFail();

    $periodosActivos = PeriodoVacacionesSistema::where('dni_empleado', $dni)
        ->where('estado', 'activo')
        ->orderByDesc('anio_laboral')
        ->get();

    $periodosVencidos = PeriodoVacacionesSistema::where('dni_empleado', $dni)
        ->where('estado', 'vencido')
        ->orderByDesc('anio_laboral')
        ->get();

    $movimientos = MovimientoPermisoSistema::where('dni_empleado', $dni)
        ->orderByDesc('created_at')
        ->get();

    $totalDiasDisponibles = 0;

    foreach ($periodosActivos as $periodo) {
        $totalDiasDisponibles += max(0, $periodo->dias_otorgados - $periodo->dias_usados);
    }

    // 🔥 IMPORTANTE: usar timezone correcto
    $fechaGeneracion = Carbon::now('America/Tegucigalpa')
        ->locale('es')
        ->translatedFormat('d \d\e F \d\e\l Y H:i');

    $pdf = Pdf::loadView('empleados.reporte', compact(
        'empleado',
        'periodosActivos',
        'periodosVencidos',
        'movimientos',
        'totalDiasDisponibles',
        'fechaGeneracion'
    ));

    $pdf->setPaper('a4', 'portrait');

    // 🔥 Renderizar primero para que calcule páginas
    $pdf->render();

    return $pdf->stream('reporte_empleado_'.$empleado->DNI.'.pdf', [
        'Attachment' => false // 👈 abre en nueva pestaña
    ]);
}





public function expediente($dni)
{
    $empleado = Empleado::with('documentos')
        ->where('DNI', $dni)
        ->firstOrFail();

    return view('empleados.expediente', compact('empleado'));
}



public function verRegistro($dni)
{
    $empleado = Empleado::with('documentos')
        ->where('DNI', $dni)
        ->firstOrFail();

    return view('empleados.verRegistro', compact('empleado'));
}


public function imprimirRegistro($dni)
{
    $empleado = Empleado::with('documentos')
        ->where('DNI', $dni)
        ->firstOrFail();

    return view('empleados.verRegistroImprimir', compact('empleado'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    

public function editarFuncion($dni)
{
    $empleado = Empleado::findOrFail($dni);

    $departamentos = DepartamentoMuni::where('activo',1)
        ->orderBy('codigo')
        ->get();

    return view('empleados.funcion', compact('empleado','departamentos'));
}

public function guardarFuncion(Request $request, $dni)
{
    $empleado = Empleado::findOrFail($dni);

    $empleado->departamento_funcional_id =
        $request->departamento_funcional_id;

    $empleado->save();

    return redirect()
        ->route('empleados.verRegistro',$dni)
        ->with('success','Asignación funcional actualizada');
}
}
