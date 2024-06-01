@extends('voyager::master')
@section('page_header')
    <h4 class=" page-title"> <i class="voyager-news"></i>Centro de costos del Presupuesto "{{ $presupuesto->nombre }} -
        {{ date('Y', strtotime($presupuesto->fecha_fin)) }}" </h4>
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
        <x-modal title="Nueva Partida">
            <x-form action="{{ route('partida.store') }}">
                <input type="hidden" name="presupuesto_id" value="{{ $uuid }}">
                <div class="form-group  col-md-12">
                    <label class="control-label" for="nombre">Nombre de la partida</label>
                    <input required="" type="text" class="form-control" name="nombre"
                        placeholder="Escriba el nombre de la partida" value="">
                </div>
                <div class="form-group  col-md-12">
                    <label class="control-label" for="supuesto">Descripción</label>
                    <textarea name="descripcion" class="form-control"></textarea>
                </div>
            </x-form>
        </x-modal>

        <div class="row">
            <div class="col-md-6">
                <a type="button" class="btn btn-primary" href="{{ route('presupuestos.index') }}">
                    <i class="voyager-angle-left"></i> Volver
                </a>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Crear partida
                </button>
            </div>


            <div class="col-md-12">

                <div class="panel panel-bordered table-responsive">
                    <!-- form start -->
                    <x-table>
                        @slot('header')
                            <th>Partida</th>
                            <th>Creado por</th>
                            <th>Fecha de creación</th>
                            <th>Total</th>
                            <th>Opciones</th>
                        @endslot
                        @php
                            $totalGlobal = 0;
                        @endphp
                        @slot('body')
                            @if (count($partidas) > 0)
                                @foreach ($partidas as $partida)
                                    <tr>
                                        <td>{{ $partida->nombre }}</td>
                                        <td>{{ $partida->user->name }}</td>
                                        <td>{{ date('d/m/Y h:i:s a', strtotime($partida->created_at)) }}</td>
                                        <td>
                                            @php
                                                $totalGeneral = 0;
                                            @endphp
                                            @foreach ($partida->partida_detalle as $item)
                                                @foreach ($item->precios as $pre)
                                                    @php
                                                        $totalGeneral += $pre->monto;
                                                    @endphp
                                                @endforeach
                                            @endforeach
                                            @if ($partida->nombre == 'Volumen de ventas')
                                                {{ $totalGeneral }}
                                            @else
                                                ${{ number_format($totalGeneral, 2) }}
                                            @endif
                                            @php
                                                $totalGlobal += $totalGeneral;
                                            @endphp

                                        </td>
                                        <td>
                                            <x-link
                                                href="{{ route('partida-detalles.create', ['uuid' => $partida, 'presupuesto' => $uuid]) }}">
                                                <i class="fa-solid fa-plus"></i>
                                                Nueva Cuenta contable
                                            </x-link>
                                            <x-link
                                                href="{{ route('partida-detalles.show', ['partida_detalle' => $partida, 'presupuesto' => $uuid]) }}">
                                                <i class="fa-solid fa-eye"></i>
                                                Ver Detalles
                                            </x-link>
                                            <x-link
                                                href="{{ route('partida.edit', ['partida' => $partida, 'presupuesto' => $uuid]) }}">
                                                <i class="fa-solid fa-pencil"></i>
                                                Editar Cuenta contable
                                            </x-link>
                                            <x-button class="btn-danger" type="button" id="btnBorrar"
                                                value="{{ $partida->id }}">
                                                <i class="fa-solid fa-trash"></i>
                                                Borrar cuenta contable
                                            </x-button>
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
                        @if (count($partidas) > 0)
                            @slot('tfoot')
                                <tr>
                                    <td class="text-right" colspan="3"><strong>TOTAL</strong></td>
                                    <td><strong>{{ number_format($totalGlobal, 2) }}</strong></td>
                                    <td></td>
                                </tr>
                            @endslot
                        @endif
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
