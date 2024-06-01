<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF; // Asegúrate de que este alias está configurado en config/app.php

class ComparativaPresupuestoPDFController extends Controller
{
    public function index(){
        $data = DB::select("select sum(pdp.monto) as total, pdp.mes, pre.nombre from partidas_detalles_precios pdp
        inner join partidas_detalles pd on (pdp.partida_detalles_id = pd.id) 
        inner join partidas p on (pd.partida_id = p.id) 
        inner join presupuestos pre on (p.presupuesto_id = pre.id) 
        where partida_detalles_id in (select id from partidas_detalles)
        and pre.id = 9
        group by pdp.mes, pre.nombre
        ORDER BY CASE 
        WHEN lower(pdp.mes) = 'enero' THEN 1
        WHEN lower(pdp.mes) = 'febrero' THEN 2
        WHEN lower(pdp.mes) = 'marzo' THEN 3
        WHEN lower(pdp.mes) = 'abril' THEN 4
        WHEN lower(pdp.mes) = 'mayo' THEN 5
        WHEN lower(pdp.mes) = 'junio' THEN 6
        WHEN lower(pdp.mes) = 'julio' THEN 7
        WHEN lower(pdp.mes) = 'agosto' THEN 8
        WHEN lower(pdp.mes) = 'septiembre' THEN 9
        WHEN lower(pdp.mes) = 'octubre' THEN 10
        WHEN lower(pdp.mes) = 'noviembre' THEN 11
        WHEN lower(pdp.mes) = 'diciembre' THEN 12
        END;");

        $data2 = DB::select("select sum(pdp.monto) as total, pdp.mes, pre.nombre from partidas_detalles_precios pdp
        inner join partidas_detalles pd on (pdp.partida_detalles_id = pd.id) 
        inner join partidas p on (pd.partida_id = p.id) 
        inner join presupuestos pre on (p.presupuesto_id = pre.id) 
        where partida_detalles_id in (select id from partidas_detalles)
        and pre.id = 6
        group by pdp.mes, pre.nombre
        ORDER BY CASE 
        WHEN lower(pdp.mes) = 'enero' THEN 1
        WHEN lower(pdp.mes) = 'febrero' THEN 2
        WHEN lower(pdp.mes) = 'marzo' THEN 3
        WHEN lower(pdp.mes) = 'abril' THEN 4
        WHEN lower(pdp.mes) = 'mayo' THEN 5
        WHEN lower(pdp.mes) = 'junio' THEN 6
        WHEN lower(pdp.mes) = 'julio' THEN 7
        WHEN lower(pdp.mes) = 'agosto' THEN 8
        WHEN lower(pdp.mes) = 'septiembre' THEN 9
        WHEN lower(pdp.mes) = 'octubre' THEN 10
        WHEN lower(pdp.mes) = 'noviembre' THEN 11
        WHEN lower(pdp.mes) = 'diciembre' THEN 12
        END;");        

        // Crear un array para almacenar las diferencias
        $data3 = [];

        // Convertir ambos conjuntos de datos a arrays asociativos con el mes como clave para fácil acceso
        $dataAssoc = [];
        foreach ($data as $item) {
            $dataAssoc[strtolower($item->mes)] = $item;
        }

        $data2Assoc = [];
        foreach ($data2 as $item) {
            $data2Assoc[strtolower($item->mes)] = $item;
        }

        // Calcular la diferencia para cada mes
        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        foreach ($meses as $mes) {
            $total1 = isset($dataAssoc[$mes]) ? $dataAssoc[$mes]->total : 0;
            $total2 = isset($data2Assoc[$mes]) ? $data2Assoc[$mes]->total : 0;

            $data3[] = (object) [
                'mes' => ucfirst($mes),
                'diferencia' => $total1 - $total2
            ];
        }

        /*
        $data = ['presupuestos' => $data];
        $data2 = ['presupuestos2' => $data2];
        $data3 = ['presupuestos3' => $data3];
        */

        $viewData = [
            'presupuestos' => $data,
            'presupuestos2' => $data2,
            'presupuestos3' => $data3
        ];

        // Enviando datos a la vista.

        $pdf = PDF::loadView('presupuestos.myPDF', $viewData);

        return $pdf->stream('myPDF.pdf');

        //return $viewData;
    }

    /*
    public function generatePDF()
    {
        $data = ['title' => 'Welcome to Laravel PDF Example'];

        $pdf = PDF::loadView('presupuestos.myPDF', $data);

        return $pdf->download('myPDF.pdf');
    }*/
}
