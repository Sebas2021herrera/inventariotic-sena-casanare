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
    'propietario', 'funcion', 'en_intune', // <-- Nuevos campos
    // Nuevos campos de Redes
    'descripcion_tecnica', 'puertos', 'mac_address', 'ap_conectado_a', 'puerto_origen'
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

    public function mantenimientos()
{
    return $this->hasMany(Mantenimiento::class);
}

public function conceptos()
{
    // Un dispositivo tiene muchos conceptos técnicos GTI-F-132
    return $this->hasMany(ConceptoTecnico::class, 'dispositivo_id');
}
}