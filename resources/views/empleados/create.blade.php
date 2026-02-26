@extends('layouts.master')

@section('title', 'Registrar Empleado')

@section('content')

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
<label>Código</label>
<input type="text"
       name="codigo"
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
       value="{{ old('DNI') }}"
       class="form-control @error('DNI') is-invalid @enderror">
@error('DNI')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-3 mb-3">
<label>RTN</label>
<input type="text"
       name="RTN"
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
<input type="text"
       name="primer_nombre"
       value="{{ old('primer_nombre') }}"
       class="form-control @error('primer_nombre') is-invalid @enderror">
@error('primer_nombre')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-3 mb-3">
<label>Segundo Nombre</label>
<input type="text"
       name="segundo_nombre"
       value="{{ old('segundo_nombre') }}"
       class="form-control @error('segundo_nombre') is-invalid @enderror">
@error('segundo_nombre')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-3 mb-3">
<label>Primer Apellido *</label>
<input type="text"
       name="primer_apellido"
       value="{{ old('primer_apellido') }}"
       class="form-control @error('primer_apellido') is-invalid @enderror">
@error('primer_apellido')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-3 mb-3">
<label>Segundo Apellido</label>
<input type="text"
       name="segundo_apellido"
       value="{{ old('segundo_apellido') }}"
       class="form-control @error('segundo_apellido') is-invalid @enderror">
@error('segundo_apellido')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

</div>

<div class="row">

<div class="col-md-2 mb-3">
<label>Día</label>
<input type="number"
       name="dia_nacimiento"
       value="{{ old('dia_nacimiento') }}"
       class="form-control @error('dia_nacimiento') is-invalid @enderror">
@error('dia_nacimiento')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-2 mb-3">
<label>Mes</label>
<input type="number"
       name="mes_nacimiento"
       value="{{ old('mes_nacimiento') }}"
       class="form-control @error('mes_nacimiento') is-invalid @enderror">
@error('mes_nacimiento')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-2 mb-3">
<label>Año</label>
<input type="number"
       name="anio_nacimiento"
       value="{{ old('anio_nacimiento') }}"
       class="form-control @error('anio_nacimiento') is-invalid @enderror">
@error('anio_nacimiento')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-3 mb-3">
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

<div class="col-md-3 mb-3">
<label>Nacionalidad</label>
<input type="text"
       name="nacionalidad"
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
</div>{{-- ============================= --}}
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
<textarea name="direccion_domicilio"
          class="form-control @error('direccion_domicilio') is-invalid @enderror">{{ old('direccion_domicilio') }}</textarea>
@error('direccion_domicilio')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="mb-3">
<label>Referencia Domicilio</label>
<textarea name="referencia_domicilio"
          class="form-control @error('referencia_domicilio') is-invalid @enderror">{{ old('referencia_domicilio') }}</textarea>
@error('referencia_domicilio')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="row">

<div class="col-md-4 mb-3">
<label>Teléfono Celular</label>
<input type="text"
       name="telefono_celular"
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
       placeholder="Nombre"
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
       value="{{ old('telefono_contacto1') }}"
       class="form-control @error('telefono_contacto1') is-invalid @enderror">
@error('telefono_contacto1')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-4">
<input type="text"
       name="parentezco_contacto1"
       placeholder="Parentezco"
       value="{{ old('parentezco_contacto1') }}"
       class="form-control @error('parentezco_contacto1') is-invalid @enderror">
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
       placeholder="Nombre"
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
       value="{{ old('telefono_contacto2') }}"
       class="form-control @error('telefono_contacto2') is-invalid @enderror">
@error('telefono_contacto2')
<div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
</div>

<div class="col-md-4">
<input type="text"
       name="parentezco_contacto2"
       placeholder="Parentezco"
       value="{{ old('parentezco_contacto2') }}"
       class="form-control @error('parentezco_contacto2') is-invalid @enderror">
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

                    <div class="col-md-4">
                        <label>Nombre</label>
                        <input type="text"
                               name="nombre_beneficiario{{ $i }}"
                               class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label>Porcentaje</label>
                        <input type="number"
                               name="porcentaje_beneficiario{{ $i }}"
                               class="form-control"
                               min="0"
                               max="100">
                    </div>

                    <div class="col-md-3">
                        <label>Parentezco</label>
                        <input type="text"
                               name="parentezco_beneficiario{{ $i }}"
                               class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label>DNI</label>
                        <input type="text"
                               name="DNI_beneficiario{{ $i }}"
                               class="form-control">
                    </div>

                </div>
            @endfor

        </div>
    </div>
</div>



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
                                <input type="text" name="puesto" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Fecha de Nombramiento</label>
                                <input type="date" name="fecha_nombramiento" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Tipo</label>
                                <select name="tipo" class="form-control">
                                    <option value="">Seleccione</option>
                                    <option value="Acuerdo">Acuerdo</option>
                                    <option value="Contrato">Contrato</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Salario Inicial</label>
                                <input type="number" step="0.01" name="salario_inicial" class="form-control">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

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
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Acuerdo / Contrato</label>
                                <input type="file" name="acuerdo" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Nota de Traslado (si es necesario)</label>
                                <input type="file" name="nota_traslado" class="form-control">
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