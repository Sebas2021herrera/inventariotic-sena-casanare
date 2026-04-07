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
        <a href="{{ route('reportes.exportar') }}"
           class="bg-[#39A900] text-white px-6 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg hover:bg-green-700 transition flex items-center gap-2">
            <i class="fas fa-file-excel text-base"></i> Exportar Consolidado
        </a>
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

    {{-- FILA 5: Últimos Mantenimientos --}}
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
</script>
@endsection
