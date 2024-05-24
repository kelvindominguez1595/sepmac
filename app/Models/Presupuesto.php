<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'codigo',
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'notas',
        'supuestos',
        'tipos_id',
        'clases_id',
        'user_id_created',
        'user_id_deleted',
        'user_id_updated',
        'estado',
        'observaciones'
    ];
    protected $dates = ['deleted_at'];

    public function partidas()
    {
        return $this->hasMany(Partida::class);
    }
}
