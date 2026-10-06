@extends('layouts.app')

@section('content')
@php
$nivelColor = [
    'Nivel 1' => ['bg-blue-100 text-blue-700',   'fa-building'],
    'Nivel 2' => ['bg-orange-100 text-orange-700','fa-network-wired'],
    'Nivel 3' => ['bg-red-100 text-red-700',      'fa-server'],
];
$ciaColor = ['Alto'=>'bg-red-100 text-red-700','Medio'=>'bg-orange-100 text-orange-700','Bajo'=>'bg-green-100 text-green-700'];
$resultadoColor = [
    'Conforme'                   => ['bg-green-100 text-green-700',  'fa-check-circle'],
    'No Conforme'                => ['bg-red-100 text-red-700',      'fa-times-circle'],
    'Conforme con Observaciones' => ['bg-yellow-100 text-yellow-700','fa-exclamation-circle'],
    'En Proceso'                 => ['bg-blue-100 text-blue-700',    'fa-clock'],
];
$estadoColor = [
    'CUMPLE'              => 'bg-green-100 text-green-700',
    'CUMPLE PARCIALMENTE' => 'bg-yellow-100 text-yellow-700',
    'NO CUMPLE'           => 'bg-red-100 text-red-700',
    'NO APLICA'           => 'bg-gray-100 text-gray-500',
];
@endphp

<div class="max-w-5xl mx-auto space-y-6">

    {{-- ── Cabecera del área ──────────────────────────────────────────────── --}}
    <div class="bg-[#1e3a5f] rounded-3xl p-7 text-white">
        <div class="flex flex-col md:flex-row justify-between items-start gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="bg-white/20 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">GIL-F-101</span>
                    <span class="text-[10px] font-bold opacity-70">Inventario de Áreas Seguras</span>
                </div>
                <h1 class="text-2xl font-black uppercase tracking-tighter">{{ $area->nombre_dependencia }}</h1>
                <div class="flex flex-wrap items-center gap-3 mt-2">
                    <span class="font-mono text-white/70 text-sm">{{ $area->codigo }}</span>
                    @if($area->zonificacion)
                        <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded font-bold">{{ $area->zonificacion }}</span>
                    @endif
                    @if($area->clasificacion_informacion)
                        <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded font-bold">{{ $area->clasificacion_informacion }}</span>
                    @endif
                </div>
            </div>
            <div class="flex flex-col items-end gap-2">
                @php $nsCfg = $nivelColor[$area->nivel_sena] ?? ['bg-gray-100 text-gray-700','fa-question']; @endphp
                <span class="px-4 py-2 rounded-2xl text-sm font-black flex items-center gap-2 {{ $nsCfg[0] }}">
                    <i class="fas {{ $nsCfg[1] }}"></i>{{ $area->nivel_sena }}
                </span>
                <span class="px-3 py-1 rounded-xl text-xs font-black {{ $ciaColor[$area->nivel_criticidad] ?? 'bg-gray-100 text-gray-600' }}">
                    CIA: {{ $area->nivel_criticidad }}
                </span>
                @if($area->sede)
                <span class="text-[10px] text-white/70 font-bold flex items-center gap-1">
                    <i class="fas fa-map-marker-alt"></i>{{ $area->sede->nombre }}
                </span>
                @endif
                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('areas-seguras.verificacion.create', $area) }}"
                       class="px-4 py-2 bg-[#39A900] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-green-600 transition flex items-center gap-2">
                        <i class="fas fa-clipboard-check"></i> Nueva Verificación
                    </a>
                    @if($area->ultimaVerificacion)
                    <a href="{{ route('areas-seguras.verificacion.pdf', $area) }}"
                       class="px-4 py-2 bg-orange-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-orange-600 transition flex items-center gap-2" title="Exportar GIL-F-102 PDF">
                        <i class="fas fa-file-pdf"></i> GIL-F-102 PDF
                    </a>
                    @endif
                    <a href="{{ route('areas-seguras.edit', $area) }}"
                       class="px-4 py-2 bg-white/10 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white/20 transition">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl px-5 py-4 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- ── Datos GIL-F-101 ────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Datos del Área --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b pb-2">
                <i class="fas fa-id-card mr-2 text-[#39A900]"></i>Datos del Área — GIL-F-101
            </h3>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div><p class="text-[9px] text-gray-400 font-black uppercase mb-0.5">Fecha de Inventario</p>
                     <p class="font-bold text-gray-700">{{ $area->fecha_inventario?->format('d/m/Y') ?? '—' }}</p></div>
                <div><p class="text-[9px] text-gray-400 font-black uppercase mb-0.5">Estado del Área</p>
                     <p class="font-bold text-gray-700">{{ $area->estado_area ?? '—' }}</p></div>
                <div><p class="text-[9px] text-gray-400 font-black uppercase mb-0.5">Sede</p>
                     <p class="font-bold text-gray-700">{{ $area->sede->nombre ?? '—' }}</p></div>
                <div><p class="text-[9px] text-gray-400 font-black uppercase mb-0.5">Tipo de Área</p>
                     <p class="font-bold text-gray-700">{{ $area->tipo_area ?? '—' }}</p></div>
                <div><p class="text-[9px] text-gray-400 font-black uppercase mb-0.5">Horario</p>
                     <p class="font-bold text-gray-700">{{ $area->horario_acceso }}</p></div>
                <div><p class="text-[9px] text-gray-400 font-black uppercase mb-0.5">Ubicación</p>
                     <p class="font-bold text-gray-700">
                        @if($area->bloque)Blq. {{ $area->bloque }}@endif
                        @if($area->piso) · Piso {{ $area->piso }}@endif
                        @if($area->numero_oficina) · Of. {{ $area->numero_oficina }}@endif
                        @if(!$area->bloque && !$area->piso && !$area->numero_oficina)—@endif
                     </p></div>
                <div class="col-span-2"><p class="text-[9px] text-gray-400 font-black uppercase mb-0.5">Perímetro de Seguridad</p>
                     <p class="font-bold text-gray-700">{{ $area->perimetro_seguridad }}</p></div>
                @if($area->descripcion)
                <div class="col-span-2"><p class="text-[9px] text-gray-400 font-black uppercase mb-0.5">Observaciones</p>
                     <p class="text-gray-600 text-xs">{{ $area->descripcion }}</p></div>
                @endif
            </div>
        </div>

        {{-- Responsable y Controles --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b pb-2">
                <i class="fas fa-user-shield mr-2 text-blue-500"></i>Responsable y Controles
            </h3>
            <div class="space-y-3 text-xs">
                <div>
                    <p class="text-[9px] text-gray-400 font-black uppercase mb-0.5">Responsable del Área</p>
                    <p class="font-bold text-gray-700">{{ $area->responsable_nombre ?? '—' }} <span class="text-gray-400 font-normal">{{ $area->responsable_cargo }}</span></p>
                    @if($area->responsable_contacto)
                        <p class="text-[10px] text-gray-400">{{ $area->responsable_contacto }}</p>
                    @endif
                </div>

                @if($area->controles_acceso && count($area->controles_acceso))
                <div>
                    <p class="text-[9px] text-gray-400 font-black uppercase mb-1">Controles de Acceso</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($area->controles_acceso as $c)
                            <span class="px-2 py-1 bg-green-50 text-green-700 rounded-lg text-[10px] font-black border border-green-100">
                                <i class="fas fa-lock text-[8px] mr-1"></i>{{ $c }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($area->controles_monitoreo && count($area->controles_monitoreo))
                <div>
                    <p class="text-[9px] text-gray-400 font-black uppercase mb-1">Controles de Monitoreo</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($area->controles_monitoreo as $c)
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-black border border-blue-100">
                                <i class="fas fa-video text-[8px] mr-1"></i>{{ $c }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <p class="text-[9px] text-gray-400 italic pt-2 border-t">
                Registrado por {{ $area->creador->name ?? 'Sistema' }} · {{ $area->created_at->format('d/m/Y') }}
            </p>
        </div>
    </div>

    {{-- ── Histórico de Cambios (GIL-F-101 campo 9) ───────────────────────── --}}
    @if($area->historico_cambios && count($area->historico_cambios))
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
        <h3 class="text-[10px] font-black text-yellow-700 uppercase tracking-widest mb-3 flex items-center gap-2">
            <i class="fas fa-history"></i>
            Histórico de Actualizaciones (GIL-F-101)
        </h3>
        <div class="space-y-2">
            @foreach(array_reverse($area->historico_cambios) as $h)
            <div class="flex items-start gap-3 text-xs">
                <span class="text-yellow-500 font-mono font-bold shrink-0">{{ $h['fecha'] }}</span>
                <span class="text-yellow-800 font-bold">{{ $h['cambio'] }}</span>
                <span class="text-yellow-500 italic ml-auto shrink-0">{{ $h['usuario'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Historial de Verificaciones GIL-F-102 ──────────────────────────── --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-clipboard-check text-[#39A900]"></i>
                Historial de Verificaciones GIL-F-102
            </h3>
            <span class="text-[10px] font-bold text-gray-400">{{ $area->verificaciones->count() }} verificación(es)</span>
        </div>

        @forelse($area->verificaciones as $v)
        @php
            $resCfg  = $resultadoColor[$v->resultado] ?? ['bg-gray-100 text-gray-600','fa-question-circle'];
            $totalAp = $v->total_items - ($v->no_aplica_count ?? 0);
        @endphp
        <div class="px-6 py-5 border-b border-gray-50 hover:bg-gray-50/40 transition">
            {{-- Encabezado --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="text-sm font-black text-gray-800">{{ $v->corte }}</span>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black flex items-center gap-1 {{ $resCfg[0] }}">
                            <i class="fas {{ $resCfg[1] }} text-[9px]"></i>{{ $v->resultado }}
                        </span>
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold">
                        {{ $v->fecha_verificacion->format('d/m/Y') }}
                        · Verificado por {{ $v->verificador->name ?? '—' }}
                        @if($v->inspectores) · Inspectores: <span class="text-gray-600">{{ $v->inspectores }}</span>@endif
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-lg font-black {{ $v->porcentaje_cumplimiento >= 70 ? 'text-green-600' : ($v->porcentaje_cumplimiento >= 40 ? 'text-yellow-600' : 'text-red-500') }}">
                            {{ $v->porcentaje_cumplimiento }}%
                        </p>
                        <p class="text-[9px] text-gray-400 font-bold">cumplimiento</p>
                    </div>
                    <div class="w-16 bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $v->porcentaje_cumplimiento >= 70 ? 'bg-green-500' : ($v->porcentaje_cumplimiento >= 40 ? 'bg-yellow-400' : 'bg-red-400') }}"
                             style="width:{{ $v->porcentaje_cumplimiento }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Resumen de estados --}}
            <div class="flex flex-wrap gap-2 mb-4">
                <span class="text-[9px] font-black bg-green-100 text-green-700 px-2 py-1 rounded-lg">
                    <i class="fas fa-check mr-1"></i>Cumplen: {{ $v->total_cumple }}
                </span>
                @if($v->cumple_parcial_count > 0)
                <span class="text-[9px] font-black bg-yellow-100 text-yellow-700 px-2 py-1 rounded-lg">
                    <i class="fas fa-minus mr-1"></i>Parcial: {{ $v->cumple_parcial_count }}
                </span>
                @endif
                @php $nc = $totalAp - $v->total_cumple - ($v->cumple_parcial_count ?? 0); @endphp
                @if($nc > 0)
                <span class="text-[9px] font-black bg-red-100 text-red-700 px-2 py-1 rounded-lg">
                    <i class="fas fa-times mr-1"></i>No Cumplen: {{ $nc }}
                </span>
                @endif
                @if($v->no_aplica_count > 0)
                <span class="text-[9px] font-black bg-gray-100 text-gray-500 px-2 py-1 rounded-lg">
                    No Aplica: {{ $v->no_aplica_count }}
                </span>
                @endif
                <span class="text-[9px] font-bold text-gray-400">/ {{ $v->total_items }} controles</span>
            </div>

            {{-- Items Bloque 2: Acceso ─────────────── --}}
            @if($v->items && count($v->items))
            <div class="mb-3">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Controles de Acceso Físico (5.4)</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1.5">
                    @foreach($v->items as $item)
                    @php
                        $est = $item['estado'] ?? 'NO APLICA';
                        $ic = match($est) {
                            'CUMPLE'              => 'fas fa-check-circle text-green-500',
                            'CUMPLE PARCIALMENTE' => 'fas fa-adjust text-yellow-500',
                            'NO CUMPLE'           => 'fas fa-times-circle text-red-400',
                            default               => 'fas fa-minus-circle text-gray-300',
                        };
                    @endphp
                    <div class="flex items-start gap-2 text-[10px]">
                        <i class="{{ $ic }} mt-0.5 flex-shrink-0"></i>
                        <div>
                            <span class="font-bold text-gray-400 mr-1">{{ $item['codigo'] }}</span>
                            <span class="text-gray-600">{{ Str::limit($item['nombre'] ?? $item['item'] ?? '', 55) }}</span>
                            @if(!empty($item['observaciones']))
                                <span class="text-gray-400 italic ml-1">— {{ $item['observaciones'] }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Items Bloque 3: Medioambiental --}}
            @if($v->items_medioambientales && count($v->items_medioambientales))
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">
                    Controles Medioambientales ({{ $v->tipo_medioambiental }}) — {{ round($v->porcentaje_medioambiental) }}%
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1.5">
                    @foreach($v->items_medioambientales as $item)
                    @php
                        $est = $item['estado'] ?? 'NO APLICA';
                        $ic = match($est) {
                            'CUMPLE'              => 'fas fa-check-circle text-green-500',
                            'CUMPLE PARCIALMENTE' => 'fas fa-adjust text-yellow-500',
                            'NO CUMPLE'           => 'fas fa-times-circle text-red-400',
                            default               => 'fas fa-minus-circle text-gray-300',
                        };
                    @endphp
                    <div class="flex items-start gap-2 text-[10px]">
                        <i class="{{ $ic }} mt-0.5 flex-shrink-0"></i>
                        <div>
                            <span class="font-bold text-gray-400 mr-1">{{ $item['codigo'] }}</span>
                            <span class="text-gray-600">{{ Str::limit($item['nombre'] ?? $item['item'] ?? '', 55) }}</span>
                            @if(!empty($item['observaciones']))
                                <span class="text-gray-400 italic ml-1">— {{ $item['observaciones'] }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($v->observaciones_generales)
                <div class="mt-3 bg-yellow-50 rounded-xl px-4 py-2 text-[10px] text-yellow-800 border border-yellow-100">
                    <i class="fas fa-comment-alt mr-1"></i>{{ $v->observaciones_generales }}
                </div>
            @endif
        </div>
        @empty
        <div class="px-6 py-12 text-center text-gray-400 font-bold italic text-xs">
            <i class="fas fa-clipboard-list text-3xl mb-3 block opacity-20"></i>
            Sin verificaciones GIL-F-102 aún. Crea la primera para este período.
        </div>
        @endforelse
    </div>

</div>
@endsection
