@extends('layouts.app')

@section('content')
@php
$nivelColor = [
    'Nivel 1' => ['bg-blue-100 text-blue-700 border-blue-300',   'bg-blue-500',   'fa-building'],
    'Nivel 2' => ['bg-orange-100 text-orange-700 border-orange-300','bg-orange-400','fa-network-wired'],
    'Nivel 3' => ['bg-red-100 text-red-700 border-red-300',       'bg-red-500',    'fa-server'],
];
$resultadoColor = [
    'Conforme'                   => 'bg-green-100 text-green-700',
    'No Conforme'                => 'bg-red-100 text-red-700',
    'Conforme con Observaciones' => 'bg-yellow-100 text-yellow-700',
    'En Proceso'                 => 'bg-blue-100 text-blue-700',
];
@endphp

<div class="max-w-7xl mx-auto space-y-7">

    {{-- Cabecera --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Áreas <span class="text-[#39A900]">Seguras</span>
            </h1>
            <p class="text-gray-500 font-bold text-sm italic">
                GIL-F-101 Inventario · GIL-F-102 Verificación de Controles
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('areas-seguras.exportar-consolidado-excel') }}"
               class="bg-[#166534] text-white px-4 py-2.5 rounded-2xl font-black uppercase text-xs tracking-widest shadow hover:bg-green-900 transition flex items-center gap-2">
                <i class="fas fa-file-excel"></i> GIL-F-101 Excel
            </a>
            <a href="{{ route('areas-seguras.exportar-consolidado') }}"
               class="bg-[#1e3a5f] text-white px-4 py-2.5 rounded-2xl font-black uppercase text-xs tracking-widest shadow hover:bg-blue-900 transition flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> GIL-F-101 PDF
            </a>
            <a href="{{ route('areas-seguras.create') }}"
               class="bg-[#39A900] text-white px-4 py-2.5 rounded-2xl font-black uppercase text-xs tracking-widest shadow hover:bg-green-700 transition flex items-center gap-2">
                <i class="fas fa-shield-alt"></i> Registrar Área
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl px-5 py-3 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Panel de Clasificación SENA (Niveles GIL) --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#1e3a5f] px-6 py-3">
            <h2 class="text-white font-black uppercase text-xs tracking-widest flex items-center gap-2">
                <i class="fas fa-layer-group"></i>
                Clasificación de Áreas Seguras — Lineamiento SENA (Centro Agroindustrial y Fortalecimiento Empresarial)
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100">
            @foreach($nivelesSena as $nivel => $cfg)
            @php
                $cnt    = match($nivel){ 'Nivel 1'=>$stats['nivel1'],'Nivel 2'=>$stats['nivel2'],'Nivel 3'=>$stats['nivel3'],default=>0 };
                $colores = $nivelColor[$nivel];
                $hexColor = match($nivel){ 'Nivel 3'=>'#ef4444','Nivel 2'=>'#f97316','Nivel 1'=>'#3b82f6',default=>'#6b7280' };
            @endphp
            <div class="p-5">
                <div class="flex items-start gap-4">
                    <div class="p-3 rounded-2xl flex-shrink-0" style="background:{{ $hexColor }};">
                        <i class="fas {{ $cfg['icono'] }} text-white text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-black text-gray-800 text-sm">{{ $nivel }}</h3>
                            <span class="text-2xl font-black {{ explode(' ',$colores[0])[1] }}">{{ $cnt }}</span>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">{{ $cfg['acceso'] }}</p>
                        <ul class="space-y-0.5">
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
        <div class="bg-white rounded-2xl shadow-sm p-4 border-b-4 border-gray-300 flex items-center gap-3">
            <div class="p-2.5 rounded-xl bg-gray-100"><i class="fas fa-shield-alt text-gray-500 text-lg"></i></div>
            <div><p class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Total Áreas</p>
                 <h3 class="text-2xl font-black text-gray-800">{{ $stats['total'] }}</h3></div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-4 border-b-4 border-blue-400 flex items-center gap-3">
            <div class="p-2.5 rounded-xl bg-blue-50"><i class="fas fa-building text-blue-500 text-lg"></i></div>
            <div><p class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Nivel 1</p>
                 <h3 class="text-2xl font-black text-blue-600">{{ $stats['nivel1'] }}</h3></div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-4 border-b-4 border-orange-400 flex items-center gap-3">
            <div class="p-2.5 rounded-xl bg-orange-50"><i class="fas fa-network-wired text-orange-500 text-lg"></i></div>
            <div><p class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Nivel 2</p>
                 <h3 class="text-2xl font-black text-orange-600">{{ $stats['nivel2'] }}</h3></div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-4 border-b-4 border-red-400 flex items-center gap-3">
            <div class="p-2.5 rounded-xl bg-red-50"><i class="fas fa-server text-red-500 text-lg"></i></div>
            <div><p class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Nivel 3</p>
                 <h3 class="text-2xl font-black text-red-600">{{ $stats['nivel3'] }}</h3></div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-4 border-b-4 border-[#39A900] flex items-center gap-3">
            <div class="p-2.5 rounded-xl bg-green-50"><i class="fas fa-clipboard-check text-[#39A900] text-lg"></i></div>
            <div><p class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Con GIL-F-102</p>
                 <h3 class="text-2xl font-black text-[#39A900]">{{ $stats['con_checklist'] }}</h3></div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-400 font-black uppercase tracking-widest text-[9px]">
                    <th class="px-5 py-4 text-left">ID Zona / Área</th>
                    <th class="px-4 py-4 text-left">Sede</th>
                    <th class="px-4 py-4 text-left">Nivel / Zona</th>
                    <th class="px-4 py-4 text-left">Responsable</th>
                    <th class="px-4 py-4 text-left">Controles</th>
                    <th class="px-4 py-4 text-left">Última Verificación</th>
                    <th class="px-4 py-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($areas as $area)
                @php
                    $nc  = $nivelColor[$area->nivel_sena] ?? ['bg-gray-100 text-gray-600 border-gray-200','bg-gray-400','fa-question'];
                    $v   = $area->ultimaVerificacion;
                @endphp
                <tr class="hover:bg-gray-50/70 transition">
                    <td class="px-5 py-4">
                        <div class="font-black text-gray-800 text-sm font-mono">{{ $area->codigo }}</div>
                        <div class="text-[10px] text-gray-500 font-bold mt-0.5">{{ $area->nombre_dependencia }}</div>
                        @if($area->tipo_area)
                            <div class="text-[9px] text-gray-400 italic mt-0.5">{{ $area->tipo_area }}</div>
                        @endif
                        @if($area->fecha_inventario)
                            <div class="text-[9px] text-gray-300 mt-0.5">inv. {{ $area->fecha_inventario->format('d/m/Y') }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-xs font-bold text-gray-600">{{ $area->sede->nombre ?? '—' }}</td>
                    <td class="px-4 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-xl text-[9px] font-black border {{ $nc[0] }}">
                            <i class="fas {{ $nc[2] }} text-[8px]"></i>{{ $area->nivel_sena }}
                        </span>
                        @if($area->zonificacion)
                            <div class="text-[9px] text-gray-400 mt-1 font-bold">{{ Str::before($area->zonificacion, ' -') ?: $area->zonificacion }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <div class="text-xs font-bold text-gray-700">{{ $area->responsable_cargo }}</div>
                        @if($area->responsable_nombre)
                            <div class="text-[10px] text-gray-400">{{ $area->responsable_nombre }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        @if($area->controles_acceso && count($area->controles_acceso))
                            <span class="text-[9px] font-black bg-green-50 text-green-700 px-2 py-0.5 rounded-lg">
                                <i class="fas fa-lock text-[8px] mr-1"></i>{{ count($area->controles_acceso) }} acceso
                            </span>
                        @endif
                        @if($area->controles_monitoreo && count($area->controles_monitoreo))
                            <span class="text-[9px] font-black bg-blue-50 text-blue-700 px-2 py-0.5 rounded-lg ml-1">
                                <i class="fas fa-video text-[8px] mr-1"></i>{{ count($area->controles_monitoreo) }} monitoreo
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        @if($v)
                            <span class="text-[9px] px-2 py-0.5 rounded-full font-black {{ $resultadoColor[$v->resultado] ?? 'bg-gray-100 text-gray-500' }}">{{ $v->resultado }}</span>
                            <div class="text-[9px] text-gray-400 mt-0.5 font-bold">
                                {{ $v->total_cumple }}C @if($v->cumple_parcial_count > 0)+{{ $v->cumple_parcial_count }}P @endif / {{ $v->total_items }}
                                · {{ $v->fecha_verificacion->format('d/m/Y') }}
                            </div>
                        @else
                            <span class="text-[9px] text-gray-400 italic">Sin verificación</span>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('areas-seguras.show', $area) }}"
                               class="p-2 bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-500 hover:text-white transition" title="Ver detalle">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('areas-seguras.verificacion.create', $area) }}"
                               class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-500 hover:text-white transition" title="Nueva Verificación GIL-F-102">
                                <i class="fas fa-clipboard-check text-xs"></i>
                            </a>
                            <a href="{{ route('areas-seguras.edit', $area) }}"
                               class="p-2 bg-orange-50 text-orange-500 rounded-lg hover:bg-orange-500 hover:text-white transition" title="Editar">
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
                        <p class="text-[10px] mt-1">Registra las áreas en el formato GIL-F-101 para comenzar el inventario.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
