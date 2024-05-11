<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class PartidasDetallesPrecio extends Model
{

    protected $table = 'partidas_detalles_precios';
    protected $fillable = [
        'partida_detalles_id',
        'mes',
        'monto',
        'user_created',
        'user_updated',
        'user_deleted'
    ];
    protected $dates = ['deleted_at'];
    public function partida_detalles()
    {
        return $this->belongsTo(PartidasDetalle::class, 'partida_detalles_id');
    }
}
