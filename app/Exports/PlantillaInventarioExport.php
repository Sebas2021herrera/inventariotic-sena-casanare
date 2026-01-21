<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class PlantillaInventarioExport implements WithHeadings, ShouldAutoSize, WithEvents
{
    public function headings(): array
    {
        return [
            // Identificación y Clasificación
            'placa', 'serial', 'marca', 'modelo',
            'propietario', 'funcion', 'en_intune',
            
            // Responsable
            'cedula', 'nombre_del_responsable', 'numero_de_celular',
            'correo_institucional', 'dependencia', 'cargo',
            'tipo_de_funcionario', 
            
            // Ubicación
            'sede_de_ubicacion_del_equipo', 'bloque', 'ambiente_de_formacion',

            // --- PERIFÉRICOS (Nuevas columnas para el Importador) ---
            'placa_monitor', 'marca_monitor', 'modelo_monitor', 'serial_monitor',
            'placa_teclado', 'marca_teclado', 'modelo_teclado', 'serial_teclado',
            'placa_mouse', 'marca_mouse', 'modelo_mouse', 'serial_mouse',
            'placa_cargador', 'marca_cargador', 'modelo_cargador', 'serial_cargador',
            
            // Especificaciones Técnicas
            'procesador', 'memoria_ram', 'so', 'tipo_de_disco_duro',
            'capacidad_de_disco_duro', 'direccion_mac_del_pc_no_de_red',
            
            // Estado y Finalización
            'estado_fisico', 'estado_logico', 'novedades_u_observaciones'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // NUEVO MAPEO DE COLUMNAS (Letras actualizadas por desplazamiento)
                
                // Propietario (E), Función (F), Intune (G)
                $this->crearValidacion($sheet, 'E', '"SENA,TELEFONICA,OTRO"');
                $this->crearValidacion($sheet, 'F', '"FORMACION,ADMINISTRATIVO"');
                $this->crearValidacion($sheet, 'G', '"SI,NO"');
                
                // Tipo Funcionario (N)
                $this->crearValidacion($sheet, 'N', '"Contratista,Planta,Aprendiz"');
                
                // Sede (O)
                $this->crearValidacion($sheet, 'O', '"YOPAL,PAZ DE ARIPORO,MONTERREY,AGUAZUL,VILLANUEVA"');

                // Estado Físico (AN) y Estado Lógico (AO)
                // Al agregar 16 columnas de periféricos, el estado pasó de la X a la AN
                $this->crearValidacion($sheet, 'AN', '"BUENO,REGULAR,MALO"');
                $this->crearValidacion($sheet, 'AO', '"BUENO,REGULAR,MALO"');
            },
        ];
    }

    /**
     * Función auxiliar para aplicar la validación a las primeras 500 filas
     */
    private function crearValidacion($sheet, $columna, $opciones)
    {
        $validacion = $sheet->getDataValidation($columna . '2:' . $columna . '500');
        $validacion->setType(DataValidation::TYPE_LIST);
        $validacion->setErrorStyle(DataValidation::STYLE_STOP);
        $validacion->setAllowBlank(false);
        $validacion->setShowInputMessage(true);
        $validacion->setShowErrorMessage(true);
        $validacion->setShowDropDown(true);
        $validacion->setErrorTitle('Dato no válido');
        $validacion->setError('Por favor, seleccione una opción de la lista.');
        $validacion->setPromptTitle('Opciones permitidas');
        $validacion->setPrompt('Seleccione un valor de la lista desplegable.');
        $validacion->setFormula1($opciones);
    }
}