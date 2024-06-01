@extends('voyager::master')
@section('page_header')
    <h4 class=" page-title"> <i class="voyager-news"></i>Presupuesto maestro</h4>
@stop
@section('css')
    <style>
        .btn.btn-primary {
            text-decoration: none;
        }

        .btn.btn-info {
            text-decoration: none;
        }

        .btn.btn-danger {
            text-decoration: none;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"
        integrity="sha512-u3fPA7V8qQmhBPNT5quvaXVa1mnnLSXUep5PS1qo5NRzHwG19aHmNJnj1Q8hpA/nBWZtZD4r4AX6YOt5ynLN2g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4>Exportar presupuesto maestro en excel</h4>
                        <x-form id="formprintpresupuestomaestro" action="{{ route('presupuestos-maestro.show', 0) }}"
                            method="Get" btntext="Generar consulta" target="_blank">


                            <div class="row">

                                <div class="form-group  col-md-12 ">
                                    <label for="presupuesto">Seleccione un presupuesto</label>
                                    <select name="presupuesto" id="presupuesto" class="form-control">
                                        <option value="">Seleccione....</option>
                                        @foreach ($presupuestos as $item)
                                            <option value="{{ $item->year }}">Presupuesto {{ $item->year }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                {{--
                                <div class="form-group  col-md-12 ">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="exportar_excel" value="si"
                                            id="exportar_excel">
                                        <label class="form-check-label" for="exportar_excel">
                                            ¿Decea exportarlo en Excel? <i class="text-success fa-solid fa-file-excel"></i>
                                        </label>
                                    </div>
                                </div> --}}

                            </div>                            

                        </x-form>
                        {{-- <div class="col-md-12">
                            <span><strong>Nota:</strong> Si necesita saber a detalle el presupuesto maestro por
                                favor marque la casilla donde dice <strong>¿Decea exportarlo en Excel?</strong>
                                para que pueda obtener el presupuesto completo, caso contrario solo obtendra el
                                resultado general del presupuesto maestro</span>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4>Aqui puedes comparar presupuesto maestros</h4>
                        {{-- <x-form id="formeditar" action="{{ route('ingresar-partidas.show', 0) }}" method="GET"
                            btntext="Ir al presupuesto">


                            <div class="row">
                                <div class="col-md-12">
                                    Hola aqui puede consultar tu presupuesto maestro
                                </div>
                                <div class="form-group  col-md-12 ">
                                    <label for="presupuesto">Seleccione un presupuesto todos los que se listan aqui es
                                        porque aun no han sido aprobado o tienen una observación</label>
                                    <select name="presupuesto" id="presupuesto" class="form-control">
                                        <option value="">Seleccione....</option>
                                        @foreach ($presupuestos as $item)
                                            <option value="{{ $item->year }}">Presupuesto {{ $item->year }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>


                        </x-form> --}}
                        <div class="row">
                            <div class="col-md-12">
                                <span><strong>Nota:</strong> Debe de seleccionar un presupuesto ya que todos los que se
                                    listan en esta sección son aquellos que aun no
                                    estan completos y si tiene algun tipo de observaciones.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4>Ver comparativa de presupuestos</h4>

                        <x-form id="formprintpresupuestocomparativa" action=""
                            method="Get" btntext="Ver comparativa" target="_blank">

                            <div class="row">
                                <div class="form-group  col-md-12 ">
                                    <label for="presupuesto1">Seleccione el primer presupuesto a comparar</label>
                                    <select name="presupuesto1" id="presupuesto1" class="form-control">
                                        <option value="">Seleccione....</option>
                                        @foreach ($presupuestos as $item)
                                            <option value="{{ $item->year }}">Presupuesto {{ $item->year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group  col-md-12 ">
                                    <label for="presupuesto2">Seleccione el segundo presupuesto a comparar</label>
                                    <select name="presupuesto2" id="presupuesto2" class="form-control">
                                        <option value="">Seleccione....</option>
                                        @foreach ($presupuestos as $item)
                                            <option value="{{ $item->year }}">Presupuesto {{ $item->year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                            

                        </x-form>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });
            $(document).on("click", "#btnBorrar", function() {
                let id = $(this).val();

                Swal.fire({
                    title: "¿Está segur@?",
                    text: "¡No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "¡Sí, bórralo!",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                                url: "/admin/partida/" + id,
                                method: "DELETE",
                                dataType: "JSON",
                            })
                            .done(function(done) {
                                Swal.fire({
                                    title: "¡Borrado!",
                                    text: "Registro ha sido borrado.",
                                    icon: "success",
                                    confirmButtonText: "Aceptar",
                                }).then((result) => {
                                    let {
                                        isConfirmed,
                                        isDismissed,
                                        isDenied
                                    } = result;
                                    if (isConfirmed || isDismissed) {
                                        location.reload();
                                    }
                                });
                            })
                            .fail(function(fail) {
                                Swal.fire({
                                    title: "Oops!",
                                    text: "Ah ocurrido un error inesperado :c",
                                    icon: "error",
                                    confirmButtonText: "Aceptar",
                                });
                            });
                    }
                });
            });

        });
    </script>
@stop
