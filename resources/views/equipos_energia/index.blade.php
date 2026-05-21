@extends('layouts.app')

@section('content')
@php
use App\Models\EquipoEnergia;
$tiposIcono  = EquipoEnergia::TIPOS_ICONO;
$estadoColor = EquipoEnergia::ESTADOS_COLOR;
@endphp

<div class="max-w-7xl mx-auto space-y-8">

    {{-- Cabecera --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Energía <span class="text-[#39A900]">Regulada</span>
            </h1>
            <p class="text-gray-500 font-bold text-sm italic">Inventario de UPS, Reguladores, Plantas y Tableros de Transferencia</p>
        </div>
        <a href="{{ route('equipos-energia.create') }}"
           class="bg-[#39A900] text-white px-5 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg hover:bg-green-700 transition flex items-center gap-2">
            <i class="fas fa-bolt"></i> Registrar Equipo
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl px-5 py-4 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- KPIs --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3">
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm p-4 border-b-4 border-[#39A900] flex flex-col items-center text-center">
            <i class="fas fa-plug text-[#39A900] text-2xl mb-1"></i>
            <p class="text-2xl font-black text-gray-800">{{ $stats['total'] }}</p>
            <p class="text-[9px] font-black text-gray-400 uppercase">Total</p>
        </div>
        @foreach(EquipoEnergia::TIPOS as $tipo)
        @php $cfg = $tiposIcono[$tipo]; $cnt = $stats['por_tipo'][$tipo] ?? 0; @endphp
        <div class="bg-white rounded-2xl shadow-sm p-4 border-b-4 border-{{ $cfg[1] }}-400 flex flex-col items-center text-center">
            <i class="fas {{ $cfg[0] }} text-{{ $cfg[1] }}-500 text-xl mb-1"></i>
            <p class="text-xl font-black text-gray-800">{{ $cnt }}</p>
            <p class="text-[8px] font-black text-gray-400 uppercase leading-tight text-center">{{ Str::words($tipo, 2, '') }}</p>
        </div>
        @endforeach
        <div class="bg-white rounded-2xl shadow-sm p-4 border-b-4 border-red-400 flex flex-col items-center text-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-xl mb-1"></i>
            <p class="text-xl font-black text-red-600">{{ $stats['mantenimiento_vencido'] }}</p>
            <p class="text-[8px] font-black text-gray-400 uppercase">Mant. vencido</p>
        </div>
    </div>

    {{-- Filtros --}}
    <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 items-end">
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Marca, modelo, placa, cuarto..."
                       class="w-full bg-gray-50 border-gray-200 rounded-xl p-2.5 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Tipo</label>
                <select name="tipo" class="w-full bg-gray-50 border-gray-200 rounded-xl p-2.5 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                    <option value="">Todos</option>
                    @foreach(EquipoEnergia::TIPOS as $t)
                        <option value="{{ $t }}" {{ request('tipo') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Sede</label>
                <select name="sede" class="w-full bg-gray-50 border-gray-200 rounded-xl p-2.5 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                    <option value="">Todas</option>
                    @foreach($sedes as $id => $nombre)
                        <option value="{{ $id }}" {{ request('sede') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-[#39A900] text-white py-2.5 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-green-700 transition">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('equipos-energia.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-600 py-2.5 rounded-xl font-black text-xs uppercase transition">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </form>

    {{-- Tabla --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-400 font-black uppercase tracking-widest text-[10px]">
                    <th class="px-4 py-3 text-left">Tipo / Marca</th>
                    <th class="px-4 py-3 text-left">Ubicación</th>
                    <th class="px-4 py-3 text-left">Placa / S/N</th>
                    <th class="px-4 py-3 text-left">Fase / Potencia</th>
                    <th class="px-4 py-3 text-left">Batería / Respaldo</th>
                    <th class="px-4 py-3 text-left">Estado</th>
                    <th class="px-4 py-3 text-left">Mantenimiento</th>
                    <th class="px-4 py-3 text-center">Acc.</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($equipos as $eq)
                @php
                    $ic    = $tiposIcono[$eq->tipo] ?? ['fa-bolt','gray'];
                    $ec    = $estadoColor[$eq->estado] ?? 'bg-gray-100 text-gray-600';
                    $venc  = $eq->proximo_mantenimiento?->isPast();
                    $prox  = $eq->proximo_mantenimiento && $eq->proximo_mantenimiento->isFuture()
                             && $eq->proximo_mantenimiento->diffInDays(now()) <= 30;
                @endphp
                <tr class="hover:bg-gray-50/70 transition {{ $venc ? 'bg-red-50/30' : '' }}">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 rounded-lg bg-{{ $ic[1] }}-50 flex-shrink-0">
                                <i class="fas {{ $ic[0] }} text-{{ $ic[1] }}-500 text-xs"></i>
                            </div>
                            <div>
                                <div class="font-black text-gray-800 text-xs">{{ $eq->marca }} {{ $eq->modelo }}</div>
                                <div class="text-[10px] text-gray-400">{{ $eq->tipo }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600">
                        <div class="font-bold">{{ $eq->cuarto }}</div>
                        <div class="text-[10px] text-gray-400">{{ $eq->sede->nombre ?? '—' }}</div>
                    </td>
                    <td class="px-4 py-3 font-mono text-[10px] text-gray-600">
                        {{ $eq->placa ?? '—' }}<br>
                        <span class="text-gray-400">{{ $eq->numero_serie ?? '' }}</span>
                    </td>
                    <td class="px-4 py-3 text-[10px] text-gray-600">
                        @if($eq->fase)<div class="font-bold">{{ $eq->fase }}</div>@endif
                        @if($eq->potencia_va)<div>{{ number_format($eq->potencia_va) }} VA</div>@endif
                        @if($eq->potencia_w)<div>{{ number_format($eq->potencia_w) }} W</div>@endif
                    </td>
                    <td class="px-4 py-3 text-[10px] text-gray-600">
                        @if($eq->capacidad_baterias_ah)<div>{{ $eq->capacidad_baterias_ah }} Ah</div>@endif
                        @if($eq->numero_baterias)<div>{{ $eq->numero_baterias }} bat.</div>@endif
                        @if($eq->tiempo_respaldo_min)<div class="font-bold text-[#39A900]">{{ $eq->tiempo_respaldo_min }} min</div>@endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-black {{ $ec }}">{{ $eq->estado }}</span>
                        @if($eq->marquillado)
                            <div class="mt-0.5"><span class="text-[9px] px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 font-bold">Marquillado</span></div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-[10px]">
                        @if($eq->proximo_mantenimiento)
                            <span class="{{ $venc ? 'text-red-600 font-black' : ($prox ? 'text-orange-500 font-bold' : 'text-gray-500') }}">
                                <i class="fas {{ $venc ? 'fa-exclamation-triangle' : 'fa-calendar' }} mr-1"></i>
                                {{ $eq->proximo_mantenimiento->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="text-gray-300 italic">Sin programar</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-center gap-1.5">
                            <a href="{{ route('equipos-energia.show', $eq) }}"
                               class="p-1.5 bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-500 hover:text-white transition" title="Ver">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('equipos-energia.edit', $eq) }}"
                               class="p-1.5 bg-orange-50 text-orange-500 rounded-lg hover:bg-orange-500 hover:text-white transition" title="Editar">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-14 text-center text-gray-400">
                        <i class="fas fa-bolt text-5xl mb-3 block opacity-10"></i>
                        <p class="font-bold text-xs uppercase tracking-widest">Sin equipos registrados.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100">{{ $equipos->links() }}</div>
    </div>

</div>
@endsection
