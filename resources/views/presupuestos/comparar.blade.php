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



                            </div>


                        </x-form>

                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4>Reporte de presupuesto</h4>
                        <x-form id="formeditar" action="{{ route('presupuestos-maestro.show', 1) }}" target="_blank"
                            method="GET" btntext="Descargar presupuesto en PDF">


                            <div class="row">

                                <div class="form-group  col-md-12 ">
                                    <label for="presupuesto">Seleccione un presupuesto para descargar un pdf con los
                                        detalles</label>
                                    <select name="invidualp" id="invidualp" class="form-control">
                                        <option value="">Seleccione....</option>
                                        @foreach ($allPresupuesto as $pre)
                                            <option value="{{ $pre->nombre }}">{{ $pre->nombre }}</option>
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
        $(document).ready(function() {
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
