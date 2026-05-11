<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 0.7cm 0.8cm; size: letter landscape; }

    body {
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 7.5pt;
        color: #111;
        line-height: 1.3;
    }

    table { width: 100%; border-collapse: collapse; }
    td, th { border: 1px solid #bbb; padding: 2px 5px; vertical-align: middle; }

    .hdr-inst { background: #1a1a1a; color: #fff; font-weight: bold;
                text-align: center; font-size: 8pt; padding: 3px 5px; }
    .hdr-green { background: #39A900; color: #fff; font-weight: bold;
                 font-size: 8pt; padding: 3px 6px; }
    .bg-gray { background: #e5e7eb; font-weight: bold;
               text-transform: uppercase; font-size: 7pt; }
    .col-hd { background: #374151; color: #fff; font-weight: bold;
              text-transform: uppercase; font-size: 6pt; text-align: center; }

    /* KPIs */
    .kpi { border: 2px solid #39A900; text-align: center; padding: 5px 4px; }
    .kpi-n { font-size: 16pt; font-weight: bold; color: #39A900; line-height: 1; }
    .kpi-l { font-size: 6pt; color: #555; text-transform: uppercase; font-weight: bold; }

    /* Badges */
    .badge { padding: 1px 4px; border-radius: 2px; font-size: 6pt; font-weight: bold; }
    .b-bueno   { background: #d1fae5; color: #065f46; }
    .b-regular { background: #fef3c7; color: #92400e; }
    .b-malo    { background: #fee2e2; color: #991b1b; }
    .b-rep     { background: #fde68a; color: #78350f; }
    .b-intune  { background: #dbeafe; color: #1e40af; }
    .b-cat     { background: #ede9fe; color: #5b21b6; }
    .b-red     { background: #cffafe; color: #164e63; }

    .mono   { font-family: 'Courier New', monospace; }
    .bold   { font-weight: bold; }
    .muted  { color: #6b7280; }
    .tc     { text-align: center; }
    .small  { font-size: 6pt; }
    .logo   { width: 45px; }
    .nb     { border: none !important; }
    .firma  { border-top: 1px solid #000; width: 80%; margin: 0 auto; margin-top: 35px; }

    tr.fila:nth-child(even) td { background: #f9fafb; }
</style>
</head>
<body>

{{-- ══ ENCABEZADO ══════════════════════════════════════════════════════════════ --}}
<table style="margin-bottom:8px;">
    <tr>
        <td rowspan="3" style="width:58px;" class="tc">
            <img src="{{ public_path('img/logo-sena.png') }}" class="logo">
        </td>
        <td rowspan="3" class="tc">
            <div style="font-size:10pt; font-weight:bold;">SENA — Servicio Nacional de Aprendizaje</div>
            <div style="font-size:9pt; font-weight:bold; color:#39A900; margin-top:2px;">
                ACTA DE EQUIPOS ASIGNADOS
            </div>
            <div style="font-size:7pt; color:#666; margin-top:2px;">
                Gestión de Tecnologías de la Información · Regional Casanare
            </div>
        </td>
        <td class="bg-gray" style="width:90px;">Código:</td>
        <td style="width:90px;" class="bold tc">GTI-INV-ASG</td>
    </tr>
    <tr>
        <td class="bg-gray">Fecha:</td>
        <td class="bold tc mono">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td class="bg-gray">Generado por:</td>
        <td class="small tc">{{ auth()->user()->name }}</td>
    </tr>
</table>

{{-- ══ DATOS DEL RESPONSABLE ═══════════════════════════════════════════════════ --}}
<div class="hdr-inst" style="margin-bottom:0;">1. DATOS DEL RESPONSABLE / FUNCIONARIO</div>
<table style="margin-bottom:8px;">
    <tr>
        <td class="bg-gray" style="width:16%;">NOMBRE COMPLETO:</td>
        <td style="width:34%;" class="bold" style="font-size:8.5pt;">{{ $responsable->nombre }}</td>
        <td class="bg-gray" style="width:16%;">CÉDULA / ID:</td>
        <td style="width:34%;" class="mono bold">{{ $responsable->cedula }}</td>
    </tr>
    <tr>
        <td class="bg-gray">CARGO:</td>
        <td>{{ $responsable->cargo ?? '—' }}</td>
        <td class="bg-gray">TIPO:</td>
        <td>{{ $responsable->tipo_funcionario ?? '—' }}</td>
    </tr>
    <tr>
        <td class="bg-gray">DEPENDENCIA:</td>
        <td>{{ $responsable->dependencia ?? '—' }}</td>
        <td class="bg-gray">CORREO INSTITUCIONAL:</td>
        <td class="small">{{ $responsable->correo_institucional ?? '—' }}</td>
    </tr>
    @if($responsable->numero_de_celular)
    <tr>
        <td class="bg-gray">CELULAR:</td>
        <td colspan="3">{{ $responsable->numero_de_celular }}</td>
    </tr>
    @endif
</table>

{{-- ══ KPIs ════════════════════════════════════════════════════════════════════ --}}
<table style="margin-bottom:8px;">
    <tr>
        <td class="kpi" style="width:20%;">
            <div class="kpi-n">{{ $stats['total'] }}</div>
            <div class="kpi-l">Equipos asignados</div>
        </td>
        <td style="width:1%; border:none;"></td>
        <td class="kpi" style="width:20%;">
            <div class="kpi-n" style="color:#065f46;">{{ $stats['buenos'] }}</div>
            <div class="kpi-l">En estado Bueno</div>
        </td>
        <td style="width:1%; border:none;"></td>
        <td class="kpi" style="width:20%;">
            <div class="kpi-n" style="color:#1e40af;">{{ $stats['en_intune'] }}</div>
            <div class="kpi-l">Enrolados en Intune</div>
        </td>
        <td style="width:2%; border:none;"></td>
        {{-- Por sede --}}
        <td style="width:36%; border:none; vertical-align:top;">
            <table>
                <tr>
                    <th class="col-hd" colspan="2">Equipos por sede</th>
                </tr>
                @foreach($stats['por_sede'] as $sede => $cnt)
                <tr>
                    <td class="small">{{ $sede }}</td>
                    <td class="tc bold small">{{ $cnt }}</td>
                </tr>
                @endforeach
            </table>
        </td>
    </tr>
</table>

{{-- ══ LISTADO DE EQUIPOS ══════════════════════════════════════════════════════ --}}
<div class="hdr-green" style="margin-bottom:0;">
    2. INVENTARIO DE EQUIPOS ASIGNADOS — {{ $stats['total'] }} equipo{{ $stats['total'] !== 1 ? 's' : '' }}
</div>
<table>
    <tr>
        <th class="col-hd" style="width:9%;">Placa SENA</th>
        <th class="col-hd" style="width:10%;">Hostname</th>
        <th class="col-hd" style="width:8%;">Serial</th>
        <th class="col-hd" style="width:14%;">Marca / Modelo</th>
        <th class="col-hd" style="width:8%;">Categoría</th>
        <th class="col-hd" style="width:11%;">Sede</th>
        <th class="col-hd" style="width:7%;">Bloque</th>
        <th class="col-hd" style="width:9%;">Ambiente</th>
        <th class="col-hd" style="width:10%;">Estado Físico</th>
        <th class="col-hd" style="width:5%;">Intune</th>
        <th class="col-hd" style="width:4%;">N°</th>
    </tr>
    @foreach($dispositivos as $i => $d)
    @php
        $badgeD = match($d->estado_fisico) {
            'Bueno'         => 'b-bueno',
            'Regular'       => 'b-regular',
            'Malo'          => 'b-malo',
            'En Reparación' => 'b-rep',
            default         => '',
        };
        $catBadge = $d->categoria === 'conectividad' ? 'b-red' : 'b-cat';
    @endphp
    <tr class="fila">
        <td class="mono bold">{{ $d->placa }}</td>
        <td class="mono muted small">{{ $d->hostname ?? '—' }}</td>
        <td class="mono small muted">{{ $d->serial ?? '—' }}</td>
        <td>
            <span class="bold">{{ $d->marca }}</span>
            <span class="muted small"> {{ $d->modelo }}</span>
        </td>
        <td class="tc"><span class="badge {{ $catBadge }}">{{ ucfirst($d->categoria ?? '—') }}</span></td>
        <td class="small">{{ $d->ubicacion?->sede?->nombre ?? '—' }}</td>
        <td class="small tc">{{ $d->ubicacion?->bloque ?? '—' }}</td>
        <td class="small tc">{{ $d->ubicacion?->ambiente ?? '—' }}</td>
        <td class="tc"><span class="badge {{ $badgeD }}">{{ $d->estado_fisico ?? '—' }}</span></td>
        <td class="tc">
            @if($d->en_intune === 'SI')
                <span class="badge b-intune">SI</span>
            @else
                <span class="muted small">NO</span>
            @endif
        </td>
        <td class="tc muted small">{{ $i + 1 }}</td>
    </tr>
    @endforeach
</table>

{{-- ══ FIRMAS ═══════════════════════════════════════════════════════════════════ --}}
<table style="border:none; margin-top:40px;">
    <tr>
        <td style="border:none; text-align:center; width:33%;">
            <div class="firma"></div>
            <span class="bold">Técnico GTI Responsable</span><br>
            <span class="small">Gestión de Tecnologías de la Información</span>
        </td>
        <td style="border:none; width:4%;"></td>
        <td style="border:none; text-align:center; width:33%;">
            <div class="firma"></div>
            <span class="bold">{{ $responsable->nombre }}</span><br>
            <span class="small">{{ $responsable->cargo ?? 'Funcionario / Responsable' }}</span>
        </td>
        <td style="border:none; width:4%;"></td>
        <td style="border:none; text-align:center; width:26%;">
            <div class="firma"></div>
            <span class="bold">Coordinador / Jefe de Área</span><br>
            <span class="small">Visto Bueno</span>
        </td>
    </tr>
</table>

<table style="border:none; margin-top:8px;">
    <tr>
        <td class="nb small muted">
            Documento generado por GITIC · SENA Regional Casanare · {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        </td>
        <td class="nb small muted" style="text-align:right;">
            Total: {{ $stats['total'] }} equipos asignados a {{ $responsable->nombre }}
        </td>
    </tr>
</table>

</body>
</html>
