@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Resultados <span class="text-[#39A900]">Buscaminas</span>
            </h1>
            <p class="text-gray-400 text-sm font-bold italic">SGSPI — Sensibilización en Seguridad de la Información</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('sgspi.admin.config') }}"
               class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-black px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-widest">
                <i class="fas fa-sliders-h"></i> Configuración
            </a>
            <a href="{{ route('sgspi.index') }}" target="_blank"
               class="flex items-center gap-2 sena-bg text-white font-black px-4 py-2.5 rounded-xl transition text-xs uppercase tracking-widest hover:opacity-90">
                <i class="fas fa-external-link-alt"></i> Ver Juego
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-400 font-black uppercase tracking-widest text-[10px]">
                    <th class="px-5 py-4 text-left">#</th>
                    <th class="px-5 py-4 text-left">Participante</th>
                    <th class="px-5 py-4 text-left">Documento</th>
                    <th class="px-5 py-4 text-left">Área</th>
                    <th class="px-5 py-4 text-center">Puntaje</th>
                    <th class="px-5 py-4 text-center">Correctas</th>
                    <th class="px-5 py-4 text-center">%</th>
                    <th class="px-5 py-4 text-left">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($resultados as $r)
                @php
                    $pct = $r->total > 0 ? round(($r->correctas / $r->total) * 100) : 0;
                    $color = $pct >= 90 ? 'text-green-600' : ($pct >= 70 ? 'text-blue-600' : ($pct >= 50 ? 'text-yellow-600' : 'text-red-500'));
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 text-[11px] text-gray-400 font-bold">{{ $r->id }}</td>
                    <td class="px-5 py-3 font-black text-gray-800 text-xs">{{ $r->participante->nombre }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500 font-bold">{{ $r->participante->documento }}</td>
                    <td class="px-5 py-3 text-xs text-gray-400 font-bold">{{ $r->participante->area }}</td>
                    <td class="px-5 py-3 text-center font-black text-[#39A900] text-base">{{ $r->puntaje }}</td>
                    <td class="px-5 py-3 text-center text-xs font-bold text-gray-600">{{ $r->correctas }}/{{ $r->total }}</td>
                    <td class="px-5 py-3 text-center font-black {{ $color }}">{{ $pct }}%</td>
                    <td class="px-5 py-3 text-[11px] text-gray-400 font-bold whitespace-nowrap">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-10 text-center text-gray-400 font-bold italic text-xs">
                        Aún no hay resultados registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
            <span class="text-[10px] font-bold text-gray-400">{{ $resultados->total() }} resultado{{ $resultados->total() !== 1 ? 's' : '' }}</span>
            {{ $resultados->links() }}
        </div>
    </div>

</div>
@endsection
