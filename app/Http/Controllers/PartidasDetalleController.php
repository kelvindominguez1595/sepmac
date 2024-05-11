<?php

namespace App\Http\Controllers;

use App\Models\PartidasDetalle;
use App\Models\PartidasDetallesPrecio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartidasDetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('admin/presupuestos');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $uuid = $request->uuid;
        $pr = $request->presupuesto;
        $meses = [
            'ENERO',
            'FEBRERO',
            'MARZO',
            'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO',
            'SEPTIEMBRE',
            'OCTUBRE',
            'NOVIEMBRE',
            'DICIEMBRE'
        ];
        return view('partidas.detalle-create', compact('meses', 'uuid', 'pr'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pr = $request->pr;
        foreach ($request['titles'] as $key => $value) {
            // creamos el detalle
            $partidaDetalle =  PartidasDetalle::create([
                'partida_id' => $request['presupuesto_id'],
                'detalle' => $value,
                'user_created' => Auth::user()->id
            ]);
            foreach ($request['meses'][$key] as $k => $v) {
                PartidasDetallesPrecio::create([
                    'partida_detalles_id' => $partidaDetalle->id,
                    'mes' => $v,
                    'monto' => isset($request['montos'][$key][$k]) ? $request['montos'][$key][$k] : 0,
                    'user_created' => Auth::user()->id
                ]);
            }
        }
        return response()->json(['message' => 'success', 'uuid' => $pr], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $presupuesto = $request->presupuesto;
        $partida_detalle = PartidasDetalle::where('partida_id', $id)->get();
        return view('partidas.edit-partida', compact('partida_detalle', 'presupuesto', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PartidasDetalle $partida_detalle, Request $request)
    {
        $presupuesto = $request->presupuesto;
        $partida = $request->partida;
        return view('partidas.edit-detalle', compact('partida_detalle', 'presupuesto', 'partida'));
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
        $pr = $request->pr;
        $partida = $request->partida;
        $pdtel = PartidasDetalle::find($id);
        $pdtel->detalle = $request->nombre;
        $pdtel->user_updated = Auth::user()->id;
        $pdtel->save();

        // editamos el detalle
        foreach ($request['preciosId'] as $k => $v) {
            $precio = PartidasDetallesPrecio::find($v);
            $precio->monto = isset($request['montos'][$k]) ? $request['montos'][$k] : 0;
            $precio->user_updated = Auth::user()->id;
            $precio->save();
        }

        return response()->json(['message' => 'success', 'presupuesto' => $pr, 'partida' => $partida], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartidasDetalle $partida_detalle)
    {
        $partida_detalle->delete();
        return response()->json(['message' => 'success'], 200);
    }
}
