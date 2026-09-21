@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Resultados <span class="sena-text">Juegos</span>
            </h1>
            <p class="text-gray-400 text-sm font-bold italic">SGSPI — Sensibilización en Seguridad de la Información</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('sgspi.admin.config') }}"
               class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-black px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-widest">
                <i class="fas fa-sliders-h"></i> Config Buscaminas
            </a>
            <a href="{{ route('sgspi.phishing.admin.config') }}"
               class="flex items-center gap-2 bg-[#1e3a5f]/10 hover:bg-[#1e3a5f]/20 text-[#1e3a5f] font-black px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-widest">
                <i class="fas fa-fish"></i> Config Phishing
            </a>
            <a href="{{ route('sgspi.index') }}" target="_blank"
               class="flex items-center gap-2 sena-bg text-white font-black px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-widest hover:opacity-90">
                <i class="fas fa-external-link-alt"></i> Ver Módulo
            </a>
        </div>
    </div>

    {{-- Stats globales --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
            <p class="text-3xl font-black text-[#39A900]">{{ $statsBuscaminas['total'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Partidas Buscaminas</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
            <p class="text-3xl font-black text-gray-600">{{ $statsBuscaminas['prom_score'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Prom. Buscaminas</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
            <p class="text-3xl font-black text-[#1e3a5f]">{{ $statsPhishing['total'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Partidas Phishing</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
            <p class="text-3xl font-black text-gray-600">{{ $statsPhishing['prom_score'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Prom. Phishing</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 bg-gray-100 p-1 rounded-2xl w-fit">
        <button onclick="mostrarTab('buscaminas')" id="tab-btn-buscaminas"
                class="tab-btn px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-white text-[#39A900] shadow-sm">
            <i class="fas fa-bomb mr-1.5"></i> Buscaminas
            <span class="ml-1.5 bg-[#39A900]/10 text-[#39A900] text-[9px] px-1.5 py-0.5 rounded-full">{{ $statsBuscaminas['total'] }}</span>
        </button>
        <button onclick="mostrarTab('phishing')" id="tab-btn-phishing"
                class="tab-btn px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-gray-500 hover:text-gray-700">
            <i class="fas fa-fish mr-1.5"></i> Detector Phishing
            <span class="ml-1.5 bg-gray-200 text-gray-500 text-[9px] px-1.5 py-0.5 rounded-full">{{ $statsPhishing['total'] }}</span>
        </button>
    </div>

    {{-- ── Tab Buscaminas ─────────────────────────────────────────────────── --}}
    <div id="tab-buscaminas">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#39A900] text-white text-[10px] font-black uppercase tracking-widest">
                        <th class="px-5 py-3 text-left">#</th>
                        <th class="px-5 py-3 text-left">Participante</th>
                        <th class="px-5 py-3 text-left">Documento</th>
                        <th class="px-5 py-3 text-left">Área</th>
                        <th class="px-5 py-3 text-center">Puntaje</th>
                        <th class="px-5 py-3 text-center">Correctas</th>
                        <th class="px-5 py-3 text-center">%</th>
                        <th class="px-5 py-3 text-left">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($resultadosBuscaminas as $r)
                    @php
                        $pct = $r->total > 0 ? round(($r->correctas/$r->total)*100) : 0;
                        $color = $pct>=90?'text-green-600':($pct>=70?'text-blue-600':($pct>=50?'text-yellow-600':'text-red-500'));
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-[11px] text-gray-400 font-bold">{{ $r->id }}</td>
                        <td class="px-5 py-3 font-black text-gray-800 text-xs">{{ $r->participante->nombre }}</td>
                        <td class="px-5 py-3 text-xs text-gray-500 font-bold font-mono">{{ $r->participante->documento }}</td>
                        <td class="px-5 py-3 text-xs text-gray-400 font-bold">{{ $r->participante->area }}</td>
                        <td class="px-5 py-3 text-center font-black text-[#39A900] text-base">{{ $r->puntaje }}</td>
                        <td class="px-5 py-3 text-center text-xs font-bold text-gray-600">{{ $r->correctas }}/{{ $r->total }}</td>
                        <td class="px-5 py-3 text-center font-black {{ $color }}">{{ $pct }}%</td>
                        <td class="px-5 py-3 text-[11px] text-gray-400 font-bold whitespace-nowrap">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400 text-xs font-bold italic">Sin resultados aún.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                <span class="text-[10px] font-bold text-gray-400">{{ $resultadosBuscaminas->total() }} resultado(s)</span>
                {{ $resultadosBuscaminas->links() }}
            </div>
        </div>
    </div>

    {{-- ── Tab Phishing ───────────────────────────────────────────────────── --}}
    <div id="tab-phishing" class="hidden">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#1e3a5f] text-white text-[10px] font-black uppercase tracking-widest">
                        <th class="px-5 py-3 text-left">#</th>
                        <th class="px-5 py-3 text-left">Participante</th>
                        <th class="px-5 py-3 text-left">Documento</th>
                        <th class="px-5 py-3 text-left">Área</th>
                        <th class="px-5 py-3 text-center">Pts</th>
                        <th class="px-5 py-3 text-center">Correctas</th>
                        <th class="px-5 py-3 text-center">Bonus</th>
                        <th class="px-5 py-3 text-center">Nivel</th>
                        <th class="px-5 py-3 text-center">%</th>
                        <th class="px-5 py-3 text-left">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($resultadosPhishing as $r)
                    @php
                        $pct = $r->total>0?round(($r->correctas/$r->total)*100):0;
                        $color = $pct>=90?'text-green-600':($pct>=70?'text-blue-600':($pct>=50?'text-yellow-600':'text-red-500'));
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-[11px] text-gray-400 font-bold">{{ $r->id }}</td>
                        <td class="px-5 py-3 font-black text-gray-800 text-xs">{{ $r->participante->nombre }}</td>
                        <td class="px-5 py-3 text-xs text-gray-500 font-bold font-mono">{{ $r->participante->documento }}</td>
                        <td class="px-5 py-3 text-xs text-gray-400 font-bold">{{ $r->participante->area }}</td>
                        <td class="px-5 py-3 text-center font-black text-[#1e3a5f] text-base">{{ $r->puntaje }}</td>
                        <td class="px-5 py-3 text-center text-xs font-bold text-gray-600">{{ $r->correctas }}/{{ $r->total }}</td>
                        <td class="px-5 py-3 text-center text-yellow-600 font-bold text-xs">{{ $r->bonus > 0 ? '+'.$r->bonus*5 : '—' }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="text-[10px] font-black px-2 py-0.5 rounded-full
                                @if($r->nivel_alcanzado==1) bg-green-100 text-green-700
                                @elseif($r->nivel_alcanzado==2) bg-blue-100 text-blue-700
                                @elseif($r->nivel_alcanzado==3) bg-orange-100 text-orange-700
                                @else bg-red-100 text-red-700 @endif">
                                N{{ $r->nivel_alcanzado }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center font-black {{ $color }}">{{ $pct }}%</td>
                        <td class="px-5 py-3 text-[11px] text-gray-400 font-bold whitespace-nowrap">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="px-5 py-10 text-center text-gray-400 text-xs font-bold italic">Sin resultados aún.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                <span class="text-[10px] font-bold text-gray-400">{{ $resultadosPhishing->total() }} resultado(s)</span>
                {{ $resultadosPhishing->links() }}
            </div>
        </div>
    </div>

</div>

<script>
function mostrarTab(tab) {
    ['buscaminas','phishing'].forEach(t => {
        const panel = document.getElementById('tab-' + t);
        const btn   = document.getElementById('tab-btn-' + t);
        if (t === tab) {
            panel.classList.remove('hidden');
            btn.classList.add('bg-white','shadow-sm');
            btn.classList.remove('text-gray-500');
            btn.classList.add(t === 'buscaminas' ? 'text-[#39A900]' : 'text-[#1e3a5f]');
        } else {
            panel.classList.add('hidden');
            btn.classList.remove('bg-white','shadow-sm','text-[#39A900]','text-[#1e3a5f]');
            btn.classList.add('text-gray-500');
        }
    });
}
// Activar tab por URL hash
const hash = window.location.hash;
if (hash === '#phishing') mostrarTab('phishing');
</script>
@endsection
