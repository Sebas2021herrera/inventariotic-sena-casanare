<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.8cm; }
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 7.5pt; line-height: 1.2; color: #000; }

        table { width: 100%; border-collapse: collapse; margin-bottom: -1px; }
        td { border: 1px solid #000; padding: 2px 5px; vertical-align: middle; }

        .bg-gray  { background-color: #D9D9D9; font-weight: bold; text-transform: uppercase; font-size: 7pt; }
        .bg-green { background-color: #2f302f; color: white; font-weight: bold; text-align: center; font-size: 8.5pt; padding: 3px; }

        .text-center { text-align: center; }
        .font-bold   { font-weight: bold; }

        .badge-preventivo { background-color: #d1fae5; color: #065f46; padding: 1px 6px; border-radius: 4px; font-weight: bold; font-size: 7pt; }
        .badge-correctivo { background-color: #fee2e2; color: #991b1b; padding: 1px 6px; border-radius: 4px; font-weight: bold; font-size: 7pt; }
        .badge-finalizado { background-color: #dbeafe; color: #1e40af; padding: 1px 6px; border-radius: 4px; font-weight: bold; font-size: 7pt; }

        .logo-sena { width: 45px; }
        .header-title { font-size: 10pt; font-weight: bold; }
        .firma-line { border-top: 1px solid #000; width: 80%; margin: 0 auto; }
    </style>
</head>
<body>

    {{-- Encabezado institucional --}}
    <table>
        <tr>
            <td rowspan="3" style="width: 60px;" class="text-center">
                <img src="{{ public_path('img/logo-sena.png') }}" class="logo-sena">
            </td>
            <td rowspan="3" class="text-center">
                <span class="header-title">Gestión de Tecnologías de la Información</span><br>
                <span>Registro de Mantenimiento</span>
            </td>
            <td class="bg-gray" style="width: 60px;">Fecha:</td>
            <td style="width: 80px;" class="text-center font-bold">
                {{ \Carbon\Carbon::parse($mantenimiento->fecha)->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <td class="bg-gray">Tipo:</td>
            <td class="text-center">
                @if($mantenimiento->tipo == 'Correctivo')
                    <span class="badge-correctivo">CORRECTIVO</span>
                @else
                    <span class="badge-preventivo">PREVENTIVO</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="bg-gray">Estado:</td>
            <td class="text-center">
                @if($mantenimiento->finalizado)
                    <span class="badge-finalizado">FINALIZADO</span>
                @else
                    <span class="badge-correctivo">EN PROCESO</span>
                @endif
            </td>
        </tr>
    </table>

    <br>

    {{-- Datos del equipo --}}
    <div class="bg-green">1. DATOS DEL EQUIPO</div>
    <table>
        <tr>
            <td class="bg-gray" style="width: 20%;">MARCA / MODELO:</td>
            <td style="width: 30%;" class="font-bold">{{ $mantenimiento->dispositivo->marca }} {{ $mantenimiento->dispositivo->modelo }}</td>
            <td class="bg-gray" style="width: 20%;">PLACA SENA:</td>
            <td style="width: 30%;" class="font-bold">{{ $mantenimiento->dispositivo->placa }}</td>
        </tr>
        <tr>
            <td class="bg-gray">SERIAL:</td>
            <td class="font-bold" style="font-family: monospace;">{{ $mantenimiento->dispositivo->serial }}</td>
            <td class="bg-gray">ESTADO FÍSICO:</td>
            <td class="font-bold">{{ $mantenimiento->dispositivo->estado_fisico }}</td>
        </tr>
        <tr>
            <td class="bg-gray">SEDE / AMBIENTE:</td>
            <td colspan="3">{{ $mantenimiento->dispositivo->ubicacion->sede->nombre }} — Ambiente {{ $mantenimiento->dispositivo->ubicacion->ambiente }}</td>
        </tr>
        <tr>
            <td class="bg-gray">RESPONSABLE:</td>
            <td>{{ $mantenimiento->dispositivo->responsable->nombre }}</td>
            <td class="bg-gray">CARGO:</td>
            <td>{{ $mantenimiento->dispositivo->responsable->cargo }}</td>
        </tr>
        @if($mantenimiento->dispositivo->especificaciones)
        @php $gb = fn($v) => is_numeric($v ?? '') ? $v . ' GB' : ($v ?? 'N/A'); @endphp
        <tr>
            <td class="bg-gray">PROCESADOR:</td>
            <td>{{ $mantenimiento->dispositivo->especificaciones->procesador ?? 'N/A' }}</td>
            <td class="bg-gray">RAM / DISCO:</td>
            <td>{{ $gb($mantenimiento->dispositivo->especificaciones->ram ?? '') }} / {{ $gb($mantenimiento->dispositivo->especificaciones->capacidad_disco ?? '') }}</td>
        </tr>
        @endif
    </table>

    <br>

    {{-- Detalle del mantenimiento --}}
    <div class="bg-green">2. DETALLE DEL MANTENIMIENTO</div>
    <table>
        <tr>
            <td class="bg-gray" style="width: 25%;">TÉCNICO ENCARGADO:</td>
            <td colspan="3" class="font-bold">{{ $mantenimiento->tecnico_encargado }}</td>
        </tr>
        @if($mantenimiento->descripcion_falla)
        <tr>
            <td class="bg-gray">DESCRIPCIÓN DE FALLA:</td>
            <td colspan="3" style="vertical-align: top;">{{ $mantenimiento->descripcion_falla }}</td>
        </tr>
        @endif
        <tr>
            <td class="bg-gray">TAREAS REALIZADAS:</td>
            <td colspan="3" style="height: 80px; vertical-align: top; font-size: 7pt; font-family: monospace;">
                {!! nl2br(e($mantenimiento->tareas_realizadas)) !!}
            </td>
        </tr>
        @if($mantenimiento->piezas_cambiadas)
        <tr>
            <td class="bg-gray">PIEZAS / REPUESTOS:</td>
            <td colspan="3">{{ $mantenimiento->piezas_cambiadas }}</td>
        </tr>
        @endif
        <tr>
            <td class="bg-gray">OBSERVACIONES:</td>
            <td colspan="3" style="height: 30px; vertical-align: top;">
                {{ $mantenimiento->observaciones ?? 'Sin observaciones adicionales.' }}
            </td>
        </tr>
    </table>

    <br>

    {{-- Firmas --}}
    <table style="border: none; margin-top: 40px;">
        <tr>
            <td style="border: none; text-align: center; width: 50%;">
                <div class="firma-line"></div>
                <span class="font-bold">Técnico Responsable</span><br>
                <span>{{ $mantenimiento->tecnico_encargado }}</span>
            </td>
            <td style="border: none; text-align: center; width: 50%;">
                <div class="firma-line"></div>
                <span class="font-bold">Funcionario / Responsable del Equipo</span><br>
                <span>{{ $mantenimiento->dispositivo->responsable->nombre }}</span>
            </td>
        </tr>
    </table>

</body>
</html>
