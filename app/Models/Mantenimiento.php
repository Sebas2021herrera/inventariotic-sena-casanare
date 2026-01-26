<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    

protected $fillable = [
    'dispositivo_id', 'fecha', 'tipo', 'descripcion_falla', 
    'tareas_realizadas', 'piezas_cambiadas', 'tecnico_encargado', 'observaciones'
];

public function dispositivo()
{
    return $this->belongsTo(Dispositivo::class);
}


}
