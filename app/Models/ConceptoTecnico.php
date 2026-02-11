<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConceptoTecnico extends Model
{
    protected $table = 'conceptos_tecnicos';

   protected $fillable = [
    'dispositivo_id', 
    'tipo_equipo', 
    'hostname', 
    'num_incidente', 
    'num_requerimiento', 
    'fecha_reporte', 
    'jefe_inmediato', 
    'descripcion_solicitud', 
    'software_base', // 👈 Importante
    'diagnostico_tecnico', 
    'flujo_solicitud', 
    'concepto_tipo', 
    'tecnico_nombre', 
    'requiere_contingencia'
];

protected $casts = [
    'software_base' => 'array', // 👈 Esto es vital para que guarde el JSON
    'fecha_reporte' => 'date',
];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class);
    }
}