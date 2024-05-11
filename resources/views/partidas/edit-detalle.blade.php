@extends('voyager::master')
@section('page_header')
    <h4 class=" page-title"> <i class="voyager-news"></i>Editar detalle </h4>
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

        .fixed-width-table {
            table-layout: fixed;
            width: 100%;
        }


        .fixed-width-table th {
            width: 100px;
            /* Ancho fijo en píxeles */
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
                <a type="button" class="btn btn-primary"
                    href="{{ route('partida-detalles.show', ['partida_detalle' => $partida, 'presupuesto' => $presupuesto]) }}">
                    <i class="voyager-angle-left"></i> Volver
                </a>
            </div>

            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-body">
                        <x-form id="formeditar" method="PUT">

                            <div class="row">
                                <div class="form-group  col-md-12 ">
                                    <label class="control-label" for="nombre">Detalle</label>
                                    <input type="hidden" name="pr" id="pr" value="{{ $presupuesto }}">
                                    <input type="hidden" name="partida" id="partida" value="{{ $partida }}">
                                    <input type="hidden" name="detalle_id" id="detalle_id"
                                        value="{{ $partida_detalle->id }}">
                                    <input required="" type="text" class="form-control" id="nombre" name="nombre"
                                        placeholder="Escriba el nombre de la partida"
                                        value="{{ $partida_detalle->detalle }}">
                                </div>
                            </div>
                            <x-table class="table table-bordered table-striped fixed-width-table" id="tbldetalles">
                                @slot('header')
                                    <tr>

                                        @foreach ($partida_detalle->precios as $mes)
                                            <th>{{ $mes->mes }}</th>
                                        @endforeach
                                    </tr>
                                @endslot


                                @php
                                    $totalGlobal = 0;
                                @endphp

                                @slot('body')
                                    @if ($partida_detalle->precios->count() > 0)
                                        <tr>
                                            @foreach ($partida_detalle->precios as $det)
                                                <td>
                                                    <input type="hidden" class="preciosid" name="precio_id"[] id="precio_id"
                                                        value="{{ $det->id }}">
                                                    <input type="number" min="0" name="precio[]" id="precio"
                                                        class="mesmonto form-control" value="{{ $det->monto }}">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <h5>No hay datos</h5>
                                            </td>
                                        </tr>
                                    @endif
                                @endslot

                            </x-table>

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
    <script src="{{ asset('js/edit-detalle.js') }}"></script>
@stop
