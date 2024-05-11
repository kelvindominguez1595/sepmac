<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Models\Presupuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartidasController extends Controller
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
        $presupuesto = Presupuesto::find($uuid);
        $query = Partida::query();
        $query->with(
            'partida_detalle',
            'partida_detalle.precios'
        );
        $query->where('presupuesto_id', $uuid);
        $partidas = $query->paginate(50);
        return view('partidas.index', compact('uuid', 'partidas', 'presupuesto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required'
        ]);

        $presupuesto_id = $request->presupuesto_id;
        Partida::create([
            'user_id' => Auth::user()->id,
            'nombre' => $request->nombre,
            'descripcion' => isset($request->descripcion) ? $request->descripcion : '-',
            'presupuesto_id' =>   $presupuesto_id
        ]);
        toastr()->success('Nueva partida creada correctamente');
        return redirect('admin/partida/create?uuid=' . $presupuesto_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Partida $partida, Request $request)
    {
        $presupuesto = $request->presupuesto;
        return view('partidas.edit-body-partida', compact('partida', 'presupuesto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partida $partida)
    {
        $partidaid = $request->presupuestoid;
        $partida->nombre = $request->nombre;
        $partida->descripcion = $request->descripcion;
        $partida->user_updated = Auth::user()->id;
        $partida->save();
        toastr()->success('Datos actualizados correctamente');
        return redirect('admin/partida/create?uuid=' . $partidaid);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partida $partida)
    {
        $partida->delete();
        return response()->json(['message' => 'success'], 200);
    }
}
