@extends('voyager::master')
@section('page_header')
    <h1 class="page-title">
        <i class="voyager-company"></i> Editar Presupuesto
    </h1>
@stop

@section('content')

    <div class="page-content edit-add container-fluid">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->

                    <x-form action="{{ route('presupuestos.update', $presupuesto->id) }}" method="POST" isPut="si">

                        <div class="form-group  col-md-3" style="display: none;">
                            <label class="control-label" for="codigo"></label>
                            <input required="" type="text" class="form-control" name="codigo" placeholder="00-000"
                                value="1-00">
                        </div>

                        <div class="form-group  col-md-9">
                            <label class="control-label" for="nombre">Presupuesto</label>
                            <input required="" type="text" class="form-control" name="nombre"
                                placeholder="Presupuesto" value="{{ $presupuesto->nombre }}">
                        </div>


                        <div class="form-group col-md-3">
                            <label class="control-label" for="clase">Clase</label>
                            <select class="form-control" name="clases_id" id="clase">
                                @foreach ($clases as $clase)
                                    <option @if ($presupuesto->clases_id == $clase->id) selected @endif value="{{ $clase->id }}">
                                        {{ $clase->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label" for="tipo">Tipo</label>
                            <select class="form-control" name="tipos_id" id="tipo">
                                @foreach ($tipos as $tipo)
                                    <option @if ($presupuesto->tipos_id == $tipo->id) selected @endif value="{{ $tipo->id }}">
                                        {{ $tipo->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group  col-md-2">
                            <label class="control-label" for="year">Año</label>
                            <input required="" type="number" min="0" class="form-control" name="year"
                                id="year" placeholder="0000"
                                value="{{ date('Y', strtotime($presupuesto->fecha_inicio)) }}">
                            <small id="yearHelp" class="form-text text-muted">
                            </small>
                        </div>

                        <div class="form-group  col-md-2">
                            <label class="control-label" for="desde">Desde</label>
                            <input required="" type="text" class="form-control" name="fecha_inicio" id="desde"
                                placeholder="00/00/00000" readonly
                                value="{{ date('d-m-Y', strtotime($presupuesto->fecha_inicio)) }}">
                        </div>
                        <div class="form-group  col-md-2">
                            <label class="control-label" for="hasta">Hasta</label>
                            <input required="" type="text" class="form-control" name="fecha_fin" id="hasta"
                                placeholder="00/00/0000" readonly
                                value="{{ date('d-m-Y', strtotime($presupuesto->fecha_fin)) }}">
                        </div>

                        <div class="form-group  col-md-12">
                            <label class="control-label" for="supuesto">Supuestos</label>
                            <textarea name="supuestos" class="form-control">{!! $presupuesto->supuestos !!}</textarea>
                            <small id="emailHelp" class="form-text text-muted">Escriba los supuestos que deban ser tomados
                                en cuenta en caso de que no existan ninguno omitir este campo.</small>
                        </div>
                        <div class="form-group  col-md-12">
                            <label class="control-label" for="nota">Notas</label>
                            <textarea name="notas" class="form-control">{!! $presupuesto->notas !!}</textarea>
                            <small id="emailHelp" class="form-text text-muted">
                                Escriba notas en caso de ser necesario y que sean de interes dentro del presupuesto.
                            </small>
                        </div>



                    </x-form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        var yearInput = document.getElementById("year");
        var desde = document.getElementById("desde");
        var hasta = document.getElementById("hasta");
        var yearHelp = document.getElementById("yearHelp");

        // Agregar un evento input al campo de texto
        yearInput.addEventListener("input", function() {
            // Llamar a la función para mostrar las fechas
            mostrarFechas();
        });

        function mostrarFechas() {
            // Obtener el valor del año del campo de texto
            var year = yearInput.value;

            // Verificar si el valor ingresado es un año válido
            if (!year || isNaN(year) || year.length !== 4) {
                yearHelp.classList.add("text-danger")
                yearHelp.innerHTML = "Por favor, ingrese un año válido (formato: YYYY).";
                desde.value = "";
                hasta.value = "";
                return;
            }
            yearHelp.innerHTML = ""
            // Crear fechas para el primer y último día del año
            var primerDia = new Date(year, 0, 1); // 0 representa enero
            var ultimoDia = new Date(year, 11, 31); // 11 representa diciembre

            // Formatear las fechas
            var primerDiaFormateado = primerDia.getDate() + "-" + (primerDia.getMonth() + 1) + "-" + primerDia
                .getFullYear();
            var ultimoDiaFormateado = ultimoDia.getDate() + "-" + (ultimoDia.getMonth() + 1) + "-" + ultimoDia
                .getFullYear();
            // Mostrar las fechas en el resultado
            desde.value = primerDiaFormateado;
            hasta.value = ultimoDiaFormateado;

        }
    </script>
@stop
