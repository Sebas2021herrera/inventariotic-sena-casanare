<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponsableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cedula'           => $this->cedula,
            'nombre'           => $this->nombre,
            'cargo'            => $this->cargo,
            'dependencia'      => $this->dependencia,
            'tipo_funcionario' => $this->tipo_funcionario,
            'correo'           => $this->correo_institucional,
            'total_equipos'    => $this->whenCounted('dispositivos'),
        ];
    }
}
