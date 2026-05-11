@extends('layouts.app')

@section('content')

@php
    $intuneTotal    = $stats['total_general'];
    $intuneEnrol    = $stats['intune_enrolados'];
    $intunePct      = $intuneTotal > 0 ? number_format(($intuneEnrol / $intuneTotal) * 100, 1) : 0;
    $enReparacion   = $stats['en_reparacion'];
    $mantMes        = $stats['mantenimientos_mes'];

    $colores = ['#39A900','#00324D','#FF9F43','#6C5CE7','#00B894','#E17055','#74B9FF','#A29BFE'];
@endphp

<div class="max-w-7xl mx-auto pb-16 space-y-10">

    {{-- CABECERA --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Dashboard <span class="text-[#39A900]">GITIC</span>
            </h1>
            <p class="text-gray-500 font-bold italic text-sm">Gestión de Infraestructura TIC — Regional Casanare</p>
        </div>
        <button onclick="abrirSelectorResponsable()"
           class="bg-[#00324D] text-white px-6 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg hover:bg-slate-700 transition flex items-center gap-2">
            <i class="fas fa-file-pdf text-base"></i> Reporte por Responsable
        </button>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('reportes.exportar') }}"
           class="bg-[#39A900] text-white px-6 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg hover:bg-green-700 transition flex items-center gap-2">
            <i class="fas fa-file-excel text-base"></i> Exportar Consolidado
        </a>
        @else
        <span class="bg-gray-200 text-gray-400 px-6 py-3 rounded-2xl font-black uppercase text-xs tracking-widest flex items-center gap-2 cursor-not-allowed select-none" title="Solo disponible para administradores">
            <i class="fas fa-file-excel text-base"></i> Exportar Consolidado
        </span>
        @endif
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex items-center gap-4">
            <div class="bg-[#39A900]/10 p-4 rounded-2xl">
                <i class="fas fa-server text-[#39A900] text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Total Activos</p>
                <p class="text-3xl font-black text-gray-800 leading-none">{{ $intuneTotal }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex items-center gap-4">
            <div class="bg-blue-50 p-4 rounded-2xl">
                <i class="fas fa-shield-alt text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Cumplimiento Intune</p>
                <p class="text-3xl font-black text-blue-500 leading-none">{{ $intunePct }}%</p>
                <p class="text-[10px] text-gray-400 font-bold">{{ $intuneEnrol }} / {{ $intuneTotal }} equipos</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex items-center gap-4">
            <div class="{{ $enReparacion > 0 ? 'bg-orange-50' : 'bg-gray-50' }} p-4 rounded-2xl">
                <i class="fas fa-tools {{ $enReparacion > 0 ? 'text-orange-500' : 'text-gray-400' }} text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">En Reparación</p>
                <p class="text-3xl font-black {{ $enReparacion > 0 ? 'text-orange-500' : 'text-gray-800' }} leading-none">{{ $enReparacion }}</p>
                <p class="text-[10px] text-gray-400 font-bold">dispositivos activos</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex items-center gap-4">
            <div class="bg-purple-50 p-4 rounded-2xl">
                <i class="fas fa-calendar-check text-purple-500 text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Mantenimientos Mes</p>
                <p class="text-3xl font-black text-purple-500 leading-none">{{ $mantMes }}</p>
                <p class="text-[10px] text-gray-400 font-bold">{{ now()->translatedFormat('F Y') }}</p>
            </div>
        </div>

    </div>

    {{-- FILA 2: Intune + Sedes --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-3">
                <i class="fas fa-shield-alt mr-2 text-blue-400"></i>Gestión Intune
            </h3>
            <div class="relative h-48">
                <canvas id="chartIntune"></canvas>
                <div class="absolute inset-0 flex items-center justify-center flex-col pointer-events-none">
                    <span class="text-2xl font-black text-gray-800">{{ $intuneEnrol }}</span>
                    <span class="text-[9px] font-black text-gray-400 uppercase">Enrolados</span>
                </div>
            </div>
            <div class="flex justify-center gap-6 mt-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#39A900] inline-block"></span>
                    <span class="text-[10px] font-bold text-gray-500">Gestionados ({{ $intuneEnrol }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-gray-200 inline-block"></span>
                    <span class="text-[10px] font-bold text-gray-500">Pendientes ({{ $stats['intune_pendientes'] }})</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-3">
                <i class="fas fa-map-marker-alt mr-2 text-[#39A900]"></i>Equipos por Sede
            </h3>
            <canvas id="chartSedes" height="110"></canvas>
        </div>

    </div>

    {{-- FILA 3: Estado Físico + Categoría + Función --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-3">
                <i class="fas fa-heartbeat mr-2 text-red-400"></i>Estado Físico
            </h3>
            <canvas id="chartEstadoFisico"></canvas>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-3">
                <i class="fas fa-th-large mr-2 text-indigo-400"></i>Categorías
            </h3>
            <canvas id="chartCategorias"></canvas>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-3">
                <i class="fas fa-sitemap mr-2 text-yellow-500"></i>Función del Equipo
            </h3>
            <canvas id="chartFuncion"></canvas>
        </div>

    </div>

    {{-- FILA 3.5: Propietario --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-3">
            <i class="fas fa-building mr-2 text-[#39A900]"></i>Equipos por Propietario
        </h3>
        @if($stats['propietario']->isEmpty())
            <p class="text-xs text-gray-400 font-bold italic text-center py-8">Sin datos de propietario registrados.</p>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
                <div class="lg:col-span-2" style="position:relative; height:{{ max(120, $stats['propietario']->count() * 44) }}px">
                    <canvas id="chartPropietario"></canvas>
                </div>
                <ul class="space-y-3">
                    @php $totalProp = $stats['propietario']->sum('total'); @endphp
                    @foreach($stats['propietario']->sortByDesc('total')->values() as $i => $p)
                    <li class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 inline-block"
                                  style="background:{{ $colores[$i % count($colores)] }}"></span>
                            <span class="text-xs font-bold text-gray-600 truncate">{{ $p->propietario ?? 'Sin especificar' }}</span>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-xs font-black text-gray-800">{{ $p->total }}</span>
                            <span class="text-[10px] text-gray-400 font-bold">
                                ({{ $totalProp > 0 ? number_format($p->total / $totalProp * 100, 1) : 0 }}%)
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- FILA 4: RAM + Discos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-3">
                <i class="fas fa-memory mr-2 text-purple-400"></i>Distribución de RAM
            </h3>
            <div class="flex items-center gap-6">
                <div class="flex-shrink-0 w-40 h-40">
                    <canvas id="chartRam"></canvas>
                </div>
                <ul class="flex-1 space-y-2">
                    @foreach($stats['ram'] as $i => $r)
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:{{ $colores[$i % count($colores)] }}"></span>
                            <span class="text-xs font-bold text-gray-600">{{ $r->ram ?? 'N/A' }}</span>
                        </div>
                        <span class="text-xs font-black text-gray-800">{{ $r->total }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-3">
                <i class="fas fa-hdd mr-2 text-teal-500"></i>Tipo de Almacenamiento
            </h3>
            <div class="flex items-center gap-6">
                <div class="flex-shrink-0 w-40 h-40">
                    <canvas id="chartDiscos"></canvas>
                </div>
                <ul class="flex-1 space-y-2">
                    @foreach($stats['discos'] as $i => $d)
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:{{ $colores[$i % count($colores)] }}"></span>
                            <span class="text-xs font-bold text-gray-600">{{ $d->tipo_disco ?? 'N/A' }}</span>
                        </div>
                        <span class="text-xs font-black text-gray-800">{{ $d->total }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>

    {{-- FILA 5: Gráfica por Ubicación Física (con filtros) --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center">
                    <i class="fas fa-map-pin mr-2 text-[#39A900]"></i>Inventario por Ubicación Física
                </h3>
                <p id="ubicacion-titulo" class="text-xs font-bold text-gray-500 mt-1">Equipos por sede</p>
            </div>
            {{-- Filtros en cascada --}}
            <div class="flex flex-wrap gap-3 items-end">
                <div class="flex flex-col">
                    <label class="text-[9px] font-black text-gray-400 uppercase mb-1">Sede</label>
                    <select id="filtro-sede" onchange="actualizarBloques()" class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-[#39A900] outline-none min-w-35">
                        <option value="">— Todas las sedes —</option>
                        @foreach($ubicacionOpciones['sedes'] as $sede)
                            <option value="{{ $sede }}">{{ $sede }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-[9px] font-black text-gray-400 uppercase mb-1">Bloque</label>
                    <select id="filtro-bloque" onchange="actualizarAmbientes()" class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-[#39A900] outline-none min-w-35" disabled>
                        <option value="">— Todos los bloques —</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-[9px] font-black text-gray-400 uppercase mb-1">Ambiente</label>
                    <select id="filtro-ambiente" onchange="cargarGraficaUbicacion()" class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-[#39A900] outline-none min-w-35" disabled>
                        <option value="">— Todos los ambientes —</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-[9px] font-black text-gray-400 uppercase mb-1">Intune</label>
                    <select id="filtro-intune" onchange="cargarGraficaUbicacion()" class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-[#39A900] outline-none min-w-35">
                        <option value="">— Todos —</option>
                        <option value="SI">Enrolados (SI)</option>
                        <option value="NO">No enrolados (NO)</option>
                    </select>
                </div>
                <button onclick="limpiarFiltrosUbicacion()" class="px-4 py-2 rounded-xl border border-gray-200 text-[10px] font-black text-gray-500 hover:bg-gray-50 transition uppercase">
                    <i class="fas fa-times mr-1"></i> Limpiar
                </button>
            </div>
        </div>

        <div class="p-8">
            <div id="ubicacion-loading" class="hidden items-center justify-center py-10">
                <div class="w-6 h-6 border-2 border-[#39A900] border-t-transparent rounded-full animate-spin mr-3"></div>
                <span class="text-xs font-bold text-gray-400 uppercase">Cargando datos...</span>
            </div>
            <div id="ubicacion-empty" class="hidden items-center justify-center py-12 text-center">
                <i class="fas fa-map-marker-alt text-gray-200 text-4xl mb-3"></i>
                <p class="text-xs font-bold text-gray-400 uppercase">Sin equipos para la ubicación seleccionada</p>
            </div>
            {{-- Wrapper con altura fija para evitar que Chart.js encoja el canvas al actualizar --}}
            <div style="position:relative; height:320px;">
                <canvas id="chartUbicacion"></canvas>
            </div>
        </div>
    </div>

    {{-- FILA 6: Últimos Mantenimientos --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                <i class="fas fa-history mr-2 text-gray-300"></i>Últimos Mantenimientos Registrados
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 font-black uppercase tracking-widest text-[10px]">
                        <th class="px-6 py-3 text-left">Dispositivo</th>
                        <th class="px-6 py-3 text-left">Tipo</th>
                        <th class="px-6 py-3 text-left">Técnico</th>
                        <th class="px-6 py-3 text-left">Fecha</th>
                        <th class="px-6 py-3 text-left">Descripción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($stats['ultimos_mantenimientos'] as $mant)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            @if($mant->dispositivo)
                                <a href="{{ route('dispositivos.show', $mant->dispositivo->id) }}"
                                   class="font-black text-gray-700 hover:text-[#39A900] transition">
                                    {{ $mant->dispositivo->placa }}
                                </a>
                                <p class="text-[10px] text-gray-400 font-bold">{{ $mant->dispositivo->marca }} {{ $mant->dispositivo->modelo }}</p>
                            @else
                                <span class="text-gray-400 italic">Dispositivo eliminado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase
                                {{ $mant->tipo === 'Correctivo' ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-700' }}">
                                {{ $mant->tipo }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-600">{{ $mant->tecnico_encargado ?? '—' }}</td>
                        <td class="px-6 py-4 font-bold text-gray-500">
                            {{ \Carbon\Carbon::parse($mant->fecha)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 max-w-xs truncate">
                            {{ Str::limit($mant->descripcion_falla ?? $mant->tareas_realizadas, 60) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 font-bold italic text-xs">
                            No hay mantenimientos registrados aún.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const C = {
    verde:  '#39A900',
    azul:   '#00324D',
    naranja:'#FF9F43',
    morado: '#6C5CE7',
    teal:   '#00B894',
    rojo:   '#E17055',
    cielo:  '#74B9FF',
    lila:   '#A29BFE',
    gris:   '#E5E7EB',
};
const palette = Object.values(C).filter(c => c !== C.gris);

const legendOpts = {
    position: 'bottom',
    labels: { boxWidth: 10, font: { size: 10 }, padding: 10 }
};

// 1. Intune — Donut
new Chart(document.getElementById('chartIntune'), {
    type: 'doughnut',
    data: {
        labels: ['Gestionados', 'Pendientes'],
        datasets: [{ data: [{{ $intuneEnrol }}, {{ $stats['intune_pendientes'] }}], backgroundColor: [C.verde, C.gris], borderWidth: 0 }]
    },
    options: { cutout: '78%', plugins: { legend: { display: false } } }
});

// 2. Sedes — Barras horizontales
new Chart(document.getElementById('chartSedes'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($stats['por_sede']->pluck('sede_nombre')) !!},
        datasets: [{
            label: 'Equipos',
            data: {!! json_encode($stats['por_sede']->pluck('total')) !!},
            backgroundColor: C.verde,
            borderRadius: 6,
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } }, y: { ticks: { font: { size: 10 } } } }
    }
});

// 3. Estado Físico — Barras verticales
new Chart(document.getElementById('chartEstadoFisico'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($stats['estado_fisico']->pluck('estado_fisico')) !!},
        datasets: [{
            label: 'Equipos',
            data: {!! json_encode($stats['estado_fisico']->pluck('total')) !!},
            backgroundColor: palette,
            borderRadius: 6,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } }, x: { ticks: { font: { size: 9 } } } }
    }
});

// 4. Categorías — Doughnut
new Chart(document.getElementById('chartCategorias'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($stats['por_categoria']->pluck('categoria')) !!},
        datasets: [{
            data: {!! json_encode($stats['por_categoria']->pluck('total')) !!},
            backgroundColor: palette,
            borderWidth: 2,
        }]
    },
    options: { plugins: { legend: legendOpts } }
});

// 5. Función — Pie
new Chart(document.getElementById('chartFuncion'), {
    type: 'pie',
    data: {
        labels: {!! json_encode($stats['funcion']->pluck('funcion')) !!},
        datasets: [{
            data: {!! json_encode($stats['funcion']->pluck('total')) !!},
            backgroundColor: palette,
            borderWidth: 2,
        }]
    },
    options: { plugins: { legend: legendOpts } }
});

// 6. RAM — Doughnut
new Chart(document.getElementById('chartRam'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($stats['ram']->pluck('ram')) !!},
        datasets: [{
            data: {!! json_encode($stats['ram']->pluck('total')) !!},
            backgroundColor: palette,
            borderWidth: 0,
        }]
    },
    options: { cutout: '60%', plugins: { legend: { display: false } } }
});

// 7. Discos — Doughnut
new Chart(document.getElementById('chartDiscos'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($stats['discos']->pluck('tipo_disco')) !!},
        datasets: [{
            data: {!! json_encode($stats['discos']->pluck('total')) !!},
            backgroundColor: [C.azul, C.verde, C.naranja, C.morado],
            borderWidth: 0,
        }]
    },
    options: { cutout: '60%', plugins: { legend: { display: false } } }
});

// 8. Propietario — Barras horizontales
@if($stats['propietario']->isNotEmpty())
@php
    $propSorted = $stats['propietario']->sortByDesc('total')->values();
    $propLabels = $propSorted->map(fn($p) => $p->propietario ?? 'Sin especificar');
    $propTotals = $propSorted->pluck('total');
    $propColors = $propSorted->keys()->map(fn($i) => $colores[$i % count($colores)]);
@endphp
new Chart(document.getElementById('chartPropietario'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($propLabels->values()) !!},
        datasets: [{
            label: 'Equipos',
            data: {!! json_encode($propTotals->values()) !!},
            backgroundColor: {!! json_encode($propColors->values()) !!},
            borderRadius: 6,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.x} equipo${ctx.parsed.x !== 1 ? 's' : ''}` } }
        },
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { color: '#f3f4f6' } },
            y: { ticks: { font: { size: 11, weight: 'bold' } }, grid: { display: false } }
        }
    }
});
@endif

// ── 9. Gráfica de Ubicación Física con filtros en cascada ──────────────────

// Todas las ubicaciones disponibles para el cascading de selects
const todasUbicaciones = {!! json_encode($ubicacionOpciones['todas']) !!};

let chartUbicacion = null;

function mostrarCapa(id) {
    ['ubicacion-loading', 'ubicacion-empty', 'chartUbicacion'].forEach(cId => {
        const el = document.getElementById(cId);
        if (!el) return;
        if (cId === id) {
            el.style.display = (cId === 'ubicacion-loading' || cId === 'ubicacion-empty') ? 'flex' : 'block';
        } else {
            el.style.display = 'none';
        }
    });
}

function actualizarBloques() {
    const sede = document.getElementById('filtro-sede').value;
    const selectBloque = document.getElementById('filtro-bloque');
    const selectAmbiente = document.getElementById('filtro-ambiente');

    // Resetear bloques y ambientes
    selectBloque.innerHTML = '<option value="">— Todos los bloques —</option>';
    selectAmbiente.innerHTML = '<option value="">— Todos los ambientes —</option>';
    selectAmbiente.disabled = true;

    if (sede) {
        const bloques = [...new Set(
            todasUbicaciones
                .filter(u => u.sede === sede && u.bloque)
                .map(u => u.bloque)
        )].sort();

        bloques.forEach(b => {
            const opt = document.createElement('option');
            opt.value = b;
            opt.textContent = b;
            selectBloque.appendChild(opt);
        });
        selectBloque.disabled = bloques.length === 0;
    } else {
        selectBloque.disabled = true;
    }

    cargarGraficaUbicacion();
}

function actualizarAmbientes() {
    const sede   = document.getElementById('filtro-sede').value;
    const bloque = document.getElementById('filtro-bloque').value;
    const selectAmbiente = document.getElementById('filtro-ambiente');

    selectAmbiente.innerHTML = '<option value="">— Todos los ambientes —</option>';

    if (bloque) {
        const ambientes = [...new Set(
            todasUbicaciones
                .filter(u => u.sede === sede && u.bloque === bloque)
                .map(u => u.ambiente)
        )].sort();

        ambientes.forEach(a => {
            const opt = document.createElement('option');
            opt.value = a;
            opt.textContent = a;
            selectAmbiente.appendChild(opt);
        });
        selectAmbiente.disabled = ambientes.length === 0;
    } else {
        selectAmbiente.disabled = true;
    }

    cargarGraficaUbicacion();
}

function limpiarFiltrosUbicacion() {
    document.getElementById('filtro-sede').value     = '';
    document.getElementById('filtro-intune').value   = '';
    document.getElementById('filtro-bloque').innerHTML   = '<option value="">— Todos los bloques —</option>';
    document.getElementById('filtro-ambiente').innerHTML  = '<option value="">— Todos los ambientes —</option>';
    document.getElementById('filtro-bloque').disabled    = true;
    document.getElementById('filtro-ambiente').disabled  = true;
    cargarGraficaUbicacion();
}

async function cargarGraficaUbicacion() {
    const sede     = document.getElementById('filtro-sede').value;
    const bloque   = document.getElementById('filtro-bloque').value;
    const ambiente = document.getElementById('filtro-ambiente').value;
    const intune   = document.getElementById('filtro-intune').value;

    mostrarCapa('ubicacion-loading');

    const params = new URLSearchParams();
    if (sede)     params.set('sede', sede);
    if (bloque)   params.set('bloque', bloque);
    if (ambiente) params.set('ambiente', ambiente);
    if (intune)   params.set('intune', intune);

    try {
        const res  = await fetch(`{{ route('reportes.ubicacion-stats') }}?${params}`);
        const data = await res.json();

        document.getElementById('ubicacion-titulo').textContent = data.titulo;

        if (!data.values || data.values.length === 0) {
            mostrarCapa('ubicacion-empty');
            return;
        }

        mostrarCapa('chartUbicacion');

        // Colores dinámicos por cantidad de barras
        const bgColors = data.labels.map((_, i) => palette[i % palette.length]);

        if (chartUbicacion) {
            chartUbicacion.data.labels   = data.labels;
            chartUbicacion.data.datasets[0].data = data.values;
            chartUbicacion.data.datasets[0].backgroundColor = bgColors;
            chartUbicacion.update();
        } else {
            chartUbicacion = new Chart(document.getElementById('chartUbicacion'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Equipos',
                        data: data.values,
                        backgroundColor: bgColors,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.y} equipo${ctx.parsed.y !== 1 ? 's' : ''}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, font: { size: 10 } },
                            grid: { color: '#f3f4f6' }
                        },
                        x: {
                            ticks: { font: { size: 10 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    } catch (e) {
        mostrarCapa('ubicacion-empty');
        console.error('Error cargando stats de ubicación:', e);
    }
}

// Carga inicial sin filtros
cargarGraficaUbicacion();
</script>

{{-- ── Modal: Seleccionar responsable para PDF ─────────────────────────────── --}}
<div id="modal-selector-resp"
     class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
     onclick="if(event.target===this)cerrarSelectorResponsable()">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg flex flex-col" style="max-height:90vh;">

        <div class="sena-bg px-6 py-4 rounded-t-3xl flex justify-between items-center flex-shrink-0">
            <div>
                <h2 class="text-white font-black text-base uppercase tracking-tight flex items-center gap-2">
                    <i class="fas fa-file-pdf opacity-80"></i> Reporte por Responsable
                </h2>
                <p class="text-white/70 text-[10px] font-bold mt-0.5">Busca la persona y descarga su acta de equipos</p>
            </div>
            <button type="button" onclick="cerrarSelectorResponsable()" class="text-white/60 hover:text-white text-xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="px-5 py-4 border-b border-gray-100 flex-shrink-0">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-3.5 text-gray-300 text-sm"></i>
                <input type="text" id="selector-input"
                       placeholder="Nombre o cédula del responsable..."
                       oninput="debounceSelector(this.value)"
                       autocomplete="off"
                       class="w-full bg-gray-50 rounded-2xl py-3 pl-9 pr-4 text-sm outline-none focus:ring-2 focus:ring-[#39A900] transition">
            </div>
        </div>

        <div class="overflow-y-auto flex-1">
            <div id="sel-inicial" class="py-10 text-center text-gray-400">
                <i class="fas fa-users text-3xl mb-3 block opacity-20"></i>
                <p class="text-xs font-bold uppercase tracking-widest">Escribe al menos 2 caracteres</p>
            </div>
            <div id="sel-spinner" class="hidden py-8 flex items-center justify-center gap-2">
                <div class="w-5 h-5 border-2 border-[#39A900] border-t-transparent rounded-full animate-spin"></div>
                <span class="text-xs font-bold text-gray-400 uppercase">Buscando...</span>
            </div>
            <div id="sel-vacio" class="hidden py-10 text-center text-gray-400">
                <i class="fas fa-user-slash text-3xl mb-3 block opacity-20"></i>
                <p class="text-xs font-bold uppercase tracking-widest">Sin coincidencias</p>
            </div>
            <ul id="sel-lista" class="divide-y divide-gray-50"></ul>
        </div>

        <div class="px-5 py-3 bg-gray-50 rounded-b-3xl flex-shrink-0 border-t border-gray-100">
            <p class="text-[10px] text-gray-400 font-bold">
                <i class="fas fa-info-circle mr-1"></i>
                El PDF se descarga automáticamente al seleccionar el responsable
            </p>
        </div>
    </div>
</div>

<script>
const _urlBuscarNombreRep = "{{ route('responsables.buscar-nombre') }}";
const _urlReportePDF = "{{ url('/responsables') }}/";
let _selTimer = null;

function abrirSelectorResponsable() {
    const m = document.getElementById('modal-selector-resp');
    m.classList.remove('hidden'); m.classList.add('flex');
    setTimeout(() => document.getElementById('selector-input').focus(), 80);
    _resetSelector();
}
function cerrarSelectorResponsable() {
    const m = document.getElementById('modal-selector-resp');
    m.classList.add('hidden'); m.classList.remove('flex');
    document.getElementById('selector-input').value = '';
    _resetSelector();
}
function _resetSelector() {
    ['sel-inicial','sel-spinner','sel-vacio','sel-lista'].forEach(id => {
        document.getElementById(id)?.classList.add('hidden');
    });
    document.getElementById('sel-inicial')?.classList.remove('hidden');
    document.getElementById('sel-lista').innerHTML = '';
}
function _mostrarSel(estado) {
    ['sel-inicial','sel-spinner','sel-vacio','sel-lista'].forEach(id =>
        document.getElementById(id)?.classList.add('hidden'));
    document.getElementById('sel-' + estado)?.classList.remove('hidden');
}
function debounceSelector(q) {
    clearTimeout(_selTimer);
    if (q.trim().length < 2) { _resetSelector(); return; }
    _mostrarSel('spinner');
    _selTimer = setTimeout(() => _buscarParaSelector(q.trim()), 300);
}
function _buscarParaSelector(q) {
    fetch(`${_urlBuscarNombreRep}?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(data => {
            const lista = document.getElementById('sel-lista');
            lista.innerHTML = '';
            if (!data.length) { _mostrarSel('vacio'); return; }

            data.forEach(r => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <a href="${_urlReportePDF}${r.id}/reporte-pdf" target="_blank"
                       onclick="cerrarSelectorResponsable()"
                       class="flex items-center justify-between px-5 py-3 hover:bg-green-50 transition group">
                        <div>
                            <div class="font-black text-gray-800 text-sm group-hover:text-[#39A900] transition">${r.nombre}</div>
                            <div class="text-[10px] font-mono text-gray-400 mt-0.5">
                                CC ${r.cedula}
                                ${r.cargo ? `<span class="text-gray-300 mx-1">·</span>${r.cargo}` : ''}
                            </div>
                        </div>
                        <span class="text-[10px] font-black text-[#39A900] flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                            <i class="fas fa-file-pdf"></i> Descargar
                        </span>
                    </a>`;
                lista.appendChild(li);
            });
            _mostrarSel('lista');
        })
        .catch(() => _mostrarSel('vacio'));
}
</script>
@endsection
