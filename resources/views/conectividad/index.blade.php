@extends('layouts.app')

@section('content')

@php
/*──────────────────────────────────────────────────────────────────────
 | Helpers locales
 *──────────────────────────────────────────────────────────────────────*/
function fmtMac(string $mac = null): string {
    if (!$mac || $mac === 'N/A') return '';
    $c = preg_replace('/[^A-Fa-f0-9]/', '', $mac);
    return strlen($c) === 12 ? implode(':', str_split($c, 2)) : $mac;
}

function tipoModelo(string $modelo = null): array {
    $m = strtolower($modelo ?? '');
    if (str_contains($m, 'airengine6760')) return ['AP Outdoor', 'bg-teal-50 text-teal-700',   'fa-broadcast-tower'];
    if (str_contains($m, 'airengine'))     return ['AP Indoor',  'bg-emerald-50 text-emerald-700','fa-wifi'];
    if (str_contains($m, 's6730'))         return ['Switch Core','bg-violet-50 text-violet-700', 'fa-server'];
    if (str_contains($m, 's48') || str_contains($m, '48p')) return ['Switch 48P','bg-blue-50 text-blue-700','fa-ethernet'];
    return ['Switch 24P', 'bg-indigo-50 text-indigo-700', 'fa-ethernet'];
}

// Agrupar: sede → [switches, aps]
$grouped = $dispositivos->groupBy(fn($d) => $d->ubicacion?->sede?->nombre ?? 'Sin sede');
$sedeKeys = $grouped->keys()->sort()->values();

$isAP = fn($d) =>  str_contains(strtolower($d->modelo ?? ''), 'airengine');
$isSW = fn($d) => !str_contains(strtolower($d->modelo ?? ''), 'airengine');
@endphp

{{-- ── KPI chips ─────────────────────────────────────────────────────────── --}}
<div class="flex flex-wrap items-center gap-3 mb-8">
    <div class="flex items-center gap-2">
        <div class="bg-blue-600 text-white p-2 rounded-xl">
            <i class="fas fa-wifi text-sm w-4 text-center"></i>
        </div>
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 leading-none">Red de Conectividad</p>
            <p class="text-xs font-black text-gray-700 leading-none mt-0.5">HUAWEI · SENA Casanare</p>
        </div>
    </div>

    <div class="ml-4 flex flex-wrap gap-2">
        <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1.5 rounded-lg">
            <span class="w-2 h-2 rounded-full bg-gray-400 inline-block"></span>
            {{ $stats['total'] }} total
        </span>
        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg">
            <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
            {{ $stats['switches'] }} switches
        </span>
        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-lg">
            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
            {{ $stats['aps'] }} access points
        </span>
        <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-lg">
            <i class="fas fa-map-marker-alt text-indigo-400 text-[10px]"></i>
            {{ $stats['sedes'] }} sedes
        </span>
    </div>
</div>

{{-- ── Filtros ────────────────────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('conectividad.index') }}"
      class="flex flex-wrap gap-3 mb-6 bg-white border border-gray-100 rounded-2xl shadow-sm px-5 py-4">

    <div class="flex-1 min-w-[200px] relative">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
        <input type="text" name="buscar" value="{{ request('buscar') }}"
               placeholder="Buscar por placa, serial, MAC o modelo…"
               class="w-full pl-8 pr-3 py-2 text-xs font-semibold border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
    </div>

    <select name="sede"
            class="text-xs font-bold border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-blue-400 bg-white text-gray-600">
        <option value="">Todas las sedes</option>
        @foreach($sedes as $s)
            <option value="{{ $s }}" {{ request('sede') === $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>

    <button type="submit"
            class="bg-blue-600 text-white text-xs font-black uppercase tracking-widest px-4 py-2 rounded-xl hover:bg-blue-700 transition">
        <i class="fas fa-filter mr-1"></i> Filtrar
    </button>

    @if(request('buscar') || request('sede'))
    <a href="{{ route('conectividad.index') }}"
       class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 px-3 py-2 rounded-xl border border-gray-200 hover:border-red-200 transition flex items-center gap-1">
        <i class="fas fa-times text-[10px]"></i> Limpiar
    </a>
    @endif
</form>

@if($dispositivos->isEmpty())
    <div class="bg-white rounded-2xl border border-dashed border-gray-200 py-16 text-center text-gray-400">
        <i class="fas fa-wifi text-4xl mb-3 opacity-20"></i>
        <p class="font-bold text-sm">No se encontraron dispositivos de conectividad</p>
        <p class="text-xs mt-1">Ajusta los filtros de búsqueda</p>
    </div>
@else

{{-- ── Tabs por sede ────────────────────────────────────────────────────── --}}
<div class="border-b border-gray-200 mb-0 flex gap-0 overflow-x-auto">
    @foreach($sedeKeys as $idx => $sedeNombre)
    @php $sedeDev = $grouped[$sedeNombre]; @endphp
    <button type="button"
            onclick="mostrarTab('tab-{{ Str::slug($sedeNombre) }}')"
            id="btn-{{ Str::slug($sedeNombre) }}"
            class="tab-btn flex-shrink-0 px-5 py-3 text-xs font-black uppercase tracking-widest border-b-2 transition-colors whitespace-nowrap
                   {{ $idx === 0 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
        {{ $sedeNombre }}
        <span class="ml-1.5 text-[10px] font-mono font-semibold bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">
            {{ $sedeDev->count() }}
        </span>
    </button>
    @endforeach
</div>

{{-- ── Paneles por sede ─────────────────────────────────────────────────── --}}
@foreach($sedeKeys as $idx => $sedeNombre)
@php
    $sedeDev   = $grouped[$sedeNombre];
    $switches  = $sedeDev->filter($isSW)->sortBy('placa')->values();
    $aps       = $sedeDev->filter($isAP)->sortBy('placa')->values();
    $tabId     = 'tab-' . Str::slug($sedeNombre);
@endphp

<div id="{{ $tabId }}" class="tab-panel {{ $idx !== 0 ? 'hidden' : '' }}">

    {{-- SWITCHES --}}
    @if($switches->count())
    <div class="mt-6 mb-2 flex items-center gap-2">
        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>
            Switches
        </span>
        <span class="text-[10px] text-gray-400 font-mono">{{ $switches->count() }} equipos</span>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm bg-white mb-6">
        <table class="w-full text-xs border-collapse" style="font-variant-numeric: tabular-nums;">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">#</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">Placa</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">Serial</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">Tipo / Modelo</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">MAC</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">Ptos.</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">Ubicación</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($switches as $i => $d)
                @php [$tipoLabel, $tipoCls, $tipoIcon] = tipoModelo($d->modelo); @endphp
                <tr class="hover:bg-blue-50/30 transition-colors">
                    <td class="px-4 py-2.5 text-gray-300 font-mono text-[10px]">{{ $i + 1 }}</td>
                    <td class="px-4 py-2.5">
                        <span class="font-mono font-semibold text-gray-800 text-[11px]">{{ $d->placa }}</span>
                    </td>
                    <td class="px-4 py-2.5">
                        <span class="font-mono text-gray-500 text-[10px]">{{ $d->serial }}</span>
                    </td>
                    <td class="px-4 py-2.5">
                        <div class="flex flex-col gap-0.5">
                            <span class="inline-flex items-center gap-1 text-[10px] font-black px-1.5 py-0.5 rounded {{ $tipoCls }}">
                                <i class="fas {{ $tipoIcon }} text-[9px]"></i> {{ $tipoLabel }}
                            </span>
                            <span class="font-mono text-[10px] text-gray-400">{{ $d->modelo }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-2.5">
                        @if($d->mac_address && $d->mac_address !== 'N/A')
                            <span class="font-mono text-[10px] text-gray-600 tracking-wide">{{ fmtMac($d->mac_address) }}</span>
                        @else
                            <span class="text-gray-200 font-mono text-[10px]">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5">
                        @if($d->puertos)
                            <span class="bg-gray-100 text-gray-600 font-mono font-semibold text-[10px] px-1.5 py-0.5 rounded">{{ $d->puertos }}p</span>
                        @else
                            <span class="text-gray-200 font-mono text-[10px]">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5">
                        @php $amb = $d->ubicacion?->ambiente; @endphp
                        @if($amb && $amb !== 'GENERAL')
                            <span class="text-gray-700 font-semibold text-[11px]">{{ $amb }}</span>
                        @else
                            <span class="text-gray-200 text-[10px]">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5 text-right">
                        <a href="{{ route('dispositivos.show', $d->id) }}"
                           class="text-blue-400 hover:text-blue-600 transition text-[10px] font-black uppercase tracking-widest">
                            Ver <i class="fas fa-arrow-right text-[9px]"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ACCESS POINTS --}}
    @if($aps->count())
    <div class="mt-4 mb-2 flex items-center gap-2">
        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
            Access Points
        </span>
        <span class="text-[10px] text-gray-400 font-mono">{{ $aps->count() }} equipos</span>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm bg-white mb-8">
        <table class="w-full text-xs border-collapse" style="font-variant-numeric: tabular-nums;">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">#</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">Placa</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">Serial</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">Tipo / Modelo</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">MAC</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">Ubicación</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">SW Conectado</th>
                    <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap">Puerto</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($aps as $i => $d)
                @php [$tipoLabel, $tipoCls, $tipoIcon] = tipoModelo($d->modelo); @endphp
                <tr class="hover:bg-emerald-50/30 transition-colors">
                    <td class="px-4 py-2.5 text-gray-300 font-mono text-[10px]">{{ $i + 1 }}</td>
                    <td class="px-4 py-2.5">
                        <span class="font-mono font-semibold text-gray-800 text-[11px]">{{ $d->placa }}</span>
                    </td>
                    <td class="px-4 py-2.5">
                        <span class="font-mono text-gray-500 text-[10px]">{{ $d->serial }}</span>
                    </td>
                    <td class="px-4 py-2.5">
                        <div class="flex flex-col gap-0.5">
                            <span class="inline-flex items-center gap-1 text-[10px] font-black px-1.5 py-0.5 rounded {{ $tipoCls }}">
                                <i class="fas {{ $tipoIcon }} text-[9px]"></i> {{ $tipoLabel }}
                            </span>
                            <span class="font-mono text-[10px] text-gray-400">{{ $d->modelo }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-2.5">
                        @if($d->mac_address && $d->mac_address !== 'N/A')
                            <span class="font-mono text-[10px] text-gray-600 tracking-wide">{{ fmtMac($d->mac_address) }}</span>
                        @else
                            <span class="text-gray-200 font-mono text-[10px]">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5">
                        @php $amb = $d->ubicacion?->ambiente; @endphp
                        @if($amb && $amb !== 'GENERAL')
                            <span class="text-gray-700 font-semibold text-[11px]">{{ $amb }}</span>
                        @else
                            <span class="text-gray-200 text-[10px]">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5 max-w-[200px]">
                        @if($d->ap_conectado_a)
                            <span class="font-mono text-[10px] text-blue-500 block truncate" title="{{ $d->ap_conectado_a }}">
                                {{ $d->ap_conectado_a }}
                            </span>
                        @else
                            <span class="text-gray-200 text-[10px]">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5">
                        @if($d->puerto_origen)
                            <span class="bg-gray-100 text-gray-600 font-mono font-semibold text-[10px] px-1.5 py-0.5 rounded">{{ $d->puerto_origen }}</span>
                        @else
                            <span class="text-gray-200 text-[10px]">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5 text-right">
                        <a href="{{ route('dispositivos.show', $d->id) }}"
                           class="text-emerald-400 hover:text-emerald-600 transition text-[10px] font-black uppercase tracking-widest">
                            Ver <i class="fas fa-arrow-right text-[9px]"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endforeach

@endif {{-- fin de dispositivos.isEmpty() --}}

<script>
function mostrarTab(id) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-blue-500', 'text-blue-600');
        b.classList.add('border-transparent', 'text-gray-400');
    });
    document.getElementById(id).classList.remove('hidden');
    const btn = document.getElementById('btn-' + id.replace('tab-', ''));
    if (btn) {
        btn.classList.remove('border-transparent', 'text-gray-400');
        btn.classList.add('border-blue-500', 'text-blue-600');
    }
}
</script>

@endsection
