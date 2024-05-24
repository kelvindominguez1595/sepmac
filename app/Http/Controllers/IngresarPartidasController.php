<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Models\PartidasDetalle;
use App\Models\Presupuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngresarPartidasController extends Controller
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
        return view('partidas-agregar.index', compact('presupuestos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validate = $request->validate(
            [
                'uuid' => 'required',
                'presupuesto' => 'required'
            ]
        );
        $isUserNormal = 'hello';
        $uuid = $validate['uuid'];
        $pr = $request['presupuesto'];
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
        $historial = PartidasDetalle::where('partida_id', $validate['uuid'])->get();
        return view('partidas.detalle-create', compact(
            'meses',
            'uuid',
            'pr',
            'isUserNormal',
            'historial'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $validate = $request->validate(['presupuesto' => 'required']);
        $year = $validate['presupuesto'];
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
        return view('partidas-agregar.ver_partidas', compact('presupuestos', 'year'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $partidas = Partida::where('presupuesto_id', $id)->get();
        $presupuesto = Presupuesto::find($id);
        return view('partidas-agregar.ver_sub_partidas', compact('partidas', 'presupuesto'));
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
