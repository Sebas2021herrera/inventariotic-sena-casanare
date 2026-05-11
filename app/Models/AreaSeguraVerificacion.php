<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AreaSeguraVerificacion extends Model
{
    protected $table = 'areas_seguras_verificaciones';

    protected $fillable = [
        'area_segura_id','fecha_verificacion','corte','items',
        'total_cumple','total_items','resultado',
        'observaciones_generales','verificado_por',
    ];

    protected $casts = [
        'items'              => 'array',
        'fecha_verificacion' => 'date',
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
        if ($this->total_items === 0) return 0;
        return round($this->total_cumple / $this->total_items * 100, 1);
    }
}
