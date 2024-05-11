<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Presupuesto;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PresupuestosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $presupuestos = Presupuesto::orderby('created_at', 'desc')->paginate(25);
        return view('presupuestos.index', compact('presupuestos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $clases = Clase::all();
        $tipos = Tipo::all();
        return view('presupuestos.create', compact('clases', 'tipos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate(
            [
                'codigo' => 'required',
                'nombre' => 'required|unique:presupuestos,nombre',
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required',
                'tipos_id' => 'required',
                'clases_id' => 'required'
            ],
            [
                'tipos_id.required' => 'El campo tipo es requerido',
                'clases_id.required' => 'El campo clase es requerido',
            ]
        );
        Presupuesto::create([
            'codigo' => $validate['codigo'],
            'nombre' => $validate['nombre'],
            'fecha_inicio' => date('Y-m-d', strtotime($validate['fecha_inicio'])),
            'fecha_fin' => date('Y-m-d', strtotime($validate['fecha_fin'])),
            'notas' => $request->nota,
            'supuestos' => $request->supuesto,
            'tipos_id' => $validate['tipos_id'],
            'clases_id' => $validate['clases_id'],
            'user_id_created' => Auth::user()->id
        ]);
        toastr()->success('Presupuesto creado correctamente');
        return redirect('admin/presupuestos');
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
    public function edit(Presupuesto $presupuesto)
    {
        $clases = Clase::all();
        $tipos = Tipo::all();
        return view('presupuestos.edit', compact('presupuesto', 'clases', 'tipos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Presupuesto $presupuesto)
    {
        $request->validate(['nombre', 'unique:presupuestos,nombre,' . $request->nombre]);
        $presupuesto->nombre = $request->nombre;
        $presupuesto->fecha_inicio = date('Y-m-d', strtotime($request['fecha_inicio']));
        $presupuesto->fecha_fin = date('Y-m-d', strtotime($request['fecha_fin']));
        $presupuesto->supuestos = $request->supuestos;
        $presupuesto->notas = $request->notas;
        $presupuesto->tipos_id = $request->tipos_id;
        $presupuesto->clases_id = $request->clases_id;
        $presupuesto->user_id_updated = $request->user_id_updated;
        $presupuesto->save();
        toastr()->success('Presupuesto actualizado correctamente');
        return redirect('admin/presupuestos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Presupuesto $presupuesto)
    {
        $presupuesto->delete();
        return response()->json(['message' => 'succes'], 200);
    }
}
