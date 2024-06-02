<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Presupuesto de flujo de efectivo</title>
    <style>
        table.blueTable {
            border: 1px solid #1C6EA4;
            background-color: #EEEEEE;
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }

        table.blueTable td,
        table.blueTable th {
            border: 1px solid #AAAAAA;
            padding: 3px 2px;
        }

        table.blueTable tbody td {
            font-size: 13px;
        }

        table.blueTable tr:nth-child(even) {
            background: #D0E4F5;
        }

        table.blueTable thead {
            background: #1C6EA4;
            background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
            background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
            background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
            border-bottom: 2px solid #444444;
        }

        table.blueTable thead th {
            font-size: 15px;
            font-weight: bold;
            color: #FFFFFF;
            border-left: 2px solid #D0E4F5;
        }

        table.blueTable thead th:first-child {
            border-left: none;
        }

        table.blueTable tfoot {
            font-size: 14px;
            font-weight: bold;
            color: #FFFFFF;
            background: #D0E4F5;
            background: -moz-linear-gradient(top, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
            background: -webkit-linear-gradient(top, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
            background: linear-gradient(to bottom, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
            border-top: 2px solid #444444;
        }

        table.blueTable tfoot td {
            font-size: 14px;
        }

        table.blueTable tfoot .links {
            text-align: right;
        }

        table.blueTable tfoot .links a {
            display: inline-block;
            background: #1C6EA4;
            color: #FFFFFF;
            padding: 2px 8px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <table style=" width: 100%;">
        <tr>
            <td><img width="50px" src="{{ public_path('images/icono.png') }}" alt="logo_solumaq"></td>
            <td>
                <h1>Presupuesto de flujo de efectivo</h1>
            </td>
            <td>Fecha de Creación: {{ date('d/m/Y h:i:s a', strtotime($data->created_at)) }}</td>
        </tr>
    </table>
    <hr>

    @php
        $ventasTotal = [];
        $ventasEfeTotal = 0;
        $ventasEgreTotal = 0;
        $ventaDetalleResult = [];
        $ventaDetalleResult = [];
        $inversionDetalleResult = [];
        // para ventas
        foreach ($ventas->partidas as $venta) {
            foreach ($venta->partida_detalle as $ventadetalle) {
                //obtengo los totales
                if (!isset($ventaDetalleResult[$ventadetalle->detalle])) {
                    $ventaDetalleResult[$ventadetalle->detalle] = array_fill(0, 12, 1); // Inicializar con 1 para la multiplicación
                }
                foreach ($ventadetalle->precios as $index => $precioventa) {
                    $ventaDetalleResult[$ventadetalle->detalle][$index] *= $precioventa->monto;
                }
            }
        }
        $ventadettotalline1 = array_fill(0, 12, 0);
        // Sumar los valores de cada array al array resultante
        foreach ($ventaDetalleResult as $categoria => $valores) {
            for ($i = 0; $i < 12; $i++) {
                $ventadettotalline1[$i] += $valores[$i];
            }
        }
        // para lo de egresos
        foreach ($gastos->partidas as $gasto) {
            foreach ($gasto->partida_detalle as $gastodetalle) {
                //obtengo los totales
                if (!isset($gastoDetalleResult[$gastodetalle->detalle])) {
                    $gastoDetalleResult[$gastodetalle->detalle] = array_fill(0, 12, 1); // Inicializar con 1 para la multiplicación
                }
                foreach ($gastodetalle->precios as $index => $gastoventa) {
                    $gastoDetalleResult[$gastodetalle->detalle][$index] += $gastoventa->monto;
                }
            }
        }
        $gastodettotalline1 = array_fill(0, 12, 0);
        // Sumar los valores de cada array al array resultante
        foreach ($gastoDetalleResult as $catgasto => $gastoval) {
            for ($i = 0; $i < 12; $i++) {
                $gastodettotalline1[$i] += $gastoval[$i];
            }
        }
        // para lo de egresos
        foreach ($inversion->partidas as $invers) {
            foreach ($invers->partida_detalle as $inversiondetalle) {
                //obtengo los totales
                if (!isset($inversionDetalleResult[$inversiondetalle->detalle])) {
                    $inversionDetalleResult[$inversiondetalle->detalle] = array_fill(0, 12, 1); // Inicializar con 1 para la multiplicación
                }
                foreach ($gastodetalle->precios as $index => $inverventa) {
                    $inversionDetalleResult[$inversiondetalle->detalle][$index] += $inverventa->monto;
                }
            }
        }
        $inverdettotalline1 = array_fill(0, 12, 0);
        // Sumar los valores de cada array al array resultante
        foreach ($inversionDetalleResult as $catginve => $invertoval) {
            for ($i = 0; $i < 12; $i++) {
                $inverdettotalline1[$i] += $invertoval[$i];
            }
        }
        // print_r(json_encode($inverdettotalline1));
        // echo '<br>';
        // sumamos los egresos
        $resultado = [];
        for ($i = 0; $i < count($gastodettotalline1); $i++) {
            $resultado[] = $gastodettotalline1[$i] + $inverdettotalline1[$i];
        }
        // print_r(json_encode($resultado));
    @endphp

    <table class="blueTable">
        <thead>
            <tr>
                <td>Cuenta / Detalle</td>
                <td>Enero</td>
                <td>Febrero</td>
                <td>Marzo</td>
                <td>Abril</td>
                <td>Mayo</td>
                <td>Junio</td>
                <td>Julio</td>
                <td>Agosto</td>
                <td>Septiembre</td>
                <td>Octubre</td>
                <td>Noviembre</td>
                <td>Diciembre</td>

            </tr>
        </thead>
        <tbody>


            @foreach ($data->partidas as $partida)
                <tr>
                    <td colspan="13"><strong>{{ $partida->nombre }}</strong></td>
                </tr>
                @foreach ($partida->partida_detalle as $partidadetalle)
                    @php
                        $subtotalFIla = 0;
                        //obtengo los totales
                        if (!isset($ventasTotal[$partidadetalle->detalle])) {
                            $ventasTotal[$partidadetalle->detalle] = [
                                'meses' => array_fill(0, 12, 1), // Inicializar con 1 para la multiplicación
                            ];
                        }
                        foreach ($partidadetalle->precios as $index => $precio) {
                            $ventasTotal[$partidadetalle->detalle]['meses'][$index] *= $precio->monto;
                        }
                    @endphp
                    <tr>
                        <td>{{ $partidadetalle->detalle }} </td>
                        @foreach ($partidadetalle->precios as $precio)
                            <td>
                                @if ($partida->nombre == 'Volumen de ventas')
                                    {{ $precio->monto }}
                                @else
                                    ${{ number_format($precio->monto, 2) }}
                                @endif
                                @php
                                    $subtotalFIla += $precio->monto;
                                @endphp
                            </td>
                        @endforeach
                    </tr>
                @endforeach

                @if ($partida->nombre == 'Ingresos por ventas en efectivo')
                    <tr>
                        <td>Ingresos por ventas en efectivo </td>
                        @foreach ($ventadettotalline1 as $colvendetalle)
                            @php
                                $ventasEfeTotal += $colvendetalle;
                            @endphp
                            <td>${{ number_format($colvendetalle, 2) }}</td>
                        @endforeach
                    </tr>
                @endif

                @if ($partida->nombre == 'Egresos de Efectivo')
                    <tr>
                        <td>Egresos por compras en efectivo</td>
                        @foreach ($resultado as $egresosfinal)
                            @php
                                $ventasEgreTotal += $egresosfinal;
                            @endphp
                            <td>${{ number_format($egresosfinal, 2) }}</td>
                        @endforeach
                    </tr>
                @endif
            @endforeach
            {{-- Resultados  --}}
            {{-- <tr>
                <td colspan="13"><strong>Ventas</strong></td>
            </tr>
            @foreach ($ventasTotal as $key => $ventatot)
                <tr>
                    <td>
                        {{ $key }}
                    </td>
                    @foreach ($ventatot['meses'] as $meses)
                        <td>${{ number_format($meses, 2) }}</td>
                    @endforeach
                </tr>
            @endforeach --}}
        </tbody>
    </table>


    <hr>
    <table class="blueTable">
        <thead>
            <tr>
                <td>Cuenta / Detalle</td>
                <td>Total Presupuesto</td>
                {{-- <td>Real</td> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($ventasTotal as $key => $ventatot)
                <tr>
                    <td>
                        {{ $key }}
                    </td>
                    @php
                        $totalpresuo = 0;
                        $totalreal = 0;
                    @endphp
                    @foreach ($ventatot['meses'] as $meses)
                        @php
                            $totalpresuo += $meses;
                        @endphp
                    @endforeach
                    <td>${{ number_format($totalpresuo, 2) }}</td>
                    {{-- <td>

                        @foreach ($data->partidas as $partida)
                            @foreach ($partida->partida_detalle as $pd)
                                @php
                                    if ($pd->detalle == $key) {
                                        if ($pd->salidas->where('partida_detalles_id', $pd->id)->sum('monto') > 0) {
                                            $totalreal = $pd->salidas
                                                ->where('partida_detalles_id', $pd->id)
                                                ->sum('monto');
                                        }
                                    }
                                @endphp
                            @endforeach
                        @endforeach
                        ${{ number_format($totalreal, 2) }}
                    </td> --}}
                </tr>
            @endforeach
            <tr>
                <td>Ingresos por ventas en efectivo</td>
                <td>{{ number_format($ventasEfeTotal, 2) }}</td>
            </tr>
            <tr>
                <td>Egresos por compras en efectivo</td>
                <td>{{ number_format($ventasEgreTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
