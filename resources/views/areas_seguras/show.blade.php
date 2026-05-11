@extends('layouts.app')

@section('content')
@php
$nivelSenaColor = [
    'Nivel 1 - Crítico'   => ['bg-red-100 text-red-700',    'fa-server'],
    'Nivel 2 - Sensible'  => ['bg-orange-100 text-orange-700','fa-briefcase'],
    'Nivel 3 - Operativo' => ['bg-blue-100 text-blue-700',  'fa-chalkboard'],
];
$ciaColor = ['Alto'=>'bg-red-100 text-red-700','Medio'=>'bg-orange-100 text-orange-700','Bajo'=>'bg-green-100 text-green-700'];
$resultadoColor = ['Conforme'=>'bg-green-100 text-green-700','No Conforme'=>'bg-red-100 text-red-700','Conforme con Observaciones'=>'bg-orange-100 text-orange-700'];
@endphp

<div class="max-w-5xl mx-auto space-y-8">

    {{-- Cabecera --}}
    <div class="bg-[#1e3a5f] rounded-3xl p-8 text-white">
        <div class="flex flex-col md:flex-row justify-between items-start gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="bg-white/20 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">ISO 27001:2022</span>
                    <span class="text-[10px] font-bold opacity-70">Control 7.5 / 7.6</span>
                </div>
                <h1 class="text-3xl font-black uppercase tracking-tighter">{{ $area->nombre_dependencia }}</h1>
                <p class="font-mono text-white/70 text-lg mt-1">{{ $area->codigo }}</p>
            </div>
            <div class="flex flex-col items-end gap-2">
                @php $nsCfg = $nivelSenaColor[$area->nivel_sena] ?? ['bg-gray-100 text-gray-700','fa-question']; @endphp
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
                <div class="flex gap-2">
                    <a href="{{ route('areas-seguras.verificacion.create', $area) }}"
                       class="px-4 py-2 bg-[#39A900] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-green-600 transition flex items-center gap-2">
                        <i class="fas fa-clipboard-check"></i> Nueva Verificación
                    </a>
                    <a href="{{ route('areas-seguras.edit', $area) }}"
                       class="px-4 py-2 bg-white/10 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white/20 transition">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl px-5 py-4 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Detalles del Área --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b pb-2">
                <i class="fas fa-info-circle mr-2 text-[#39A900]"></i>Datos del Área
            </h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Sede</p><p class="font-bold text-gray-700">{{ $area->sede->nombre ?? '—' }}</p></div>
                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Tipo de Área</p><p class="font-bold text-gray-700">{{ $area->tipo_area ?? '—' }}</p></div>
                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Responsable</p><p class="font-bold text-gray-700">{{ $area->responsable_cargo }}</p></div>
                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Horario</p><p class="font-bold text-gray-700">{{ $area->horario_acceso }}</p></div>
                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Bloque</p><p class="font-bold text-gray-700">{{ $area->bloque ?? '—' }}</p></div>
                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Piso / Oficina</p><p class="font-bold text-gray-700">{{ $area->piso ?? '—' }} / {{ $area->numero_oficina ?? '—' }}</p></div>
                <div class="col-span-2"><p class="text-[10px] text-gray-400 font-bold uppercase">Perímetro de Seguridad</p><p class="font-bold text-gray-700">{{ $area->perimetro_seguridad }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b pb-2">
                <i class="fas fa-lock mr-2 text-blue-500"></i>Controles de Acceso
            </h3>
            <div class="flex flex-wrap gap-2">
                @foreach($area->controles_acceso as $ctrl)
                    <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-xl text-[11px] font-black border border-blue-100">
                        <i class="fas fa-check-circle mr-1 text-[9px]"></i>{{ $ctrl }}
                    </span>
                @endforeach
            </div>
            @if($area->descripcion)
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Observaciones</p>
                <p class="text-xs text-gray-600">{{ $area->descripcion }}</p>
            </div>
            @endif
            <p class="text-[9px] text-gray-400 italic">Registrado por {{ $area->creador->name ?? 'Sistema' }} · {{ $area->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    {{-- Historial de Verificaciones --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                <i class="fas fa-history mr-2 text-[#39A900]"></i>Historial de Verificaciones Checklist
            </h3>
            <span class="text-[10px] font-bold text-gray-400">{{ $area->verificaciones->count() }} verificaciones</span>
        </div>
        @forelse($area->verificaciones as $v)
        <div class="px-6 py-5 border-b border-gray-50 hover:bg-gray-50/50 transition">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="text-sm font-black text-gray-800">{{ $v->corte }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $resultadoColor[$v->resultado] ?? '' }}">{{ $v->resultado }}</span>
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold">
                        {{ $v->fecha_verificacion->format('d/m/Y') }} · Verificado por {{ $v->verificador->name ?? '—' }}
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    {{-- Barra de progreso --}}
                    <div class="text-right">
                        <p class="text-xl font-black {{ $v->porcentaje_cumplimiento >= 70 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $v->total_cumple }}/{{ $v->total_items }}
                        </p>
                        <p class="text-[10px] text-gray-400 font-bold">{{ $v->porcentaje_cumplimiento }}% conformes</p>
                    </div>
                    <div class="w-16 bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $v->porcentaje_cumplimiento >= 70 ? 'bg-green-500' : 'bg-red-400' }}"
                             style="width:{{ $v->porcentaje_cumplimiento }}%"></div>
                    </div>
                </div>
            </div>
            {{-- Items del checklist --}}
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-2">
                @foreach($v->items as $item)
                <div class="flex items-start gap-2 text-[10px]">
                    @if($item['cumple'] === 'S')
                        <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                    @else
                        <i class="fas fa-times-circle text-red-400 mt-0.5 flex-shrink-0"></i>
                    @endif
                    <div>
                        <span class="font-bold text-gray-600">{{ $item['categoria'] }}</span>
                        @if(!empty($item['observaciones']))
                            <span class="text-gray-400 italic ml-1">— {{ $item['observaciones'] }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @if($v->observaciones_generales)
                <div class="mt-3 bg-yellow-50 rounded-xl px-4 py-2 text-[10px] text-yellow-800">
                    <i class="fas fa-comment-alt mr-1"></i>{{ $v->observaciones_generales }}
                </div>
            @endif
        </div>
        @empty
        <div class="px-6 py-12 text-center text-gray-400 font-bold italic text-xs">
            <i class="fas fa-clipboard-list text-3xl mb-3 block opacity-20"></i>
            Sin verificaciones aún. Crea la primera para este corte.
        </div>
        @endforelse
    </div>

</div>
@endsection
