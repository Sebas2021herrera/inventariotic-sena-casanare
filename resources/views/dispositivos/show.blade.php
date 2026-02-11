@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('dispositivos.index') }}" class="text-gray-500 hover:text-[#39A900] font-bold transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
        <div class="flex gap-2">
            <a href="{{ route('conceptos.create', $dispositivo) }}" class="bg-orange-500 text-white px-4 py-2 rounded-xl font-bold hover:bg-orange-600 transition flex items-center shadow-lg shadow-orange-100">
                <i class="fas fa-file-signature mr-2"></i> Nuevo GTI-F-132
            </a>
            
            <a href="{{ route('dispositivos.edit', $dispositivo) }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-edit mr-2"></i> Editar Equipo
            </a>
            <button onclick="window.print()" class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-xl font-bold hover:bg-gray-50 transition">
                <i class="fas fa-print mr-1"></i> Imprimir Ficha
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="{{ $dispositivo->categoria == 'conectividad' ? 'bg-blue-700' : 'sena-bg' }} p-8 text-white relative">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <span class="bg-white/20 text-white text-[10px] px-3 py-1 rounded-full font-black uppercase tracking-widest mb-2 inline-block">
                        Categoría: {{ $dispositivo->categoria == 'conectividad' ? 'Redes e Infraestructura' : 'Equipos de Cómputo' }}
                    </span>
                    <h1 class="text-4xl font-black italic tracking-tighter uppercase">{{ $dispositivo->marca }} {{ $dispositivo->modelo }}</h1>
                    <p class="text-white/80 font-mono text-lg mt-1">
                        PLACA: <span class="bg-white text-gray-800 px-2 rounded">{{ $dispositivo->placa }}</span> 
                        | S/N: {{ $dispositivo->serial }}
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex flex-col items-end">
                    <span class="bg-white {{ $dispositivo->categoria == 'conectividad' ? 'text-blue-700' : 'text-[#39A900]' }} px-6 py-2 rounded-2xl font-black shadow-lg text-xl uppercase">
                        {{ $dispositivo->estado_fisico }}
                    </span>
                    @if($dispositivo->en_intune == 'SI')
                        <span class="mt-2 text-[10px] bg-blue-400 text-white px-3 py-1 rounded-full font-bold flex items-center">
                            <i class="fas fa-check-circle mr-1"></i> REGISTRADO EN INTUNE
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 bg-gray-50 border-b border-gray-100">
            <div class="p-4 border-r border-gray-100 text-center">
                <p class="text-[9px] font-black text-gray-400 uppercase">Propietario</p>
                <p class="font-bold text-gray-700">{{ $dispositivo->propietario }}</p>
            </div>
            <div class="p-4 border-r border-gray-100 text-center">
                <p class="text-[9px] font-black text-gray-400 uppercase">Función</p>
                <p class="font-bold text-gray-700">{{ $dispositivo->funcion }}</p>
            </div>
            <div class="p-4 border-r border-gray-100 text-center">
                <p class="text-[9px] font-black text-gray-400 uppercase">Estado Lógico</p>
                <p class="font-bold text-gray-700">{{ $dispositivo->estado_logico }}</p>
            </div>
            <div class="p-4 text-center">
                <p class="text-[9px] font-black text-gray-400 uppercase">Sede</p>
                <p class="font-bold text-gray-700">{{ $dispositivo->ubicacion->sede }}</p>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1 space-y-6">
                @if($dispositivo->categoria == 'conectividad')
                    <h3 class="font-black text-blue-600 uppercase text-[10px] tracking-widest mb-4 flex items-center">
                        <i class="fas fa-network-wired mr-2"></i> Parámetros de Red
                    </h3>
                    <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 space-y-4">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-blue-400 uppercase">No. Puertos</span>
                            <span class="font-bold text-gray-800">{{ $dispositivo->puertos ?? 'N/A' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-blue-400 uppercase">MAC Address</span>
                            <span class="font-mono text-sm font-bold text-blue-600">{{ $dispositivo->mac_address ?? 'N/A' }}</span>
                        </div>
                    </div>
                @else
                    <h3 class="font-black text-[#39A900] uppercase text-[10px] tracking-widest mb-4 flex items-center">
                        <i class="fas fa-microchip mr-2"></i> Hardware Interno
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-1">
                            <span class="text-sm text-gray-400">Procesador</span>
                            <span class="text-sm font-bold text-gray-700">{{ $dispositivo->especificaciones->procesador ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <span class="text-sm text-gray-400">Memoria RAM</span>
                            <span class="text-sm font-bold text-gray-700">{{ $dispositivo->especificaciones->ram ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <span class="text-sm text-gray-400">Almacenamiento</span>
                            <span class="text-sm font-bold text-gray-700">{{ $dispositivo->especificaciones->capacidad_disco ?? 'N/A' }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="md:col-span-2 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-5 rounded-2xl border border-gray-100 bg-gray-50">
                        <p class="text-[9px] font-black text-gray-400 uppercase mb-2">Responsable Asignado</p>
                        <p class="font-black text-gray-800 text-lg leading-tight">{{ $dispositivo->responsable->nombre }}</p>
                        <p class="text-xs text-gray-500">{{ $dispositivo->responsable->cargo }}</p>
                    </div>
                    <div class="p-5 rounded-2xl border border-gray-100 bg-gray-50">
                        <p class="text-[9px] font-black text-gray-400 uppercase mb-2">Ubicación Detallada</p>
                        <p class="font-black text-gray-800 text-lg leading-tight">Ambiente {{ $dispositivo->ubicacion->ambiente }}</p>
                        <p class="text-xs text-gray-500">Bloque: {{ $dispositivo->ubicacion->bloque }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="font-black text-gray-400 uppercase text-[10px] tracking-widest mb-4 flex items-center">
                        <i class="fas fa-plug mr-2 text-[#39A900]"></i> Accesorios Asociados
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @forelse($dispositivo->perifericos as $peri)
                            <div class="flex items-center p-3 bg-white border border-gray-100 rounded-xl shadow-sm">
                                <div class="w-10 h-10 rounded-lg bg-green-50 text-[#39A900] flex items-center justify-center mr-3">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[9px] font-black text-gray-400 uppercase truncate">{{ $peri->tipo }}</p>
                                    <p class="text-xs font-bold text-gray-700 truncate">{{ $peri->placa }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">No se han registrado accesorios.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @if($dispositivo->descripcion_tecnica || $dispositivo->observaciones)
        <div class="px-8 pb-8">
            <div class="bg-gray-900 text-gray-300 p-6 rounded-2xl font-mono text-xs leading-relaxed shadow-inner">
                <div class="flex items-center text-white font-bold mb-3 border-b border-gray-700 pb-2 uppercase tracking-tighter">
                    <i class="fas fa-info-circle mr-2 text-yellow-400"></i> Notas Técnicas y Observaciones
                </div>
                @if($dispositivo->descripcion_tecnica)
                    <p class="mb-3 text-white">DESC: {{ $dispositivo->descripcion_tecnica }}</p>
                @endif
                <p class="italic">OBS: {{ $dispositivo->observaciones ?? 'Sin novedades registradas.' }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="mt-8 bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-orange-50/30">
            <div>
                <h3 class="font-black text-gray-800 uppercase text-sm tracking-tighter flex items-center">
                    <i class="fas fa-file-contract mr-2 text-orange-500"></i> 
                    Historial de Conceptos Técnicos GTI-F-132
                </h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase">Bajas, Garantías y Reasignaciones V.05</p>
            </div>
            <a href="{{ route('conceptos.create', $dispositivo) }}" 
               class="bg-orange-500 text-white px-4 py-2 rounded-xl text-xs font-black hover:bg-orange-600 transition shadow-lg flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> CREAR REPORTE
            </a>
        </div>

        <div class="p-0">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100/50 text-[9px] font-black text-gray-400 uppercase">
                        <th class="p-4">Fecha</th>
                        <th class="p-4">Trámite</th>
                        <th class="p-4">INC / WO</th>
                        <th class="p-4">Técnico N2</th>
                        <th class="p-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse(($dispositivo->conceptos ?? collect())->sortByDesc('fecha_reporte') as $concepto)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-bold text-gray-700 italic">{{ $concepto->fecha_reporte->format('d/m/Y') }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-full text-[10px] font-black uppercase bg-orange-100 text-orange-600">
                                {{ $concepto->concepto_tipo }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-500 font-mono text-xs">
                            {{ $concepto->num_incidente ?? 'N/A' }} / {{ $concepto->num_requerimiento ?? 'N/A' }}
                        </td>
                        <td class="p-4 text-gray-600 font-medium">{{ $concepto->tecnico_nombre }}</td>
                        <td class="p-4 text-right">
                            <a href="{{ route('conceptos.pdf', $concepto->id) }}" class="text-red-500 hover:text-red-700 font-bold uppercase text-[10px] tracking-widest">
                                <i class="fas fa-file-pdf mr-1"></i> Descargar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-400 italic text-xs">
                            No se han generado reportes técnicos para este equipo.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="font-black text-gray-800 uppercase text-sm tracking-tighter flex items-center">
                    <i class="fas fa-tools mr-2 text-[#39A900]"></i> 
                    Hoja de Vida: Historial de Mantenimientos
                </h3>
            </div>
            <a href="{{ route('mantenimientos.create', ['dispositivo_id' => $dispositivo->id]) }}" 
               class="bg-[#39A900] text-white px-4 py-2 rounded-xl text-xs font-black hover:bg-[#2d8500] transition shadow-lg">
                <i class="fas fa-plus-circle mr-2"></i> REGISTRAR MANTENIMIENTO
            </a>
        </div>

        <div class="p-0">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100/50">
                        <th class="p-4 text-[9px] font-black text-gray-400 uppercase">Fecha</th>
                        <th class="p-4 text-[9px] font-black text-gray-400 uppercase">Tipo</th>
                        <th class="p-4 text-[9px] font-black text-gray-400 uppercase">Técnico</th>
                        <th class="p-4 text-[9px] font-black text-gray-400 uppercase text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($dispositivo->mantenimientos->sortByDesc('fecha') as $mtto)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-bold text-gray-700 italic">{{ \Carbon\Carbon::parse($mtto->fecha)->format('d/m/Y') }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-full text-[10px] font-black uppercase {{ $mtto->tipo == 'Correctivo' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-[#39A900]' }}">
                                {{ $mtto->tipo }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-600 font-medium">{{ $mtto->tecnico_encargado }}</td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('mantenimientos.edit', $mtto) }}" class="text-blue-500"><i class="fas fa-edit"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-400 italic text-xs">Sin registros de mantenimiento.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection