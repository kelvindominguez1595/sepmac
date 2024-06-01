@extends('voyager::master')
@section('page_header')
    <h4 class=" page-title"> <i class="voyager-paper-plane"></i>Solicitud de ventas</h4>
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
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"
        integrity="sha512-u3fPA7V8qQmhBPNT5quvaXVa1mnnLSXUep5PS1qo5NRzHwG19aHmNJnj1Q8hpA/nBWZtZD4r4AX6YOt5ynLN2g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css"
        rel="stylesheet">
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

        <x-modal title="Nueva solicitud de ingreso">
            <x-form id="formrealizar_solicitud">
                <input type="hidden" name="tipo" id="tipo" value="1">
                <div class="form-group  col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label class="control-label" for="nombre">Cuenta contable</label>
                    <select name="cuenta_id" id="cuenta_id" class="form-control">
                        <option value="">Seleccione</option>
                        @foreach ($presupuestos->whereIn('id', [7]) as $presupuesto)
                            @foreach ($presupuesto->partidas as $partida)
                                @foreach ($partida->partida_detalle->whereIn('partida_id', [41, 56]) as $partidaDetalle)
                                    <option value="{{ $partidaDetalle->id }}">{{ $partidaDetalle->detalle }}
                                    </option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="form-group  col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label class="control-label" for="nombre">Departamento</label>
                    <select name="departamento_id" id="departamento_id" class="form-control">
                        <option value="">Selccione</option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento->id }}">{{ $departamento->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group  col-md-12">
                    <label class="control-label" for="supuesto">Descripción</label>
                    <textarea name="descripcion" class="form-control"></textarea>
                </div>


                <div class="form-group  col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label class="control-label" for="nombre">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" placeholder="0" min="1" value="1"
                        class="form-control">
                </div>
                <div class="form-group  col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label class="control-label" for="nombre">Monto:$</label>
                    <input type="number" name="monto" id="monto" step="any" placeholder="0.00"
                        class="form-control">
                </div>
                <div class="col-12 text-center">
                    <h4 class="text-danger" id="txttotalfinal">Monto total:$0.00</h4>
                </div>

            </x-form>
        </x-modal>



        <div class="row">

            <div class="col-md-12 ">

                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#exampleModal">
                            Nueva solicitud de ingresos
                        </button>



                        <hr>
                        <x-table>
                            @slot('header')
                                <th>ID</th>
                                <th>Descripcion</th>
                                <th>Monto</th>
                                <th>Opciones</th>
                            @endslot
                            @slot('body')
                                @foreach ($ingresos as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->descripcion }}</td>
                                        <td>${{ number_format($item->monto, 2) }}</td>
                                        <td>
                                            <a href="{{ route('salidas.show', $item) }}" target="_blank"
                                                class="btn btn-primary"><i class="fa fa-print"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endslot
                        </x-table>

                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('javascript')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('js/salidas_create.js') }}"></script>
@stop
