<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
@page { margin: 0.7cm 0.8cm; }
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Helvetica', Arial, sans-serif; font-size: 7pt; color: #1a1a1a; }

/* ── Cabecera institucional ── */
.hdr-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
.hdr-table td { border: 1px solid #000; padding: 3px 5px; vertical-align: middle; }
.hdr-logo { width: 55px; text-align: center; }
.hdr-logo img { width: 45px; }
.hdr-titulo { text-align: center; }
.hdr-titulo .titulo { font-size: 9.5pt; font-weight: bold; display: block; }
.hdr-titulo .subtitulo { font-size: 7pt; display: block; margin-top: 1px; }
.hdr-meta { width: 130px; font-size: 6.5pt; }
.hdr-meta .lbl { background: #D9D9D9; font-weight: bold; padding: 1px 4px; }
.hdr-meta .val { padding: 1px 4px; font-weight: bold; }

/* ── Resumen de stats ── */
.stats-row { display: table; width: 100%; margin-bottom: 6px; border-collapse: collapse; }
.stat-box { display: table-cell; border: 1px solid #ccc; padding: 4px 6px; text-align: center; width: 16.6%; }
.stat-box .num { font-size: 13pt; font-weight: bold; display: block; }
.stat-box .lbl { font-size: 6pt; text-transform: uppercase; color: #555; display: block; }
.stat-n1 .num { color: #dc2626; }
.stat-n2 .num { color: #ea580c; }
.stat-n3 .num { color: #2563eb; }
.stat-conf .num { color: #16a34a; }

/* ── Tabla principal ── */
.main-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
.main-table th {
    background: #1e3a5f;
    color: white;
    font-size: 6.5pt;
    font-weight: bold;
    text-transform: uppercase;
    padding: 4px 5px;
    text-align: left;
    border: 1px solid #1e3a5f;
}
.main-table td {
    border: 1px solid #d1d5db;
    padding: 3.5px 5px;
    vertical-align: top;
    font-size: 6.5pt;
}
.main-table tr:nth-child(even) td { background: #f9fafb; }
.main-table tr:last-child td { border-bottom: 1px solid #9ca3af; }

/* Badges de nivel */
.badge { display: inline-block; padding: 1px 5px; border-radius: 3px; font-size: 6pt; font-weight: bold; }
.badge-n1 { background: #fee2e2; color: #991b1b; }
.badge-n2 { background: #ffedd5; color: #9a3412; }
.badge-n3 { background: #dbeafe; color: #1e40af; }
.badge-conf   { background: #dcfce7; color: #166534; }
.badge-noconf { background: #fee2e2; color: #991b1b; }
.badge-obs    { background: #ffedd5; color: #9a3412; }
.badge-sin    { background: #f3f4f6; color: #6b7280; }

/* CIA */
.cia-alto  { color: #dc2626; font-weight: bold; }
.cia-medio { color: #ea580c; font-weight: bold; }
.cia-bajo  { color: #16a34a; font-weight: bold; }

/* Footer */
.footer { margin-top: 8px; font-size: 6pt; color: #9ca3af; display: table; width: 100%; }
.footer-l { display: table-cell; text-align: left; }
.footer-r { display: table-cell; text-align: right; }

/* Separador de nivel en la tabla */
.nivel-sep td {
    background: #374151;
    color: white;
    font-weight: bold;
    font-size: 6.5pt;
    padding: 2px 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>
</head>
<body>

{{-- ── Cabecera institucional ── --}}
<table class="hdr-table">
    <tr>
        <td class="hdr-logo" rowspan="3">
            <img src="{{ public_path('img/logo-sena.png') }}" alt="SENA">
        </td>
        <td class="hdr-titulo" rowspan="3">
            <span class="titulo">Gestión de Tecnologías de la Información y las Comunicaciones</span>
            <span class="subtitulo">CONSOLIDADO DE ÁREAS SEGURAS — ISO 27001:2022 · Controles 7.5 / 7.6</span>
            <span class="subtitulo" style="margin-top:2px;">Centro Agroindustrial y Fortalecimiento Empresarial — Regional Casanare</span>
        </td>
        <td class="hdr-meta">
            <span class="lbl">Fecha de emisión</span>
            <span class="val">{{ $fecha }}</span>
        </td>
    </tr>
    <tr>
        <td class="hdr-meta">
            <span class="lbl">Total áreas</span>
            <span class="val">{{ $stats['total'] }}</span>
        </td>
    </tr>
    <tr>
        <td class="hdr-meta">
            <span class="lbl">Código documento</span>
            <span class="val">GTIC-ISO-AS-001</span>
        </td>
    </tr>
</table>

{{-- ── Stats resumen ── --}}
<div class="stats-row">
    <div class="stat-box">
        <span class="num">{{ $stats['total'] }}</span>
        <span class="lbl">Total Áreas</span>
    </div>
    <div class="stat-box stat-n1">
        <span class="num">{{ $stats['nivel1'] }}</span>
        <span class="lbl">Nivel 1 · Crítico</span>
    </div>
    <div class="stat-box stat-n2">
        <span class="num">{{ $stats['nivel2'] }}</span>
        <span class="lbl">Nivel 2 · Sensible</span>
    </div>
    <div class="stat-box stat-n3">
        <span class="num">{{ $stats['nivel3'] }}</span>
        <span class="lbl">Nivel 3 · Operativo</span>
    </div>
    <div class="stat-box">
        <span class="num">{{ $stats['con_checklist'] }}</span>
        <span class="lbl">Con Checklist</span>
    </div>
    <div class="stat-box stat-conf">
        <span class="num">{{ $stats['conformes'] }}</span>
        <span class="lbl">Conformes</span>
    </div>
</div>

{{-- ── Tabla principal ── --}}
@php
$niveles = ['Nivel 1 - Crítico','Nivel 2 - Sensible','Nivel 3 - Operativo'];
$areasPorNivel = $areas->groupBy('nivel_sena');
@endphp

<table class="main-table">
    <thead>
        <tr>
            <th style="width:6%">Código</th>
            <th style="width:18%">Dependencia / Área</th>
            <th style="width:8%">Sede</th>
            <th style="width:10%">Nivel SENA</th>
            <th style="width:5%">CIA</th>
            <th style="width:8%">Ubicación</th>
            <th style="width:7%">Horario</th>
            <th style="width:14%">Controles de Acceso</th>
            <th style="width:11%">Responsable / Cargo</th>
            <th style="width:7%">Última Verif.</th>
            <th style="width:6%">Resultado</th>
        </tr>
    </thead>
    <tbody>
    @foreach($niveles as $nivel)
    @if(isset($areasPorNivel[$nivel]) && $areasPorNivel[$nivel]->count())
        <tr class="nivel-sep">
            <td colspan="11">
                @php
                $icono = match($nivel) {
                    'Nivel 1 - Crítico'   => '▲',
                    'Nivel 2 - Sensible'  => '●',
                    'Nivel 3 - Operativo' => '◆',
                    default               => '—'
                };
                @endphp
                {{ $icono }} {{ $nivel }} — {{ $areasPorNivel[$nivel]->count() }} área(s)
            </td>
        </tr>
        @foreach($areasPorNivel[$nivel] as $area)
        @php
            $v = $area->ultimaVerificacion;
            $badgeNivel = match($nivel) {
                'Nivel 1 - Crítico'   => 'badge-n1',
                'Nivel 2 - Sensible'  => 'badge-n2',
                'Nivel 3 - Operativo' => 'badge-n3',
                default               => ''
            };
            $badgeRes = match($v?->resultado) {
                'Conforme'                   => 'badge-conf',
                'No Conforme'                => 'badge-noconf',
                'Conforme con Observaciones' => 'badge-obs',
                default                      => 'badge-sin'
            };
            $ciaClass = match($area->nivel_criticidad) {
                'Alto'  => 'cia-alto',
                'Medio' => 'cia-medio',
                default => 'cia-bajo'
            };
            $controles = is_array($area->controles_acceso)
                ? implode(', ', $area->controles_acceso)
                : ($area->controles_acceso ?? '—');
            $ubicacion = collect([
                $area->bloque     ? 'Blq. '.$area->bloque : null,
                $area->piso       ? 'Piso '.$area->piso   : null,
                $area->numero_oficina ? 'Of. '.$area->numero_oficina : null,
            ])->filter()->implode(' · ') ?: '—';
        @endphp
        <tr>
            <td><strong>{{ $area->codigo }}</strong></td>
            <td>
                {{ $area->nombre_dependencia }}
                @if($area->tipo_area)
                    <br><span style="color:#9ca3af;font-size:6pt;">{{ $area->tipo_area }}</span>
                @endif
            </td>
            <td>{{ $area->sede->nombre ?? '—' }}</td>
            <td><span class="badge {{ $badgeNivel }}">{{ $nivel }}</span></td>
            <td class="{{ $ciaClass }}">{{ $area->nivel_criticidad }}</td>
            <td>{{ $ubicacion }}</td>
            <td>{{ $area->horario_acceso ?? '—' }}</td>
            <td style="font-size:6pt;">{{ $controles }}</td>
            <td style="font-size:6pt;">{{ $area->responsable_cargo ?? '—' }}</td>
            <td>
                @if($v)
                    {{ $v->fecha_verificacion->format('d/m/Y') }}<br>
                    <span style="color:#6b7280;font-size:6pt;">{{ $v->total_cumple }}/{{ $v->total_items }} ítems</span>
                @else
                    <span style="color:#9ca3af;">Sin datos</span>
                @endif
            </td>
            <td>
                @if($v)
                    <span class="badge {{ $badgeRes }}">{{ $v->resultado }}</span>
                @else
                    <span class="badge badge-sin">Pendiente</span>
                @endif
            </td>
        </tr>
        @endforeach
    @endif
    @endforeach
    </tbody>
</table>

{{-- ── Footer ── --}}
<div class="footer">
    <div class="footer-l">
        SENA · Gestión TIC · Áreas Seguras ISO 27001:2022 — Documento generado el {{ $fecha }}
    </div>
    <div class="footer-r">
        GTIC-ISO-AS-001 · Uso interno
    </div>
</div>

</body>
</html>
