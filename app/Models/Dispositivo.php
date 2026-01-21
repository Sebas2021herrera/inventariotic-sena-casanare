<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dispositivo extends Model
{
  protected $fillable = [
    'placa', 'serial', 'marca', 'modelo', 'categoria', 
    'estado_fisico', 'estado_logico', 'observaciones', 
    'responsable_id', 'ubicacion_id',
    'propietario', 'funcion', 'en_intune' // <-- Nuevos campos
];

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Responsable::class);
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class);
    }

    public function especificaciones(): HasOne
    {
        return $this->hasOne(Especificacion::class);
    }
    // Un dispositivo tiene muchos periféricos (Monitor, Teclado, etc.)
    public function perifericos()
    {
        return $this->hasMany(Periferico::class);
    }
}