<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\Salida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class PresupuestoMaestroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $presupuestos =
            Presupuesto::select(DB::raw('YEAR(fecha_inicio) as year'))
            ->groupBy('year')
            ->havingRaw('SUM(CASE WHEN estado = "aprobado" THEN 1 ELSE 0 END) < COUNT(*)')
            ->orderBy('year', 'desc')
            ->get();

        $allPresupuesto = Presupuesto::all();

        return view('presupuestos.comparar', compact('presupuestos', 'allPresupuesto'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($id > 0) {
            $queryPresupuesto = Presupuesto::query();
            if ('Presupuesto de Ventas' == $request->invidualp) {
                $data = $queryPresupuesto->where('nombre', $request->invidualp)->first();
                $pdf = Pdf::loadView('pdf.ventas', compact('data'))
                    ->setPaper('legal', 'landscape');
                return $pdf->stream('presupuestoventas.pdf');
            }
            if ('Presupuesto de Gastos' == $request->invidualp) {
                $data = $queryPresupuesto->where('nombre', $request->invidualp)->first();
                $pdf = Pdf::loadView('pdf.gastos', compact('data'))
                    ->setPaper('legal', 'landscape');
                return $pdf->stream('presupuestoventas.pdf');
            }
            if ('Presupuesto de Inversión de Maquinaria y Equipo' == $request->invidualp) {
                $data = $queryPresupuesto->where('nombre', $request->invidualp)->first();
                $pdf = Pdf::loadView('pdf.inversiones', compact('data'))
                    ->setPaper('legal', 'landscape');
                return $pdf->stream('presupuestoventas.pdf');
            }
        } else {
            // VALIDATIONS
            $validate = $request->validate(['presupuesto' => 'required']);
            $year = $validate['presupuesto'];
            return $this->presupuestoGeneral($year);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function presupuestoVenta($data)
    {
    }
    public function presupuestoGeneral($year)
    {

        // QUERY CONSULT
        $presupuestos = Presupuesto::with('partidas', 'partidas.partida_detalle')
            ->whereYear(
                'fecha_inicio',
                $year
            )
            ->where(function ($query) {
                $query->where('estado', '<>', 'aprobado')
                    ->orWhereNull('estado');
            })
            ->get();
        // QUERY GET UNIQUE RESULT
        $getnamepresupuesto  = Presupuesto::whereYear(
            'fecha_inicio',
            $year
        )
            ->where(function ($query) {
                $query->where('estado', '<>', 'aprobado')
                    ->orWhereNull('estado');
            })
            ->first();

        // CONFIG EXCEL format xlsx
        $spreadsheet = new Spreadsheet();
        $write = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator("Dev Kev")
            ->setTitle("Reporte del presupuesto maestro")
            ->setKeywords('Reportes')
            ->setCategory("Reportes ");


        // Establecer el título en la primera fila y combinar celdas A1 y B1
        $sheet->setCellValue('A1', $getnamepresupuesto->nombre . '    ' . date('Y', strtotime($getnamepresupuesto->fecha_inicio)));
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getColumnDimension('A')->setWidth(50);

        // para los anchos de las columnas
        $columns = range('B', 'M');
        foreach ($columns as $column) {
            $sheet->getColumnDimension($column)->setWidth(15); // Puedes ajustar el ancho según lo necesites
        }

        $sheet->getStyle("B2:M2")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
        $sheet->setCellValue('A1', $getnamepresupuesto->nombre . '    ' . date('Y', strtotime($getnamepresupuesto->fecha_inicio)));
        // pitamos las partidas
        $sheet->setCellValue('A2', 'Partida / Detalle');
        $sheet->setCellValue('B2', 'Enero');
        $sheet->setCellValue('C2', 'Febrero');
        $sheet->setCellValue('D2', 'Marzo');
        $sheet->setCellValue('E2', 'Abril');
        $sheet->setCellValue('F2', 'Mayo');
        $sheet->setCellValue('G2', 'Junio');
        $sheet->setCellValue('H2', 'Julio');
        $sheet->setCellValue('I2', 'Agosto');
        $sheet->setCellValue('J2', 'Septiembre');
        $sheet->setCellValue('K2', 'Octubre');
        $sheet->setCellValue('L2', 'Noviembre');
        $sheet->setCellValue('M2', 'Diciembre');
        $rowIndex = 3; // Empezar en la fila 3
        // Inicializar el array de totales
        $totals = array_fill(2, 12, 0);
        foreach ($presupuestos as $item) {
            foreach ($item->partidas as $partida) {
                $sheet->setCellValue('A' . $rowIndex, $partida->nombre);
                $sheet->getStyle("A{$rowIndex}:M{$rowIndex}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FFCCFFFF', // Color celeste
                        ],
                    ],
                ]);
                $rowIndex++; // Avanzar a la siguiente fila para los detalles

                foreach ($partida->partida_detalle as $detalle) {
                    $sheet->setCellValue('A' . $rowIndex, $detalle->detalle);
                    $colIndex = 2; // Empezar en la columna B (columna 2)
                    $subtotal = 0; // Inicializar el subtotal para esta fila
                    foreach ($detalle->precios as $precio) {

                        $sheet->setCellValue([$colIndex, $rowIndex], $precio->monto);
                        $sheet->getStyle([$colIndex, $rowIndex])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);

                        $subtotal += $precio->monto; // Sumar al subtotal de esta fila
                        $totals[$colIndex] += $precio->monto;
                        $colIndex++; // Mover a la siguiente columna
                    }
                    // Poner el subtotal al final de la fila
                    $sheet->setCellValue([$colIndex, $rowIndex], $subtotal);
                    $sheet->getStyle([$colIndex, $rowIndex])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);



                    // Calcular el total de salidas por partida_detalle_id utilizando Laravel
                    $totalSalidas = Salida::where('partida_detalles_id', $detalle->id)->sum('monto');
                    $colIndex++;
                    // Poner el total de salidas al final de la fila
                    $sheet->setCellValue([$colIndex, $rowIndex], $totalSalidas);
                    $sheet->getStyle([$colIndex, $rowIndex])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);

                    $rowIndex++; // Mover a la siguiente fila para el siguiente detalle
                }
            }
        }
        // Añadir una fila de totales al final de cada columna de precios
        $totalRowIndex = $rowIndex;
        $sheet->setCellValue('A' . $totalRowIndex, 'Totales');

        // Escribir los totales acumulados en la fila de totales
        foreach ($totals as $colIndex => $total) {
            $sheet->setCellValue([$colIndex, $totalRowIndex], $total);
            $sheet->getStyle([$colIndex, $totalRowIndex])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);
        }
        $sheet->getStyle("A{$totalRowIndex}:M{$totalRowIndex}")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFCCFFFF', // Color celeste
                ],
            ],
        ]);


        $filename = $getnamepresupuesto->nombre . ' ' . date('d-m-y-h-i-s-a') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');

        $write->save('php://output');
        exit;
    }
}
