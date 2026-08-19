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
            <a href="{{ route('dispositivos.hoja-vida-pdf', $dispositivo) }}" class="bg-[#39A900] text-white px-4 py-2 rounded-xl font-bold hover:bg-[#2d8500] transition flex items-center shadow-lg shadow-green-100">
                <i class="fas fa-file-pdf mr-2"></i> Hoja de Vida PDF
            </a>
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
                        @if($dispositivo->hostname)
                            | <i class="fas fa-desktop text-sm opacity-70"></i> {{ $dispositivo->hostname }}
                        @endif
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex flex-col items-end">
                    <span class="bg-white {{ $dispositivo->categoria == 'conectividad' ? 'text-blue-700' : 'text-[#39A900]' }} px-6 py-2 rounded-2xl font-black shadow-lg text-xl uppercase">
                        {{ $dispositivo->estado_fisico }}
                    </span>
                    @if($intuneCuenta && $intuneCuenta->estado === 'activa')
                        <span class="mt-2 text-[10px] bg-blue-600 text-white px-3 py-1 rounded-full font-bold flex items-center gap-1">
                            <i class="fab fa-microsoft text-[9px]"></i> INTUNE ACTIVO
                        </span>
                    @elseif($intuneCuenta && $intuneCuenta->estado === 'pendiente')
                        <span class="mt-2 text-[10px] bg-orange-500 text-white px-3 py-1 rounded-full font-bold flex items-center gap-1">
                            <i class="fab fa-microsoft text-[9px]"></i> INTUNE PENDIENTE
                        </span>
                    @elseif($dispositivo->en_intune == 'SI')
                        <span class="mt-2 text-[10px] bg-blue-400 text-white px-3 py-1 rounded-full font-bold flex items-center gap-1">
                            <i class="fas fa-check-circle text-[9px]"></i> REGISTRADO EN INTUNE
                        </span>
                    @elseif($dispositivo->categoria == 'computo')
                        <span class="mt-2 text-[10px] bg-orange-100 text-orange-600 px-3 py-1 rounded-full font-bold flex items-center gap-1">
                            <i class="fas fa-exclamation-triangle text-[9px]"></i> SIN CUENTA INTUNE
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
                <p class="font-bold text-gray-700">{{ $dispositivo->ubicacion->sede->nombre }}</p>
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

                {{-- Cuenta Intune --}}
                @if($dispositivo->categoria == 'computo')
                <div>
                    <h3 class="font-black text-gray-400 uppercase text-[10px] tracking-widest mb-3 flex items-center">
                        <i class="fab fa-microsoft mr-2 text-blue-600"></i> Cuenta Intune
                    </h3>

                    @if($dispositivo->funcion === 'ADMINISTRATIVO')
                        {{-- Equipo administrativo: Intune por correo de usuario --}}
                        @if($intuneCuenta)
                            <div class="p-4 rounded-2xl border border-blue-100 bg-blue-50 space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-0.5">Usuario asignado</p>
                                        <p class="font-mono text-xs font-bold text-blue-800 break-all">{{ $intuneCuenta->cuenta }}</p>
                                    </div>
                                    @if($intuneCuenta->estado === 'activa')
                                        <span class="flex-shrink-0 px-2.5 py-1 bg-green-100 text-green-700 text-[9px] font-black rounded-full uppercase">Activa</span>
                                    @else
                                        <span class="flex-shrink-0 px-2.5 py-1 bg-orange-100 text-orange-600 text-[9px] font-black rounded-full uppercase">Pendiente</span>
                                    @endif
                                </div>
                                @if($intuneCuenta->notas)
                                    <p class="text-[10px] text-blue-600 font-bold italic">{{ $intuneCuenta->notas }}</p>
                                @endif
                                {{-- Formulario para actualizar --}}
                                <details class="mt-2">
                                    <summary class="text-[10px] font-black text-blue-500 cursor-pointer hover:text-blue-700 transition">Actualizar correo</summary>
                                    <form method="POST" action="{{ route('intune.administrativo') }}" class="mt-2 space-y-2">
                                        @csrf
                                        <input type="hidden" name="placa" value="{{ $dispositivo->placa }}">
                                        <input type="email" name="correo_usuario" value="{{ $intuneCuenta->cuenta }}"
                                               placeholder="correo@sena.edu.co"
                                               class="w-full border border-blue-200 rounded-lg px-3 py-2 text-xs font-bold focus:outline-none focus:border-blue-400">
                                        <input type="text" name="notas" value="{{ $intuneCuenta->notas }}"
                                               placeholder="Notas opcionales..."
                                               class="w-full border border-blue-200 rounded-lg px-3 py-2 text-xs font-bold focus:outline-none focus:border-blue-400">
                                        <button type="submit"
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-2 rounded-lg text-[10px] uppercase tracking-widest transition">
                                            Actualizar
                                        </button>
                                    </form>
                                </details>
                            </div>
                        @else
                            <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50 space-y-3">
                                <div class="flex items-center gap-2">
                                    <i class="fab fa-microsoft text-gray-400"></i>
                                    <p class="text-xs font-black text-gray-600">Registrar usuario de Intune</p>
                                </div>
                                <p class="text-[10px] text-gray-400 font-bold">Este equipo administrativo se sincroniza con el correo institucional del usuario asignado.</p>
                                <form method="POST" action="{{ route('intune.administrativo') }}" class="space-y-2">
                                    @csrf
                                    <input type="hidden" name="placa" value="{{ $dispositivo->placa }}">
                                    <input type="email" name="correo_usuario" value="{{ old('correo_usuario') }}"
                                           placeholder="correo@sena.edu.co" required
                                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs font-bold focus:outline-none focus:border-blue-400">
                                    <input type="text" name="notas" value="{{ old('notas') }}"
                                           placeholder="Notas opcionales..."
                                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs font-bold focus:outline-none focus:border-blue-400">
                                    <button type="submit"
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-2 rounded-lg text-[10px] uppercase tracking-widest transition">
                                        <i class="fas fa-save mr-1"></i> Registrar
                                    </button>
                                </form>
                            </div>
                        @endif

                    @else
                        {{-- Equipo de formación: cuenta por dispositivo desde DG --}}
                        @if($intuneCuenta)
                            <div class="p-4 rounded-2xl border border-blue-100 bg-blue-50 space-y-2">
                                <p class="font-mono text-xs font-bold text-blue-800 break-all">{{ $intuneCuenta->cuenta }}</p>
                                <div class="flex flex-wrap gap-3 text-[10px]">
                                    <span class="font-bold text-gray-500">Sede: <span class="text-gray-700">{{ $intuneCuenta->id_sede }}</span></span>
                                    <span class="font-bold text-gray-500">Estado:
                                        @if($intuneCuenta->estado === 'activa')
                                            <span class="text-green-600 font-black">Activa</span>
                                        @else
                                            <span class="text-orange-500 font-black">Pendiente por asignar</span>
                                        @endif
                                    </span>
                                    @if($intuneCuenta->fecha_reporte)
                                    <span class="font-bold text-gray-500">Reporte: <span class="text-gray-700">{{ $intuneCuenta->fecha_reporte->format('d/m/Y') }}</span></span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="p-4 rounded-2xl border border-orange-100 bg-orange-50 flex items-center gap-3">
                                <i class="fas fa-exclamation-triangle text-orange-400"></i>
                                <div>
                                    <p class="text-xs font-black text-orange-700">Sin cuenta Intune registrada</p>
                                    <p class="text-[10px] text-orange-500 font-bold mt-0.5">Verificar con el administrador si debe gestionarse la licencia en Dirección General.</p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                @endif

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

@if($dispositivo->descripcion_tecnica || $dispositivo->observaciones || $dispositivo->creador || $dispositivo->editor)
<div class="px-8 pb-8">
    <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm">
        
        <div class="flex flex-wrap items-center gap-3 mb-5 pb-4 border-b border-gray-100">
            <div class="flex items-center text-[10px] font-black uppercase tracking-wider text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">
                <i class="fas fa-user-plus mr-2 text-blue-400"></i>
                Registrado por: 
                <span class="ml-1 text-blue-900">{{ $dispositivo->creador->name ?? 'Sistema/Masivo' }}</span>
            </div>

            @if($dispositivo->editor)
            <div class="flex items-center text-[10px] font-black uppercase tracking-wider text-green-700 bg-green-50 px-3 py-1.5 rounded-lg border border-green-100">
                <i class="fas fa-user-edit mr-2 text-green-400"></i>
                Modificado por: 
                <span class="ml-1 text-green-900">{{ $dispositivo->editor->name }}</span>
            </div>
            @endif
        </div>

        <div class="flex items-center text-gray-800 font-black mb-3 uppercase tracking-tighter text-sm">
            <i class="fas fa-comment-dots mr-2 text-yellow-500"></i> Notas Técnicas y Observaciones
        </div>

        <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300 font-mono text-[11px] leading-relaxed text-gray-700">
            @if($dispositivo->descripcion_tecnica)
                <div class="mb-3">
                    <span class="text-gray-900 font-bold underline decoration-yellow-400 decoration-2">DESC:</span> 
                    {{ $dispositivo->descripcion_tecnica }}
                </div>
            @endif
            
            <div>
                <span class="text-gray-900 font-bold">OBS:</span> 
                <span class="italic text-gray-600">
                    {{ $dispositivo->observaciones ?? 'Sin novedades registradas.' }}
                </span>
            </div>
        </div>
    </div>
</div>
@endif
    </div>

    {{-- SOFTWARE INSTALADO --}}
    @if($dispositivo->categoria == 'computo')
    <div class="mt-8 bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-indigo-50/30">
            <div>
                <h3 class="font-black text-gray-800 uppercase text-sm tracking-tighter flex items-center">
                    <i class="fas fa-boxes mr-2 text-indigo-500"></i>
                    Software Instalado
                </h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase">Catálogo Autorizado SENA — Dirección General</p>
            </div>
            <button onclick="abrirModalSw()"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-black hover:bg-indigo-700 transition shadow-lg flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> AGREGAR SOFTWARE
            </button>
        </div>

        <div class="p-0">
            @if($dispositivo->softwareInstalado->isEmpty())
                <div class="p-10 text-center text-gray-400 italic text-xs">
                    <i class="fas fa-box-open text-3xl mb-3 block opacity-20"></i>
                    No se ha registrado software instalado en este equipo.
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100/50 text-[9px] font-black text-gray-400 uppercase">
                            <th class="p-4">Software</th>
                            <th class="p-4">Tipo</th>
                            <th class="p-4">Instalación</th>
                            <th class="p-4">Versión / Notas</th>
                            <th class="p-4">Registrado por</th>
                            <th class="p-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($dispositivo->softwareInstalado->sortBy('software.nombre') as $inst)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4">
                                <p class="font-bold text-gray-800 text-xs">{{ $inst->software->nombre }}</p>
                                @if($inst->software->subproducto)
                                    <p class="text-[10px] text-gray-500">{{ $inst->software->subproducto }}</p>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($inst->software->tipo === 'licenciado')
                                    <span class="px-2 py-1 rounded-full text-[9px] font-black uppercase bg-blue-100 text-blue-700">Licenciado</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-[9px] font-black uppercase bg-green-100 text-green-700">Libre</span>
                                @endif
                            </td>
                            <td class="p-4 font-bold text-gray-700 italic text-xs whitespace-nowrap">
                                {{ $inst->fecha_instalacion->format('d/m/Y') }}
                            </td>
                            <td class="p-4 text-[10px] text-gray-500 max-w-xs">{{ $inst->version_notas ?? '—' }}</td>
                            <td class="p-4 text-[10px] text-gray-600 whitespace-nowrap">{{ $inst->tecnico->name ?? 'Sistema' }}</td>
                            <td class="p-4 text-right">
                                <form method="POST"
                                      action="{{ route('dispositivos.software.destroy', [$dispositivo, $inst]) }}"
                                      onsubmit="return confirm('¿Eliminar este software del registro?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-red-400 hover:text-red-600 transition p-1" title="Eliminar">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- MODAL AGREGAR SOFTWARE (multi-select) --}}
    <div id="modal-sw" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px)">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="bg-indigo-600 text-white px-6 py-4 flex items-center justify-between">
                <p class="font-black text-sm uppercase tracking-tight">
                    <i class="fas fa-boxes mr-2"></i> Registrar Software Instalado
                </p>
                <button onclick="cerrarModalSw()" class="text-white/70 hover:text-white transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="form-sw" method="POST" action="{{ route('dispositivos.software.store', $dispositivo) }}" class="p-6 space-y-4">
                @csrf
                @include('software._picker', ['prefix' => 'modal-sw'])
                <div class="flex gap-3 pt-2">
                    <button type="submit" id="modal-sw-submit" disabled
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed text-white font-black py-2.5 rounded-xl text-sm uppercase tracking-widest transition">
                        <i class="fas fa-save mr-1"></i> Registrar
                    </button>
                    <button type="button" onclick="cerrarModalSw()"
                            class="px-5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-black rounded-xl text-sm uppercase tracking-widest transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('software._picker_js')

    <script>
    function abrirModalSw() {
        const m = document.getElementById('modal-sw');
        m.classList.remove('hidden');
        m.classList.add('flex');
        swPickerInit('modal-sw');
        setTimeout(function() {
            var s = document.getElementById('modal-sw-search');
            if (s) s.focus();
        }, 50);
    }

    function cerrarModalSw() {
        const m = document.getElementById('modal-sw');
        m.classList.add('hidden');
        m.classList.remove('flex');
        swResetPicker('modal-sw');
    }

    document.getElementById('modal-sw').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalSw();
    });

    document.getElementById('form-sw').addEventListener('submit', function(e) {
        var p = _swPickers['modal-sw'];
        if (!p || !p.items.length) { e.preventDefault(); return; }
        swInjectInputs('modal-sw', this);
    });
    </script>
    @endif

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
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('conceptos.edit', $concepto->id) }}" class="text-blue-500 hover:text-blue-700 font-bold uppercase text-[10px] tracking-widest">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>
                                <a href="{{ route('conceptos.pdf', $concepto->id) }}" class="text-red-500 hover:text-red-700 font-bold uppercase text-[10px] tracking-widest">
                                    <i class="fas fa-file-pdf mr-1"></i> PDF
                                </a>
                            </div>
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
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('mantenimientos.edit', $mtto) }}" class="text-blue-500 hover:text-blue-700 font-bold uppercase text-[10px] tracking-widest">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>
                                <a href="{{ route('mantenimientos.pdf', $mtto) }}" class="text-red-500 hover:text-red-700 font-bold uppercase text-[10px] tracking-widest">
                                    <i class="fas fa-file-pdf mr-1"></i> Imprimir
                                </a>
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