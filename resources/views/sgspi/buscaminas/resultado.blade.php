@extends('layouts.public')
@section('title', 'Resultado — Buscaminas SGSPI')

@section('content')
<div class="max-w-lg mx-auto py-6">

    @php
        if ($porcentaje >= 90)      { $nivel = 'Excelente';   $color = 'green';  $icon = 'fa-trophy'; }
        elseif ($porcentaje >= 70)  { $nivel = 'Muy Bien';    $color = 'blue';   $icon = 'fa-star'; }
        elseif ($porcentaje >= 50)  { $nivel = 'Aceptable';   $color = 'yellow'; $icon = 'fa-thumbs-up'; }
        else                        { $nivel = 'A Mejorar';   $color = 'red';    $icon = 'fa-book'; }

        $colorMap = [
            'green'  => ['bg' => 'bg-green-50',  'border' => 'border-green-200', 'text' => 'text-green-700',  'badge' => 'bg-green-100 text-green-800'],
            'blue'   => ['bg' => 'bg-blue-50',   'border' => 'border-blue-200',  'text' => 'text-blue-700',   'badge' => 'bg-blue-100 text-blue-800'],
            'yellow' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200','text' => 'text-yellow-700', 'badge' => 'bg-yellow-100 text-yellow-800'],
            'red'    => ['bg' => 'bg-red-50',    'border' => 'border-red-200',   'text' => 'text-red-700',    'badge' => 'bg-red-100 text-red-800'],
        ];
        $c = $colorMap[$color];
    @endphp

    {{-- Certificado visual --}}
    <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">

        {{-- Header SENA --}}
        <div class="sena-bg text-white text-center py-6 px-6">
            <p class="text-[10px] font-black uppercase tracking-widest opacity-75 mb-1">Constancia de Participación</p>
            <h1 class="text-2xl font-black uppercase tracking-tight">Buscaminas SGSPI</h1>
            <p class="text-white/70 text-xs font-bold mt-1 uppercase tracking-widest">Sistema de Gestión de Seguridad de la Información</p>
        </div>

        <div class="p-8 space-y-6">

            {{-- Nivel / medalla --}}
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full {{ $c['bg'] }} border-4 {{ $c['border'] }} mb-3">
                    <i class="fas {{ $icon }} {{ $c['text'] }} text-3xl"></i>
                </div>
                <span class="block text-xs font-black {{ $c['text'] }} {{ $c['badge'] }} px-4 py-1.5 rounded-full uppercase tracking-widest inline-block">
                    {{ $nivel }}
                </span>
            </div>

            {{-- Nombre participante --}}
            <div class="text-center border-t border-b border-gray-100 py-4">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Participante</p>
                <p class="text-2xl font-black text-gray-800 uppercase tracking-tight">{{ $resultado->participante->nombre }}</p>
                <p class="text-xs text-gray-400 font-bold mt-0.5">{{ $resultado->participante->area }} &bull; Doc: {{ $resultado->participante->documento }}</p>
            </div>

            {{-- Estadísticas --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center bg-gray-50 rounded-2xl p-4">
                    <p class="text-3xl font-black sena-text">{{ $resultado->puntaje }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Puntos</p>
                </div>
                <div class="text-center bg-gray-50 rounded-2xl p-4">
                    <p class="text-3xl font-black text-blue-500">{{ $resultado->correctas }}<span class="text-gray-300 text-lg">/{{ $resultado->total }}</span></p>
                    <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Correctas</p>
                </div>
                <div class="text-center bg-gray-50 rounded-2xl p-4">
                    <p class="text-3xl font-black {{ $c['text'] }}">{{ $porcentaje }}<span class="text-lg">%</span></p>
                    <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Aciertos</p>
                </div>
            </div>

            {{-- Barra de progreso --}}
            <div>
                <div class="flex justify-between text-[10px] font-black text-gray-400 uppercase mb-1.5">
                    <span>Desempeño</span><span>{{ $porcentaje }}%</span>
                </div>
                <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full sena-bg rounded-full transition-all duration-700"
                         style="width: {{ $porcentaje }}%"></div>
                </div>
            </div>

            {{-- Mensaje --}}
            <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-2xl p-4 text-sm {{ $c['text'] }} font-bold text-center">
                @if($porcentaje >= 90)
                    ¡Excelente! Demuestras un sólido conocimiento en seguridad de la información.
                @elseif($porcentaje >= 70)
                    ¡Muy bien! Continúa fortaleciendo tus conocimientos en seguridad.
                @elseif($porcentaje >= 50)
                    Desempeño aceptable. Te recomendamos revisar los temas con respuestas incorrectas.
                @else
                    Es importante fortalecer tus conocimientos. ¡Inténtalo de nuevo!
                @endif
            </div>

            {{-- Fecha --}}
            <p class="text-center text-[10px] text-gray-300 font-bold uppercase tracking-widest">
                {{ $resultado->created_at->format('d/m/Y H:i') }}
            </p>

            {{-- Acciones --}}
            <div class="flex gap-3">
                <a href="{{ route('sgspi.buscaminas') }}"
                   class="flex-1 text-center sena-bg text-white font-black py-3.5 rounded-2xl hover:scale-[1.02] transition-transform text-xs uppercase tracking-widest shadow-lg">
                    <i class="fas fa-redo mr-1"></i> Jugar de Nuevo
                </a>
                <a href="{{ route('sgspi.index') }}"
                   class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-black py-3.5 rounded-2xl transition text-xs uppercase tracking-widest">
                    <i class="fas fa-home mr-1"></i> Inicio
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
