<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class PartidasDetalle extends Model
{
    use SoftDeletes;
    protected $table = 'partidas_detalles';
    protected $fillable = [
        'partida_id',
        'detalle',
        'user_created',
        'user_updated',
        'user_deleted'
    ];
    protected $dates = ['deleted_at'];


    public function usercreado()
    {
        return $this->belongsTo(User::class, 'user_created');
    }
    public function partidas()
    {
        return $this->belongsTo(Partida::class, 'partida_id');
    }

    public function precios()
    {
        return $this->hasMany(PartidasDetallesPrecio::class, 'partida_detalles_id');
    }
}
