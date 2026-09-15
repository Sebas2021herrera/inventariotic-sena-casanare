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
    // Los datos empiezan en A5 (filas 1-3 son la cabecera institucional, fila 4 encabezados)
    public function startCell(): string
    {
        return 'A5';
    }

    public function collection()
    {
        return AreaSegura::with(['ultimaVerificacion', 'sede'])
            ->orderByRaw("CASE nivel_sena
                WHEN 'Nivel 1 - Crítico'   THEN 1
                WHEN 'Nivel 2 - Sensible'  THEN 2
                WHEN 'Nivel 3 - Operativo' THEN 3
                ELSE 4 END")
            ->orderBy('codigo')
            ->get()
            ->map(function ($a) {
                $v = $a->ultimaVerificacion;
                $controles = is_array($a->controles_acceso)
                    ? implode(', ', $a->controles_acceso)
                    : ($a->controles_acceso ?? '');
                $ubicacion = collect([
                    $a->bloque         ? 'Blq. '.$a->bloque          : null,
                    $a->piso           ? 'Piso '.$a->piso             : null,
                    $a->numero_oficina ? 'Of. '.$a->numero_oficina    : null,
                ])->filter()->implode(' · ');

                return [
                    $a->codigo,
                    $a->nombre_dependencia,
                    $a->sede->nombre ?? '—',
                    $a->nivel_sena,
                    $a->nivel_criticidad,
                    $a->tipo_area ?? '—',
                    $ubicacion ?: '—',
                    $a->horario_acceso ?? '—',
                    $controles ?: '—',
                    $a->perimetro_seguridad ?? '—',
                    $a->responsable_cargo ?? '—',
                    $v ? $v->fecha_verificacion->format('d/m/Y') : '—',
                    $v ? $v->resultado                           : 'Pendiente',
                    $v ? "{$v->total_cumple}/{$v->total_items}"  : '—',
                    $v ? ($v->observaciones_generales ?? '')      : '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Código',
            'Dependencia / Área',
            'Sede',
            'Nivel SENA',
            'CIA (Criticidad)',
            'Tipo de Área',
            'Ubicación',
            'Horario de Acceso',
            'Controles de Acceso',
            'Perímetro de Seguridad',
            'Responsable / Cargo',
            'Última Verificación',
            'Resultado',
            'Cumplimiento',
            'Observaciones',
        ];
    }

    public function title(): string
    {
        return 'Áreas Seguras';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // ── Cabecera institucional ──────────────────────────────────────
                $sheet->mergeCells('A1:O1');
                $sheet->setCellValue('A1', 'SENA — Centro Agroindustrial y Fortalecimiento Empresarial · Regional Casanare');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1E3A5F']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(24);

                $sheet->mergeCells('A2:O2');
                $sheet->setCellValue('A2', 'CONSOLIDADO DE ÁREAS SEGURAS — ISO 27001:2022 · Controles 7.5 / 7.6 · Generado: ' . now()->format('d/m/Y H:i'));
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '39A900']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(16);

                $sheet->getRowDimension(3)->setRowHeight(5);

                // ── Encabezados de columna (fila 4) ────────────────────────────
                $sheet->getStyle('A4:O4')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '374151']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '4B5563']]],
                ]);
                $sheet->getRowDimension(4)->setRowHeight(32);

                // ── Datos ───────────────────────────────────────────────────────
                if ($lastRow >= 5) {
                    $sheet->getStyle("A5:O{$lastRow}")->applyFromArray([
                        'font'      => ['size' => 8.5],
                        'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
                        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                    ]);

                    // Franjas alternadas
                    for ($r = 5; $r <= $lastRow; $r++) {
                        if ($r % 2 === 0) {
                            $sheet->getStyle("A{$r}:O{$r}")->applyFromArray([
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F3F4F6']],
                            ]);
                        }
                    }

                    // Código: negrita + centrado
                    $sheet->getStyle("A5:A{$lastRow}")->applyFromArray([
                        'font'      => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    // Sede + CIA + Horario + Última verif + Cumplimiento: centrado
                    foreach (['C', 'E', 'H', 'L', 'N'] as $col) {
                        $sheet->getStyle("{$col}5:{$col}{$lastRow}")->applyFromArray([
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);
                    }
                }

                // ── Anchos de columna ───────────────────────────────────────────
                $widths = [
                    'A' => 12, 'B' => 34, 'C' => 14, 'D' => 22, 'E' => 13,
                    'F' => 18, 'G' => 18, 'H' => 14, 'I' => 32, 'J' => 30,
                    'K' => 30, 'L' => 14, 'M' => 24, 'N' => 13, 'O' => 42,
                ];
                foreach ($widths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // Freeze pane: bloquear cabecera al hacer scroll
                $sheet->freezePane('A5');
            },
        ];
    }
}
