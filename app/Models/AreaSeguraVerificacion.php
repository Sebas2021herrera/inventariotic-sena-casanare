<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AreaSeguraVerificacion extends Model
{
    protected $table = 'areas_seguras_verificaciones';

    protected $fillable = [
        'area_segura_id', 'fecha_verificacion', 'corte',
        'items',                    // GIL-F-102 Bloque 2: 15 controles acceso (4 estados)
        'total_cumple', 'cumple_parcial_count', 'no_aplica_count', 'total_items',
        'tipo_medioambiental',      // GIL-F-102 Bloque 3: tipo de área
        'items_medioambientales',   // GIL-F-102 Bloque 3: controles medioambientales
        'resultado',
        'observaciones_generales',
        'verificado_por',
        'inspectores',              // GIL-F-102 Bloque 1: personas que inspeccionan
    ];

    protected $casts = [
        'items'                  => 'array',
        'items_medioambientales' => 'array',
        'fecha_verificacion'     => 'date',
    ];

    // Estados posibles para cada control (GIL-F-102)
    const ESTADOS = [
        'CUMPLE'              => ['label' => 'Cumple',              'color' => 'green',  'value' => 3],
        'CUMPLE PARCIALMENTE' => ['label' => 'Cumple Parcialmente', 'color' => 'yellow', 'value' => 1],
        'NO CUMPLE'           => ['label' => 'No Cumple',           'color' => 'red',    'value' => 0],
        'NO APLICA'           => ['label' => 'No Aplica',           'color' => 'gray',   'value' => 0],
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(AreaSegura::class, 'area_segura_id');
    }

    public function verificador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }

    public function getPorcentajeCumplimientoAttribute(): float
    {
        $aplicables = $this->total_items - ($this->no_aplica_count ?? 0);
        if ($aplicables <= 0) return 0;
        // Cumple = 1 punto, Cumple Parcialmente = 0.5 puntos
        $puntos = $this->total_cumple + (($this->cumple_parcial_count ?? 0) * 0.5);
        return round($puntos / $aplicables * 100, 1);
    }

    public function getPorcentajeMedioambientalAttribute(): float
    {
        if (!$this->items_medioambientales) return 0;
        $items    = collect($this->items_medioambientales);
        $total    = $items->count();
        $noAplica = $items->where('estado', 'NO APLICA')->count();
        $aplica   = $total - $noAplica;
        if ($aplica <= 0) return 0;
        $cumple   = $items->where('estado', 'CUMPLE')->count();
        $parcial  = $items->where('estado', 'CUMPLE PARCIALMENTE')->count();
        return round(($cumple + $parcial * 0.5) / $aplica * 100, 1);
    }
}
