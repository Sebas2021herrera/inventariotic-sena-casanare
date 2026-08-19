@extends('layouts.app')
@section('title', 'Catálogo de Software')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight flex items-center gap-3">
                <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-boxes text-white text-sm"></i>
                </div>
                Catálogo de Software
            </h1>
            <p class="text-gray-400 text-xs font-bold mt-1">Repositorio Autorizado — Dirección General SENA</p>
        </div>
        @if(Auth::user()->role === 'admin')
        <button onclick="abrirNuevo()"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-black px-5 py-2.5 rounded-xl text-sm uppercase tracking-widest transition shadow-md">
            <i class="fas fa-plus"></i> Nuevo Software
        </button>
        @endif
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
        <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
        <i class="fas fa-exclamation-circle text-red-500"></i> {{ $errors->first() }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center col-span-2 sm:col-span-1">
            <p class="text-2xl font-black text-gray-800">{{ $stats['total'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Total</p>
        </div>
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-black text-blue-600">{{ $stats['licenciados'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Licenciados</p>
        </div>
        <div class="bg-white rounded-2xl border border-green-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-black text-green-600">{{ $stats['libres'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Libres</p>
        </div>
        <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-black text-red-500">{{ $stats['no_autorizados'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">No Autoriz.</p>
        </div>
        <div class="bg-white rounded-2xl border border-orange-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-black text-orange-500">{{ $stats['inactivos'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Inactivos</p>
        </div>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('sw.catalogo.index') }}"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-col sm:flex-row gap-3">
        <input type="text" name="buscar" value="{{ request('buscar') }}"
               placeholder="Buscar por nombre o subproducto..."
               class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold focus:outline-none focus:border-indigo-400">
        <select name="tipo"
                class="border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold focus:outline-none focus:border-indigo-400">
            <option value="">Todos los tipos</option>
            <option value="licenciado"    {{ request('tipo') === 'licenciado'    ? 'selected' : '' }}>Licenciado</option>
            <option value="libre"         {{ request('tipo') === 'libre'         ? 'selected' : '' }}>Libre / Open Source</option>
            <option value="no_autorizado" {{ request('tipo') === 'no_autorizado' ? 'selected' : '' }}>No Autorizado</option>
        </select>
        <button type="submit"
                class="bg-indigo-600 text-white font-black px-5 py-2 rounded-xl text-sm uppercase tracking-widest hover:bg-indigo-700 transition">
            <i class="fas fa-search mr-1"></i> Filtrar
        </button>
        @if(request('buscar') || request('tipo'))
        <a href="{{ route('sw.catalogo.index') }}"
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
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Producto</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Subproducto</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Tipo</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Vigencia</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Estado</th>
                    @if(Auth::user()->role === 'admin')
                    <th class="px-5 py-3 text-right text-[10px] font-black text-gray-400 uppercase">Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($software as $sw)
                <tr class="hover:bg-gray-50 transition {{ $sw->activo ? '' : 'opacity-50' }}">
                    <td class="px-5 py-3 font-bold text-gray-800 text-xs">{{ $sw->nombre }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $sw->subproducto ?? '—' }}</td>
                    <td class="px-5 py-3">
                        @if($sw->tipo === 'licenciado')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-[9px] font-black rounded-full uppercase">
                                <i class="fas fa-certificate text-[8px]"></i> Licenciado
                            </span>
                        @elseif($sw->tipo === 'libre')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-700 text-[9px] font-black rounded-full uppercase">
                                <i class="fas fa-unlock text-[8px]"></i> Libre
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-600 text-[9px] font-black rounded-full uppercase">
                                <i class="fas fa-ban text-[8px]"></i> No Autorizado
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400 font-bold">
                        @if($sw->vigencia_hasta)
                            @php $vencido = $sw->vigencia_hasta->isPast(); @endphp
                            <span class="{{ $vencido ? 'text-red-500' : 'text-gray-600' }}">
                                {{ $sw->vigencia_hasta->format('d/m/Y') }}
                                @if($vencido) <span class="text-[9px] bg-red-100 text-red-500 px-1 py-0.5 rounded font-black">VENCIDA</span> @endif
                            </span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($sw->activo)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-700 text-[9px] font-black rounded-full uppercase">
                                <i class="fas fa-check-circle text-[8px]"></i> Activo
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-400 text-[9px] font-black rounded-full uppercase">
                                <i class="fas fa-eye-slash text-[8px]"></i> Inactivo
                            </span>
                        @endif
                    </td>
                    @if(Auth::user()->role === 'admin')
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="abrirEditar({{ $sw->id }}, {{ json_encode($sw->nombre) }}, {{ json_encode($sw->subproducto) }}, '{{ $sw->tipo }}', '{{ $sw->vigencia_hasta?->format('Y-m-d') }}')"
                                    class="text-blue-400 hover:text-blue-600 transition p-1" title="Editar">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </button>
                            <form method="POST" action="{{ route('sw.catalogo.toggle', $sw) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="{{ $sw->activo ? 'text-orange-400 hover:text-orange-600' : 'text-green-500 hover:text-green-700' }} transition p-1"
                                        title="{{ $sw->activo ? 'Desactivar' : 'Activar' }}">
                                    <i class="fas {{ $sw->activo ? 'fa-eye-slash' : 'fa-eye' }} text-xs"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('sw.catalogo.destroy', $sw) }}"
                                  onsubmit="return confirm('¿Eliminar permanentemente esta entrada del catálogo?')">
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
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                        <i class="fas fa-boxes text-4xl mb-3 block opacity-20"></i>
                        <p class="font-bold text-sm">No se encontraron entradas</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($software->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $software->links() }}
        </div>
        @endif
    </div>

</div>

@if(Auth::user()->role === 'admin')

{{-- Modal: Nuevo Software --}}
<div id="modal-nuevo" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-indigo-600 text-white px-6 py-4 flex items-center justify-between">
            <p class="font-black text-sm uppercase tracking-tight"><i class="fas fa-plus mr-2"></i> Nuevo Software en Catálogo</p>
            <button onclick="cerrarModal('modal-nuevo')" class="text-white/70 hover:text-white transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('sw.catalogo.store') }}" class="p-6 space-y-4">
            @csrf
            @include('software._form')
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-2.5 rounded-xl text-sm uppercase tracking-widest transition">
                    <i class="fas fa-save mr-1"></i> Guardar
                </button>
                <button type="button" onclick="cerrarModal('modal-nuevo')"
                        class="px-5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-black rounded-xl text-sm uppercase tracking-widest transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Editar Software --}}
<div id="modal-editar" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
            <p class="font-black text-sm uppercase tracking-tight"><i class="fas fa-pencil-alt mr-2"></i> Editar Entrada</p>
            <button onclick="cerrarModal('modal-editar')" class="text-white/70 hover:text-white transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="form-editar" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            @include('software._form', ['edit' => true])
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-2.5 rounded-xl text-sm uppercase tracking-widest transition">
                    <i class="fas fa-save mr-1"></i> Actualizar
                </button>
                <button type="button" onclick="cerrarModal('modal-editar')"
                        class="px-5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-black rounded-xl text-sm uppercase tracking-widest transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirNuevo() {
    const m = document.getElementById('modal-nuevo');
    m.classList.remove('hidden'); m.classList.add('flex');
}

function abrirEditar(id, nombre, subproducto, tipo, vigencia) {
    const f = document.getElementById('form-editar');
    f.action = '/software-catalogo/' + id;
    f.querySelector('[name="nombre"]').value      = nombre || '';
    f.querySelector('[name="subproducto"]').value  = subproducto || '';
    f.querySelector('[name="vigencia_hasta"]').value = vigencia || '';
    const sel = f.querySelector('[name="tipo"]');
    for (let opt of sel.options) opt.selected = opt.value === tipo;
    const m = document.getElementById('modal-editar');
    m.classList.remove('hidden'); m.classList.add('flex');
}

function cerrarModal(id) {
    const m = document.getElementById(id);
    m.classList.add('hidden'); m.classList.remove('flex');
}

document.querySelectorAll('#modal-nuevo, #modal-editar').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) cerrarModal(m.id); });
});
</script>
@endif

@endsection
