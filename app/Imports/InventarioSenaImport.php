<?php

namespace App\Imports;

use App\Models\Dispositivo;
use App\Models\Responsable;
use App\Models\Ubicacion;
use App\Models\Especificacion;
use App\Models\Periferico; // Importante asegurar que el modelo esté importado
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InventarioSenaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. VALIDACIÓN ESTRICTA
        if (empty($row['placa']) || empty($row['cedula'])) {
            return null;
        }

        // 2. Gestionar el Responsable
        $responsable = Responsable::updateOrCreate(
            ['cedula' => $row['cedula']],
            [
                'nombre' => trim($row['nombre_del_responsable'] ?? 'Desconocido'),
                'correo_institucional' => trim($row['correo_institucional'] ?? null),
                'numero_de_celular' => trim($row['numero_de_celular'] ?? null),
                'dependencia' => trim($row['dependencia'] ?? 'N/A'),
                'cargo' => trim($row['cargo'] ?? 'N/A'),
                'tipo_funcionario' => trim($row['tipo_de_funcionario'] ?? 'Contratista'),
            ]
        );

        // 3. Gestionar la Ubicación
        $ubicacion = Ubicacion::firstOrCreate([
            'sede' => trim($row['sede_de_ubicacion_del_equipo'] ?? 'Casanare'),
            'bloque' => trim($row['bloque'] ?? 'N/A'),
            'ambiente' => trim($row['ambiente_de_formacion'] ?? 'N/A'),
        ]);

        // 4. Crear o Actualizar el Dispositivo (Incluyendo Propietario, Función e Intune)
        $dispositivo = Dispositivo::updateOrCreate(
            ['placa' => trim($row['placa'])],
            [
                'serial' => trim($row['serial'] ?? 'N/A'),
                'marca' => trim($row['marca'] ?? 'N/A'),
                'modelo' => trim($row['modelo'] ?? 'N/A'),
                'propietario' => strtoupper(trim($row['propietario'] ?? 'SENA')),
                'funcion' => strtoupper(trim($row['funcion'] ?? 'FORMACION')),
                'en_intune' => strtoupper(trim($row['en_intune'] ?? 'NO')),
                'categoria' => 'computo',
                'estado_fisico' => isset($row['estado_fisico']) ? strtoupper(trim($row['estado_fisico'])) : 'N/A',
                'estado_logico' => isset($row['estado_logico']) ? strtoupper(trim($row['estado_logico'])) : 'N/A',
                'observaciones' => trim($row['novedades_u_observaciones'] ?? null),
                'responsable_id' => $responsable->id,
                'ubicacion_id' => $ubicacion->id,
            ]
        );

        // 5. Especificaciones técnicas (Tabla interna)
        Especificacion::updateOrCreate(
            ['dispositivo_id' => $dispositivo->id],
            [
                'procesador' => trim($row['procesador'] ?? 'N/A'),
                'ram' => trim($row['memoria_ram'] ?? 'N/A'),
                'so' => trim($row['so'] ?? 'N/A'),
                'tipo_disco' => trim($row['tipo_de_disco_duro'] ?? 'N/A'),
                'capacidad_disco' => trim($row['capacidad_de_disco_duro'] ?? 'N/A'),
                'mac_address' => trim($row['direccion_mac_del_pc_no_de_red'] ?? 'N/A'),
            ]
        );

        // 6. Gestionar Periféricos (Mapeo exacto con la plantilla de Excel)
        $perifericosMap = [
            'Monitor'  => ['placa' => 'placa_monitor', 'marca' => 'marca_monitor', 'modelo' => 'modelo_monitor', 'serial' => 'serial_monitor'],
            'Teclado'  => ['placa' => 'placa_teclado', 'marca' => 'marca_teclado', 'modelo' => 'modelo_teclado', 'serial' => 'serial_teclado'],
            'Mouse'    => ['placa' => 'placa_mouse',   'marca' => 'marca_mouse',   'modelo' => 'modelo_mouse',   'serial' => 'serial_mouse'],
            'Cargador' => ['placa' => 'placa_cargador','marca' => 'marca_cargador','modelo' => 'modelo_cargador','serial' => 'serial_cargador'],
        ];

        foreach ($perifericosMap as $tipo => $campos) {
            // Verificamos si existe al menos placa o serial en la fila para este tipo de periférico
            if (!empty($row[$campos['placa']]) || !empty($row[$campos['serial']])) {
                Periferico::updateOrCreate(
                    [
                        'dispositivo_id' => $dispositivo->id,
                        'tipo' => $tipo
                    ],
                    [
                        'placa'  => trim($row[$campos['placa']] ?? 'N/A'),
                        'marca'  => trim($row[$campos['marca']] ?? 'N/A'),
                        'modelo' => trim($row[$campos['modelo']] ?? 'N/A'),
                        'serial' => trim($row[$campos['serial']] ?? 'N/A'),
                        'estado' => 'BUENO'
                    ]
                );
            }
        }

        return null;
    }
}