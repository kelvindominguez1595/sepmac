<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Solicitud de

        @if ($salida->tipo == 1)
            ventas
        @else
            compras
        @endif

    </title>
    <style>
        table {
            width: 100%;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td><img width="100px" src="{{ public_path('images/icono.png') }}" alt="logo_solumaq"></td>
            <td>
                <h1>Solicitud de @if ($salida->tipo == 1)
                        ventas
                    @else
                        compras
                    @endif
                </h1>
            </td>
            <td>
                Fecha de Creación: {{ date('d/m/Y h:i:s a', strtotime($salida->created_at)) }}
            </td>
        </tr>
        <tr>
            <td><strong>Departamento:</strong> {{ $salida->departamento->name }}</td>
        </tr>
    </table>
    <hr>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Centro de costo</th>
                <th>Cuenta contable</th>
                <th>Costo</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">{{ $salida->id }}</td>
                <td style="text-align: center;">{{ $salida->descripcion }}</td>
                <td style="text-align: center;">{{ $salida->partidadetalle->detalle }}</td>
                <td style="text-align: center;">{{ $salida->partidadetalle->partidas->nombre }}</td>
                <td style="text-align: center;">${{ number_format($salida->monto, 2) }}</td>
                <td style="text-align: center;">{{ $salida->cantidad }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
