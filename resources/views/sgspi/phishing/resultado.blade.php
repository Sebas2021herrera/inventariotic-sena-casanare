@extends('layouts.public')
@section('title', 'Resultado — Detector de Phishing')

@section('content')
<div class="max-w-lg mx-auto py-6 space-y-5">

@php
if ($porcentaje >= 90)     { $nivel='Experto en Seguridad';  $color='green';  $icon='fa-shield-alt'; }
elseif ($porcentaje >= 70) { $nivel='Buen Detector';         $color='blue';   $icon='fa-user-shield'; }
elseif ($porcentaje >= 50) { $nivel='En Desarrollo';         $color='yellow'; $icon='fa-graduation-cap'; }
else                       { $nivel='Necesita Refuerzo';     $color='red';    $icon='fa-book-open'; }
$cm = [
    'green'  => ['bg'=>'bg-green-50', 'border'=>'border-green-300', 'text'=>'text-green-700', 'badge'=>'bg-green-100 text-green-800'],
    'blue'   => ['bg'=>'bg-blue-50',  'border'=>'border-blue-300',  'text'=>'text-blue-700',  'badge'=>'bg-blue-100 text-blue-800'],
    'yellow' => ['bg'=>'bg-yellow-50','border'=>'border-yellow-300','text'=>'text-yellow-700','badge'=>'bg-yellow-100 text-yellow-800'],
    'red'    => ['bg'=>'bg-red-50',   'border'=>'border-red-300',   'text'=>'text-red-700',   'badge'=>'bg-red-100 text-red-800'],
];
$c = $cm[$color];
@endphp

{{-- Constancia --}}
<div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-[#1e3a5f] to-[#0f2236] text-white text-center py-6 px-6">
        <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Constancia de Participación</p>
        <h1 class="text-2xl font-black uppercase tracking-tight">Detector de Phishing</h1>
        <p class="text-white/60 text-xs font-bold mt-1 uppercase tracking-widest">SGSPI — Seguridad de la Información</p>
    </div>

    <div class="p-6 space-y-5">

        {{-- Medalla --}}
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full {{ $c['bg'] }} border-4 {{ $c['border'] }} mb-3">
                <i class="fas {{ $icon }} {{ $c['text'] }} text-3xl"></i>
            </div>
            <span class="block text-xs font-black {{ $c['text'] }} {{ $c['badge'] }} px-4 py-1.5 rounded-full uppercase tracking-widest inline-block">
                {{ $nivel }}
            </span>
        </div>

        {{-- Participante --}}
        <div class="text-center border-t border-b border-gray-100 py-4">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Participante</p>
            <p class="text-2xl font-black text-gray-800 uppercase tracking-tight">{{ $resultado->participante->nombre }}</p>
            <p class="text-xs text-gray-400 font-bold mt-0.5">{{ $resultado->participante->area }} &bull; Doc: {{ $resultado->participante->documento }}</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-4 gap-3">
            <div class="text-center bg-gray-50 rounded-2xl p-3">
                <p class="text-2xl font-black text-[#1e3a5f]">{{ $resultado->puntaje }}</p>
                <p class="text-[9px] font-black text-gray-400 uppercase mt-1">Puntos</p>
            </div>
            <div class="text-center bg-gray-50 rounded-2xl p-3">
                <p class="text-2xl font-black text-[#39A900]">{{ $resultado->correctas }}<span class="text-gray-300 text-base">/{{ $resultado->total }}</span></p>
                <p class="text-[9px] font-black text-gray-400 uppercase mt-1">Correctas</p>
            </div>
            <div class="text-center bg-gray-50 rounded-2xl p-3">
                <p class="text-2xl font-black text-yellow-500">{{ $resultado->bonus }}</p>
                <p class="text-[9px] font-black text-gray-400 uppercase mt-1">Bonus</p>
            </div>
            <div class="text-center bg-gray-50 rounded-2xl p-3">
                <p class="text-2xl font-black {{ $c['text'] }}">{{ $porcentaje }}<span class="text-base">%</span></p>
                <p class="text-[9px] font-black text-gray-400 uppercase mt-1">Aciertos</p>
            </div>
        </div>

        {{-- Barra desempeño --}}
        <div>
            <div class="flex justify-between text-[10px] font-black text-gray-400 uppercase mb-1.5">
                <span>Desempeño general</span><span>{{ $porcentaje }}%</span>
            </div>
            <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-[#1e3a5f] to-[#39A900] rounded-full transition-all duration-700"
                     style="width: {{ $porcentaje }}%"></div>
            </div>
        </div>

        {{-- Mensaje motivacional --}}
        <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-2xl p-4 text-sm {{ $c['text'] }} font-bold text-center">
            @if($porcentaje >= 90)
                ¡Excelente! Demuestras un sólido conocimiento para identificar amenazas de phishing. Sigues siendo el escudo de tu organización.
            @elseif($porcentaje >= 70)
                ¡Buen trabajo! Identificas la mayoría de las amenazas. Practica más con los escenarios de nivel avanzado.
            @elseif($porcentaje >= 50)
                Desempeño aceptable. El phishing moderno es sofisticado — te recomendamos revisar las señales de alerta destacadas.
            @else
                El phishing es una amenaza real y creciente. Comparte lo aprendido y vuelve a intentarlo — cada práctica te hace más seguro.
            @endif
        </div>

        <p class="text-center text-[10px] text-gray-300 font-bold uppercase tracking-widest">
            {{ $resultado->created_at->format('d/m/Y H:i') }} &bull; Posición #{{ $posicion }} en el ranking
        </p>

        {{-- Acciones --}}
        <div class="flex gap-3">
            <a href="{{ route('sgspi.phishing.index') }}"
               class="flex-1 text-center bg-gradient-to-r from-[#1e3a5f] to-[#0f2236] text-white font-black py-3.5 rounded-2xl hover:scale-[1.02] transition-transform text-xs uppercase tracking-widest shadow-lg">
                <i class="fas fa-redo mr-1"></i> Jugar de Nuevo
            </a>
            <a href="{{ route('sgspi.index') }}"
               class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-black py-3.5 rounded-2xl transition text-xs uppercase tracking-widest">
                <i class="fas fa-home mr-1"></i> Inicio
            </a>
        </div>

    </div>
</div>

{{-- Leaderboard --}}
@if($leaderboard->count() > 0)
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-[#1e3a5f] px-5 py-4">
        <h2 class="text-white font-black uppercase tracking-tight flex items-center gap-2">
            <i class="fas fa-trophy text-yellow-400"></i> Top 10 — Mejores Detectores
        </h2>
    </div>
    <div class="divide-y divide-gray-50">
        @foreach($leaderboard as $i => $r)
        @php
            $isMe = $r->id === $resultado->id;
            $medal = match($i) { 0=>'🥇', 1=>'🥈', 2=>'🥉', default=>($i+1).'.' };
            $pct   = $r->total > 0 ? round(($r->correctas/$r->total)*100) : 0;
        @endphp
        <div class="flex items-center gap-3 px-5 py-3 {{ $isMe ? 'bg-[#1e3a5f]/5 font-black' : '' }}">
            <span class="text-base w-8 text-center shrink-0">{{ $medal }}</span>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-black text-gray-800 truncate {{ $isMe ? 'text-[#1e3a5f]' : '' }}">
                    {{ $r->participante->nombre }}
                    @if($isMe) <span class="text-[10px] font-black bg-[#1e3a5f] text-white px-2 py-0.5 rounded-full ml-1">Tú</span> @endif
                </p>
                <p class="text-[11px] text-gray-400 font-bold">{{ $r->participante->area }}</p>
            </div>
            <div class="text-right shrink-0">
                <p class="font-black text-[#1e3a5f] text-sm">{{ $r->puntaje }} pts</p>
                <p class="text-[10px] text-gray-400 font-bold">{{ $r->correctas }}/{{ $r->total }} · {{ $pct }}%</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

</div>
@endsection
