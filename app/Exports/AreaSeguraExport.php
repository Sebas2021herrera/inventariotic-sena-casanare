<?php

namespace App\Exports;

use App\Models\AreaSegura;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AreaSeguraExport implements
    FromCollection,
    WithHeadings,
    WithTitle,
    WithCustomStartCell,
    WithEvents
{
    // Los datos empiezan en A6 (filas 1-3 cabecera, fila 4 espacio, fila 5 encabezados)
    public function startCell(): string
    {
        return 'A6';
    }

    public function collection()
    {
        return AreaSegura::with(['ultimaVerificacion', 'sede'])
            ->orderByRaw("CASE nivel_sena
                WHEN 'Nivel 3' THEN 1
                WHEN 'Nivel 2' THEN 2
                WHEN 'Nivel 1' THEN 3
                ELSE 4 END")
            ->orderBy('codigo')
            ->get()
            ->map(function ($a) {
                $v          = $a->ultimaVerificacion;
                $acceso     = is_array($a->controles_acceso)    ? implode(', ', $a->controles_acceso)    : ($a->controles_acceso    ?? '');
                $monitoreo  = is_array($a->controles_monitoreo) ? implode(', ', $a->controles_monitoreo) : ($a->controles_monitoreo ?? '');
                $ubicacion  = collect([
                    $a->bloque         ? 'Blq. '.$a->bloque           : null,
                    $a->piso           ? 'Piso '.$a->piso              : null,
                    $a->numero_oficina ? 'Ofic. '.$a->numero_oficina   : null,
                ])->filter()->implode(', ');

                // GIL-F-101 campo 9: Histórico de cambios
                $historico = '';
                if ($a->historico_cambios) {
                    $historico = collect($a->historico_cambios)
                        ->map(fn($h) => "[{$h['fecha']}] {$h['cambio']}")
                        ->implode(' | ');
                }

                return [
                    // GIL-F-101 campos
                    $a->codigo,                                                     // 1. ID de Zona
                    $a->fecha_inventario?->format('d/m/Y') ?? '—',                 // 2. Fecha Inventario
                    $a->nombre_dependencia,                                         // 3. Nombre Área Segura
                    $ubicacion ?: '—',                                              // 4. Ubicación
                    collect([$a->responsable_nombre, $a->responsable_cargo, $a->responsable_contacto])->filter()->implode(' · '), // 5. Responsable
                    $acceso   ?: '—',                                               // 6. Controles Acceso
                    $monitoreo ?: '—',                                              // 7. Controles Monitoreo
                    $a->estado_area ?? ($a->activa ? 'Operativo' : 'Inactivo'),    // 8. Estado
                    $historico ?: '—',                                              // 9. Actualizaciones/Cambios
                    $a->descripcion ?? '—',                                         // 10. Observaciones
                    // Campos adicionales
                    $a->nivel_sena,
                    $a->zonificacion ?? '—',
                    $a->clasificacion_informacion ?? '—',
                    $a->sede->nombre ?? '—',
                    $v ? $v->fecha_verificacion->format('d/m/Y') : '—',
                    $v ? $v->resultado                            : 'Pendiente',
                    $v ? "{$v->total_cumple}/{$v->total_items}"  : '—',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID de Zona',                  // 1
            'Fecha de Inventario',          // 2
            'Nombre del Área Segura',       // 3
            'Ubicación del Área',           // 4
            'Responsable del Área',         // 5
            'Controles de Acceso',          // 6
            'Controles de Monitoreo',       // 7
            'Estado',                       // 8
            'Actualizaciones / Cambios',    // 9
            'Observaciones',                // 10
            'Clasificación (Nivel)',
            'Zonificación',
            'Clasif. Información',
            'Sede',
            'Última Verificación',
            'Resultado Verif.',
            'Cumplimiento',
        ];
    }

    public function title(): string
    {
        return 'GIL-F-101';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastCol = 'Q'; // 17 columnas (A a Q)

                // ── Fila 1: Nombre institucional ────────────────────────────
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->setCellValue('A1', 'SERVICIO NACIONAL DE APRENDIZAJE — SENA');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1E3A5F']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(20);

                // ── Fila 2: Nombre del formato ──────────────────────────────
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->setCellValue('A2', 'GIL-F-101 — INVENTARIO DE ÁREAS SEGURAS | Centro Agroindustrial y Fortalecimiento Empresarial · Regional Casanare');
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '39A900']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(18);

                // ── Fila 3: Metadatos del archivo ───────────────────────────
                $sheet->mergeCells('A3:I3');
                $sheet->setCellValue('A3', 'Lineamiento GIL-G-027 | Controles ISO/IEC 27001:2022 — A.7.1 a A.7.9 | Generado: ' . now()->format('d/m/Y H:i'));
                $sheet->getStyle('A3')->applyFromArray([
                    'font'      => ['size' => 8, 'color' => ['rgb' => '4B5563'], 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->mergeCells("J3:{$lastCol}3");
                $sheet->setCellValue('J3', 'Clasificación: Pública · GIL-F-101 v2026');
                $sheet->getStyle("J3:{$lastCol}3")->applyFromArray([
                    'font'      => ['size' => 8, 'bold' => true, 'color' => ['rgb' => '1E3A5F']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);
                $sheet->getRowDimension(3)->setRowHeight(14);

                // ── Fila 4: Campos GIL-F-101 (sublabels) ───────────────────
                $gilLabels = [
                    'A' => 'Campo 1', 'B' => 'Campo 2', 'C' => 'Campo 3', 'D' => 'Campo 4',
                    'E' => 'Campo 5', 'F' => 'Campo 6', 'G' => 'Campo 7', 'H' => 'Campo 8',
                    'I' => 'Campo 9', 'J' => 'Campo 10',
                    'K' => 'Nivel', 'L' => 'Zona', 'M' => 'Clasif.Info',
                    'N' => 'Sede', 'O' => 'F-102 Fecha', 'P' => 'F-102 Resultado', 'Q' => 'F-102 Cumpli.',
                ];
                foreach ($gilLabels as $col => $label) {
                    $sheet->setCellValue("{$col}4", $label);
                }
                $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
                    'font'      => ['size' => 7, 'bold' => true, 'color' => ['rgb' => '9CA3AF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F9FAFB']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(4)->setRowHeight(11);

                // ── Fila 5: Encabezados principales ────────────────────────
                $sheet->getStyle("A5:{$lastCol}5")->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 8.5, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '374151']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '4B5563']]],
                ]);
                $sheet->getRowDimension(5)->setRowHeight(28);

                // ── Datos ───────────────────────────────────────────────────
                if ($lastRow >= 6) {
                    $sheet->getStyle("A6:{$lastCol}{$lastRow}")->applyFromArray([
                        'font'      => ['size' => 8],
                        'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
                        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                    ]);

                    // Franjas alternadas
                    for ($r = 6; $r <= $lastRow; $r++) {
                        if ($r % 2 === 0) {
                            $sheet->getStyle("A{$r}:{$lastCol}{$r}")->applyFromArray([
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F3F4F6']],
                            ]);
                        }
                    }

                    // ID de Zona: negrita + centrado
                    $sheet->getStyle("A6:A{$lastRow}")->applyFromArray([
                        'font'      => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                    // Fecha: centrado
                    foreach (['B', 'O'] as $col) {
                        $sheet->getStyle("{$col}6:{$col}{$lastRow}")->applyFromArray([
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);
                    }
                    // Nivel, Zona, Clasificación, Sede: centrado
                    foreach (['K', 'L', 'M', 'N'] as $col) {
                        $sheet->getStyle("{$col}6:{$col}{$lastRow}")->applyFromArray([
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);
                    }
                }

                // ── Anchos de columna ───────────────────────────────────────
                $widths = [
                    'A' => 11, 'B' => 13, 'C' => 28, 'D' => 20, 'E' => 30,
                    'F' => 32, 'G' => 28, 'H' => 14, 'I' => 38, 'J' => 36,
                    'K' => 10, 'L' => 20, 'M' => 18, 'N' => 14,
                    'O' => 13, 'P' => 22, 'Q' => 12,
                ];
                foreach ($widths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                $sheet->freezePane('A6');
            },
        ];
    }
}
