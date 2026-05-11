@extends('layouts.app')

@section('content')
@php
$nivelColor = [
    'Nivel 1 - Crítico'   => ['bg-red-100 text-red-700 border-red-300',   'bg-red-500',   'fa-server'],
    'Nivel 2 - Sensible'  => ['bg-orange-100 text-orange-700 border-orange-300','bg-orange-400','fa-briefcase'],
    'Nivel 3 - Operativo' => ['bg-blue-100 text-blue-700 border-blue-300',   'bg-blue-400',  'fa-chalkboard'],
];
$resultadoColor = [
    'Conforme'                   => 'bg-green-100 text-green-700',
    'No Conforme'                => 'bg-red-100 text-red-700',
    'Conforme con Observaciones' => 'bg-orange-100 text-orange-700',
];
@endphp

<div class="max-w-7xl mx-auto space-y-8">

    {{-- Cabecera --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Áreas <span class="text-[#39A900]">Seguras</span>
            </h1>
            <p class="text-gray-500 font-bold text-sm italic">
                ISO 27001:2022 · Controles 7.5 Perímetros / 7.6 Entrada física
            </p>
        </div>
        <a href="{{ route('areas-seguras.create') }}"
           class="bg-[#39A900] text-white px-5 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg hover:bg-green-700 transition flex items-center gap-2">
            <i class="fas fa-shield-alt"></i> Registrar Área
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl px-5 py-4 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Panel de Clasificación SENA --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#1e3a5f] px-6 py-4">
            <h2 class="text-white font-black uppercase text-xs tracking-widest flex items-center gap-2">
                <i class="fas fa-layer-group"></i>
                Inventario de Áreas Seguras — Clasificación SENA (Centro Agroindustrial y Fortalecimiento Empresarial)
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100">
            @foreach($nivelesSena as $nivel => $cfg)
            @php
                $cnt = match($nivel) {
                    'Nivel 1 - Crítico'   => $stats['nivel1'],
                    'Nivel 2 - Sensible'  => $stats['nivel2'],
                    'Nivel 3 - Operativo' => $stats['nivel3'],
                    default => 0,
                };
                $colores = $nivelColor[$nivel];
            @endphp
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="p-3 rounded-2xl {{ str_replace(['text-','border-'], ['text-white bg-',''], explode(' ',$colores[0])[1]) }} bg-opacity-80 flex-shrink-0"
                         style="background:{{ match($nivel){ 'Nivel 1 - Crítico'=>'#ef4444','Nivel 2 - Sensible'=>'#f97316','Nivel 3 - Operativo'=>'#3b82f6',default=>'#6b7280'} }};">
                        <i class="fas {{ $cfg['icono'] }} text-white text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-black text-gray-800 text-sm">{{ $nivel }}</h3>
                            <span class="text-2xl font-black {{ explode(' ',$colores[0])[1] }}">{{ $cnt }}</span>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">{{ $cfg['acceso'] }}</p>
                        <ul class="space-y-1">
                            @foreach($cfg['ejemplos'] as $ej)
                            <li class="text-[10px] text-gray-500 flex items-start gap-1.5">
                                <i class="fas fa-chevron-right text-[8px] mt-0.5 opacity-50"></i>{{ $ej }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl shadow-sm p-5 border-b-4 border-gray-300 flex items-center gap-3">
            <div class="p-3 rounded-xl bg-gray-100"><i class="fas fa-shield-alt text-gray-500 text-xl"></i></div>
            <div><p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Total Áreas</p>
                 <h3 class="text-2xl font-black text-gray-800">{{ $stats['total'] }}</h3></div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 border-b-4 border-red-400 flex items-center gap-3">
            <div class="p-3 rounded-xl bg-red-50"><i class="fas fa-server text-red-500 text-xl"></i></div>
            <div><p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Críticas N1</p>
                 <h3 class="text-2xl font-black text-red-600">{{ $stats['nivel1'] }}</h3></div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 border-b-4 border-orange-400 flex items-center gap-3">
            <div class="p-3 rounded-xl bg-orange-50"><i class="fas fa-briefcase text-orange-500 text-xl"></i></div>
            <div><p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Sensibles N2</p>
                 <h3 class="text-2xl font-black text-orange-600">{{ $stats['nivel2'] }}</h3></div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 border-b-4 border-blue-400 flex items-center gap-3">
            <div class="p-3 rounded-xl bg-blue-50"><i class="fas fa-chalkboard text-blue-500 text-xl"></i></div>
            <div><p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Operativas N3</p>
                 <h3 class="text-2xl font-black text-blue-600">{{ $stats['nivel3'] }}</h3></div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 border-b-4 border-[#39A900] flex items-center gap-3">
            <div class="p-3 rounded-xl bg-green-50"><i class="fas fa-clipboard-check text-[#39A900] text-xl"></i></div>
            <div><p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Con Checklist</p>
                 <h3 class="text-2xl font-black text-[#39A900]">{{ $stats['con_checklist'] }}</h3></div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-400 font-black uppercase tracking-widest text-[10px]">
                    <th class="px-5 py-4 text-left">Código / Área</th>
                    <th class="px-5 py-4 text-left">Sede</th>
                    <th class="px-5 py-4 text-left">Clasificación SENA</th>
                    <th class="px-5 py-4 text-left">CIA</th>
                    <th class="px-5 py-4 text-left">Ubicación</th>
                    <th class="px-5 py-4 text-left">Última Verificación</th>
                    <th class="px-5 py-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($areas as $area)
                @php
                    $nc  = $nivelColor[$area->nivel_sena] ?? ['bg-gray-100 text-gray-600 border-gray-200','bg-gray-400','fa-question'];
                    $v   = $area->ultimaVerificacion;
                    $ciaColor = ['Alto'=>'text-red-600 bg-red-50','Medio'=>'text-orange-600 bg-orange-50','Bajo'=>'text-green-600 bg-green-50'];
                @endphp
                <tr class="hover:bg-gray-50/70 transition">
                    <td class="px-5 py-4">
                        <div class="font-black text-gray-800 text-sm font-mono">{{ $area->codigo }}</div>
                        <div class="text-[10px] text-gray-500 font-bold mt-0.5">{{ $area->nombre_dependencia }}</div>
                        @if($area->tipo_area)
                            <div class="text-[9px] text-gray-400 italic mt-0.5">{{ $area->tipo_area }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-xs font-bold text-gray-600">
                        {{ $area->sede->nombre ?? '—' }}
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[10px] font-black border {{ $nc[0] }}">
                            <i class="fas {{ $nc[2] }} text-[9px]"></i>{{ $area->nivel_sena }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full {{ $ciaColor[$area->nivel_criticidad] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $area->nivel_criticidad }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-[10px] text-gray-500">
                        @if($area->bloque)Bloque {{ $area->bloque }}@endif
                        @if($area->piso) · Piso {{ $area->piso }}@endif
                        @if($area->numero_oficina) · Of. {{ $area->numero_oficina }}@endif
                    </td>
                    <td class="px-5 py-4">
                        @if($v)
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-black {{ $resultadoColor[$v->resultado] ?? '' }}">{{ $v->resultado }}</span>
                            <div class="text-[9px] text-gray-400 mt-0.5">{{ $v->total_cumple }}/{{ $v->total_items }} · {{ $v->fecha_verificacion->format('d/m/Y') }}</div>
                        @else
                            <span class="text-[10px] text-gray-400 italic">Sin verificación</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('areas-seguras.show', $area) }}" class="p-2 bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-500 hover:text-white transition" title="Ver">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('areas-seguras.verificacion.create', $area) }}" class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-500 hover:text-white transition" title="Checklist">
                                <i class="fas fa-clipboard-check text-xs"></i>
                            </a>
                            <a href="{{ route('areas-seguras.edit', $area) }}" class="p-2 bg-orange-50 text-orange-500 rounded-lg hover:bg-orange-500 hover:text-white transition" title="Editar">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-14 text-center text-gray-400">
                        <i class="fas fa-shield-alt text-5xl mb-3 block opacity-10"></i>
                        <p class="font-bold text-xs uppercase tracking-widest">Sin áreas registradas.</p>
                        <p class="text-[10px] mt-1">Registra el Data Center como primera área de Nivel 1 para comenzar.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
