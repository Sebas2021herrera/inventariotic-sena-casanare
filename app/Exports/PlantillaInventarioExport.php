<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PlantillaInventarioExport implements WithHeadings, ShouldAutoSize
{
    /**
     * Definimos los encabezados exactos que tu importador procesa
     */
    public function headings(): array
    {
        return [
           
            'cedula',
            'nombre_del_responsable',
            'numero_de_celular',
            'correo_institucional',
            'dependencia',
            'cargo',
            'tipo_de_funcionario',
            'sede_de_ubicacion_del_equipo',
            'bloque',
            'ambiente_de_formacion',
             'placa',
            'serial',
            'marca',
            'modelo',
            'procesador',
            'memoria_ram',
            'tipo_de_disco_duro',
            'capacidad_de_disco_duro',
            'direccion_mac_del_pc_no_de_red',
            'so',
            'estado_fisico',
            'estado_logico',
            'novedades_u_observaciones'
        ];
    }
}