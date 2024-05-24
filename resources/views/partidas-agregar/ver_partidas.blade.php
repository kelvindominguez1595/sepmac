@extends('voyager::master')
@section('page_header')
    <h4 class=" page-title"> <i class="voyager-news"></i>Presupuesto del año {{ $year }}</h4>
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
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($presupuestos as $item)
                                <div class="col-md-3">
                                    <a href="{{ route('ingresar-partidas.edit', ['ingresar_partida' => $item]) }}">
                                        <div class="card border border-secondary"
                                            style="border-radius:15px; border: 1px solid #337ab7;">
                                            <div class="card-body">
                                                <h3 class="text-center">
                                                    {{ $item->nombre }}

                                                </h3>
                                                <hr>

                                                @php
                                                    $totalGeneral = 0;
                                                @endphp
                                                @foreach ($item->partidas as $parti)
                                                    @php
                                                        $totalPorPartida = 0;
                                                    @endphp
                                                    @foreach ($parti->partida_detalle as $pd)
                                                        @foreach ($pd->precios as $pre)
                                                            @php
                                                                $totalPorPartida += $pre->monto;
                                                            @endphp
                                                        @endforeach
                                                    @endforeach
                                                    @php
                                                        $totalGeneral += $totalPorPartida;
                                                    @endphp
                                                @endforeach


                                                <h4>
                                                    Total Presupuesto: ${{ number_format($totalGeneral, 2) }}
                                                </h4>
                                                <span>Cantidad de partidas: {{ $item->partidas->count() }}</span><br>
                                                <span>Fecha de creación:
                                                    {{ date('d-m-Y h:i:s a', strtotime($item->created_at)) }}</span> <br>
                                                Descripcion: {{ $item->descripcion }}
                                                <h3>Estado: {{ isset($item->estado) ? $item->estado : 'En Proceso' }}</h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

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
