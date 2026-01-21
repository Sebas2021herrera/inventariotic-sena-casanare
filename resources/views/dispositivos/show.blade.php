@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('dispositivos.index') }}" class="text-gray-500 hover:text-[#39A900] font-bold transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
        <div class="flex space-x-2">
            <button onclick="window.print()" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl font-bold hover:bg-gray-200 transition">
                <i class="fas fa-print mr-1"></i> Imprimir Acta
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="sena-bg p-8 text-white relative">
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <i class="fas fa-desktop text-9xl"></i>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <span class="bg-white/20 text-white text-[10px] px-3 py-1 rounded-full font-black uppercase tracking-widest mb-2 inline-block">
                        {{ $dispositivo->categoria }}
                    </span>
                    <h1 class="text-4xl font-black italic tracking-tighter uppercase">{{ $dispositivo->marca }} {{ $dispositivo->modelo }}</h1>
                    <p class="text-white/80 font-mono text-lg mt-1">
                        PLACA: <span class="bg-white text-[#39A900] px-2 rounded">{{ $dispositivo->placa }}</span> 
                        | S/N: {{ $dispositivo->serial }}
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex flex-col items-end">
                    <span class="bg-white text-[#39A900] px-6 py-2 rounded-2xl font-black shadow-lg text-xl uppercase">
                        {{ $dispositivo->estado_fisico }}
                    </span>
                    @if($dispositivo->en_intune == 'SI')
                        <span class="mt-2 text-[10px] bg-blue-500 text-white px-3 py-1 rounded-full font-bold flex items-center">
                            <i class="fas fa-check-circle mr-1"></i> EN INTUNE
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 border-b border-gray-100 bg-gray-50">
            <div class="p-4 border-r border-gray-100">
                <p class="text-[9px] font-black text-gray-400 uppercase">Propietario</p>
                <p class="font-bold text-gray-700">{{ $dispositivo->propietario }}</p>
            </div>
            <div class="p-4 border-r border-gray-100">
                <p class="text-[9px] font-black text-gray-400 uppercase">Función</p>
                <p class="font-bold text-gray-700">{{ $dispositivo->funcion }}</p>
            </div>
            <div class="p-4 border-r border-gray-100">
                <p class="text-[9px] font-black text-gray-400 uppercase">Dirección MAC</p>
                <p class="font-mono text-xs font-bold text-blue-600">{{ $dispositivo->especificaciones->mac_address ?? 'N/A' }}</p>
            </div>
            <div class="p-4">
                <p class="text-[9px] font-black text-gray-400 uppercase">Estado Lógico</p>
                <p class="font-bold text-gray-700">{{ $dispositivo->estado_logico }}</p>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1">
                <h3 class="font-black text-gray-400 uppercase text-[10px] tracking-widest mb-4 flex items-center">
                    <i class="fas fa-microchip mr-2 text-[#39A900]"></i> Especificaciones
                </h3>
                <div class="space-y-3">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400">Procesador</span>
                        <span class="font-bold text-gray-800 border-b pb-1">{{ $dispositivo->especificaciones->procesador ?? 'N/A' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400">Memoria RAM</span>
                        <span class="font-bold text-gray-800 border-b pb-1">{{ $dispositivo->especificaciones->ram ?? 'N/A' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400">Almacenamiento</span>
                        <span class="font-bold text-gray-800 border-b pb-1">{{ $dispositivo->especificaciones->capacidad_disco ?? 'N/A' }} {{ $dispositivo->especificaciones->tipo_disco ?? '' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400">Sistema Operativo</span>
                        <span class="font-bold text-blue-600">{{ $dispositivo->especificaciones->so ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                        <h3 class="font-black text-gray-400 uppercase text-[10px] mb-3">Responsable del Bien</h3>
                        <p class="font-black text-gray-800 text-lg leading-tight">{{ $dispositivo->responsable->nombre }}</p>
                        <p class="text-sm text-gray-500 mb-2 font-medium">{{ $dispositivo->responsable->cargo }}</p>
                        <div class="flex items-center text-xs text-gray-400 mt-2">
                            <i class="fas fa-id-card mr-1"></i> CC: {{ $dispositivo->responsable->cedula }}
                        </div>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                        <h3 class="font-black text-gray-400 uppercase text-[10px] mb-3">Ubicación Actual</h3>
                        <p class="font-black text-[#39A900] text-lg">{{ $dispositivo->ubicacion->sede }}</p>
                        <p class="text-sm text-gray-700 font-bold">Ambiente: {{ $dispositivo->ubicacion->ambiente }}</p>
                        <p class="text-xs text-gray-500">Bloque: {{ $dispositivo->ubicacion->bloque }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="font-black text-gray-400 uppercase text-[10px] tracking-widest mb-4 flex items-center">
                        <i class="fas fa-plug mr-2 text-[#39A900]"></i> Periféricos Asociados
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        @forelse($dispositivo->perifericos as $peri)
                            <div class="flex items-center p-3 bg-white border border-gray-100 rounded-xl shadow-sm">
                                <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-[#39A900] mr-3">
                                    @switch($peri->tipo)
                                        @case('Monitor') <i class="fas fa-tv"></i> @break
                                        @case('Teclado') <i class="fas fa-keyboard"></i> @break
                                        @case('Mouse') <i class="fas fa-mouse"></i> @break
                                        @case('Cargador') <i class="fas fa-bolt"></i> @break
                                        @default <i class="fas fa-box"></i>
                                    @endswitch
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase leading-none">{{ $peri->tipo }}</p>
                                    <p class="text-xs font-bold text-gray-700">{{ $peri->marca }} {{ $peri->modelo }}</p>
                                    <p class="text-[9px] text-gray-500 font-mono">PLACA: {{ $peri->placa }} | S/N: {{ $peri->serial }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 p-4 border border-dashed border-gray-200 rounded-xl text-center text-gray-400 text-xs">
                                No se registraron periféricos adicionales para este equipo.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @if($dispositivo->observaciones)
        <div class="px-8 pb-8">
            <div class="bg-yellow-50 p-5 rounded-2xl border border-yellow-100">
                <h4 class="text-yellow-800 font-black text-xs uppercase mb-2 flex items-center">
                    <i class="fas fa-sticky-note mr-2"></i> Notas y Novedades del Equipo
                </h4>
                <p class="text-yellow-700 text-sm italic leading-relaxed">{{ $dispositivo->observaciones }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection