@extends('layouts.app')
@section('title', 'Mantenimientos')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight flex items-center gap-3">
                <div class="w-9 h-9 bg-orange-400 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tools text-white text-sm"></i>
                </div>
                Mantenimientos
            </h1>
            <p class="text-gray-400 text-xs font-bold mt-1">Registro de mantenimientos preventivos y correctivos</p>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
        <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
            <p class="text-3xl font-black text-gray-800">{{ $stats['total'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Total</p>
        </div>
        <div class="bg-white rounded-2xl border border-orange-100 shadow-sm p-5 text-center">
            <p class="text-3xl font-black text-orange-500">{{ $stats['pendientes'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">En proceso</p>
        </div>
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-5 text-center">
            <p class="text-3xl font-black text-blue-500">{{ $stats['preventivos'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Preventivos</p>
        </div>
        <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-5 text-center">
            <p class="text-3xl font-black text-red-500">{{ $stats['correctivos'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Correctivos</p>
        </div>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('mantenimientos.index') }}"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-col sm:flex-row gap-3">
        <input type="text" name="buscar" value="{{ request('buscar') }}"
               placeholder="Buscar por placa, marca o técnico..."
               class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold focus:outline-none focus:border-orange-400">
        <select name="tipo"
                class="border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold focus:outline-none focus:border-orange-400">
            <option value="">Todos los tipos</option>
            <option value="Preventivo" {{ request('tipo') === 'Preventivo' ? 'selected' : '' }}>Preventivo</option>
            <option value="Correctivo" {{ request('tipo') === 'Correctivo' ? 'selected' : '' }}>Correctivo</option>
        </select>
        <label class="flex items-center gap-2 border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold cursor-pointer hover:border-orange-300 transition">
            <input type="checkbox" name="pendientes" value="1" {{ request('pendientes') ? 'checked' : '' }}
                   class="accent-orange-400">
            Solo en proceso
        </label>
        <button type="submit"
                class="bg-orange-400 text-white font-black px-5 py-2 rounded-xl text-sm uppercase tracking-widest hover:bg-orange-500 transition">
            <i class="fas fa-search mr-1"></i> Filtrar
        </button>
        @if(request('buscar') || request('tipo') || request('pendientes'))
        <a href="{{ route('mantenimientos.index') }}"
           class="bg-gray-100 text-gray-600 font-black px-4 py-2 rounded-xl text-sm uppercase tracking-widest hover:bg-gray-200 transition flex items-center gap-1">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Fecha</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Equipo</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Tipo</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Técnico</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Estado</th>
                    <th class="px-5 py-3 text-right text-[10px] font-black text-gray-400 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($mantenimientos as $m)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-bold text-gray-600 text-xs whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y') }}
                    </td>
                    <td class="px-5 py-3">
                        @if($m->dispositivo)
                            <a href="{{ route('dispositivos.show', $m->dispositivo) }}"
                               class="font-black text-gray-800 hover:text-[#39A900] transition text-xs">
                                {{ $m->dispositivo->placa }}
                            </a>
                            <p class="text-[10px] text-gray-400">{{ $m->dispositivo->marca }} {{ $m->dispositivo->modelo }}</p>
                        @else
                            <span class="text-gray-400 text-xs italic">Equipo eliminado</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($m->tipo === 'Preventivo')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-[10px] font-black rounded-full uppercase">
                                <i class="fas fa-shield-alt text-[8px]"></i> Preventivo
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-600 text-[10px] font-black rounded-full uppercase">
                                <i class="fas fa-wrench text-[8px]"></i> Correctivo
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-xs font-bold text-gray-700">{{ $m->tecnico_encargado }}</td>
                    <td class="px-5 py-3">
                        @if($m->finalizado)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-700 text-[10px] font-black rounded-full uppercase">
                                <i class="fas fa-check-circle text-[8px]"></i> Finalizado
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-orange-50 text-orange-600 text-[10px] font-black rounded-full uppercase">
                                <i class="fas fa-clock text-[8px]"></i> En proceso
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('mantenimientos.pdf', $m) }}"
                               class="text-gray-400 hover:text-gray-700 transition p-1" title="Descargar PDF">
                                <i class="fas fa-file-pdf text-xs"></i>
                            </a>
                            <a href="{{ route('mantenimientos.edit', $m) }}"
                               class="text-blue-400 hover:text-blue-600 transition p-1" title="Editar">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </a>
                            @if(Auth::user()->role === 'admin')
                            <form method="POST" action="{{ route('mantenimientos.destroy', $m) }}"
                                  onsubmit="return confirm('¿Eliminar este registro de mantenimiento?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 transition p-1" title="Eliminar">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                        <i class="fas fa-tools text-4xl mb-3 block opacity-20"></i>
                        <p class="font-bold text-sm">No hay registros de mantenimiento</p>
                        <p class="text-xs mt-1">Los mantenimientos se crean desde el detalle de cada equipo.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($mantenimientos->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $mantenimientos->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
