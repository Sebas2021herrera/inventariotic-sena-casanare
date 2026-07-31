@extends('layouts.app')
@section('title', 'Cuentas Intune')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fab fa-microsoft text-white text-sm"></i>
                </div>
                Cuentas Intune
            </h1>
            <p class="text-gray-400 text-xs font-bold mt-1">Licencias Microsoft Intune — formato: {IdSede}_{placa}@senaedu.edu.co</p>
        </div>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('intune.cargar') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-black px-5 py-2.5 rounded-xl text-sm uppercase tracking-widest transition shadow-md">
            <i class="fas fa-upload"></i> Cargar Cuentas
        </a>
        @endif
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
        <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
            <p class="text-3xl font-black text-gray-800">{{ $stats['total'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Total Cuentas</p>
        </div>
        <div class="bg-white rounded-2xl border border-green-100 shadow-sm p-5 text-center">
            <p class="text-3xl font-black text-green-600">{{ $stats['activas'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Activas</p>
        </div>
        <div class="bg-white rounded-2xl border border-orange-100 shadow-sm p-5 text-center">
            <p class="text-3xl font-black text-orange-500">{{ $stats['pendientes'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Pendientes por asignar</p>
        </div>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('intune.index') }}"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-col sm:flex-row gap-3">
        <input type="text" name="buscar" value="{{ request('buscar') }}"
               placeholder="Buscar por placa, cuenta o sede..."
               class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold focus:outline-none focus:border-blue-400">
        <select name="estado"
                class="border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold focus:outline-none focus:border-blue-400">
            <option value="">Todos los estados</option>
            <option value="activa"    {{ request('estado') === 'activa'    ? 'selected' : '' }}>Activas</option>
            <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendientes</option>
        </select>
        <button type="submit"
                class="bg-blue-600 text-white font-black px-5 py-2 rounded-xl text-sm uppercase tracking-widest hover:bg-blue-700 transition">
            <i class="fas fa-search mr-1"></i> Filtrar
        </button>
        @if(request('buscar') || request('estado'))
        <a href="{{ route('intune.index') }}"
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
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Cuenta</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Placa</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Sede</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Dispositivo</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Estado</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Reporte</th>
                    @if(Auth::user()->role === 'admin')
                    <th class="px-5 py-3 text-right text-[10px] font-black text-gray-400 uppercase"></th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($cuentas as $c)
                @php $disp = $c->dispositivo; @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-mono text-xs text-blue-700 font-bold">{{ $c->cuenta }}</td>
                    <td class="px-5 py-3">
                        @if($disp)
                            <a href="{{ route('dispositivos.show', $disp) }}"
                               class="font-black text-gray-800 hover:text-[#39A900] transition">
                                {{ $c->placa }}
                            </a>
                        @else
                            <span class="font-bold text-gray-500">{{ $c->placa }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 font-bold text-gray-600">{{ $c->id_sede }}</td>
                    <td class="px-5 py-3">
                        @if($disp)
                            <div>
                                <p class="font-bold text-gray-700 text-xs">{{ $disp->marca }} {{ $disp->modelo }}</p>
                                <p class="text-[10px] text-gray-400">{{ $disp->tipo_equipo }}</p>
                            </div>
                        @else
                            <span class="text-[10px] text-gray-400 italic">Sin coincidencia en inventario</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($c->estado === 'activa')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-700 text-[10px] font-black rounded-full uppercase">
                                <i class="fas fa-check-circle text-[8px]"></i> Activa
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-orange-50 text-orange-600 text-[10px] font-black rounded-full uppercase">
                                <i class="fas fa-clock text-[8px]"></i> Pendiente por asignar
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400 font-bold">
                        {{ $c->fecha_reporte ? $c->fecha_reporte->format('d/m/Y') : '—' }}
                    </td>
                    @if(Auth::user()->role === 'admin')
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="abrirEditar({{ $c->id }}, '{{ $c->fecha_reporte?->format('Y-m-d') }}', {{ json_encode($c->notas) }})"
                                    class="text-blue-400 hover:text-blue-600 transition p-1" title="Editar">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </button>
                            <form method="POST" action="{{ route('intune.destroy', $c) }}"
                                  onsubmit="return confirm('¿Eliminar esta cuenta Intune?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-red-400 hover:text-red-600 transition p-1" title="Eliminar">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <i class="fab fa-microsoft text-4xl mb-3 block opacity-20"></i>
                        <p class="font-bold text-sm">No se encontraron cuentas Intune</p>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('intune.cargar') }}" class="text-blue-500 text-xs font-black mt-2 inline-block hover:underline">
                                Cargar cuentas →
                            </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($cuentas->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $cuentas->links() }}
        </div>
        @endif
    </div>

</div>

@if(Auth::user()->role === 'admin')
{{-- Modal editar --}}
<div id="modal-editar" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
            <p class="font-black text-sm uppercase tracking-tight">Editar Cuenta Intune</p>
            <button onclick="cerrarEditar()" class="text-white/70 hover:text-white transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="form-editar" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Fecha del Reporte</label>
                <input type="date" name="fecha_reporte" id="edit-fecha"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none focus:border-blue-400">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Notas</label>
                <textarea name="notas" id="edit-notas" rows="3"
                          placeholder="Observaciones opcionales..."
                          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none focus:border-blue-400 resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-2.5 rounded-xl text-sm uppercase tracking-widest transition">
                    Guardar
                </button>
                <button type="button" onclick="cerrarEditar()"
                        class="px-5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-black rounded-xl text-sm uppercase tracking-widest transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirEditar(id, fecha, notas) {
    document.getElementById('form-editar').action = '/intune/' + id;
    document.getElementById('edit-fecha').value  = fecha || '';
    document.getElementById('edit-notas').value  = notas || '';
    const modal = document.getElementById('modal-editar');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function cerrarEditar() {
    const modal = document.getElementById('modal-editar');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
document.getElementById('modal-editar').addEventListener('click', function(e) {
    if (e.target === this) cerrarEditar();
});
</script>
@endif

@endsection
