<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DispositivoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // Identificación
            'placa'    => $this->placa,
            'serial'   => $this->serial,
            'hostname' => $this->hostname,

            // Clasificación
            'marca'       => $this->marca,
            'modelo'      => $this->modelo,
            'categoria'   => $this->categoria,
            'propietario' => $this->propietario,
            'funcion'     => $this->funcion,
            'en_intune'   => $this->en_intune,

            // Estado
            'estado_fisico' => $this->estado_fisico,
            'estado_logico' => $this->estado_logico,
            'observaciones' => $this->observaciones,

            // Relaciones (solo se incluyen si fueron cargadas con with())
            'responsable' => $this->whenLoaded('responsable', fn() => [
                'cedula'      => $this->responsable->cedula,
                'nombre'      => $this->responsable->nombre,
                'cargo'       => $this->responsable->cargo,
                'dependencia' => $this->responsable->dependencia,
            ]),

            'ubicacion' => $this->whenLoaded('ubicacion', fn() => [
                'sede'     => $this->ubicacion->sede->nombre ?? null,
                'bloque'   => $this->ubicacion->bloque,
                'ambiente' => $this->ubicacion->ambiente,
            ]),

            'especificaciones' => $this->whenLoaded('especificaciones', fn() => [
                'procesador'      => $this->especificaciones->procesador,
                'ram'             => $this->especificaciones->ram,
                'tipo_disco'      => $this->especificaciones->tipo_disco,
                'capacidad_disco' => $this->especificaciones->capacidad_disco,
                'so'              => $this->especificaciones->so,
                'mac_address'     => $this->especificaciones->mac_address,
            ]),

            // Auditoría
            'creado_en'      => $this->created_at?->toIso8601String(),
            'actualizado_en' => $this->updated_at?->toIso8601String(),
        ];
    }
}
