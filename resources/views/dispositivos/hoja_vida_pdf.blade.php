<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.8cm; }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 7.5pt;
            line-height: 1.3;
            color: #111;
        }

        /* ── Tablas ── */
        table { width: 100%; border-collapse: collapse; margin-bottom: -1px; }
        td, th { border: 1px solid #000; padding: 2px 5px; vertical-align: middle; }

        /* ── Cabeceras de sección ── */
        .sec-header {
            background-color: #2f302f;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
            text-align: center;
            padding: 3px 5px;
        }
        .bg-gray {
            background-color: #D9D9D9;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7pt;
        }
        .col-head {
            background-color: #e5e7eb;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 6.5pt;
            text-align: center;
        }

        /* ── Utilidades ── */
        .text-center { text-align: center; }
        .font-bold   { font-weight: bold; }
        .mono        { font-family: monospace; }
        .small       { font-size: 6.5pt; }
        .mt          { margin-top: 4px; }

        /* ── Badges ── */
        .badge-bueno      { background-color: #d1fae5; color: #065f46; padding: 1px 5px; border-radius: 3px; font-weight: bold; }
        .badge-malo       { background-color: #fee2e2; color: #991b1b; padding: 1px 5px; border-radius: 3px; font-weight: bold; }
        .badge-reparacion { background-color: #fef3c7; color: #92400e; padding: 1px 5px; border-radius: 3px; font-weight: bold; }
        .badge-preventivo { background-color: #d1fae5; color: #065f46; padding: 1px 5px; border-radius: 3px; font-weight: bold; }
        .badge-correctivo { background-color: #fee2e2; color: #991b1b; padding: 1px 5px; border-radius: 3px; font-weight: bold; }
        .badge-si         { background-color: #dbeafe; color: #1e40af; padding: 1px 5px; border-radius: 3px; font-weight: bold; }

        .logo-sena    { width: 50px; }
        .firma-line   { border-top: 1px solid #000; width: 75%; margin: 0 auto; }
        .page-break   { page-break-after: always; }
    </style>
</head>
<body>

{{-- ════════════════════════════════════════════════════════
     ENCABEZADO INSTITUCIONAL
═══════════════════════════════════════════════════════════ --}}
<table>
    <tr>
        <td rowspan="3" style="width:60px;" class="text-center">
            <img src="{{ public_path('img/logo-sena.png') }}" class="logo-sena">
        </td>
        <td rowspan="3" class="text-center">
            <span style="font-size:10pt; font-weight:bold;">Servicio Nacional de Aprendizaje — SENA</span><br>
            <span style="font-size:9pt; font-weight:bold;">HOJA DE VIDA DEL EQUIPO</span><br>
            <span class="small">Gestión de Tecnologías de la Información · Regional Casanare</span>
        </td>
        <td class="bg-gray" style="width:65px;">Placa SENA:</td>
        <td style="width:90px;" class="font-bold mono text-center">{{ $dispositivo->placa }}</td>
    </tr>
    <tr>
        <td class="bg-gray">Categoría:</td>
        <td class="text-center font-bold">{{ $dispositivo->categoria == 'conectividad' ? 'Redes / Infraestructura' : 'Equipo de Cómputo' }}</td>
    </tr>
    <tr>
        <td class="bg-gray">Fecha impresión:</td>
        <td class="text-center">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</td>
    </tr>
</table>

<br>

{{-- ════════════════════════════════════════════════════════
     SECCIÓN 1 — DATOS GENERALES DEL EQUIPO
═══════════════════════════════════════════════════════════ --}}
<div class="sec-header">1. Datos Generales del Equipo</div>
<table>
    <tr>
        <td class="bg-gray" style="width:18%;">MARCA:</td>
        <td style="width:32%;" class="font-bold">{{ $dispositivo->marca }}</td>
        <td class="bg-gray" style="width:18%;">MODELO:</td>
        <td style="width:32%;" class="font-bold">{{ $dispositivo->modelo }}</td>
    </tr>
    <tr>
        <td class="bg-gray">SERIAL:</td>
        <td class="mono font-bold">{{ $dispositivo->serial }}</td>
        <td class="bg-gray">PLACA SENA:</td>
        <td class="mono font-bold">{{ $dispositivo->placa }}</td>
    </tr>
    <tr>
        <td class="bg-gray">ESTADO FÍSICO:</td>
        <td>
            @php $ef = $dispositivo->estado_fisico; @endphp
            @if($ef == 'BUENO')
                <span class="badge-bueno">{{ $ef }}</span>
            @elseif($ef == 'EN REPARACIÓN')
                <span class="badge-reparacion">{{ $ef }}</span>
            @else
                <span class="badge-malo">{{ $ef }}</span>
            @endif
        </td>
        <td class="bg-gray">ESTADO LÓGICO:</td>
        <td>{{ $dispositivo->estado_logico ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="bg-gray">PROPIETARIO:</td>
        <td>{{ $dispositivo->propietario ?? 'SENA' }}</td>
        <td class="bg-gray">FUNCIÓN:</td>
        <td>{{ $dispositivo->funcion ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="bg-gray">EN INTUNE:</td>
        <td>
            @if($dispositivo->en_intune == 'SI')
                <span class="badge-si">SI</span>
            @else
                NO
            @endif
        </td>
        <td class="bg-gray">FECHA REGISTRO:</td>
        <td>{{ $dispositivo->created_at->format('d/m/Y') }}</td>
    </tr>
    @if($dispositivo->observaciones)
    <tr>
        <td class="bg-gray">OBSERVACIONES:</td>
        <td colspan="3" style="vertical-align:top;">{{ $dispositivo->observaciones }}</td>
    </tr>
    @endif
</table>

<br>

{{-- ════════════════════════════════════════════════════════
     SECCIÓN 2 — UBICACIÓN
═══════════════════════════════════════════════════════════ --}}
<div class="sec-header">2. Ubicación</div>
<table>
    <tr>
        <td class="bg-gray" style="width:18%;">SEDE / REGIONAL:</td>
        <td style="width:32%;">{{ $dispositivo->ubicacion->sede->nombre }}</td>
        <td class="bg-gray" style="width:18%;">BLOQUE:</td>
        <td style="width:32%;">{{ $dispositivo->ubicacion->bloque ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="bg-gray">AMBIENTE:</td>
        <td colspan="3">{{ $dispositivo->ubicacion->ambiente }}</td>
    </tr>
</table>

<br>

{{-- ════════════════════════════════════════════════════════
     SECCIÓN 3 — RESPONSABLE ASIGNADO
═══════════════════════════════════════════════════════════ --}}
<div class="sec-header">3. Responsable Asignado</div>
<table>
    <tr>
        <td class="bg-gray" style="width:18%;">NOMBRE:</td>
        <td style="width:32%;" class="font-bold">{{ $dispositivo->responsable->nombre }}</td>
        <td class="bg-gray" style="width:18%;">CÉDULA:</td>
        <td style="width:32%;" class="mono">{{ $dispositivo->responsable->cedula }}</td>
    </tr>
    <tr>
        <td class="bg-gray">CARGO:</td>
        <td>{{ $dispositivo->responsable->cargo ?? 'N/A' }}</td>
        <td class="bg-gray">TIPO:</td>
        <td>{{ $dispositivo->responsable->tipo_funcionario ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="bg-gray">DEPENDENCIA:</td>
        <td>{{ $dispositivo->responsable->dependencia ?? 'N/A' }}</td>
        <td class="bg-gray">CORREO:</td>
        <td class="small">{{ $dispositivo->responsable->correo_institucional ?? 'N/A' }}</td>
    </tr>
    @if($dispositivo->responsable->numero_de_celular)
    <tr>
        <td class="bg-gray">CELULAR:</td>
        <td colspan="3">{{ $dispositivo->responsable->numero_de_celular }}</td>
    </tr>
    @endif
</table>

<br>

{{-- ════════════════════════════════════════════════════════
     SECCIÓN 4 — ESPECIFICACIONES TÉCNICAS
═══════════════════════════════════════════════════════════ --}}
<div class="sec-header">4. Especificaciones Técnicas</div>
@if($dispositivo->categoria == 'conectividad')
<table>
    <tr>
        <td class="bg-gray" style="width:25%;">No. PUERTOS:</td>
        <td style="width:25%;">{{ $dispositivo->puertos ?? 'N/A' }}</td>
        <td class="bg-gray" style="width:25%;">MAC ADDRESS:</td>
        <td style="width:25%;" class="mono">{{ $dispositivo->mac_address ?? 'N/A' }}</td>
    </tr>
    @if($dispositivo->ap_conectado_a)
    <tr>
        <td class="bg-gray">AP CONECTADO A:</td>
        <td>{{ $dispositivo->ap_conectado_a }}</td>
        <td class="bg-gray">PUERTO ORIGEN:</td>
        <td>{{ $dispositivo->puerto_origen ?? 'N/A' }}</td>
    </tr>
    @endif
    @if($dispositivo->descripcion_tecnica)
    <tr>
        <td class="bg-gray">DESC. TÉCNICA:</td>
        <td colspan="3">{{ $dispositivo->descripcion_tecnica }}</td>
    </tr>
    @endif
</table>
@else
<table>
    <tr>
        <td class="bg-gray" style="width:25%;">PROCESADOR:</td>
        <td colspan="3">{{ $dispositivo->especificaciones->procesador ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="bg-gray">MEMORIA RAM:</td>
        <td style="width:25%;">{{ $dispositivo->especificaciones->ram ?? 'N/A' }}</td>
        <td class="bg-gray" style="width:25%;">TIPO DISCO:</td>
        <td style="width:25%;">{{ $dispositivo->especificaciones->tipo_disco ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="bg-gray">CAPACIDAD DISCO:</td>
        <td>{{ $dispositivo->especificaciones->capacidad_disco ?? 'N/A' }}</td>
        <td class="bg-gray">SISTEMA OPERATIVO:</td>
        <td>{{ $dispositivo->especificaciones->so ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="bg-gray">MAC ADDRESS:</td>
        <td colspan="3" class="mono">{{ $dispositivo->especificaciones->mac_address ?? 'N/A' }}</td>
    </tr>
</table>
@endif

<br>

{{-- ════════════════════════════════════════════════════════
     SECCIÓN 5 — ACCESORIOS / PERIFÉRICOS
═══════════════════════════════════════════════════════════ --}}
<div class="sec-header">5. Accesorios y Periféricos</div>
@if($dispositivo->perifericos->count() > 0)
<table>
    <tr>
        <th class="col-head" style="width:20%;">Tipo</th>
        <th class="col-head" style="width:20%;">Marca</th>
        <th class="col-head" style="width:20%;">Modelo</th>
        <th class="col-head" style="width:20%;">Serial</th>
        <th class="col-head" style="width:20%;">Placa SENA</th>
    </tr>
    @foreach($dispositivo->perifericos as $p)
    <tr>
        <td class="bg-gray text-center">{{ strtoupper($p->tipo) }}</td>
        <td>{{ $p->marca ?? 'N/A' }}</td>
        <td>{{ $p->modelo ?? 'N/A' }}</td>
        <td class="mono small">{{ $p->serial ?? 'N/A' }}</td>
        <td class="mono">{{ $p->placa ?? 'N/A' }}</td>
    </tr>
    @endforeach
</table>
@else
<table>
    <tr><td class="text-center" style="padding:6px; color:#666; font-style:italic;">No se han registrado accesorios para este equipo.</td></tr>
</table>
@endif

<br>

{{-- ════════════════════════════════════════════════════════
     SECCIÓN 6 — HISTORIAL DE MANTENIMIENTOS
═══════════════════════════════════════════════════════════ --}}
<div class="sec-header">6. Historial de Mantenimientos</div>
@php $mantenimientos = $dispositivo->mantenimientos->sortByDesc('fecha'); @endphp
@if($mantenimientos->count() > 0)
<table>
    <tr>
        <th class="col-head" style="width:12%;">Fecha</th>
        <th class="col-head" style="width:14%;">Tipo</th>
        <th class="col-head" style="width:22%;">Técnico</th>
        <th class="col-head" style="width:40%;">Tareas Realizadas</th>
        <th class="col-head" style="width:12%;">Estado</th>
    </tr>
    @foreach($mantenimientos as $m)
    <tr>
        <td class="text-center">{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y') }}</td>
        <td class="text-center">
            @if($m->tipo == 'Correctivo')
                <span class="badge-correctivo">Correctivo</span>
            @else
                <span class="badge-preventivo">Preventivo</span>
            @endif
        </td>
        <td>{{ $m->tecnico_encargado }}</td>
        <td class="small" style="vertical-align:top;">{!! nl2br(e($m->tareas_realizadas)) !!}</td>
        <td class="text-center">
            @if($m->finalizado)
                <span class="badge-si">Finalizado</span>
            @else
                <span class="badge-reparacion">En Proceso</span>
            @endif
        </td>
    </tr>
    @if($m->observaciones)
    <tr>
        <td class="bg-gray text-center" colspan="2" style="font-size:6pt;">OBS:</td>
        <td colspan="3" class="small" style="font-style:italic;">{{ $m->observaciones }}</td>
    </tr>
    @endif
    @endforeach
</table>
@else
<table>
    <tr><td class="text-center" style="padding:6px; color:#666; font-style:italic;">Sin registros de mantenimiento.</td></tr>
</table>
@endif

<br>

{{-- ════════════════════════════════════════════════════════
     SECCIÓN 7 — CONCEPTOS TÉCNICOS GTI-F-132
═══════════════════════════════════════════════════════════ --}}
<div class="sec-header">7. Historial de Conceptos Técnicos GTI-F-132</div>
@php $conceptos = ($dispositivo->conceptos ?? collect())->sortByDesc('fecha_reporte'); @endphp
@if($conceptos->count() > 0)
<table>
    <tr>
        <th class="col-head" style="width:12%;">Fecha</th>
        <th class="col-head" style="width:20%;">Trámite</th>
        <th class="col-head" style="width:18%;">Flujo</th>
        <th class="col-head" style="width:18%;">INC / WO</th>
        <th class="col-head" style="width:32%;">Técnico N2</th>
    </tr>
    @foreach($conceptos as $c)
    <tr>
        <td class="text-center">{{ $c->fecha_reporte->format('d/m/Y') }}</td>
        <td class="text-center font-bold">{{ $c->concepto_tipo }}</td>
        <td class="small">{{ $c->flujo_solicitud }}</td>
        <td class="mono small text-center">{{ $c->num_incidente ?? 'N/A' }} / {{ $c->num_requerimiento ?? 'N/A' }}</td>
        <td>{{ $c->tecnico_nombre }}</td>
    </tr>
    @if($c->diagnostico_tecnico)
    <tr>
        <td class="bg-gray" colspan="2" style="font-size:6pt;">DIAGNÓSTICO:</td>
        <td colspan="3" class="small" style="vertical-align:top; font-family:monospace;">{!! nl2br(e(\Illuminate\Support\Str::limit($c->diagnostico_tecnico, 200))) !!}</td>
    </tr>
    @endif
    @endforeach
</table>
@else
<table>
    <tr><td class="text-center" style="padding:6px; color:#666; font-style:italic;">No se han generado conceptos técnicos para este equipo.</td></tr>
</table>
@endif

<br>

{{-- ════════════════════════════════════════════════════════
     PIE DE PÁGINA — AUDITORÍA Y FIRMAS
═══════════════════════════════════════════════════════════ --}}
<table style="margin-top:6px; border:none;">
    <tr>
        <td style="border:none; font-size:6pt; color:#555;">
            Registrado por: <strong>{{ $dispositivo->creador->name ?? 'Sistema/Masivo' }}</strong>
            @if($dispositivo->editor)
                &nbsp;·&nbsp; Última modificación: <strong>{{ $dispositivo->editor->name }}</strong>
                ({{ $dispositivo->updated_at->format('d/m/Y') }})
            @endif
        </td>
        <td style="border:none; text-align:right; font-size:6pt; color:#555;">
            Impreso el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        </td>
    </tr>
</table>

<table style="border:none; margin-top:35px;">
    <tr>
        <td style="border:none; text-align:center; width:50%;">
            <div class="firma-line"></div>
            <span class="font-bold">Técnico GTI Responsable</span><br>
            <span class="small">Gestión de Tecnologías de la Información</span>
        </td>
        <td style="border:none; text-align:center; width:50%;">
            <div class="firma-line"></div>
            <span class="font-bold">{{ $dispositivo->responsable->nombre }}</span><br>
            <span class="small">{{ $dispositivo->responsable->cargo ?? 'Responsable del Equipo' }}</span>
        </td>
    </tr>
</table>

</body>
</html>
