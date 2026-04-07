@extends('layouts.app')

@section('content')

@php
    $activeFilters = collect([
        'estado'    => request('estado'),
        'categoria' => request('categoria'),
        'intune'    => request('intune'),
        'sede'      => request('sede'),
    ])->filter()->count();

    $estadoConfig = [
        'BUENO'         => ['bg-green-100 text-green-700',  'fa-check-circle'],
        'REGULAR'       => ['bg-orange-100 text-orange-700','fa-exclamation-circle'],
        'MALO'          => ['bg-red-100 text-red-700',      'fa-times-circle'],
        'EN REPARACIÓN' => ['bg-yellow-100 text-yellow-700','fa-tools'],
    ];

    $catConfig = [
        'conectividad' => ['bg-blue-100 text-blue-700',   'Red'],
        'computo'      => ['bg-gray-100 text-gray-600',   'PC'],
        'impresora'    => ['bg-purple-100 text-purple-700','Impresora'],
        'servidor'     => ['bg-rose-100 text-rose-700',   'Servidor'],
    ];
@endphp

{{-- KPI CARDS --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-b-4 border-blue-500 flex items-center gap-4">
        <div class="p-3 rounded-xl bg-blue-50 text-blue-500"><i class="fas fa-laptop text-xl"></i></div>
        <div>
            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Total Equipos</p>
            <h3 class="text-2xl font-black text-gray-800">{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-b-4 border-green-500 flex items-center gap-4">
        <div class="p-3 rounded-xl bg-green-50 text-green-500"><i class="fas fa-check-circle text-xl"></i></div>
        <div>
            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Estado Bueno</p>
            <h3 class="text-2xl font-black text-gray-800">{{ $stats['buenos'] }}</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-b-4 border-orange-500 flex items-center gap-4">
        <div class="p-3 rounded-xl bg-orange-50 text-orange-500"><i class="fas fa-exclamation-triangle text-xl"></i></div>
        <div>
            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Con Novedades</p>
            <h3 class="text-2xl font-black text-gray-800">{{ $stats['criticos'] }}</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-b-4 border-cyan-600 flex items-center gap-4">
        <div class="p-3 rounded-xl bg-cyan-50 text-cyan-600"><i class="fas fa-network-wired text-xl"></i></div>
        <div>
            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Equipos de Red</p>
            <h3 class="text-2xl font-black text-gray-800">{{ $stats['red'] }}</h3>
        </div>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-6">

    {{-- PANEL LATERAL: IMPORTAR --}}
    <div class="w-full lg:w-64 flex-shrink-0">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h2 class="font-black text-gray-700 text-sm flex items-center mb-1">
                <i class="fas fa-file-import mr-2 text-[#39A900]"></i> Carga Masiva
            </h2>
            <a href="{{ route('dispositivos.plantilla') }}" class="text-[10px] text-blue-600 hover:text-blue-800 font-bold flex items-center mb-4 transition">
                <i class="fas fa-download mr-1"></i> Descargar plantilla (.xlsx)
            </a>
            <form action="{{ route('dispositivos.importar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Archivo</label>
                <input type="file" name="archivo"
                    class="w-full text-xs text-gray-500 border border-dashed border-gray-200 p-2 rounded-xl cursor-pointer mb-3"
                    required>
                <button type="submit"
                    class="w-full bg-[#39A900] text-white font-black uppercase text-xs tracking-widest py-3 rounded-xl hover:bg-green-700 transition shadow flex justify-center items-center gap-2">
                    <i class="fas fa-upload"></i> Procesar
                </button>
            </form>

            @if(session('success'))
                <div class="mt-3 p-3 bg-green-50 text-green-700 rounded-xl text-xs flex items-start gap-2 border border-green-200">
                    <i class="fas fa-check-circle mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mt-3 p-3 bg-red-50 text-red-700 rounded-xl text-xs border border-red-200">
                    {{ $errors->first() }}
                </div>
            @endif
        </div>
    </div>

    {{-- TABLA PRINCIPAL --}}
    <div class="flex-1 min-w-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- CABECERA --}}
            <div class="p-5 border-b border-gray-100 space-y-3">
                <div class="flex justify-between items-center">
                    <h2 class="font-black text-gray-700 uppercase tracking-wider text-sm">Inventario de Equipos</h2>
                    <a href="{{ route('dispositivos.create') }}"
                        class="bg-[#39A900] text-white px-4 py-2 rounded-xl text-xs font-black hover:bg-green-700 transition shadow flex items-center gap-2">
                        <i class="fas fa-plus"></i> Agregar
                    </a>
                </div>

                {{-- BUSCADOR + BOTÓN FILTROS --}}
                <form id="filtroForm" action="{{ route('dispositivos.index') }}" method="GET">
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input id="searchInput" type="text" name="search" value="{{ request('search') }}"
                                autocomplete="off"
                                class="block w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-xl text-xs font-bold placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#39A900] transition"
                                placeholder="Buscar por placa, serial o responsable...">
                        </div>
                        <button type="button" id="toggleFiltros"
                            class="relative px-4 py-2.5 border rounded-xl text-xs font-black uppercase tracking-widest transition flex items-center gap-2
                                   {{ $activeFilters > 0 ? 'bg-[#39A900] text-white border-[#39A900]' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                            <i class="fas fa-sliders-h"></i> Filtros
                            @if($activeFilters > 0)
                                <span class="bg-white text-[#39A900] text-[9px] font-black rounded-full w-4 h-4 flex items-center justify-center leading-none">
                                    {{ $activeFilters }}
                                </span>
                            @endif
                        </button>
                        @if(request('search') || $activeFilters > 0)
                            <a href="{{ route('dispositivos.index') }}"
                                class="px-3 py-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition flex items-center"
                                title="Limpiar filtros">
                                <i class="fas fa-times text-xs"></i>
                            </a>
                        @endif

                        {{-- Campos ocultos para filtros activos --}}
                        <input type="hidden" name="estado"    id="h_estado"    value="{{ request('estado') }}">
                        <input type="hidden" name="categoria" id="h_categoria" value="{{ request('categoria') }}">
                        <input type="hidden" name="intune"    id="h_intune"    value="{{ request('intune') }}">
                        <input type="hidden" name="sede"      id="h_sede"      value="{{ request('sede') }}">
                    </div>

                    {{-- PANEL DE FILTROS (colapsable) --}}
                    <div id="panelFiltros" class="{{ $activeFilters > 0 ? '' : 'hidden' }} mt-3 p-4 bg-gray-50 rounded-xl border border-gray-200 grid grid-cols-2 md:grid-cols-4 gap-3">

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Estado Físico</label>
                            <select id="sel_estado" onchange="aplicarFiltro('h_estado', this.value)"
                                class="w-full text-xs font-bold border border-gray-200 rounded-lg px-2 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#39A900]">
                                <option value="">Todos</option>
                                @foreach($estados as $e)
                                    <option value="{{ $e }}" {{ request('estado') === $e ? 'selected' : '' }}>{{ $e }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Categoría</label>
                            <select id="sel_categoria" onchange="aplicarFiltro('h_categoria', this.value)"
                                class="w-full text-xs font-bold border border-gray-200 rounded-lg px-2 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#39A900]">
                                <option value="">Todas</option>
                                @foreach($categorias as $c)
                                    <option value="{{ $c }}" {{ request('categoria') === $c ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Intune</label>
                            <select id="sel_intune" onchange="aplicarFiltro('h_intune', this.value)"
                                class="w-full text-xs font-bold border border-gray-200 rounded-lg px-2 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#39A900]">
                                <option value="">Todos</option>
                                <option value="SI"  {{ request('intune') === 'SI'  ? 'selected' : '' }}>Gestionado (SI)</option>
                                <option value="NO"  {{ request('intune') === 'NO'  ? 'selected' : '' }}>Pendiente (NO)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Sede</label>
                            <select id="sel_sede" onchange="aplicarFiltro('h_sede', this.value)"
                                class="w-full text-xs font-bold border border-gray-200 rounded-lg px-2 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#39A900]">
                                <option value="">Todas</option>
                                @foreach($sedes as $s)
                                    <option value="{{ $s }}" {{ request('sede') === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </form>

                {{-- CHIPS DE FILTROS ACTIVOS --}}
                @if($activeFilters > 0)
                    <div class="flex flex-wrap gap-2">
                        @if(request('estado'))
                            <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-[10px] font-black px-2 py-1 rounded-full">
                                Estado: {{ request('estado') }}
                            </span>
                        @endif
                        @if(request('categoria'))
                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-[10px] font-black px-2 py-1 rounded-full">
                                Categoría: {{ ucfirst(request('categoria')) }}
                            </span>
                        @endif
                        @if(request('intune'))
                            <span class="inline-flex items-center gap-1 bg-cyan-100 text-cyan-700 text-[10px] font-black px-2 py-1 rounded-full">
                                Intune: {{ request('intune') }}
                            </span>
                        @endif
                        @if(request('sede'))
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-[10px] font-black px-2 py-1 rounded-full">
                                Sede: {{ request('sede') }}
                            </span>
                        @endif
                        <span class="text-[10px] text-gray-400 font-bold self-center">
                            — {{ $dispositivos->total() }} resultado(s)
                        </span>
                    </div>
                @endif
            </div>

            {{-- TABLA --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-black tracking-widest">
                        <tr>
                            <th class="px-5 py-3">Placa / Serial</th>
                            <th class="px-5 py-3">Equipo</th>
                            <th class="px-5 py-3">Responsable</th>
                            <th class="px-5 py-3">Ubicación</th>
                            <th class="px-5 py-3">Modificación</th>
                            <th class="px-5 py-3">Estado</th>
                            <th class="px-5 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($dispositivos as $d)
                        @php
                            $estado   = strtoupper(trim($d->estado_fisico ?? ''));
                            $eCfg     = $estadoConfig[$estado] ?? ['bg-gray-100 text-gray-600', 'fa-question-circle'];
                            $cat      = strtolower($d->categoria ?? '');
                            $cCfg     = $catConfig[$cat] ?? ['bg-gray-100 text-gray-600', ucfirst($d->categoria ?? 'N/A')];
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition">

                            {{-- Placa / Serial --}}
                            <td class="px-5 py-4">
                                <div class="font-black text-gray-800 text-sm">{{ $d->placa }}</div>
                                <div class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $d->serial }}</div>
                            </td>

                            {{-- Equipo --}}
                            <td class="px-5 py-4">
                                <div class="font-bold text-gray-700 text-sm">{{ $d->marca }} {{ $d->modelo }}</div>
                                <div class="flex gap-1 mt-1 flex-wrap">
                                    <span class="text-[9px] px-1.5 py-0.5 rounded font-black uppercase {{ $cCfg[0] }}">
                                        {{ $cCfg[1] }}
                                    </span>
                                    @if($d->en_intune === 'SI')
                                        <span class="text-[9px] px-1.5 py-0.5 rounded font-black uppercase bg-cyan-100 text-cyan-700">
                                            Intune
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Responsable --}}
                            <td class="px-5 py-4">
                                <div class="text-sm text-gray-700 font-bold">{{ $d->responsable->nombre ?? '—' }}</div>
                                <div class="text-[10px] text-gray-400 italic">{{ $d->responsable->cargo ?? '' }}</div>
                            </td>

                            {{-- Ubicación --}}
                            <td class="px-5 py-4">
                                <div class="text-xs font-black text-gray-600">{{ $d->ubicacion->sede ?? '—' }}</div>
                                <div class="text-[10px] text-[#39A900] font-bold">
                                    Amb: {{ $d->ubicacion->bloque ?? '' }} {{ $d->ubicacion->ambiente ?? '' }}
                                </div>
                            </td>

                            {{-- Modificación --}}
                            <td class="px-5 py-4">
                                <div class="text-xs font-bold text-gray-700 leading-none">
                                    {{ $d->updated_at->locale('es')->diffForHumans(null, true) }}
                                </div>
                                <div class="text-[10px] text-gray-400 mt-0.5">
                                    {{ $d->updated_at->format('d/m/Y') }}
                                </div>
                                <div class="text-[9px] text-gray-400 mt-0.5 uppercase tracking-wide">
                                    por: <span class="font-black text-blue-500">
                                        {{ $d->editor->name ?? ($d->creador->name ?? 'Sistema') }}
                                    </span>
                                </div>
                            </td>

                            {{-- Estado --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-black uppercase {{ $eCfg[0] }}">
                                    <i class="fas {{ $eCfg[1] }} text-[9px]"></i>
                                    {{ $d->estado_fisico ?? 'N/A' }}
                                </span>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-4">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('dispositivos.show', $d) }}"
                                        class="p-2 bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-500 hover:text-white transition"
                                        title="Ver detalles">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('dispositivos.edit', $d) }}"
                                        class="p-2 bg-orange-50 text-orange-500 rounded-lg hover:bg-orange-500 hover:text-white transition"
                                        title="Editar">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('dispositivos.destroy', $d) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar equipo permanentemente?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition"
                                                title="Eliminar">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-2 text-gray-300 cursor-not-allowed" title="Solo admin puede eliminar">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="text-gray-300 mb-3"><i class="fas fa-search text-4xl"></i></div>
                                <p class="text-gray-500 font-black text-sm">No se encontraron equipos</p>
                                <p class="text-gray-400 text-xs mt-1">Intenta con otros filtros o términos de búsqueda</p>
                                @if(request()->hasAny(['search','estado','categoria','intune','sede']))
                                    <a href="{{ route('dispositivos.index') }}"
                                        class="mt-4 inline-block text-xs font-black text-[#39A900] hover:underline">
                                        Limpiar filtros
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold">
                    Mostrando {{ $dispositivos->firstItem() ?? 0 }}–{{ $dispositivos->lastItem() ?? 0 }}
                    de {{ $dispositivos->total() }} equipos
                </p>
                {{ $dispositivos->links() }}
            </div>

        </div>
    </div>
</div>

<script>
// Búsqueda en tiempo real con debounce
let debounceTimer;
document.getElementById('searchInput').addEventListener('input', function () {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        document.getElementById('filtroForm').submit();
    }, 1200);
});

// Toggle panel de filtros
document.getElementById('toggleFiltros').addEventListener('click', function () {
    const panel = document.getElementById('panelFiltros');
    panel.classList.toggle('hidden');
});

// Aplicar filtro desde select y enviar formulario
function aplicarFiltro(hiddenId, value) {
    document.getElementById(hiddenId).value = value;
    document.getElementById('filtroForm').submit();
}
</script>

@endsection
