<?php

namespace App\Exports;

use App\Models\Dispositivo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventarioGeneralExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * Consulta base con relaciones para optimizar velocidad
     */
    public function query()
    {
        return Dispositivo::with(['responsable', 'ubicacion', 'especificaciones', 'perifericos']);
    }

    /**
     * Encabezados exactos según tu requerimiento (42 columnas)
     */
    public function headings(): array
    {
        return [
            'placa', 'serial', 'marca', 'modelo', 'propietario', 'funcion', 'en_intune',
            'cedula', 'nombre_del_responsable', 'numero_de_celular', 'correo_institucional', 'dependencia', 'cargo', 'tipo_de_funcionario',
            'sede_de_ubicacion_del_equipo', 'bloque', 'ambiente_de_formacion',
            'placa_monitor', 'marca_monitor', 'modelo_monitor', 'serial_monitor',
            'placa_teclado', 'marca_teclado', 'modelo_teclado', 'serial_teclado',
            'placa_mouse', 'marca_mouse', 'modelo_mouse', 'serial_mouse',
            'placa_cargador', 'marca_cargador', 'modelo_cargador', 'serial_cargador',
            'procesador', 'memoria_ram', 'so', 'tipo_de_disco_duro', 'capacidad_de_disco_duro', 'direccion_mac_del_pc_no_de_red',
            'estado_fisico', 'estado_logico', 'novedades_u_observaciones'
        ];
    }

    /**
     * Mapeo de datos columna por columna
     */
    public function map($d): array
    {
        // Función interna para buscar periféricos por tipo
        $getP = function($tipo) use ($d) {
            return $d->perifericos->where('tipo', $tipo)->first();
        };

        $mon = $getP('Monitor');
        $tec = $getP('Teclado');
        $mou = $getP('Mouse');
        $car = $getP('Cargador');

        return [
            // Identificación y Clasificación
            $d->placa,
            $d->serial,
            $d->marca,
            $d->modelo,
            $d->propietario,
            $d->funcion,
            $d->en_intune,

            // Responsable
            $d->responsable->cedula,
            $d->responsable->nombre,
            $d->responsable->numero_de_celular,
            $d->responsable->correo ?? 'N/A',
            $d->responsable->dependencia,
            $d->responsable->cargo,
            $d->responsable->tipo_funcionario,

            // Ubicación
            $d->ubicacion->sede,
            $d->ubicacion->bloque,
            $d->ubicacion->ambiente,

            // Periféricos: Monitor
            $mon->placa ?? '', $mon->marca ?? '', $mon->modelo ?? '', $mon->serial ?? '',
            // Periféricos: Teclado
            $tec->placa ?? '', $tec->marca ?? '', $tec->modelo ?? '', $tec->serial ?? '',
            // Periféricos: Mouse
            $mou->placa ?? '', $mou->marca ?? '', $mou->modelo ?? '', $mou->serial ?? '',
            // Periféricos: Cargador
            $car->placa ?? '', $car->marca ?? '', $car->modelo ?? '', $car->serial ?? '',

            // Especificaciones Técnicas
            $d->especificaciones->procesador ?? '',
            $d->especificaciones->ram ?? '',
            $d->especificaciones->so ?? '',
            $d->especificaciones->tipo_disco ?? '',
            $d->especificaciones->capacidad_disco ?? '',
            $d->especificaciones->mac_address ?? '',

            // Estados y Observaciones
            $d->estado_fisico,
            $d->estado_logico ?? 'Bueno',
            $d->observaciones
        ];
    }
}