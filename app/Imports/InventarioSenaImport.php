<?php

namespace App\Imports;

use App\Models\Dispositivo;
use App\Models\Responsable;
use App\Models\Ubicacion;
use App\Models\Sede;
use App\Models\Especificacion;
use App\Models\Periferico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Auth;

class InventarioSenaImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function model(array $row)
    {
        // 1. Identificadores Clave (Limpieza de espacios)
        $placa = isset($row['placa']) ? trim($row['placa']) : null;
        $cedula = isset($row['cedula']) ? trim($row['cedula']) : null;

        // Si faltan los datos raíz, ignoramos la fila
        if (empty($placa) || empty($cedula)) {
            return null; 
        }

        // Eliminar límites de tiempo para procesos grandes
        ini_set('max_execution_time', '0');

        try {
            return DB::transaction(function () use ($row, $placa, $cedula) {
                
                // 2. RESPONSABLE (Mapeo de las 7 columnas del Export)
                $responsable = Responsable::updateOrCreate(
                    ['cedula' => $cedula],
                    [
                        'nombre'               => trim($row['nombre_del_responsable'] ?? 'Desconocido'),
                        'numero_de_celular'    => trim($row['numero_de_celular'] ?? null),
                        'correo_institucional' => trim($row['correo_institucional'] ?? null),
                        'dependencia'          => trim($row['dependencia'] ?? 'N/A'),
                        'cargo'                => trim($row['cargo'] ?? 'N/A'),
                        'tipo_funcionario'     => trim($row['tipo_de_funcionario'] ?? 'Contratista'),
                    ]
                );

                // 3. SEDE + UBICACIÓN (normalizado)
                $nombreSede = ucwords(mb_strtolower(trim($row['sede_de_ubicacion_del_equipo'] ?? 'Yopal')));
                $sede = Sede::firstOrCreate(['nombre' => $nombreSede]);

                $ubicacion = Ubicacion::firstOrCreate([
                    'sede_id'  => $sede->id,
                    'bloque'   => mb_strtoupper(trim($row['bloque'] ?? '')),
                    'ambiente' => mb_strtoupper(trim($row['ambiente_de_formacion'] ?? '')),
                ]);

                // 4. TRATAMIENTO DE SERIALES DUPLICADOS (Ej: Xxx, Pendiente, N/A)
                $serialRaw = trim($row['serial'] ?? 'N/A');
                $serialProcesado = in_array(strtoupper($serialRaw), ['X', 'XX', 'XXX', 'N/A', 'PENDIENTE', 'NO TIENE']) 
                    ? $serialRaw . '-' . $placa 
                    : $serialRaw;

                // 5. DISPOSITIVO (Clasificación, Estados y Novedades)
                $dispositivo = Dispositivo::updateOrCreate(
                    ['placa' => $placa],
                    [
                        'serial'        => $serialProcesado,
                        'hostname'      => trim($row['hostname'] ?? '') ?: null,
                        'marca'         => trim($row['marca'] ?? 'N/A'),
                        'modelo'        => trim($row['modelo'] ?? 'N/A'),
                        'propietario'   => strtoupper(trim($row['propietario'] ?? 'SENA')),
                        'funcion'       => strtoupper(trim($row['funcion'] ?? 'FORMACION')),
                        'en_intune'     => strtoupper(trim($row['en_intune'] ?? 'NO')),
                        'categoria'     => 'computo',
                        'estado_fisico' => self::normalizarEstado($row['estado_fisico'] ?? 'Bueno'),
                        'estado_logico' => self::normalizarEstado($row['estado_logico'] ?? 'Bueno'),
                        'observaciones' => trim($row['novedades_u_observaciones'] ?? null),
                        'responsable_id' => $responsable->id,
                        'ubicacion_id'   => $ubicacion->id,
                        'updated_by'     => Auth::id(),
                    ]
                );
                // 👈 3. LÓGICA DE CREADOR: Solo si el registro es nuevo en la BD
                if ($dispositivo->wasRecentlyCreated) {
                    $dispositivo->update(['created_by' => Auth::id()]);
                }

                // 6. ESPECIFICACIONES (Procesador, RAM, SO, Disco, MAC)
                Especificacion::updateOrCreate(
                    ['dispositivo_id' => $dispositivo->id],
                    [
                        'procesador'      => trim($row['procesador'] ?? 'N/A'),
                        'ram'             => trim($row['memoria_ram'] ?? 'N/A'),
                        'so'              => trim($row['so'] ?? 'N/A'),
                        'tipo_disco'      => trim($row['tipo_de_disco_duro'] ?? 'N/A'),
                        'capacidad_disco' => trim($row['capacidad_de_disco_duro'] ?? 'N/A'),
                        'mac_address'     => trim($row['direccion_mac_del_pc_no_de_red'] ?? 'N/A'),
                    ]
                );

                // 7. PERIFÉRICOS (Lógica de 16 columnas: Monitor, Teclado, Mouse, Cargador)
                $this->importarPerifericos($dispositivo->id, $row);

                return $dispositivo;
            });
        } catch (\Exception $e) {
            Log::error("Fila saltada - Placa $placa: " . $e->getMessage());
            return null; // Salta errores de serial duplicado u otros y sigue con el archivo
        }
    }

    /**
     * Mapeo inteligente de periféricos basado en los encabezados del Export
     */
    private function importarPerifericos($dispositivoId, $row)
    {
        $tipos = [
            'Monitor'  => 'monitor',
            'Teclado'  => 'teclado',
            'Mouse'    => 'mouse',
            'Cargador' => 'cargador'
        ];

        foreach ($tipos as $label => $key) {
            $p_placa = trim($row["placa_$key"] ?? '');
            $p_serial = trim($row["serial_$key"] ?? '');

            // Si tiene placa o serial, lo registramos
            if (!empty($p_placa) || !empty($p_serial)) {
                if ($p_placa !== 'NA' && $p_placa !== 'N/A' && $p_placa !== '') {
                    Periferico::updateOrCreate(
                        ['dispositivo_id' => $dispositivoId, 'tipo' => $label],
                        [
                            'placa'  => $p_placa ?: 'N/A',
                            'marca'  => trim($row["marca_$key"] ?? 'N/A'),
                            'modelo' => trim($row["modelo_$key"] ?? 'N/A'),
                            'serial' => $p_serial ?: 'N/A',
                            'estado' => 'Bueno'
                        ]
                    );
                }
            }
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }

    private static function normalizarEstado(string $valor): string
    {
        $mapa = [
            'bueno'          => 'Bueno',
            'regular'        => 'Regular',
            'malo'           => 'Malo',
            'en reparacion'  => 'En Reparación',
            'en reparación'  => 'En Reparación',
        ];
        return $mapa[strtolower(trim($valor))] ?? 'Bueno';
    }
}