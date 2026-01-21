@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('dispositivos.index') }}" class="text-gray-500 hover:text-green-700 font-bold transition">
            <i class="fas fa-arrow-left mr-1"></i> Volver al listado
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="sena-bg p-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-black">{{ $dispositivo->marca }} {{ $dispositivo->modelo }}</h1>
                    <p class="opacity-80">Placa: {{ $dispositivo->placa }} | Serial: {{ $dispositivo->serial }}</p>
                </div>
                <span class="bg-white text-green-700 px-4 py-2 rounded-lg font-bold shadow">
                    {{ $dispositivo->estado_fisico }}
                </span>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="font-bold text-gray-400 uppercase text-xs tracking-widest mb-4">Especificaciones Técnicas</h3>
                <ul class="space-y-3">
                    <li class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Procesador:</span>
                        <span class="font-medium">{{ $dispositivo->especificaciones->procesador ?? 'N/A' }}</span>
                    </li>
                    <li class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Memoria RAM:</span>
                        <span class="font-medium">{{ $dispositivo->especificaciones->ram ?? 'N/A' }}</span>
                    </li>
                    <li class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Disco:</span>
                        <span class="font-medium">{{ $dispositivo->especificaciones->capacidad_disco ?? 'N/A' }} ({{ $dispositivo->especificaciones->tipo_disco ?? 'N/A' }})</span>
                    </li>
                    <li class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">S.O:</span>
                        <span class="font-medium text-blue-600">{{ $dispositivo->especificaciones->so ?? 'N/A' }}</span>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-gray-400 uppercase text-xs tracking-widest mb-4">Asignación Actual</h3>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-sm text-gray-500 uppercase font-bold text-[10px]">Responsable</p>
                    <p class="font-bold text-gray-800">{{ $dispositivo->responsable->nombre }}</p>
                    <p class="text-sm text-gray-600 mb-4">{{ $dispositivo->responsable->dependencia }}</p>

                    <p class="text-sm text-gray-500 uppercase font-bold text-[10px]">Ubicación Física</p>
                    <p class="font-medium">{{ $dispositivo->ubicacion->sede }} - Ambiente {{ $dispositivo->ubicacion->ambiente }}</p>
                    <p class="text-xs text-gray-400">Bloque: {{ $dispositivo->ubicacion->bloque }}</p>
                </div>
            </div>
        </div>

        @if($dispositivo->observaciones)
        <div class="px-8 pb-8">
            <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                <h4 class="text-yellow-800 font-bold text-sm mb-1"><i class="fas fa-sticky-note mr-1"></i> Observaciones</h4>
                <p class="text-yellow-700 text-sm italic">{{ $dispositivo->observaciones }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection