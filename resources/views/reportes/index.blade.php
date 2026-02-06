@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-12">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">Reportes e <span class="text-[#39A900]">Indicadores</span></h1>
            <p class="text-gray-500 font-bold italic">Gestión de Infraestructura - Casanare</p>
        </div>
        <a href="{{ route('reportes.exportar') }}" class="bg-[#39A900] text-white px-8 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl hover:bg-green-700 transition flex items-center">
            <i class="fas fa-file-excel mr-3 text-xl"></i> Descargar Consolidado
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm text-center">
            <p class="text-[10px] font-black text-gray-400 uppercase">Total Activos</p>
            <p class="text-4xl font-black text-gray-800">{{ $stats['total_general'] }}</p>
        </div>
        @php 
            $si = $stats['intune']->where('en_intune', 'SI')->first()->total ?? 0; 
            $no = $stats['total_general'] - $si;
        @endphp
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm text-center">
            <p class="text-[10px] font-black text-gray-400 uppercase">Cumplimiento Intune</p>
            <p class="text-4xl font-black text-[#39A900]">{{ $stats['total_general'] > 0 ? number_format(($si/$stats['total_general'])*100, 1) : 0 }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 relative">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 border-b pb-2">Gestión Intune</h3>
            <div class="relative h-48">
                <canvas id="chartIntune"></canvas>
                <div class="absolute inset-0 flex items-center justify-center flex-col">
                    <span class="text-2xl font-black text-gray-800">{{ $si }}</span>
                    <span class="text-[8px] font-bold text-gray-400 uppercase">Enrolados</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 border-b pb-2">Equipos por Sede (Unificado)</h3>
            <canvas id="chartSedes" height="100"></canvas>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 border-b pb-2">Distribución RAM</h3>
            <canvas id="chartRam"></canvas>
        </div>
        
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 border-b pb-2">Tipo Almacenamiento</h3>
            <canvas id="chartDiscos"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const senaColor = '#39A900';
    const senaDark = '#00324D';
    const senaGray = '#E5E7EB';

    // 1. Gráfico Intune
    new Chart(document.getElementById('chartIntune'), {
        type: 'doughnut',
        data: {
            labels: ['Gestionados (SI)', 'Pendientes (NO)'],
            datasets: [{
                data: [{{ $si }}, {{ $no }}],
                backgroundColor: [senaColor, senaGray],
                borderWidth: 0
            }]
        },
        options: { cutout: '80%', plugins: { legend: { display: false } } }
    });

    // 2. Gráfico de Sedes (Ajustado para datos unificados)
    new Chart(document.getElementById('chartSedes'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($stats['por_sede']->pluck('sede_nombre')) !!},
            datasets: [{
                label: 'Cantidad de Equipos',
                data: {!! json_encode($stats['por_sede']->pluck('total')) !!},
                backgroundColor: senaColor,
                borderRadius: 8
            }]
        },
        options: { 
            indexAxis: 'y', 
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // 3. Gráfico de Memoria RAM
    new Chart(document.getElementById('chartRam'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($stats['ram']->pluck('ram')) !!},
            datasets: [{
                data: {!! json_encode($stats['ram']->pluck('total')) !!},
                backgroundColor: [senaColor, senaDark, '#FF9F43', '#707070'],
                borderWidth: 2
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } }
        }
    });

    // 4. Gráfico de Discos
    new Chart(document.getElementById('chartDiscos'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($stats['discos']->pluck('disco')) !!},
            datasets: [{
                data: {!! json_encode($stats['discos']->pluck('total')) !!},
                backgroundColor: [senaDark, senaColor, '#FF9F43'],
                borderWidth: 2
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } }
        }
    });
</script>
@endsection