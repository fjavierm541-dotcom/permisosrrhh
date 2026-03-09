@extends('layouts.master')

@section('title', 'Registrar Empleado')

@section('content')


@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Hay errores en el formulario:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="glass-card p-4">

    <h4 class="mb-4 fw-bold">Crear Empleado</h4>

   

    <form method="POST" action="{{ route('empleados.store') }}" enctype="multipart/form-data">
        @csrf
        

       <div class="accordion" id="empleadoAccordion">

{{-- ============================= --}}
{{-- 1️⃣ DATOS GENERALES --}}
{{-- ============================= --}}
<div class="accordion-item">
<h2 class="accordion-header">
<button class="accordion-button fw-bold"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#datosGenerales">
    Datos Generales
</button>
</h2>

<div id="datosGenerales"
     class="accordion-collapse collapse show"
     data-bs-parent="#empleadoAccordion">

<div class="accordion-body">

<div class="row">

<div class="col-md-3 mb-3">
<label>Código *</label>
<input type="text"
       name="codigo"
       minlength="1"
       maxlength="4"
       inputmode="numeric"
       pattern="[0-9]{1,4}"
       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
       placeholder="00000"
       required
       value="{{ old('codigo') }}"
       class="form-control @error('codigo') is-invalid @enderror">
@error('codigo')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-3 mb-3">
<label>DNI *</label>
<input type="text"
       name="DNI"
       id="dni"
       maxlength="15"
       pattern="^\d{4}-\d{4}-\d{5}$"
       inputmode="numeric"
       placeholder="0000-0000-00000"
       required
       value="{{ old('DNI') }}"
       class="form-control @error('DNI') is-invalid @enderror">
@error('DNI')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-3 mb-3">
<label>RTN *</label>
<input type="text"
       name="RTN"
       id="rtn"
       maxlength="16"
       pattern="^\d{4}-\d{4}-\d{6}$"
       inputmode="numeric"
       placeholder="0000-00000-00000"
       required
       value="{{ old('RTN') }}"
       class="form-control @error('RTN') is-invalid @enderror">
@error('RTN')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-3 mb-3">
<label>Sexo *</label>
<select name="sexo"
        class="form-control @error('sexo') is-invalid @enderror">
    <option value="">Seleccione</option>
    <option value="Masculino" {{ old('sexo') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
    <option value="Femenino" {{ old('sexo') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
</select>
@error('sexo')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

</div>

<div class="row">

<div class="col-md-3 mb-3">
<label>Primer Nombre *</label>
<input type="text" minlength="3" maxlength="20" required 
       name="primer_nombre"
        placeholder="Ingrese el primer nombre"
       value="{{ old('primer_nombre') }}"
       class="form-control @error('primer_nombre') is-invalid @enderror">
@error('primer_nombre')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-3 mb-3">
<label>Segundo Nombre</label>
<input type="text" minlength="3" maxlength="20"  
       name="segundo_nombre"
        placeholder="Ingrese el segundo nombre"
       value="{{ old('segundo_nombre') }}"
       class="form-control @error('segundo_nombre') is-invalid @enderror">
@error('segundo_nombre')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-3 mb-3">
<label>Primer Apellido *</label>
<input type="text" minlength="3" maxlength="20" required 
       name="primer_apellido"
       value="{{ old('primer_apellido') }}"
       placeholder="Ingrese el primer apellido"
       class="form-control @error('primer_apellido') is-invalid @enderror">
@error('primer_apellido')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-3 mb-3">
<label>Segundo Apellido</label>
<input type="text" minlength="3" maxlength="20"  
       name="segundo_apellido"
        placeholder="Ingrese el segundo apellido"
       value="{{ old('segundo_apellido') }}"
       class="form-control @error('segundo_apellido') is-invalid @enderror">
@error('segundo_apellido')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

</div>

<div class="row">

<div class="col-md-4 mb-3">
         <label>Día de nacimiento *</label>
        <select name="dia_nacimiento"
                required
                class="form-control @error('dia_nacimiento') is-invalid @enderror">
            <option value="">Seleccione</option>
            @for ($d = 1; $d <= 31; $d++)
                <option value="{{ $d }}"
                    {{ old('dia_nacimiento') == $d ? 'selected' : '' }}>
                    {{ $d }}
                </option>
            @endfor
        </select>
        @error('dia_nacimiento')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Mes --}}
    <div class="col-md-4 mb-3">
         <label>Mes de nacimiento *</label>
        <select name="mes_nacimiento"
                required
                class="form-control @error('mes_nacimiento') is-invalid @enderror">
            <option value="">Seleccione</option>
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}"
                    {{ old('mes_nacimiento') == $m ? 'selected' : '' }}>
                    {{ $m }}
                </option>
            @endfor
        </select>
        @error('mes_nacimiento')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

      @php
        $anioActual = date('Y');
        $anioMaximo = $anioActual - 18; // mínimo 18 años
    @endphp
    {{-- Año --}}
    <div class="col-md-4 mb-3">
        <label>Año de nacimiento *</label>
        <select name="anio_nacimiento"
                required
                class="form-control @error('anio_nacimiento') is-invalid @enderror">
            <option value="">Seleccione</option>

            @for ($a = $anioMaximo; $a >= 1940; $a--)
                <option value="{{ $a }}"
                    {{ old('anio_nacimiento') == $a ? 'selected' : '' }}>
                    {{ $a }}
                </option>
            @endfor

        </select>
        @error('anio_nacimiento')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    



<div class="col-md-4 mb-3">
<label>Estado Civil</label>
<select name="estado_civil"
        class="form-control @error('estado_civil') is-invalid @enderror">
    <option value="">Seleccione</option>
    <option value="Soltero(a)" {{ old('estado_civil') == 'Soltero(a)' ? 'selected' : '' }}>Soltero(a)</option>
    <option value="Casado(a)" {{ old('estado_civil') == 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
    <option value="Unión Libre" {{ old('estado_civil') == 'Unión Libre' ? 'selected' : '' }}>Unión Libre</option>
    <option value="Divorciado(a)" {{ old('estado_civil') == 'Divorciado(a)' ? 'selected' : '' }}>Divorciado(a)</option>
    <option value="Viudo(a)" {{ old('estado_civil') == 'Viudo(a)' ? 'selected' : '' }}>Viudo(a)</option>
</select>
@error('estado_civil')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-4 mb-3">
<label>Nacionalidad</label>
<input type="text" minlength="3" maxlength="20" required 
       name="nacionalidad"
        placeholder="Ingrese la nacionalidad del empleado"
       value="{{ old('nacionalidad') }}"
       class="form-control @error('nacionalidad') is-invalid @enderror">
@error('nacionalidad')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

</div>

<div class="row">
<div class="col-md-4 mb-3">
<label>Tipo de Sangre</label>
<select name="tipo_sangre"
        class="form-control @error('tipo_sangre') is-invalid @enderror">
    <option value="">Seleccione</option>
    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $tipo)
        <option value="{{ $tipo }}" {{ old('tipo_sangre') == $tipo ? 'selected' : '' }}>
            {{ $tipo }}
        </option>
    @endforeach
</select>
@error('tipo_sangre')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>
</div>

</div>
</div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const fechaInput = document.getElementById('fecha_nacimiento');

    fechaInput.addEventListener('change', function() {
        let fecha = this.value; // YYYY-MM-DD
        
        if (fecha) {
            let partes = fecha.split('-');
            
            document.getElementById('anio_nacimiento').value = partes[0];
            document.getElementById('mes_nacimiento').value = partes[1];
            document.getElementById('dia_nacimiento').value = partes[2];
        }
    });

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function aplicarMascara(input, ultimoBloque) {
        input.addEventListener('input', function () {

            let value = this.value.replace(/[^0-9]/g, '');

            if (value.length > 4) {
                value = value.slice(0,4) + '-' + value.slice(4);
            }

            if (value.length > 9) {
                value = value.slice(0,9) + '-' + value.slice(9, 9 + ultimoBloque);
            }

            this.value = value;
        });
    }

    aplicarMascara(document.getElementById('dni'), 5);
    aplicarMascara(document.getElementById('rtn'), 6);

});
</script>
















{{-- ============================= --}}
{{-- 2️⃣ DIRECCIÓN Y CONTACTO --}}
{{-- ============================= --}}
<div class="accordion-item">
<h2 class="accordion-header">
<button class="accordion-button fw-bold collapsed"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#contacto">
    Información de Contacto
</button>
</h2>

<div id="contacto"
     class="accordion-collapse collapse"
     data-bs-parent="#empleadoAccordion">

<div class="accordion-body">

<div class="mb-3">
<label>Dirección</label>
<textarea name="direccion_domicilio"  minlength="3" maxlength="200" 
placeholder="Ingrese la dirección del empleado" required 
          class="form-control @error('direccion_domicilio') is-invalid @enderror">{{ old('direccion_domicilio') }}</textarea>
@error('direccion_domicilio')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="mb-3">
<label>Referencia Domicilio</label>
<textarea name="referencia_domicilio"  minlength="3" maxlength="150" placeholder="Ingrese una referencia de domicilio del empleado" required 
          class="form-control @error('referencia_domicilio') is-invalid @enderror">{{ old('referencia_domicilio') }}</textarea>
@error('referencia_domicilio')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="row">

<div class="col-md-4 mb-3">
<label>Teléfono Celular</label>
<input type="text" required 
    placeholder="Ingrese el número de teléfono celular"
       name="telefono_celular"
       maxlength="8"
       inputmode="numeric"
       pattern="[0-9]{8}"
       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
       required
       value="{{ old('telefono_celular') }}"
       class="form-control @error('telefono_celular') is-invalid @enderror">
@error('telefono_celular')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-4 mb-3">
<label>Teléfono Fijo</label>
<input type="text"
       name="telefono_fijo"
       placeholder="Ingrese el número de teléfono fijo"
       maxlength="8"
       inputmode="numeric"
       pattern="[0-9]{8}"
       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
       value="{{ old('telefono_fijo') }}"
       class="form-control @error('telefono_fijo') is-invalid @enderror">
@error('telefono_fijo')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-4 mb-3">
<label>Nivel Educativo</label>
<select name="nivel_educativo"
        class="form-control @error('nivel_educativo') is-invalid @enderror">
    <option value="">Seleccione</option>
    <option value="Nivel Primario" {{ old('nivel_educativo') == 'Nivel Primario' ? 'selected' : '' }}>Nivel Primario</option>
    <option value="Nivel Secundario" {{ old('nivel_educativo') == 'Nivel Secundario' ? 'selected' : '' }}>Nivel Secundario</option>
    <option value="Nivel Superior" {{ old('nivel_educativo') == 'Nivel Superior' ? 'selected' : '' }}>Nivel Superior</option>
    <option value="Postgrado" {{ old('nivel_educativo') == 'Postgrado' ? 'selected' : '' }}>Postgrado</option>
</select>
@error('nivel_educativo')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

</div>

</div>
</div>
</div>






{{-- ============================= --}}
{{-- 3️⃣ CONTACTOS DE EMERGENCIA --}}
{{-- ============================= --}}
<div class="accordion-item">
<h2 class="accordion-header">
<button class="accordion-button fw-bold collapsed"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#emergencia">
    Contactos de Emergencia
</button>
</h2>

<div id="emergencia"
     class="accordion-collapse collapse"
     data-bs-parent="#empleadoAccordion">

<div class="accordion-body">

<p class="text-muted fw-bold">
En caso de emergencia se autoriza llamar a las personas en el siguiente orden:
</p>

<h6>Contacto 1</h6>
<div class="row mb-3">

<div class="col-md-4">
<input type="text"
       name="nombre_contacto1"
       placeholder="Nombre del primer contacto de emergencia"
       minlength="3"
       maxlength="20"
       required
       value="{{ old('nombre_contacto1') }}"
       class="form-control @error('nombre_contacto1') is-invalid @enderror">
@error('nombre_contacto1')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-4">
<input type="text"
       name="telefono_contacto1"
       placeholder="Teléfono"
       maxlength="8"
       pattern="[0-9]{8}"
       inputmode="numeric"
       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
       required
       value="{{ old('telefono_contacto1') }}"
       class="form-control @error('telefono_contacto1') is-invalid @enderror">
@error('telefono_contacto1')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-4">
<select name="parentezco_contacto1"
        required
        class="form-control @error('parentezco_contacto1') is-invalid @enderror">
    <option value="">Seleccione Parentesco</option>
    @foreach([
        'Padre',
        'Madre',
        'Hermano(a)',
        'Abuelo(a)',
        'Tío(a)',
        'Primo(a)',
        'Esposo(a)',
        'Pareja',
        'Hijo(a)',
        'Amigo(a)',
        'Vecino(a)',
        'Otro'
    ] as $parentesco)
        <option value="{{ $parentesco }}"
            {{ old('parentezco_contacto1') == $parentesco ? 'selected' : '' }}>
            {{ $parentesco }}
        </option>
    @endforeach
</select>
@error('parentezco_contacto1')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

</div>

<h6>Contacto 2</h6>
<div class="row">

<div class="col-md-4">
<input type="text"
       name="nombre_contacto2"
       placeholder="Nombre del segundo contacto de emergencia"
       minlength="3"
       maxlength="20"
       value="{{ old('nombre_contacto2') }}"
       class="form-control @error('nombre_contacto2') is-invalid @enderror">
@error('nombre_contacto2')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-4">
<input type="text"
       name="telefono_contacto2"
       placeholder="Teléfono"
       maxlength="8"
       pattern="[0-9]{8}"
       inputmode="numeric"
       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
       value="{{ old('telefono_contacto2') }}"
       class="form-control @error('telefono_contacto2') is-invalid @enderror">
@error('telefono_contacto2')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-4">
<select name="parentezco_contacto2"
        class="form-control @error('parentezco_contacto2') is-invalid @enderror">
    <option value="">Seleccione Parentesco</option>
    @foreach([
        'Padre',
        'Madre',
        'Hermano(a)',
        'Abuelo(a)',
        'Tío(a)',
        'Primo(a)',
        'Esposo(a)',
        'Pareja',
        'Hijo(a)',
        'Amigo(a)',
        'Vecino(a)',
        'Otro'
    ] as $parentesco)
        <option value="{{ $parentesco }}"
            {{ old('parentezco_contacto2') == $parentesco ? 'selected' : '' }}>
            {{ $parentesco }}
        </option>
    @endforeach
</select>
@error('parentezco_contacto2')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

</div>

</div>
</div>
</div>











{{-- ============================= --}}
{{-- 4️⃣ BENEFICIARIOS (OPCIONAL) --}}
{{-- ============================= --}}
<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button fw-bold collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#beneficiarios">
            Beneficiarios en caso de muerte
        </button>
    </h2>

    <div id="beneficiarios"
         class="accordion-collapse collapse"
         data-bs-parent="#empleadoAccordion">

        <div class="accordion-body">

            <p class="text-muted fw-bold">
                Complete únicamente si el empleado desea registrar beneficiarios.
            </p>

           @for ($i = 1; $i <= 7; $i++)
<hr>
<h6 class="fw-bold">Beneficiario {{ $i }}</h6>

<div class="row mb-3">

    {{-- Nombre --}}
    <div class="col-md-4">
        <label>Nombre</label>
        <input type="text"
                placeholder="Ingrese el nombre del beneficiario"
               name="nombre_beneficiario{{ $i }}"
               minlength="3"
               maxlength="20"
               value="{{ old('nombre_beneficiario'.$i) }}"
               class="form-control @error('nombre_beneficiario'.$i) is-invalid @enderror">
        @error('nombre_beneficiario'.$i)
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Porcentaje --}}
    <div class="col-md-2">
        <label>Porcentaje</label>
        <input type="text"
               name="porcentaje_beneficiario{{ $i }}"
               placeholder="00"
               maxlength="3" 
               pattern="^(100|[0-9]{1,2})$"
               inputmode="numeric"
                oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (parseInt(this.value || 0) > 100) this.value = '100';"
               value="{{ old('porcentaje_beneficiario'.$i) }}"
               class="form-control @error('porcentaje_beneficiario'.$i) is-invalid @enderror">
        @error('porcentaje_beneficiario'.$i)
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Parentezco --}}
    <div class="col-md-3">
        <label>Parentezco</label>
        <input type="text"
               name="parentezco_beneficiario{{ $i }}"
               placeholder="escriba el parentezco"
               minlength="3"
               maxlength="20"
               value="{{ old('parentezco_beneficiario'.$i) }}"
               class="form-control @error('parentezco_beneficiario'.$i) is-invalid @enderror">
        @error('parentezco_beneficiario'.$i)
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- DNI --}}
    <div class="col-md-3">
        <label>DNI</label>
        <input type="text"
               name="DNI_beneficiario{{ $i }}"
               id="dni_beneficiario{{ $i }}"
               maxlength="15"
               pattern="^\d{4}-\d{4}-\d{5}$"
               inputmode="numeric"
               placeholder="0000-0000-00000"
               value="{{ old('DNI_beneficiario'.$i) }}"
               class="form-control @error('DNI_beneficiario'.$i) is-invalid @enderror">
        @error('DNI_beneficiario'.$i)
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

</div>
@endfor
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    function aplicarMascaraDNI(input) {
        input.addEventListener('input', function () {

            let value = this.value.replace(/[^0-9]/g, '');

            if (value.length > 4) {
                value = value.slice(0,4) + '-' + value.slice(4);
            }

            if (value.length > 9) {
                value = value.slice(0,9) + '-' + value.slice(9,14);
            }

            this.value = value;
        });
    }

    for (let i = 1; i <= 7; i++) {
        let input = document.getElementById('dni_beneficiario' + i);
        if (input) {
            aplicarMascaraDNI(input);
        }
    }

});
</script>


{{-- PORCENTAJES --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const fechaInput = document.getElementById('fecha_nacimiento');

    if (!fechaInput) {
        return;
    }

    fechaInput.addEventListener('change', function() {
        let fecha = this.value; // YYYY-MM-DD
        
        if (fecha) {
            let partes = fecha.split('-');
            
            document.getElementById('anio_nacimiento').value = partes[0];
            document.getElementById('mes_nacimiento').value = partes[1];
            document.getElementById('dia_nacimiento').value = partes[2];
        }
    });

});
</script>








            {{-- ============================= --}}
            {{-- 4️⃣ INFORMACIÓN LABORAL --}}
            {{-- ============================= --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#laboral">
                        Información Laboral
                    </button>
                </h2>

                <div id="laboral"
                     class="accordion-collapse collapse"
                     data-bs-parent="#empleadoAccordion">

                    <div class="accordion-body">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Puesto de Nombramiento</label>
                                <input type="text" name="puesto" class="form-control"
                                placeholder="Ingrese el puesto de nombramiento" minlength="3" maxlength="20"
                                value="{{ old('puesto') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Fecha de Nombramiento</label>
                                <input type="date" name="fecha_nombramiento" class="form-control">
                                @error('fecha_nombramiento')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                             @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Tipo</label>
                                <select name="tipo" class="form-control">
                                    <option value="">Seleccione</option>
                                    <option value="Acuerdo" {{ old('Acuerdo') == 'Acuerdo' ? 'selected' : '' }}>Acuerdo</option>
                                    <option value="Contrato" {{ old('Contrato') == 'Contrato' ? 'selected' : '' }}>Contrato</option>
                                </select>
                                @error('tipo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                             @enderror
                            </div>

                            <div class="col-md-4 mb-3">
    <label>Salario Inicial</label>
    <input type="text"
           name="salario_inicial"
           id="salario_inicial"
           inputmode="decimal"
           placeholder="L. 0.00"
           value="{{ old('salario_inicial') }}"
           class="form-control @error('salario_inicial') is-invalid @enderror">
</div>

                    </div>
                </div>
            </div>


           <script>
document.addEventListener('DOMContentLoaded', function () {

    const salarioInput = document.getElementById('salario_inicial');

    // Limpiar mientras escribe (solo números y punto)
    salarioInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^\d.]/g, '');
    });

    // Formatear cuando sale del campo
    salarioInput.addEventListener('blur', function () {

        if (this.value === '') return;

        let number = parseFloat(this.value);

        if (!isNaN(number)) {
            this.value = 'L. ' + number.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    });

});
</script>








            {{-- ============================= --}}
            {{-- 5️⃣ DOCUMENTACIÓN --}}
            {{-- ============================= --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#documentos">
                        Documentación
                    </button>
                </h2>

                <div id="documentos"
                     class="accordion-collapse collapse"
                     data-bs-parent="#empleadoAccordion">

                    <div class="accordion-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label>Copia DNI</label>
                                <input type="file" name="copia_dni" class="form-control">
                                @error('copia_dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                             @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Acuerdo / Contrato</label>
                                <input type="file" name="acuerdo" class="form-control">
                                @error('acuerdo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                             @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Nota de Traslado (si es necesario)</label>
                                <input type="file" name="nota_traslado" class="form-control">
                                @error('nota_traslado')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                             @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Copìa RTN</label>
                                <input type="file" name="copia_rtn" class="form-control">
                                @error('copia_rtn')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                             @enderror
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary-custom">
                Guardar Empleado
            </button>
        </div>

    </form>

</div>

@endsection