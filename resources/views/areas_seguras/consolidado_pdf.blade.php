<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
@page { margin: 1cm 0.8cm; }
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, Helvetica, sans-serif; font-size: 7pt; color: #000; }
table { border-collapse: collapse; width: 100%; }

/* ── Bloque de cabecera GIL-F-101 ── */
.hdr td { border: 1px solid #000; vertical-align: middle; }
.hdr .logo-cell { width: 90px; text-align: center; padding: 4px; }
.hdr .logo-cell img { width: 60px; }
.hdr .title-cell { padding: 0; }
.title-row { display: block; background: #1E1E1E; color: #fff;
    text-align: center; font-weight: bold; padding: 2.5px 4px;
    border-bottom: 1px solid #555; }
.title-row.proceso  { font-size: 6.5pt; font-weight: normal; }
.title-row.seccion  { font-size: 7.5pt; }
.title-row.lbl      { font-size: 6.5pt; font-weight: normal; }
.title-row.nombre   { font-size: 9pt; letter-spacing: 0.5px; }
.title-row.clasif   { font-size: 7pt; border-bottom: none; }
.hdr .code-cell { width: 95px; padding: 4px 5px; font-size: 7pt; vertical-align: top; }
.hdr .code-cell .lbl { color: #555; font-size: 6pt; display: block; }
.hdr .code-cell .val { font-weight: bold; font-size: 8pt; display: block; margin-bottom: 4px; }

/* ── Fila de clasificación de la información ── */
.clasif-fila td { border: 1px solid #000; border-top: none; padding: 3px 8px; font-size: 7pt; }
.chk { display: inline-block; width: 9px; height: 9px; border: 1px solid #000;
    margin-right: 4px; vertical-align: middle; text-align: center; line-height: 9px; font-size: 7pt; font-weight: bold; }

/* ── Filas de metadatos ── */
.meta td { border: 1px solid #000; border-top: none; padding: 2.5px 5px; font-size: 7pt; }
.meta .lbl { background: #F0F0F0; font-weight: bold; width: 38%; border-right: 1px solid #000; }
.meta .fecha-lbl { width: 30%; }
.meta .fecha-val { text-align: center; font-weight: bold; }

/* ── Tabla de datos (10 columnas GIL-F-101) ── */
.data-table { margin-top: 5px; }
.data-table th {
    border: 1px solid #000; background: #D4D4D4;
    text-align: center; padding: 3px 3px;
    font-size: 6pt; font-weight: bold; vertical-align: middle;
}
.data-table td {
    border: 1px solid #000; padding: 2.5px 3px;
    font-size: 6pt; vertical-align: top;
}
.data-table tr.fila-nivel td {
    background: #3D3D3D; color: #fff; font-weight: bold;
    font-size: 6.5pt; padding: 2px 4px;
}
.data-table tr:nth-child(even) td { background: #F7F7F7; }
.data-table tr.fila-nivel:nth-child(even) td { background: #3D3D3D; }

/* Estado badges en tabla */
.est-c  { background: #D4EDDA; color: #155724; padding: 1px 3px; }
.est-nc { background: #F8D7DA; color: #721C24; padding: 1px 3px; }
.est-co { background: #FFF3CD; color: #856404; padding: 1px 3px; }
.est-p  { background: #E2E3E5; color: #383D41; padding: 1px 3px; }
</style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════════════
     CABECERA INSTITUCIONAL — Réplica exacta GIL-F-101 (página 2)
     ══════════════════════════════════════════════════════════════ --}}
<table class="hdr">
    <tr>
        <td class="logo-cell" rowspan="5">
            <img src="{{ public_path('img/logo-sena.png') }}" alt="SENA">
        </td>
        <td class="title-cell">
            <span class="title-row proceso">PROCESO</span>
        </td>
        <td class="code-cell" rowspan="5">
            <span class="lbl">Código:</span>
            <span class="val">GIL-F-101</span>
            <span class="lbl">Versión:</span>
            <span class="val">02</span>
        </td>
    </tr>
    <tr>
        <td class="title-cell">
            <span class="title-row seccion">GESTIÓN DE INFRAESTRUCTURA Y LOGÍSTICA</span>
        </td>
    </tr>
    <tr>
        <td class="title-cell">
            <span class="title-row lbl">NOMBRE DEL FORMATO</span>
        </td>
    </tr>
    <tr>
        <td class="title-cell">
            <span class="title-row nombre">INVENTARIO DE ÁREAS SEGURAS</span>
        </td>
    </tr>
    <tr>
        <td class="title-cell">
            <span class="title-row clasif">CLASIFICACIÓN DE LA INFORMACIÓN</span>
        </td>
    </tr>
</table>

{{-- Clasificación de la información --}}
@php
$clasifDoc = 'Pública'; // El formato consolidado es público
@endphp
<table class="clasif-fila">
    <tr>
        <td style="width:33%;">
            <span class="chk">{{ $clasifDoc === 'Pública' ? 'X' : '' }}</span> <strong>Pública</strong>
        </td>
        <td style="width:33%;">
            <span class="chk">{{ $clasifDoc === 'Pública Clasificada' ? 'X' : '' }}</span> Pública Clasificada
        </td>
        <td style="width:34%;">
            <span class="chk">{{ $clasifDoc === 'Pública Reservada' ? 'X' : '' }}</span> Pública Reservada
        </td>
    </tr>
</table>

{{-- Metadatos: Sede, Responsable, Fecha --}}
<table class="meta">
    <tr>
        <td class="lbl">Nombre de la Sede (Dirección General/Regional/Centro de Formación)</td>
        <td>Centro Agroindustrial y Fortalecimiento Empresarial — SENA Regional Casanare</td>
    </tr>
    <tr>
        <td class="lbl">Persona que realiza el inventario (Nombre, Rol, Dependencia)</td>
        <td>{{ auth()->user()->name ?? 'Sistema' }} — Gestión de Infraestructura y Logística</td>
    </tr>
    <tr>
        <td class="lbl fecha-lbl" style="width:30%; text-align:center;">Fecha de Diligenciamiento</td>
        <td class="fecha-val">{{ $fecha }}</td>
    </tr>
</table>

{{-- ══════════════════════════════════════════════════════════════
     TABLA DE DATOS — 10 columnas GIL-F-101
     ══════════════════════════════════════════════════════════════ --}}
<table class="data-table">
    <thead>
        <tr>
            <th style="width:6%">ID de Zona</th>
            <th style="width:7%">Fecha de Inventario</th>
            <th style="width:13%">Nombre de Área Segura</th>
            <th style="width:9%">Ubicación Área Segura</th>
            <th style="width:12%">Responsable del Área</th>
            <th style="width:13%">Controles de Acceso</th>
            <th style="width:11%">Controles de Monitoreo</th>
            <th style="width:7%">Estado</th>
            <th style="width:12%">En caso de actualización o modificación indique que cambios se realizaron</th>
            <th style="width:10%">Observaciones</th>
        </tr>
    </thead>
    <tbody>
    @php
    $niveles       = ['Nivel 3','Nivel 2','Nivel 1'];
    $porNivel      = $areas->groupBy('nivel_sena');
    $nivelLabel    = [
        'Nivel 3' => 'NIVEL 3 — Alta Seguridad (Data Centers, Archivos Centrales, SOC/NOC)',
        'Nivel 2' => 'NIVEL 2 — Acceso Restringido (Cuartos de Telecomunicaciones, MDF/IDF)',
        'Nivel 1' => 'NIVEL 1 — Acceso Controlado (Oficinas Administrativas, Salas de Reuniones)',
    ];
    @endphp

    @foreach($niveles as $nivel)
    @if(($porNivel[$nivel] ?? collect())->count())
        <tr class="fila-nivel">
            <td colspan="10">{{ $nivelLabel[$nivel] ?? $nivel }} &nbsp;·&nbsp; {{ $porNivel[$nivel]->count() }} área(s)</td>
        </tr>
        @foreach($porNivel[$nivel] as $area)
        @php
            $v        = $area->ultimaVerificacion;
            $acceso   = is_array($area->controles_acceso)    ? implode(', ', $area->controles_acceso)    : ($area->controles_acceso    ?? '—');
            $monitor  = is_array($area->controles_monitoreo) ? implode(', ', $area->controles_monitoreo) : ($area->controles_monitoreo ?? '—');
            $ubic     = collect([
                $area->bloque         ? 'Bloque ' . $area->bloque         : null,
                $area->piso           ? 'Piso ' . $area->piso             : null,
                $area->numero_oficina ? 'Ofic. ' . $area->numero_oficina  : null,
            ])->filter()->implode(', ') ?: '—';
            $resp     = collect([$area->responsable_nombre, $area->responsable_cargo, $area->responsable_contacto])->filter()->implode(', ') ?: '—';
            $historico = '';
            if ($area->historico_cambios) {
                $historico = collect($area->historico_cambios)
                    ->map(fn($h) => "[{$h['fecha']}] {$h['cambio']}")
                    ->implode('; ');
            }
            $estado = $area->estado_area ?? ($area->activa ? 'Operativo' : 'Inactivo');
            $estClass = match($v?->resultado) {
                'Conforme'                   => 'est-c',
                'No Conforme'                => 'est-nc',
                'Conforme con Observaciones' => 'est-co',
                default                      => 'est-p',
            };
        @endphp
        <tr>
            <td style="font-weight:bold;">{{ $area->codigo }}</td>
            <td style="text-align:center;">{{ $area->fecha_inventario?->format('d/m/Y') ?? '—' }}</td>
            <td>
                <strong>{{ $area->nombre_dependencia }}</strong>
                @if($area->tipo_area)<br><em>{{ $area->tipo_area }}</em>@endif
            </td>
            <td>{{ $ubic }}</td>
            <td>{{ $resp }}</td>
            <td>{{ $acceso ?: '—' }}</td>
            <td>{{ $monitor ?: '—' }}</td>
            <td>
                {{ $estado }}
                @if($v)<br><span class="{{ $estClass }}">{{ $v->resultado }}</span>@endif
            </td>
            <td>{{ $historico ?: '—' }}</td>
            <td>{{ $area->descripcion ?? '—' }}</td>
        </tr>
        @endforeach
    @endif
    @endforeach

    @if($areas->isEmpty())
    <tr>
        <td colspan="10" style="text-align:center; padding:10px; color:#888;">
            Sin áreas registradas en el inventario.
        </td>
    </tr>
    @endif
    </tbody>
</table>

{{-- Pie de página --}}
<div style="margin-top:8px; font-size:5.5pt; color:#888; display:table; width:100%;">
    <div style="display:table-cell; text-align:left;">
        SENA — Centro Agroindustrial y Fortalecimiento Empresarial | GIL-F-101 Inventario de Áreas Seguras | Generado: {{ $fecha }}
    </div>
    <div style="display:table-cell; text-align:right;">
        Lineamiento GIL-G-027 · Total: {{ $stats['total'] }} área(s) | N3:{{ $stats['nivel3'] }} · N2:{{ $stats['nivel2'] }} · N1:{{ $stats['nivel1'] }}
    </div>
</div>

</body>
</html>
