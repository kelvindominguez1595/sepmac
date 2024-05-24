<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

        return view('presupuestos.comparar', compact('presupuestos'));
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
        // VALIDATIONS
        $validate = $request->validate(['presupuesto' => 'required']);
        $year = $validate['presupuesto'];
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

        foreach ($presupuestos as $item) {
            foreach ($item->partidas as $partida) {
                $sheet->setCellValue('A' . $rowIndex, $partida->nombre);
                $rowIndex++; // Avanzar a la siguiente fila para los detalles

                foreach ($partida->partida_detalle as $detalle) {
                    $sheet->setCellValue('A' . $rowIndex, $detalle->detalle);
                    $colIndex = 2; // Empezar en la columna B (columna 2)
                    foreach ($detalle->precios as $precio) {
                        $sheet->setCellValue([$colIndex, $rowIndex], $precio->monto);
                        $colIndex++; // Mover a la siguiente columna
                    }

                    $rowIndex++; // Mover a la siguiente fila para el siguiente detalle
                }
            }
        }

        $filename = $getnamepresupuesto->nombre . ' ' . date('d-m-y-h-i-s-a') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');

        $write->save('php://output');
        exit;
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
}
