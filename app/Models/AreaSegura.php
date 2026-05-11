<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AreaSegura extends Model
{
    protected $table = 'areas_seguras';

    protected $fillable = [
        'sede_id','codigo','nombre_dependencia',
        'nivel_criticidad','nivel_sena','tipo_area','responsable_cargo',
        'bloque','piso','numero_oficina','perimetro_seguridad',
        'controles_acceso','horario_acceso','descripcion','activa',
        'created_by','updated_by',
    ];

    // Catálogo SENA de niveles con ejemplos orientativos
    const NIVELES_SENA = [
        'Nivel 1 - Crítico' => [
            'color'    => 'red',
            'icono'    => 'fa-server',
            'acceso'   => 'Restringido Total',
            'ejemplos' => ['Centro de Datos (Data Center)', 'Centros de Cableado (Racks / IDF / MDF)', 'Bóveda de Títulos y Certificaciones'],
        ],
        'Nivel 2 - Sensible' => [
            'color'    => 'orange',
            'icono'    => 'fa-briefcase',
            'acceso'   => 'Acceso Controlado',
            'ejemplos' => ['Oficinas Administrativas y Financieras', 'Área de Coordinación Académica', 'Archivo de datos de aprendices'],
        ],
        'Nivel 3 - Operativo' => [
            'color'    => 'blue',
            'icono'    => 'fa-chalkboard',
            'acceso'   => 'Supervisado',
            'ejemplos' => ['Ambientes de formación con Intune', 'Bodegas de herramientas y activos fijos'],
        ],
    ];

    protected $casts = [
        'controles_acceso' => 'array',
        'activa'           => 'boolean',
    ];

    // Items fijos del checklist ISO 27001:2022 — Categoría Físicos
    const CHECKLIST_ITEMS = [
        ['control'=>'7.6',  'categoria'=>'Acceso Físico',       'item'=>'¿Las puertas de acceso permanecen cerradas y con llave/seguro cuando no hay personal?'],
        ['control'=>'7.7',  'categoria'=>'Monitoreo',            'item'=>'¿Existen cámaras de seguridad funcionando y cubriendo los puntos de entrada/salida?'],
        ['control'=>'7.5',  'categoria'=>'Protección',           'item'=>'¿Las ventanas o puntos de entrada alternos cuentan con protecciones físicas (rejas, sensores)?'],
        ['control'=>'7.15', 'categoria'=>'Escritorio Limpio',    'item'=>'¿Se evidencia ausencia de documentos sensibles o claves pegadas en monitores/escritorios?'],
        ['control'=>'Vis.', 'categoria'=>'Visitantes',           'item'=>'¿Existe un registro de ingreso para personal externo o de mantenimiento a esta área?'],
        ['control'=>'7.9',  'categoria'=>'Equipos',              'item'=>'¿Los racks o gabinetes de comunicaciones dentro del área están debidamente cerrados?'],
        ['control'=>'Amb.', 'categoria'=>'Riesgos Ambientales',  'item'=>'¿El área está libre de materiales inflamables o líquidos cerca de los activos tecnológicos?'],
    ];

    public function sede(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Sede::class);
    }

    public function verificaciones(): HasMany
    {
        return $this->hasMany(AreaSeguraVerificacion::class, 'area_segura_id')
                    ->orderBy('fecha_verificacion', 'desc');
    }

    public function ultimaVerificacion()
    {
        return $this->hasOne(AreaSeguraVerificacion::class, 'area_segura_id')
                    ->latestOfMany('fecha_verificacion');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
