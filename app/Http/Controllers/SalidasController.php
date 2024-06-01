<?php

namespace App\Http\Controllers;

use App\Departamento;
use App\Models\PartidasDetallesPrecio;
use App\Models\Presupuesto;
use App\Models\Salida;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class SalidasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $presupuestos = Presupuesto::with('partidas', 'partidas.partida_detalle')
            ->whereYear(
                'fecha_inicio',
                date('Y')
            )
            ->get();
        $departamentos = Departamento::all();
        $ingresos = Salida::where('tipo', 1)->get();
        return view('salidas.index', compact(
            'presupuestos',
            'departamentos',
            'ingresos'
        ));
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
        $validate = $request->validate([
            'departamento_id' => 'required',
            'cuenta_id' => 'required',
            'monto' => 'required|min:1',
            'descripcion' => 'required',
            'cantidad' => 'required|min:1',
        ]);
        $monto = $validate['cantidad'] * $validate['monto'];
        // verificamos si ya hizo una salida
        // $salidaRegistrada = 0;
        // if (Salida::where('partida_detalles_id', $validate['cuenta_id'])->exists()) {
        //     $salidaRegistrada = Salida::where('partida_detalles_id', $validate['cuenta_id'])
        //         ->sum('monto');
        // }
        // // obtenemos el total de la cuenta
        // $detallePrecio = PartidasDetallesPrecio::where(
        //     'partida_detalles_id',
        //     $validate['cuenta_id']
        // )->sum('monto');
        // restamos salida menos total de la cuenta
        // $subtotalReal = abs($salidaRegistrada - $detallePrecio);
        // restamos el resultado con el nuevo monto y si este es cubierto
        // if ($monto > $subtotalReal) {
        //     return response()->json(['message' => 'El monto enviado es mayor al presupuesto'], 422);
        // }
        Salida::create([
            'departamento_id' => $validate['departamento_id'],
            'partida_detalles_id' => $validate['cuenta_id'],
            'monto' => $monto,
            'descripcion' => $validate['descripcion'],
            'cantidad' => $validate['cantidad'],
            'tipo' => $request->tipo
        ]);
        return response()->json(['message' => 'Solicitud creada correctamente'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Salida $salida)
    {
        $pdf = Pdf::loadView('salidas.salida-pdf', compact('salida'));
        return $pdf->stream('salidas.pdf');
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

    public function meses($numeroMes)
    {
        $meses = [
            'ENERO',
            'FEBRERO',
            'MARZO',
            'ABRIL',
            'MAYO',
            'JUNIO',
            'JULIO',
            'AGOSTO',
            'SEPTIEMBRE',
            'OCTUBRE',
            'NOVIEMBRE',
            'DICIEMBRE'
        ];
        return $meses[$numeroMes];
    }
}
