@extends('voyager::master')
@section('page_header')
    <h4 class=" page-title"> <i class="voyager-news"></i>Nueva cuenta contables</h4>
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

        .btn {

            margin-bottom: 0px;
            margin-top: 0px;
        }

        .table tbody tr td:nth-last-child(-n+12) {
            width: 100px;
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
            @if (empty($isUserNormal))
                <div class="col-md-6">
                    <a type="button" class="btn btn-primary" href="{{ route('partida.create', ['uuid' => $pr]) }}">
                        <i class="voyager-angle-left"></i> Volver
                    </a>
                </div>
            @endif


            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <x-form id="fm-detalles">
                        <input type="hidden" name="presupuesto_id" id="presupuesto_id" value="{{ $uuid }}"
                            autocomplete="false">
                        <input type="hidden" name="pr" id="pr" value="{{ $pr }}"
                            autocomplete="false">
                        <input type="hidden" name="volver" id="volver"
                            value="{{ isset($isUserNormal) ? 'volver' : 'buttonactive' }}" autocomplete="false">
                        <div class="table-responsive">

                            <table id="tbldetalles" class="table table-striped table-bordered fixed-width-table">
                                <thead>
                                    <tr>
                                        <th colspan="11" class="text-right ">
                                            <h3>Presione el boton para crear una nueva cuenta contable ---></h3>
                                        </th>
                                        <th class="text-center">
                                            <x-button type="button" id="btnadd" title="Agregar un detalle a la partida">
                                                <i class="fa fa-plus"></i> Agregar</x-button>
                                        </th>
                                    </tr>

                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                    </x-form>

                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header ">
                        <span class="text-center">Historial de cuentas contables registradas</span>
                    </div>
                    <div class="card-body">
                        <x-table>
                            @slot('header')
                                <th>Cuenta contable</th>
                                <th>Total General</th>
                            @endslot
                            @slot('body')
                                @foreach ($historial as $item)
                                    <tr>
                                        <td>{{ $item->detalle }}</td>
                                        <td>
                                            @php
                                                $totalGeneral = 0;
                                            @endphp
                                            @foreach ($item->precios as $pre)
                                                @php
                                                    $totalGeneral += $pre->monto;
                                                @endphp
                                            @endforeach

                                            ${{ number_format($totalGeneral, 2) }}
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
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/deatlle.js') }}"></script>
    <script>
        function botton() {
            location.href = `../partida/create?uuid=1`;
        }
    </script>
@stop
