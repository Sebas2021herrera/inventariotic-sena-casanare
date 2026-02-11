<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.8cm; }
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 7.5pt; line-height: 1.1; color: #000; }
        
        /* Tablas base */
        table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: -1px; }
        td { border: 1px solid #000; padding: 2px 4px; vertical-align: middle; }
        
        /* Colores y Cabeceras */
        .bg-gray { background-color: #D9D9D9; font-weight: bold; text-transform: uppercase; font-size: 7pt; }
        .bg-green { background-color: #2f302f; color: white; font-weight: bold; text-align: center; font-size: 8.5pt; padding: 3px; }
        
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        /* Simulación de Checkbox V05 */
        .check-container { font-size: 8pt; }
        .box { display: inline-block; width: 9px; height: 9px; border: 1px solid #000; text-align: center; line-height: 8px; font-size: 7pt; margin-right: 2px; }
        
        .logo-sena { width: 45px; }
        .header-title { font-size: 10pt; font-weight: bold; }
    </style>
</head>
<body>

    <table>
        <tr>
            <td rowspan="3" style="width: 60px;" class="text-center">
                <img src="{{ public_path('img/logo-sena.png') }}" class="logo-sena">
            </td>
            <td rowspan="3" class="text-center">
                <span class="header-title">Gestión de Tecnologías de la Información</span><br>
                <span>Reporte técnico</span>
            </td>
            <td class="bg-gray" style="width: 60px;">Versión:</td>
            <td style="width: 70px;" class="text-center font-bold">05</td>
        </tr>
        <tr>
            <td class="bg-gray">Código:</td>
            <td class="text-center font-bold">GTI-F-132</td>
        </tr>
        <tr>
            <td class="bg-gray">Fecha:</td>
            <td class="text-center font-bold">{{ $concepto->fecha_reporte->format('d/m/Y') }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="bg-gray" style="width: 15%;">TIPO DE EQUIPO:</td>
            <td style="width: 35%;" class="check-container">
                <span class="box">{{ $concepto->tipo_equipo == 'Administrativo' ? 'X' : '' }}</span> Administrativo &nbsp;&nbsp;
                <span class="box">{{ $concepto->tipo_equipo == 'Formación' ? 'X' : '' }}</span> Formación
            </td>
            <td class="bg-gray" style="width: 15%;">HOSTNAME:</td>
            <td style="width: 35%;" class="font-bold">{{ $concepto->hostname }}</td>
        </tr>
        <tr>
            <td class="bg-gray">DOMINIO:</td>
            <td class="font-bold">SENA.RED</td>
            <td class="bg-gray">Nº INC / WO:</td>
            <td class="font-bold">{{ $concepto->num_incidente ?? 'N/A' }} / {{ $concepto->num_requerimiento ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="bg-green">1. DATOS BÁSICOS</div>
    <table>
        <tr>
            <td class="bg-gray" style="width: 20%;">NOMBRE USUARIO:</td>
            <td style="width: 30%;">{{ $concepto->dispositivo->responsable->nombre }}</td>
            <td class="bg-gray" style="width: 20%;">JEFE INMEDIATO:</td>
            <td style="width: 30%;">{{ $concepto->jefe_inmediato }}</td>
        </tr>
        <tr>
            <td class="bg-gray">SEDE / CENTRO:</td>
            <td colspan="3">{{ $concepto->dispositivo->ubicacion->sede }} - {{ $concepto->dispositivo->ubicacion->ambiente }}</td>
        </tr>
        <tr>
            <td class="bg-gray">DESCRIPCIÓN:</td>
            <td colspan="3" style="height: 20px; vertical-align: top;">{{ $concepto->descripcion_solicitud }}</td>
        </tr>
    </table>

    <div class="bg-green">2. DATOS DEL EQUIPO REPORTADO</div>
    <table>
        <tr class="bg-gray text-center" style="font-size: 6.5pt;">
            <td style="width: 20%;">COMPONENTE</td>
            <td style="width: 20%;">MARCA</td>
            <td style="width: 20%;">MODELO</td>
            <td style="width: 20%;">SERIAL</td>
            <td style="width: 20%;">PLACA SENA</td>
        </tr>
        <tr class="text-center">
            <td class="font-bold bg-gray" style="font-size: 6pt;">EQUIPO PRINCIPAL</td>
            <td>{{ $concepto->dispositivo->marca }}</td>
            <td>{{ $concepto->dispositivo->modelo }}</td>
            <td>{{ $concepto->dispositivo->serial }}</td>
            <td>{{ $concepto->dispositivo->placa }}</td>
        </tr>
        @foreach($concepto->dispositivo->perifericos as $p)
        <tr class="text-center">
            <td class="bg-gray" style="font-size: 6pt;">{{ strtoupper($p->tipo) }}</td>
            <td>{{ $p->marca ?? 'N/A' }}</td>
            <td>{{ $p->modelo ?? 'N/A' }}</td>
            <td>{{ $p->serial ?? 'N/A' }}</td>
            <td>{{ $p->placa ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </table>

    <table>
        <tr>
            <td class="bg-gray" style="width: 15%;">DISCO DURO:</td>
            <td style="width: 35%;">{{ $concepto->dispositivo->especificaciones->capacidad_disco ?? 'N/A' }}</td>
            <td class="bg-gray" style="width: 15%;">MEMORIA RAM:</td>
            <td style="width: 35%;">{{ $concepto->dispositivo->especificaciones->ram ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="bg-gray">PROCESADOR:</td>
            <td colspan="3">{{ $concepto->dispositivo->especificaciones->procesador ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="bg-green">3. SOFTWARE BASE DE EQUIPO</div>
    <table>
        <tr class="bg-gray text-center" style="font-size: 6.5pt;">
            <td style="width: 25%;">TIPO DE SOFTWARE</td>
            <td style="width: 45%;">DESCRIPCIÓN</td>
            <td style="width: 15%;">VERSIÓN</td>
            <td style="width: 15%;">LICENCIADO (SI/NO)</td>
        </tr>
        @foreach($concepto->software_base as $tipo => $soft)
        <tr>
            <td class="bg-gray">{{ strtoupper($tipo) }}</td>
            <td>{{ $soft['nombre'] }}</td>
            <td class="text-center">{{ $soft['version'] }}</td>
            <td class="text-center font-bold">{{ $soft['licencia'] }}</td>
        </tr>
        @endforeach
    </table>

    <div class="bg-green">4. DETALLE DE LA SOLICITUD</div>
    <table class="check-container">
        <tr>
            <td class="bg-gray" style="width: 20%;">Flujo de solicitud:</td>
            <td style="width: 80%;">
                <span class="box">{{ $concepto->flujo_solicitud == 'Repuesto' ? 'X' : '' }}</span> 1. Repuesto &nbsp;&nbsp;
                <span class="box">{{ $concepto->flujo_solicitud == 'Garantía' ? 'X' : '' }}</span> 2. Garantía &nbsp;&nbsp;
                <span class="box">{{ $concepto->flujo_solicitud == 'Siniestro' ? 'X' : '' }}</span> 3. Siniestro &nbsp;&nbsp;
                <span class="box">{{ $concepto->flujo_solicitud == 'Concepto Técnico' ? 'X' : '' }}</span> 4. Concepto Técnico
            </td>
        </tr>
        <tr>
            <td class="bg-gray">Tipo concepto:</td>
            <td>
                <span class="box">{{ $concepto->concepto_tipo == 'Asignación' ? 'X' : '' }}</span> a. Asignación &nbsp;&nbsp;
                <span class="box">{{ $concepto->concepto_tipo == 'Reasignación' ? 'X' : '' }}</span> b. Reasignación &nbsp;&nbsp;
                <span class="box">{{ $concepto->concepto_tipo == 'Baja' ? 'X' : '' }}</span> c. Baja
            </td>
        </tr>
    </table>

    <table>
        <tr><td class="bg-gray">DIAGNÓSTICO:</td></tr>
        <tr>
            <td style="height: 100px; vertical-align: top; font-size: 7pt; font-family: monospace;">
                {!! nl2br(e($concepto->diagnostico_tecnico)) !!}
            </td>
        </tr>
        <tr><td class="bg-gray">CAUSAS DEL DAÑO:</td></tr>
        <tr><td>{{ $concepto->causas_daño ?? 'N/A' }}</td></tr>
        <tr><td class="bg-gray">RECOMENDACIÓN:</td></tr>
        <tr><td>{{ $concepto->recomendacion ?? 'N/A' }}</td></tr>
    </table>

    <table style="border: none; margin-top: 35px;">
        <tr>
            <td style="border: none; text-align: center;">
                <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto;"></div>
                <span class="font-bold">Firma Técnico N2</span><br>
                <span>{{ $concepto->tecnico_nombre }}</span>
            </td>
            <td style="border: none; text-align: center;">
                <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto;"></div>
                <span class="font-bold">Firma Funcionario</span><br>
                <span>{{ $concepto->dispositivo->responsable->nombre }}</span>
            </td>
        </tr>
    </table>

</body>
</html>