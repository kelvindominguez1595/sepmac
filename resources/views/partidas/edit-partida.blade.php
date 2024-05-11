@extends('voyager::master')
@section('page_header')
    <h4 class=" page-title"> <i class="voyager-news"></i>Ver detalles </h4>
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
            <div class="col-md-6">
                <a type="button" class="btn btn-primary" href="{{ route('partida.create', ['uuid' => $presupuesto]) }}">
                    <i class="voyager-angle-left"></i> Volver
                </a>
            </div>

            <div class="col-md-12">

                <div class="panel panel-bordered table-responsive">
                    <!-- form start -->

                    <x-table>
                        @slot('header')
                            <th>Detalle</th>
                            <th>Creado por</th>
                            <th>Fecha de creación</th>
                            <th>Total</th>
                            <th>Opciones</th>
                        @endslot
                        @php
                            $totalGlobal = 0;
                        @endphp

                        @slot('body')
                            @if ($partida_detalle->count() > 0)
                                @foreach ($partida_detalle as $det)
                                    <tr>
                                        <td>{{ $det->detalle }}</td>
                                        <td>
                                            @if ($det->usercreado)
                                                {{ $det->usercreado->name }}
                                            @endif
                                        </td>
                                        <td>{{ date('d/m/Y h:is a', strtotime($det->created_at)) }}</td>
                                        <td>
                                            @php
                                                $total = 0;
                                            @endphp
                                            @foreach ($det->precios as $precio)
                                                @php
                                                    $total += $precio->monto;
                                                @endphp
                                            @endforeach
                                            $ {{ number_format($total, 2) }}
                                        </td>
                                        <td>
                                            <x-link
                                                href="{{ route('partida-detalles.edit', ['partida_detalle' => $det->id, 'presupuesto' => $presupuesto, 'partida' => $det->partida_id]) }}">
                                                <i class="fas fa-pencil"></i>
                                                Editar</x-link>
                                            <x-button type="button" class="btn-danger" id="btnBorrar"
                                                value="{{ $det->id }}"> <i class="fas fa-trash"></i>
                                                Borrar</x-button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <h5>No hay datos</h5>
                                    </td>
                                </tr>
                            @endif
                        @endslot

                    </x-table>

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
                                url: "/admin/partida-detalles/" + id,
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
