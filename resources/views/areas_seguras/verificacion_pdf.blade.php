<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
@page { margin: 1cm 0.8cm; }
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, Helvetica, sans-serif; font-size: 7pt; color: #000; }
table { border-collapse: collapse; width: 100%; }

/* ── Cabecera institucional ── */
.hdr td { border: 1px solid #000; vertical-align: middle; }
.hdr .logo-cell { width: 85px; text-align: center; padding: 4px; }
.hdr .logo-cell img { width: 55px; }
.hdr .title-cell { padding: 0; }
.title-row { display: block; background: #1E1E1E; color: #fff;
    text-align: center; font-weight: bold; padding: 2.5px 4px;
    border-bottom: 1px solid #555; }
.title-row.proceso { font-size: 6.5pt; font-weight: normal; }
.title-row.seccion { font-size: 7.5pt; }
.title-row.lbl     { font-size: 6.5pt; font-weight: normal; }
.title-row.nombre  { font-size: 8.5pt; letter-spacing: 0.3px; }
.title-row.clasif  { font-size: 7pt; border-bottom: none; }
.hdr .code-cell { width: 90px; padding: 4px 5px; font-size: 7pt; vertical-align: top; }
.hdr .code-cell .lbl { color: #555; font-size: 6pt; display: block; }
.hdr .code-cell .val { font-weight: bold; font-size: 8pt; display: block; margin-bottom: 4px; }

/* ── Fila clasificación ── */
.clasif-fila td { border: 1px solid #000; border-top: none; padding: 3px 8px; font-size: 7pt; }
.chk { display: inline-block; width: 9px; height: 9px; border: 1px solid #000;
    margin-right: 4px; vertical-align: middle; text-align: center; line-height: 9px; font-size: 7pt; font-weight: bold; }

/* ── Separadores de sección ── */
.seccion-hdr { background: #BFBFBF; font-weight: bold; text-align: center;
    padding: 2.5px 4px; font-size: 7pt; border: 1px solid #000; margin-top: 5px; }

/* ── Tabla de Identificación ── */
.id-table td { border: 1px solid #000; padding: 2.5px 5px; font-size: 7pt; vertical-align: middle; }
.id-table .campo { background: #F0F0F0; font-weight: bold; width: 30%; border-right: 1px solid #000; }
.id-table .detalle { width: 70%; }

/* ── Tabla de controles de acceso ── */
.acceso-table th {
    border: 1px solid #000; background: #D4D4D4;
    padding: 3px 3px; font-size: 6.5pt; font-weight: bold;
    text-align: center; vertical-align: middle;
}
.acceso-table td {
    border: 1px solid #000; padding: 2.5px 3px;
    font-size: 6.5pt; vertical-align: top;
}
.acceso-table .th-control  { width: 22%; }
.acceso-table .th-desc     { width: 48%; }
.acceso-table .th-estado   { width: 12%; text-align: center; }
.acceso-table .th-obs      { width: 18%; }
.codigo { font-weight: bold; color: #333; font-size: 6pt; }

/* ── Tabla de controles medioambientales ── */
.medio-table th {
    border: 1px solid #000; background: #D4D4D4;
    padding: 3px 3px; font-size: 6.5pt; font-weight: bold;
    text-align: center; vertical-align: middle;
}
.medio-table td {
    border: 1px solid #000; padding: 2.5px 3px;
    font-size: 6pt; vertical-align: top;
}
.medio-table .th-area     { width: 13%; }
.medio-table .th-cat      { width: 11%; }
.medio-table .th-nombre   { width: 18%; }
.medio-table .th-desc     { width: 34%; }
.medio-table .th-estado   { width: 10%; text-align: center; }
.medio-table .th-obs      { width: 14%; }
.area-sep td { background: #E8E8E8; font-weight: bold; font-size: 6.5pt; padding: 2px 4px; }

/* ── Estados ── */
.cumple   { background: #D4EDDA; color: #155724; padding: 1px 2px; font-size: 5.5pt; font-weight: bold; }
.parcial  { background: #FFF3CD; color: #856404; padding: 1px 2px; font-size: 5.5pt; font-weight: bold; }
.no-cumple{ background: #F8D7DA; color: #721C24; padding: 1px 2px; font-size: 5.5pt; font-weight: bold; }
.no-aplica{ background: #E2E3E5; color: #383D41; padding: 1px 2px; font-size: 5.5pt; }

/* ── Resumen de cumplimiento ── */
.resumen td { border: 1px solid #000; padding: 2px 4px; font-size: 6.5pt; text-align: center; vertical-align: middle; }
.resumen .lbl { background: #F0F0F0; font-weight: bold; text-align: left; }

/* ── Registro adicional ── */
.registro-hdr { background: #BFBFBF; font-weight: bold; padding: 2.5px 4px;
    font-size: 7pt; border: 1px solid #000; }
.registro-linea td { border: 1px solid #000; border-top: none; padding: 4px 5px; font-size: 6.5pt; height: 14px; }
</style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════════════
     CABECERA INSTITUCIONAL — Réplica exacta GIL-F-102 (página 2)
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
            <span class="val">GIL-F-102</span>
            <span class="lbl">Versión:</span>
            <span class="val">01</span>
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
            <span class="title-row nombre">VERIFICACION DE CONTROLES DE AREAS SEGURAS</span>
        </td>
    </tr>
    <tr>
        <td class="title-cell">
            <span class="title-row clasif">CLASIFICACIÓN DE LA INFORMACIÓN</span>
        </td>
    </tr>
</table>

{{-- Clasificación dle documento --}}
<table class="clasif-fila">
    <tr>
        <td style="width:33%;"><span class="chk">X</span> <strong>Pública</strong></td>
        <td style="width:33%;"><span class="chk"> </span> Pública Clasificada</td>
        <td style="width:34%;"><span class="chk"> </span> Pública Reservada</td>
    </tr>
</table>

{{-- ══════════════════════════════════════════════════════════════
     BLOQUE 1: DATOS DE IDENTIFICACIÓN DEL ÁREA SEGURA
     ══════════════════════════════════════════════════════════════ --}}
<div class="seccion-hdr" style="margin-top:5px;">Datos de Identificación del Área Segura</div>
<table class="id-table" style="border-top:none;">
    <thead>
        <tr>
            <th style="border:1px solid #000; border-top:none; background:#D4D4D4; padding:2px 4px; font-size:6.5pt; width:30%;">Campo</th>
            <th style="border:1px solid #000; border-top:none; background:#D4D4D4; padding:2px 4px; font-size:6.5pt; width:70%;">Detalle de la Inspección</th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td class="campo">Nombre del Área</td>
        <td class="detalle">{{ $area->nombre_dependencia }}</td>
    </tr>
    <tr>
        <td class="campo">Código o ID</td>
        <td class="detalle">{{ $area->codigo }}</td>
    </tr>
    <tr>
        <td class="campo">Ubicación</td>
        <td class="detalle">
            @php
            $ubic = collect([
                $area->bloque         ? 'Bloque '.$area->bloque         : null,
                $area->piso           ? 'Piso '.$area->piso             : null,
                $area->numero_oficina ? 'Oficina '.$area->numero_oficina: null,
            ])->filter()->implode(', ');
            @endphp
            {{ $ubic ?: ($area->perimetro_seguridad ?? '—') }}
        </td>
    </tr>
    <tr>
        <td class="campo">Clasificación del área segura</td>
        <td class="detalle">{{ $area->nivel_sena ?? '—' }}</td>
    </tr>
    <tr>
        <td class="campo">Zonificación del área segura</td>
        <td class="detalle">{{ $area->zonificacion ?? '—' }}</td>
    </tr>
    <tr>
        <td class="campo">Responsable del Área</td>
        <td class="detalle">
            {{ collect([$area->responsable_nombre, $area->responsable_cargo, $area->responsable_contacto])->filter()->implode(' — ') ?: '—' }}
        </td>
    </tr>
    <tr>
        <td class="campo">Fecha de la Inspección</td>
        <td class="detalle">{{ $verificacion->fecha_verificacion?->format('d/m/Y') ?? '—' }}</td>
    </tr>
    <tr>
        <td class="campo">Persona(s) que Realizan la inspección</td>
        <td class="detalle">{{ $verificacion->inspectores ?? '—' }}</td>
    </tr>
    </tbody>
</table>

{{-- ══════════════════════════════════════════════════════════════
     BLOQUE 2: CONTROLES DE ACCESO FÍSICO (15 controles GIL-F-102)
     ══════════════════════════════════════════════════════════════ --}}
<div class="seccion-hdr" style="margin-top:5px;">Controles de Acceso físico</div>
<table class="acceso-table" style="border-top:none;">
    <thead>
        <tr>
            <th class="th-control"  style="border-top:none;">Control</th>
            <th class="th-desc"     style="border-top:none;">Descripción</th>
            <th class="th-estado"   style="border-top:none;">Estado</th>
            <th class="th-obs"      style="border-top:none;">Observación</th>
        </tr>
    </thead>
    <tbody>
    @foreach($controlesAcceso as $item)
    @php
        $estClass = match($item['estado'] ?? '') {
            'CUMPLE'              => 'cumple',
            'CUMPLE PARCIALMENTE' => 'parcial',
            'NO CUMPLE'           => 'no-cumple',
            'NO APLICA'           => 'no-aplica',
            default               => '',
        };
    @endphp
    <tr>
        <td>
            <span class="codigo">{{ $item['codigo'] }}</span><br>
            {{ $item['item'] ?? $item['nombre'] ?? '' }}
        </td>
        <td>{{ $item['descripcion'] ?? '' }}</td>
        <td style="text-align:center;">
            @if($item['estado'] ?? '')
                <span class="{{ $estClass }}">{{ $item['estado'] }}</span>
            @endif
        </td>
        <td>{{ $item['observaciones'] ?? '' }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

{{-- Resumen de cumplimiento (controles de acceso) --}}
@php
$cumpleCount   = collect($controlesAcceso)->where('estado','CUMPLE')->count();
$parcialCount  = collect($controlesAcceso)->where('estado','CUMPLE PARCIALMENTE')->count();
$noCumpleCount = collect($controlesAcceso)->where('estado','NO CUMPLE')->count();
$noAplicaCount = collect($controlesAcceso)->where('estado','NO APLICA')->count();
$totalAplican  = count($controlesAcceso) - $noAplicaCount;
@endphp
<table class="resumen" style="margin-top:3px;">
    <tr>
        <td class="lbl" style="width:30%;">Resumen Controles de Acceso</td>
        <td><span class="cumple">CUMPLE: {{ $cumpleCount }}</span></td>
        <td><span class="parcial">CUMPLE PARCIALMENTE: {{ $parcialCount }}</span></td>
        <td><span class="no-cumple">NO CUMPLE: {{ $noCumpleCount }}</span></td>
        <td><span class="no-aplica">NO APLICA: {{ $noAplicaCount }}</span></td>
        <td>Aplican: {{ $totalAplican }} / {{ count($controlesAcceso) }}</td>
        <td style="font-weight:bold;">
            Resultado:
            <span class="{{ match($verificacion->resultado){
                'Conforme' => 'cumple',
                'No Conforme' => 'no-cumple',
                default => 'parcial'
            } }}">{{ $verificacion->resultado }}</span>
        </td>
    </tr>
</table>

{{-- ══════════════════════════════════════════════════════════════
     BLOQUE 3: CONTROLES MEDIOAMBIENTALES
     ══════════════════════════════════════════════════════════════ --}}
<div class="seccion-hdr" style="margin-top:5px;">Controles medioambientales</div>
<table class="medio-table" style="border-top:none;">
    <thead>
        <tr>
            <th class="th-area"   style="border-top:none;">Área de Aplicación</th>
            <th class="th-cat"    style="border-top:none;">Categoría del Control</th>
            <th class="th-nombre" style="border-top:none;">Nombre del Control</th>
            <th class="th-desc"   style="border-top:none;">Descripción y Especificaciones</th>
            <th class="th-estado" style="border-top:none;">Estado</th>
            <th class="th-obs"    style="border-top:none;">Observación</th>
        </tr>
    </thead>
    <tbody>
    @foreach($controlesMedioambientales as $bloque => $items)
    @php $firstRow = true; $rowCount = count($items); @endphp
    @foreach($items as $item)
    @php
        $estMClass = match($item['estado'] ?? '') {
            'CUMPLE'              => 'cumple',
            'CUMPLE PARCIALMENTE' => 'parcial',
            'NO CUMPLE'           => 'no-cumple',
            'NO APLICA'           => 'no-aplica',
            default               => '',
        };
    @endphp
    <tr>
        @if($firstRow)
        <td rowspan="{{ $rowCount }}" style="border:1px solid #000; font-weight:bold; font-size:6pt; text-align:center; vertical-align:middle; background:#F0F0F0;">
            {{ $bloque }}
        </td>
        @php $firstRow = false; @endphp
        @endif
        <td>{{ $item['categoria'] ?? '' }}</td>
        <td>{{ $item['nombre'] ?? '' }}</td>
        <td>{{ $item['descripcion'] ?? '' }}</td>
        <td style="text-align:center;">
            @if($item['estado'] ?? '')
                <span class="{{ $estMClass }}">{{ $item['estado'] }}</span>
            @endif
        </td>
        <td>{{ $item['observaciones'] ?? '' }}</td>
    </tr>
    @endforeach
    @endforeach
    </tbody>
</table>

{{-- ══════════════════════════════════════════════════════════════
     REGISTRO ADICIONAL
     ══════════════════════════════════════════════════════════════ --}}
<div class="registro-hdr" style="margin-top:5px;">Registro adicional</div>
<table style="border-top:none;">
    <tr class="registro-linea">
        <td colspan="4" style="border:1px solid #000; border-top:none; padding:3px 5px; font-size:6.5pt; color:#555; font-style:italic;">
            Registre cualquier anomalía física o ambiental identificada (ej. filtraciones de agua, daños estructurales, objetos extraños) o cualquier situación atípica dentro del área segura.
        </td>
    </tr>
    <tr class="registro-linea">
        <td colspan="4" style="border:1px solid #000; border-top:none; height:18px; padding:3px 5px; font-size:7pt;">
            {{ $verificacion->observaciones_generales ?? '' }}
        </td>
    </tr>
    <tr class="registro-linea">
        <td colspan="4" style="border:1px solid #000; border-top:none; height:14px;"></td>
    </tr>
    <tr class="registro-linea">
        <td colspan="4" style="border:1px solid #000; border-top:none; height:14px;"></td>
    </tr>
</table>

{{-- Pie ── --}}
<div style="margin-top:6px; font-size:5.5pt; color:#888; display:table; width:100%;">
    <div style="display:table-cell; text-align:left;">
        SENA — Centro Agroindustrial y Fortalecimiento Empresarial | GIL-F-102 Verificación de Controles | Generado: {{ $fecha }}
    </div>
    <div style="display:table-cell; text-align:right;">
        Área: {{ $area->codigo }} — {{ $area->nombre_dependencia }} | Verificación: {{ $verificacion->fecha_verificacion?->format('d/m/Y') }} | Corte: {{ $verificacion->corte }}
    </div>
</div>

</body>
</html>
