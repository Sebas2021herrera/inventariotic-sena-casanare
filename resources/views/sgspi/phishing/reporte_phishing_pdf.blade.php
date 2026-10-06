<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
@page { margin: 1cm 0.8cm; size: A4 landscape; }
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, Helvetica, sans-serif; font-size: 7.5pt; color: #000; }
table { border-collapse: collapse; width: 100%; }

/* ── Cabecera institucional ── */
.hdr td { border: 1px solid #000; vertical-align: middle; }
.hdr .logo-cell { width: 80px; text-align: center; padding: 4px; }
.hdr .logo-cell img { width: 55px; }
.hdr .title-cell { padding: 0; }
.title-row { display: block; background: #1E1E1E; color: #fff;
    text-align: center; font-weight: bold; padding: 2.5px 4px;
    border-bottom: 1px solid #555; }
.title-row.proceso  { font-size: 6pt; font-weight: normal; }
.title-row.seccion  { font-size: 7pt; }
.title-row.nombre   { font-size: 8.5pt; letter-spacing: 0.3px; }
.title-row.last     { font-size: 7.5pt; border-bottom: none; }
.hdr .code-cell { width: 90px; padding: 4px 5px; font-size: 7pt; vertical-align: top; }
.hdr .code-cell .lbl { color: #555; font-size: 5.5pt; display: block; }
.hdr .code-cell .val { font-weight: bold; font-size: 8pt; display: block; margin-bottom: 4px; }

/* ── Metadatos ── */
.meta-box { margin-top: 5px; border: 1px solid #000; }
.meta-box table td { border: 1px solid #ccc; padding: 3px 6px; font-size: 7pt; }
.meta-box .lbl { background: #F0F0F0; font-weight: bold; width: 18%; }

/* ── Stats ── */
.stats { margin-top: 5px; }
.stats td { border: 1px solid #000; padding: 5px 8px; text-align: center; }
.stats .num { font-size: 14pt; font-weight: bold; display: block; }
.stats .cap { font-size: 6pt; font-weight: bold; color: #666; text-transform: uppercase; }

/* ── Tabla de resultados ── */
.data { margin-top: 6px; }
.data th {
    border: 1px solid #000; background: #1e3a5f; color: #fff;
    text-align: center; padding: 3px 3px;
    font-size: 6pt; font-weight: bold; vertical-align: middle;
}
.data td {
    border: 1px solid #ccc; padding: 2.5px 3.5px;
    font-size: 6.5pt; vertical-align: middle;
}
.data tr:nth-child(even) td { background: #f5f7ff; }
.num-col { text-align: center; }
.pts { text-align: center; font-weight: bold; color: #1e3a5f; }
.bonus-col { text-align: center; color: #856404; font-weight: bold; }

/* Nivel badges */
.n1 { background: #D1FAE5; color: #065F46; padding: 1px 4px; }
.n2 { background: #DBEAFE; color: #1E40AF; padding: 1px 4px; }
.n3 { background: #FEF3C7; color: #92400E; padding: 1px 4px; }
.n4 { background: #FEE2E2; color: #991B1B; padding: 1px 4px; }

.pct-b { color: #1a6600; font-weight: bold; }
.pct-m { color: #0055aa; font-weight: bold; }
.pct-r { color: #aa2200; font-weight: bold; }

/* ── Pie ── */
.footer { margin-top: 8px; font-size: 6pt; color: #777; text-align: right; }
</style>
</head>
<body>

{{-- ══ CABECERA INSTITUCIONAL ══ --}}
<table class="hdr">
    <tr>
        <td class="logo-cell" rowspan="4">
            <img src="{{ public_path('img/logo-sena.png') }}" alt="SENA">
        </td>
        <td class="title-cell">
            <span class="title-row proceso">PROCESO GESTIÓN DE TI — SGSPI</span>
        </td>
        <td class="code-cell" rowspan="4">
            <span class="lbl">Código:</span>
            <span class="val">SGSPI-R-02</span>
            <span class="lbl">Versión:</span>
            <span class="val">01</span>
            <span class="lbl">Fecha:</span>
            <span class="val">{{ now()->format('d/m/Y') }}</span>
        </td>
    </tr>
    <tr>
        <td class="title-cell">
            <span class="title-row seccion">SISTEMA DE GESTIÓN DE SEGURIDAD DE LA INFORMACIÓN</span>
        </td>
    </tr>
    <tr>
        <td class="title-cell">
            <span class="title-row nombre">INFORME DE RESULTADOS — DETECTOR DE PHISHING</span>
        </td>
    </tr>
    <tr>
        <td class="title-cell">
            <span class="title-row last">SENA — Regional Casanare · Centro de Desarrollo Agroempresarial y Turístico del Casanare</span>
        </td>
    </tr>
</table>

{{-- ══ METADATOS ══ --}}
<div class="meta-box">
    <table>
        <tr>
            <td class="lbl">Fecha generación</td>
            <td>{{ now()->format('d/m/Y H:i') }}</td>
            <td class="lbl">Generado por</td>
            <td>{{ auth()->user()->name ?? 'Administrador' }}</td>
            <td class="lbl">Escenarios / partida</td>
            <td>{{ $config->escenarios }}</td>
            <td class="lbl">Total registros</td>
            <td>{{ $resultados->count() }}</td>
        </tr>
    </table>
</div>

{{-- ══ ESTADÍSTICAS ══ --}}
<table class="stats">
    <tr>
        <td style="width:20%">
            <span class="num" style="color:#1e3a5f">{{ $stats['total'] }}</span>
            <span class="cap">Partidas jugadas</span>
        </td>
        <td style="width:20%">
            <span class="num" style="color:#39A900">{{ $stats['prom_score'] }}</span>
            <span class="cap">Puntaje promedio</span>
        </td>
        <td style="width:20%">
            <span class="num" style="color:#0055aa">{{ $stats['prom_pct'] }}%</span>
            <span class="cap">Aciertos promedio</span>
        </td>
        <td style="width:20%">
            <span class="num" style="color:#856404">{{ $stats['total_bonus'] }}</span>
            <span class="cap">Bonus otorgados (total)</span>
        </td>
        <td style="width:20%">
            <span class="num" style="color:#1e3a5f">{{ $stats['mejor_score'] }}</span>
            <span class="cap">Mejor puntaje</span>
        </td>
    </tr>
</table>

{{-- ══ TABLA DE RESULTADOS ══ --}}
<table class="data">
    <thead>
        <tr>
            <th style="width:3%">#</th>
            <th style="width:16%">Participante</th>
            <th style="width:10%">Documento</th>
            <th style="width:18%">Área / Dependencia</th>
            <th style="width:7%">Puntaje</th>
            <th style="width:9%">Correctas</th>
            <th style="width:7%">% Acierto</th>
            <th style="width:7%">Bonus</th>
            <th style="width:7%">Nivel</th>
            <th style="width:16%">Fecha y hora</th>
        </tr>
    </thead>
    <tbody>
        @forelse($resultados as $i => $r)
        @php
            $pct = $r->total > 0 ? round(($r->correctas / $r->total) * 100) : 0;
            $pctClass = $pct >= 90 ? 'pct-b' : ($pct >= 60 ? 'pct-m' : 'pct-r');
            $nivelClass = ['', 'n1', 'n2', 'n3', 'n4'][$r->nivel_alcanzado] ?? 'n1';
            $nivelLabels = ['', 'Fácil', 'Medio', 'Difícil', 'Avanzado'];
        @endphp
        <tr>
            <td class="num-col">{{ $i + 1 }}</td>
            <td>{{ $r->participante->nombre }}</td>
            <td class="num-col" style="font-family:monospace">{{ $r->participante->documento }}</td>
            <td>{{ $r->participante->area }}</td>
            <td class="pts">{{ $r->puntaje }}</td>
            <td class="num-col">{{ $r->correctas }} / {{ $r->total }}</td>
            <td class="num-col {{ $pctClass }}">{{ $pct }}%</td>
            <td class="bonus-col">{{ $r->bonus > 0 ? '+'.($r->bonus*5).' pts' : '—' }}</td>
            <td class="num-col">
                <span class="{{ $nivelClass }}">N{{ $r->nivel_alcanzado }} · {{ $nivelLabels[$r->nivel_alcanzado] ?? '' }}</span>
            </td>
            <td class="num-col">{{ $r->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="10" style="text-align:center;padding:10px;color:#999">Sin resultados registrados.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<p class="footer">
    SENA — Regional Casanare &nbsp;·&nbsp; Documento generado automáticamente el {{ now()->format('d/m/Y \a \l\a\s H:i') }} &nbsp;·&nbsp; SGSPI v1
</p>

</body>
</html>
