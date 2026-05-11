<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 0.7cm 0.8cm; size: letter landscape; }

    body {
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 7pt;
        color: #111;
        line-height: 1.3;
    }

    /* ── Layout general ── */
    table   { width: 100%; border-collapse: collapse; }
    td, th  { border: 1px solid #ccc; padding: 2px 4px; vertical-align: middle; }

    /* ── Cabeceras institucionales ── */
    .hdr-dark  { background: #1a1a1a; color: #fff; font-weight: bold; text-transform: uppercase; font-size: 7.5pt; text-align: center; padding: 3px 5px; }
    .hdr-green { background: #39A900; color: #fff; font-weight: bold; text-transform: uppercase; font-size: 7.5pt; padding: 3px 5px; }
    .bg-gray   { background: #e5e7eb; font-weight: bold; text-transform: uppercase; font-size: 6.5pt; }
    .bg-light  { background: #f3f4f6; }
    .col-head  { background: #374151; color: #fff; font-weight: bold; text-transform: uppercase; font-size: 6pt; text-align: center; }

    /* ── KPI cards ── */
    .kpi-table { margin-bottom: 8px; }
    .kpi-cell  { border: 2px solid #39A900; border-radius: 4px; text-align: center; padding: 6px 4px; width: 25%; }
    .kpi-num   { font-size: 18pt; font-weight: bold; color: #39A900; line-height: 1; }
    .kpi-lbl   { font-size: 6pt; color: #666; text-transform: uppercase; font-weight: bold; margin-top: 2px; }

    /* ── Tablas de resumen ── */
    .sum-table  { margin-bottom: 8px; }
    .sum-table td { font-size: 7pt; }
    .bar-cell   { padding: 1px 3px; }
    .bar-fill   { background: #39A900; height: 8px; border-radius: 2px; min-width: 3px; display: inline-block; }
    .bar-num    { font-weight: bold; color: #374151; margin-left: 4px; font-size: 6.5pt; }

    /* ── Secciones de responsable ── */
    .resp-block        { margin-bottom: 10px; page-break-inside: avoid; }
    .resp-hdr          { background: #1e3a5f; color: #fff; padding: 4px 6px; margin-bottom: 0; }
    .resp-hdr-nombre   { font-size: 8.5pt; font-weight: bold; text-transform: uppercase; }
    .resp-hdr-sub      { font-size: 6pt; opacity: 0.8; margin-top: 1px; }
    .resp-hdr-badge    { background: #39A900; color: #fff; padding: 1px 5px; border-radius: 10px;
                         font-size: 6.5pt; font-weight: bold; float: right; margin-top: 2px; }
    .resp-tabla td     { font-size: 6.5pt; padding: 2px 3px; }
    .resp-tabla tr:nth-child(even) td { background: #f9fafb; }

    /* ── Badges de estado ── */
    .badge         { padding: 1px 4px; border-radius: 2px; font-size: 6pt; font-weight: bold; }
    .badge-bueno   { background: #d1fae5; color: #065f46; }
    .badge-regular { background: #fef3c7; color: #92400e; }
    .badge-malo    { background: #fee2e2; color: #991b1b; }
    .badge-rep     { background: #fde68a; color: #78350f; }
    .badge-intune  { background: #dbeafe; color: #1e40af; }
    .badge-cat     { background: #ede9fe; color: #5b21b6; }
    .badge-red     { background: #cffafe; color: #164e63; }

    /* ── Utilidades ── */
    .mono       { font-family: 'Courier New', monospace; }
    .text-c     { text-align: center; }
    .text-r     { text-align: right; }
    .bold       { font-weight: bold; }
    .small      { font-size: 6pt; }
    .muted      { color: #6b7280; }
    .page-break { page-break-before: always; }
    .logo-sena  { width: 45px; }
    .no-border  { border: none !important; }
</style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════
     ENCABEZADO INSTITUCIONAL
══════════════════════════════════════════════════════ --}}
<table style="margin-bottom:10px;">
    <tr>
        <td rowspan="3" style="width:60px; border:1px solid #ccc;" class="text-c">
            <img src="{{ public_path('img/logo-sena.png') }}" class="logo-sena">
        </td>
        <td rowspan="3" style="border:1px solid #ccc; text-align:center;">
            <div style="font-size:11pt; font-weight:bold;">SENA — Servicio Nacional de Aprendizaje</div>
            <div style="font-size:9pt; font-weight:bold; color:#39A900; margin-top:2px;">
                REPORTE DE EQUIPOS POR RESPONSABLE
            </div>
            <div style="font-size:7pt; color:#666; margin-top:2px;">
                Gestión de Tecnologías de la Información · Regional Casanare
            </div>
        </td>
        <td class="bg-gray" style="width:90px; border:1px solid #ccc;">Fecha generación:</td>
        <td style="width:100px; border:1px solid #ccc;" class="bold text-c mono">{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <td class="bg-gray" style="border:1px solid #ccc;">Generado por:</td>
        <td style="border:1px solid #ccc;" class="bold text-c">{{ auth()->user()->name }}</td>
    </tr>
    <tr>
        <td class="bg-gray" style="border:1px solid #ccc;">Vigencia:</td>
        <td style="border:1px solid #ccc; color:#666;" class="text-c small">{{ \Carbon\Carbon::now()->format('Y') }}</td>
    </tr>
</table>

{{-- ══════════════════════════════════════════════════════
     KPI CARDS
══════════════════════════════════════════════════════ --}}
<table class="kpi-table" style="margin-bottom:10px;">
    <tr>
        <td class="kpi-cell">
            <div class="kpi-num">{{ $totalDispositivos }}</div>
            <div class="kpi-lbl">Total equipos</div>
        </td>
        <td style="width:2%; border:none;"></td>
        <td class="kpi-cell">
            <div class="kpi-num">{{ $totalResponsables }}</div>
            <div class="kpi-lbl">Responsables con equipos</div>
        </td>
        <td style="width:2%; border:none;"></td>
        <td class="kpi-cell">
            <div class="kpi-num">{{ $totalSedes }}</div>
            <div class="kpi-lbl">Sedes regionales</div>
        </td>
        <td style="width:2%; border:none;"></td>
        <td class="kpi-cell">
            <div class="kpi-num">{{ $totalUbicaciones }}</div>
            <div class="kpi-lbl">Ambientes/Ubicaciones</div>
        </td>
    </tr>
</table>

{{-- ══════════════════════════════════════════════════════
     RESUMEN: SEDES + ESTADO + CATEGORÍA
══════════════════════════════════════════════════════ --}}
<table style="margin-bottom:12px;">
    <tr>

        {{-- Por sede --}}
        <td style="width:38%; vertical-align:top; border:none; padding-right:6px;">
            <div class="hdr-dark" style="margin-bottom:0;">Distribución geográfica por sede</div>
            <table class="sum-table">
                <tr>
                    <th class="col-head" style="width:50%;">Sede / Regional</th>
                    <th class="col-head" style="width:30%;">Distribución</th>
                    <th class="col-head" style="width:10%;">Total</th>
                    <th class="col-head" style="width:10%;">%</th>
                </tr>
                @foreach($porSede as $s)
                @php $pct = $totalDispositivos > 0 ? round($s->total / $totalDispositivos * 100, 1) : 0; @endphp
                <tr>
                    <td class="bold">{{ $s->nombre }}</td>
                    <td class="bar-cell">
                        <span class="bar-fill" style="width:{{ min($pct * 1.8, 100) }}px;"></span>
                    </td>
                    <td class="text-c bold">{{ $s->total }}</td>
                    <td class="text-c muted">{{ $pct }}%</td>
                </tr>
                @endforeach
            </table>
        </td>

        <td style="width:2%; border:none;"></td>

        {{-- Por estado --}}
        <td style="width:28%; vertical-align:top; border:none; padding-right:6px;">
            <div class="hdr-dark" style="margin-bottom:0;">Estado físico</div>
            <table class="sum-table">
                <tr>
                    <th class="col-head" style="width:55%;">Estado</th>
                    <th class="col-head" style="width:20%;">Total</th>
                    <th class="col-head" style="width:25%;">%</th>
                </tr>
                @foreach($porEstado as $e)
                @php
                    $pctE = $totalDispositivos > 0 ? round($e->total / $totalDispositivos * 100, 1) : 0;
                    $badgeE = match($e->estado_fisico) {
                        'Bueno'         => 'badge-bueno',
                        'Regular'       => 'badge-regular',
                        'Malo'          => 'badge-malo',
                        'En Reparación' => 'badge-rep',
                        default         => '',
                    };
                @endphp
                <tr>
                    <td><span class="badge {{ $badgeE }}">{{ $e->estado_fisico }}</span></td>
                    <td class="text-c bold">{{ $e->total }}</td>
                    <td class="text-c muted">{{ $pctE }}%</td>
                </tr>
                @endforeach
            </table>
        </td>

        <td style="width:2%; border:none;"></td>

        {{-- Por categoría --}}
        <td style="width:30%; vertical-align:top; border:none;">
            <div class="hdr-dark" style="margin-bottom:0;">Por categoría</div>
            <table class="sum-table">
                <tr>
                    <th class="col-head" style="width:55%;">Categoría</th>
                    <th class="col-head" style="width:20%;">Total</th>
                    <th class="col-head" style="width:25%;">%</th>
                </tr>
                @foreach($porCategoria as $cat)
                @php $pctC = $totalDispositivos > 0 ? round($cat->total / $totalDispositivos * 100, 1) : 0; @endphp
                <tr>
                    <td class="bold" style="text-transform:capitalize;">{{ $cat->categoria ?? 'Sin clasificar' }}</td>
                    <td class="text-c bold">{{ $cat->total }}</td>
                    <td class="text-c muted">{{ $pctC }}%</td>
                </tr>
                @endforeach
            </table>
        </td>

    </tr>
</table>

{{-- ══════════════════════════════════════════════════════
     DETALLE POR RESPONSABLE
══════════════════════════════════════════════════════ --}}
<div class="hdr-green" style="margin-bottom:8px; padding: 4px 8px;">
    DETALLE DE EQUIPOS POR RESPONSABLE — {{ $responsables->count() }} responsables · {{ $totalDispositivos }} equipos
</div>

@foreach($responsables as $resp)
@php
    $devs = $resp->dispositivos;
    $totalResp = $devs->count();
    $buenosResp = $devs->where('estado_fisico','Bueno')->count();
    $intuneResp = $devs->where('en_intune','SI')->count();
@endphp

<div class="resp-block {{ !$loop->first ? '' : '' }}">

    {{-- Cabecera del responsable --}}
    <div class="resp-hdr">
        <span class="resp-hdr-badge">{{ $totalResp }} equipo{{ $totalResp !== 1 ? 's' : '' }}</span>
        <div class="resp-hdr-nombre">{{ $resp->nombre }}</div>
        <div class="resp-hdr-sub">
            CC {{ $resp->cedula }}
            @if($resp->cargo) &nbsp;·&nbsp; {{ $resp->cargo }} @endif
            @if($resp->dependencia) &nbsp;·&nbsp; {{ $resp->dependencia }} @endif
            @if($resp->tipo_funcionario) &nbsp;·&nbsp; {{ $resp->tipo_funcionario }} @endif
            &nbsp;·&nbsp;
            <span style="color:#86efac;">{{ $buenosResp }} buenos</span>
            &nbsp;·&nbsp;
            <span style="color:#93c5fd;">{{ $intuneResp }} en Intune</span>
        </div>
    </div>

    {{-- Tabla de equipos --}}
    <table class="resp-tabla">
        <tr>
            <th class="col-head" style="width:10%;">Placa SENA</th>
            <th class="col-head" style="width:12%;">Hostname</th>
            <th class="col-head" style="width:17%;">Marca / Modelo</th>
            <th class="col-head" style="width:10%;">Categoría</th>
            <th class="col-head" style="width:13%;">Sede</th>
            <th class="col-head" style="width:12%;">Ambiente</th>
            <th class="col-head" style="width:10%;">Estado Físico</th>
            <th class="col-head" style="width:6%;">Intune</th>
        </tr>
        @foreach($devs as $d)
        @php
            $badgeD = match($d->estado_fisico) {
                'Bueno'         => 'badge-bueno',
                'Regular'       => 'badge-regular',
                'Malo'          => 'badge-malo',
                'En Reparación' => 'badge-rep',
                default         => '',
            };
            $catBadge = $d->categoria === 'conectividad' ? 'badge-red' : 'badge-cat';
        @endphp
        <tr>
            <td class="mono bold">{{ $d->placa }}</td>
            <td class="mono muted">{{ $d->hostname ?? '—' }}</td>
            <td>
                <span class="bold">{{ $d->marca }}</span>
                <span class="muted small"> {{ $d->modelo }}</span>
            </td>
            <td class="text-c">
                <span class="badge {{ $catBadge }}">{{ ucfirst($d->categoria ?? '—') }}</span>
            </td>
            <td class="small">{{ $d->ubicacion?->sede?->nombre ?? '—' }}</td>
            <td class="small text-c">{{ $d->ubicacion?->ambiente ?? '—' }}</td>
            <td class="text-c">
                <span class="badge {{ $badgeD }}">{{ $d->estado_fisico ?? '—' }}</span>
            </td>
            <td class="text-c">
                @if($d->en_intune === 'SI')
                    <span class="badge badge-intune">SI</span>
                @else
                    <span class="muted">NO</span>
                @endif
            </td>
        </tr>
        @endforeach
    </table>

</div>
{{-- Salto de página entre responsables grandes --}}
@if(!$loop->last && $totalResp >= 15)
<div class="page-break"></div>
@endif

@endforeach

{{-- Pie de página --}}
<table style="margin-top:10px; border:none;">
    <tr>
        <td class="no-border small muted">
            Documento generado automáticamente por el sistema GITIC · SENA Regional Casanare · {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        </td>
        <td class="no-border small muted text-r">
            Total: {{ $totalDispositivos }} equipos · {{ $responsables->count() }} responsables
        </td>
    </tr>
</table>

</body>
</html>
