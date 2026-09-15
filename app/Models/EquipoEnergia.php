<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipoEnergia extends Model
{
    protected $table = 'equipos_energia';

    protected $fillable = [
        'sede_id','cuarto','ubicacion_id','tipo','marca','modelo',
        'numero_serie','placa','pertenece','estado','marquillado',
        // Especificaciones nominales
        'fase','potencia_va','potencia_w','voltaje_entrada','voltaje_salida','frecuencia',
        'tecnologia','factor_de_potencia',
        // Mediciones actuales
        'carga_actual_pct','potencia_activa_actual_kw','potencia_aparente_actual_kva',
        'bateria_actual_pct','autonomia_estimada_min','estado_operativo',
        // Baterías y respaldo
        'tipo_bateria','capacidad_baterias_ah','baterias_internas','baterias_banco_externo',
        'modelo_banco','serial_banco',
        // Administrativa
        'fecha_instalacion','fecha_ultimo_mantenimiento',
        'proximo_mantenimiento','garantia_hasta','proveedor','observaciones',
        'activo','created_by','updated_by',
    ];

    protected $casts = [
        'marquillado'         => 'boolean',
        'activo'              => 'boolean',
        'fecha_instalacion'   => 'date',
        'fecha_ultimo_mantenimiento' => 'date',
        'proximo_mantenimiento'      => 'date',
        'garantia_hasta'             => 'date',
    ];

    const TIPOS = [
        'UPS',
        'Regulador de Voltaje',
        'Planta Eléctrica / Generador',
        'Tablero de Transferencia (ATS)',
        'Estabilizador',
        'PDU',
        'Otro',
    ];

    const TIPOS_ICONO = [
        'UPS'                             => ['fa-battery-three-quarters', 'green'],
        'Regulador de Voltaje'            => ['fa-tachometer-alt',         'blue'],
        'Planta Eléctrica / Generador'    => ['fa-bolt',                   'yellow'],
        'Tablero de Transferencia (ATS)'  => ['fa-exchange-alt',           'purple'],
        'Estabilizador'                   => ['fa-wave-square',            'orange'],
        'PDU'                             => ['fa-plug',                   'gray'],
        'Otro'                            => ['fa-question-circle',        'gray'],
    ];

    const ESTADOS_COLOR = [
        'Bueno'            => 'bg-green-100 text-green-700',
        'Regular'          => 'bg-yellow-100 text-yellow-700',
        'Malo'             => 'bg-red-100 text-red-700',
        'En Mantenimiento' => 'bg-orange-100 text-orange-700',
        'Dado de Baja'     => 'bg-gray-100 text-gray-500',
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class);
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ¿El mantenimiento está próximo (≤30 días)?
    public function getMantenimientoProximoAttribute(): bool
    {
        return $this->proximo_mantenimiento &&
               $this->proximo_mantenimiento->diffInDays(now(), false) >= -30 &&
               $this->proximo_mantenimiento->isFuture();
    }

    // ¿El mantenimiento está vencido?
    public function getMantenimientoVencidoAttribute(): bool
    {
        return $this->proximo_mantenimiento && $this->proximo_mantenimiento->isPast();
    }
}
