<?php

namespace App\Models;

use App\Departamento;
use Illuminate\Database\Eloquent\Model;


class Salida extends Model
{

    protected $fillable = [
        'departamento_id',
        'partida_detalles_id',
        'monto',
        'descripcion',
        'cantidad',
        'tipo'
    ];

    public function partidadetalle()
    {
        return $this->belongsTo(PartidasDetalle::class, 'partida_detalles_id');
    }
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }
}
