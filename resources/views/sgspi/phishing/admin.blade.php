@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 uppercase italic tracking-tighter">
                Resultados <span class="text-[#1e3a5f]">Phishing</span>
            </h1>
            <p class="text-gray-400 text-xs font-bold">Detector de Phishing — SGSPI · {{ $config->escenarios }} escenarios / partida · banco: {{ $totalBanco }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('sgspi.phishing.admin.config') }}"
               class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-black px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-widest">
                <i class="fas fa-sliders-h"></i> Configuración
            </a>
            <a href="{{ route('sgspi.admin.resultados') }}#phishing"
               class="flex items-center gap-2 bg-[#1e3a5f] text-white font-black px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-widest hover:opacity-90">
                <i class="fas fa-arrow-left"></i> Resultados Juegos
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <p class="text-3xl font-black text-[#1e3a5f]">{{ $stats['total'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Partidas</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <p class="text-3xl font-black text-[#39A900]">{{ $stats['prom_score'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Puntuación media</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <p class="text-3xl font-black text-blue-500">{{ $stats['prom_pct'] }}<span class="text-lg">%</span></p>
            <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Aciertos promedio</p>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-[#1e3a5f] text-white text-[10px] font-black uppercase tracking-widest">
                    <th class="px-4 py-3 text-left">Participante</th>
                    <th class="px-4 py-3 text-left">Área</th>
                    <th class="px-4 py-3 text-center">Pts</th>
                    <th class="px-4 py-3 text-center">Correctas</th>
                    <th class="px-4 py-3 text-center">Bonus</th>
                    <th class="px-4 py-3 text-center">Nivel</th>
                    <th class="px-4 py-3 text-center">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($resultados as $r)
                @php $pct = $r->total > 0 ? round(($r->correctas/$r->total)*100) : 0; @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-black text-gray-800">{{ $r->participante->nombre }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $r->participante->area }}</td>
                    <td class="px-4 py-3 text-center font-black text-[#1e3a5f]">{{ $r->puntaje }}</td>
                    <td class="px-4 py-3 text-center text-[#39A900] font-bold">{{ $r->correctas }}/{{ $r->total }} <span class="text-gray-400">({{ $pct }}%)</span></td>
                    <td class="px-4 py-3 text-center text-yellow-600 font-bold">{{ $r->bonus > 0 ? '+'.$r->bonus*5.' pts' : '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full
                            @if($r->nivel_alcanzado==1) bg-green-100 text-green-700
                            @elseif($r->nivel_alcanzado==2) bg-blue-100 text-blue-700
                            @elseif($r->nivel_alcanzado==3) bg-orange-100 text-orange-700
                            @else bg-red-100 text-red-700 @endif">
                            N{{ $r->nivel_alcanzado }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center text-[11px] text-gray-400 font-bold">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-10 text-center text-gray-400 text-sm font-bold">Sin resultados aún.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($resultados->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $resultados->links() }}</div>
        @endif
    </div>

</div>
@endsection
