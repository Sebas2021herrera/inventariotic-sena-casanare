<?php

namespace App\Exports;

use App\Models\Dispositivo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DispositivoTecnicoExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    public function __construct(
        protected ?int    $tecnico_id,
        protected ?string $estado,
        protected ?string $categoria,
        protected ?string $tipo_equipo,
        protected ?string $sede,
        protected ?string $intune,
        protected ?string $fecha_desde,
        protected ?string $fecha_hasta,
        protected ?string $search,
    ) {}

    public function query()
    {
        $query = Dispositivo::with(['responsable', 'ubicacion.sede', 'creador', 'editor'])
            ->orderBy('created_at', 'desc');

        if ($this->tecnico_id) {
            $query->where('created_by', $this->tecnico_id);
        }
        if ($this->estado) {
            $query->where('estado_fisico', $this->estado);
        }
        if ($this->categoria) {
            $query->where('categoria', $this->categoria);
        }
        if ($this->tipo_equipo) {
            $query->where('tipo_equipo', $this->tipo_equipo);
        }
        if ($this->intune) {
            $query->where('en_intune', $this->intune);
        }
        if ($this->sede) {
            $query->whereHas('ubicacion.sede', fn ($q) => $q->where('nombre', 'LIKE', "%{$this->sede}%"));
        }
        if ($this->fecha_desde) {
            $query->whereDate('created_at', '>=', $this->fecha_desde);
        }
        if ($this->fecha_hasta) {
            $query->whereDate('created_at', '<=', $this->fecha_hasta);
        }
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('placa', 'LIKE', "%{$this->search}%")
                  ->orWhere('serial', 'LIKE', "%{$this->search}%")
                  ->orWhereHas('responsable', fn ($r) => $r->where('nombre', 'LIKE', "%{$this->search}%"));
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Placa', 'Serial', 'Hostname', 'Tipo Equipo', 'Marca', 'Modelo',
            'Categoría', 'Propietario', 'En Intune',
            'Responsable', 'Dependencia', 'Cargo',
            'Sede', 'Bloque', 'Ambiente',
            'Estado Físico', 'Estado Lógico', 'Observaciones',
            'Registrado Por', 'Fecha Registro',
            'Modificado Por', 'Última Modificación',
        ];
    }

    public function map($d): array
    {
        return [
            $d->placa,
            $d->serial,
            $d->hostname ?? '',
            $d->tipo_equipo ?? '',
            $d->marca,
            $d->modelo,
            ucfirst($d->categoria ?? ''),
            $d->propietario ?? '',
            $d->en_intune ?? '',
            $d->responsable->nombre ?? '',
            $d->responsable->dependencia ?? '',
            $d->responsable->cargo ?? '',
            $d->ubicacion->sede->nombre ?? '',
            $d->ubicacion->bloque ?? '',
            $d->ubicacion->ambiente ?? '',
            $d->estado_fisico ?? '',
            $d->estado_logico ?? '',
            $d->observaciones ?? '',
            $d->creador->name ?? 'Sistema',
            $d->created_at ? $d->created_at->format('d/m/Y H:i') : '',
            $d->editor->name ?? '—',
            $d->updated_at ? $d->updated_at->format('d/m/Y H:i') : '',
        ];
    }

    public function title(): string
    {
        return 'Consolidado Técnico';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e3a5f']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
