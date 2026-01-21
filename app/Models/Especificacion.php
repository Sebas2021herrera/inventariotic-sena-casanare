<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Especificacion extends Model
{
    protected $table = 'especificaciones';
    protected $fillable = ['dispositivo_id', 'procesador', 'ram', 'tipo_disco', 'capacidad_disco', 'so', 'mac_address', 'placa_monitor', 'serial_cargador'];
    public function dispositivo(): BelongsTo
    {
        return $this->belongsTo(Dispositivo::class);
    }
}