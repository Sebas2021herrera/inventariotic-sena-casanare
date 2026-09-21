@extends('layouts.public')
@section('title', 'Módulo de Juegos — SGSPI')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Acceso rápido QR --}}
    <div class="text-center pt-4">
        <a href="{{ route('sgspi.instrucciones') }}"
           class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-600 font-black px-4 py-2 rounded-xl text-xs uppercase tracking-widest shadow-sm hover:border-[#39A900] hover:text-[#39A900] transition">
            <i class="fas fa-qrcode text-[#39A900]"></i> Imprimir QR del módulo
        </a>
    </div>

    {{-- Hero --}}
    <div class="text-center py-4">
        <div class="inline-flex items-center justify-center w-20 h-20 sena-bg rounded-3xl shadow-xl mb-5">
            <i class="fas fa-gamepad text-white text-4xl"></i>
        </div>
        <h1 class="text-4xl font-black text-gray-800 uppercase italic tracking-tighter">
            Módulo de <span class="sena-text">Juegos</span>
        </h1>
        <p class="text-gray-500 font-bold mt-2 text-sm">SGSPI — Sistema de Gestión de Seguridad de la Información</p>
        <p class="text-gray-400 text-xs mt-1 max-w-lg mx-auto">Aprende sobre seguridad de la información a través de actividades interactivas y desafíos.</p>
    </div>

    {{-- ── Juegos disponibles ─────────────────────────────────────────────── --}}
    <div>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-7 h-7 sena-bg rounded-xl flex items-center justify-center">
                <i class="fas fa-star text-white text-xs"></i>
            </div>
            <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Actividades disponibles</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-5">

            {{-- Buscaminas --}}
            <a href="{{ route('sgspi.buscaminas') }}"
               class="group bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex gap-5 items-center hover:shadow-lg hover:border-[#39A900] transition-all duration-200">
                <div class="w-16 h-16 bg-[#39A900]/10 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#39A900] transition-colors">
                    <i class="fas fa-bomb text-[#39A900] text-2xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-gray-800 text-base uppercase tracking-tight">Buscaminas SGSPI</p>
                    <p class="text-gray-400 text-xs font-bold mt-1">Descubre celdas y responde preguntas sobre seguridad de la información.</p>
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        <span class="text-[10px] font-black bg-green-50 text-[#39A900] px-2 py-0.5 rounded-full uppercase">25 celdas</span>
                        <span class="text-[10px] font-black bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full uppercase">{{ $configBuscaminas->preguntas }} preguntas</span>
                        <span class="text-[10px] font-black bg-yellow-50 text-yellow-600 px-2 py-0.5 rounded-full uppercase">{{ $configBuscaminas->puntajeMaximo() }} pts</span>
                        @if($statsBuscaminas['partidas'] > 0)
                        <span class="text-[10px] font-black bg-gray-50 text-gray-500 px-2 py-0.5 rounded-full uppercase">{{ $statsBuscaminas['partidas'] }} jugadas</span>
                        @endif
                    </div>
                </div>
            </a>

            {{-- Detector de Phishing --}}
            <a href="{{ route('sgspi.phishing.index') }}"
               class="group bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex gap-5 items-center hover:shadow-lg hover:border-[#1e3a5f] transition-all duration-200">
                <div class="w-16 h-16 bg-[#1e3a5f]/10 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#1e3a5f] transition-colors relative">
                    <i class="fas fa-fish text-[#1e3a5f] text-2xl group-hover:text-white transition-colors"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full">!</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-gray-800 text-base uppercase tracking-tight">Detector de Phishing</p>
                    <p class="text-gray-400 text-xs font-bold mt-1">Identifica emails falsos en 4 niveles progresivos de dificultad.</p>
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        <span class="text-[10px] font-black bg-[#1e3a5f]/10 text-[#1e3a5f] px-2 py-0.5 rounded-full uppercase">4 niveles</span>
                        <span class="text-[10px] font-black bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full uppercase">{{ $configPhishing->escenarios }} escenarios</span>
                        <span class="text-[10px] font-black bg-yellow-50 text-yellow-600 px-2 py-0.5 rounded-full uppercase">{{ $configPhishing->puntajeMaximo() }} pts</span>
                        @if($statsPhishing['partidas'] > 0)
                        <span class="text-[10px] font-black bg-gray-50 text-gray-500 px-2 py-0.5 rounded-full uppercase">{{ $statsPhishing['partidas'] }} jugadas</span>
                        @endif
                    </div>
                </div>
            </a>

        </div>
    </div>

    {{-- ── Leaderboards ───────────────────────────────────────────────────── --}}
    <div>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-7 h-7 bg-yellow-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-trophy text-white text-xs"></i>
            </div>
            <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Mejores puntajes</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-5">

            {{-- Leaderboard Buscaminas --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-[#39A900] px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-bomb text-white/80 text-sm"></i>
                        <span class="text-white font-black text-xs uppercase tracking-widest">Buscaminas</span>
                    </div>
                    <div class="text-right">
                        <span class="text-white/60 text-[10px] font-bold">Prom: {{ $statsBuscaminas['prom'] }} pts</span>
                    </div>
                </div>
                @if($topBuscaminas->isEmpty())
                    <div class="px-5 py-8 text-center text-gray-400 text-xs font-bold">
                        <i class="fas fa-clock text-2xl mb-2 block opacity-30"></i>
                        Sé el primero en jugar
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($topBuscaminas as $i => $r)
                        @php
                            $medal = match($i) { 0=>'🥇', 1=>'🥈', 2=>'🥉', default=>($i+1).'.' };
                            $pct = $r->total > 0 ? round(($r->correctas/$r->total)*100) : 0;
                        @endphp
                        <div class="flex items-center gap-3 px-4 py-2.5">
                            <span class="text-base w-7 text-center shrink-0">{{ $medal }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-black text-gray-800 truncate">{{ $r->participante->nombre }}</p>
                                <p class="text-[10px] text-gray-400 font-bold truncate">{{ $r->participante->area }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-black text-[#39A900] text-sm">{{ $r->puntaje }}<span class="text-gray-300 text-xs"> pts</span></p>
                                <p class="text-[10px] text-gray-400">{{ $pct }}%</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Leaderboard Phishing --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-[#1e3a5f] px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-fish text-white/80 text-sm"></i>
                        <span class="text-white font-black text-xs uppercase tracking-widest">Detector de Phishing</span>
                    </div>
                    <div class="text-right">
                        <span class="text-white/60 text-[10px] font-bold">Prom: {{ $statsPhishing['prom'] }} pts</span>
                    </div>
                </div>
                @if($topPhishing->isEmpty())
                    <div class="px-5 py-8 text-center text-gray-400 text-xs font-bold">
                        <i class="fas fa-clock text-2xl mb-2 block opacity-30"></i>
                        Sé el primero en jugar
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($topPhishing as $i => $r)
                        @php
                            $medal = match($i) { 0=>'🥇', 1=>'🥈', 2=>'🥉', default=>($i+1).'.' };
                            $pct = $r->total > 0 ? round(($r->correctas/$r->total)*100) : 0;
                        @endphp
                        <div class="flex items-center gap-3 px-4 py-2.5">
                            <span class="text-base w-7 text-center shrink-0">{{ $medal }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-black text-gray-800 truncate">{{ $r->participante->nombre }}</p>
                                <p class="text-[10px] text-gray-400 font-bold truncate">{{ $r->participante->area }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-black text-[#1e3a5f] text-sm">{{ $r->puntaje }}<span class="text-gray-300 text-xs"> pts</span></p>
                                <p class="text-[10px] text-gray-400">
                                    N{{ $r->nivel_alcanzado }} · {{ $pct }}%
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ── Temas cubiertos ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Temas cubiertos</p>
        <div class="flex flex-wrap gap-2">
            @foreach(['🔐 Contraseñas','📧 Phishing','🎣 Ingeniería Social','🦠 Malware','☁️ Copias de Seguridad','🔑 Privacidad','💾 USB y Dispositivos','✉️ Correo Electrónico','💼 BEC / Fraude empresarial'] as $t)
                <span class="px-3 py-1.5 bg-gray-50 border border-gray-200 text-gray-600 text-[11px] font-bold rounded-xl">{{ $t }}</span>
            @endforeach
        </div>
    </div>

</div>
@endsection
