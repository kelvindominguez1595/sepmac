<!DOCTYPE html>
<html>
<head>
    <title>Comparativa de Presupuesto 1</title>
    <style>
        @page {
            size: landscape; /* Establece la orientación de la página en horizontal */
        }
        table {
            size: 10px;
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: center;
        }
        .header {
            position: relative;
            width: 100%;
            height: 100px;
            background-color: #f5f5f5;
            text-align: center;
            line-height: 50px;
        }
        .header img {
            position: absolute;
            top: -50px;
            left: 10px;
            width: 300px;
            height: auto;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo">
        <h1>Comparativa de Presupuestos</h1>
    </div>

    <h2>Presupuesto 1</h2>
    <table>        
        <thead>
            <tr>
            @foreach ($presupuestos as $presupuesto)            
                <th>{{ $presupuesto->mes }}</th>                            
            @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
            @foreach ($presupuestos as $presupuesto)                                          
                <td>{{ $presupuesto->total }}</td>            
            @endforeach
            </tr>
        </tbody>
    </table>

    <h2>Presupuesto 2</h2>
    <table>        
        <thead>
            <tr>
            @foreach ($presupuestos2 as $presupuesto2)            
                <th>{{ $presupuesto2->mes }}</th>                            
            @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
            @foreach ($presupuestos2 as $presupuesto2)                                          
                <td>{{ $presupuesto2->total }}</td>            
            @endforeach
            </tr>
        </tbody>
    </table>

    <br><br><br>
    <hr>

    <h3>Diferencia de presupuestos</h3>
    <table>        
        <thead>
            <tr>
            @foreach ($presupuestos2 as $presupuesto2)            
                <th>{{ $presupuesto2->mes }}</th>                            
            @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach ($presupuestos3 as $presupuesto3)
                    <th>{{ $presupuesto3->diferencia }}</th>
                @endforeach
            </tr>         
        </tbody>
    </table>
</body>
</html>