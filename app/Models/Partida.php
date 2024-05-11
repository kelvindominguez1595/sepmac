<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class Partida extends Model
{
    use SoftDeletes;
    protected $table = 'partidas';
    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'presupuesto_id',
        'user_updated',
        'user_deleted'
    ];
    protected $dates = ['deleted_at'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function partida_detalle()
    {
        return $this->hasMany(PartidasDetalle::class, 'partida_id');
    }
}
