@extends('layouts.app')

@section('content')
@php $role = Auth::user()->role; @endphp

<div class="max-w-5xl mx-auto space-y-8">

    {{-- Bienvenida --}}
    <div class="flex items-center gap-5">
        <div class="w-14 h-14 sena-bg rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
            <span class="text-white font-black text-2xl uppercase">{{ substr(Auth::user()->name, 0, 1) }}</span>
        </div>
        <div>
            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Bienvenido</p>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">{{ Auth::user()->name }}</h1>
            <span class="inline-flex items-center gap-1.5 mt-1 px-3 py-0.5 rounded-full text-[10px] font-black uppercase
                {{ $role === 'admin' ? 'bg-[#39A900]/10 text-[#39A900]' : 'bg-blue-50 text-blue-600' }}">
                <i class="fas {{ $role === 'admin' ? 'fa-shield-alt' : 'fa-wrench' }} text-[9px]"></i>
                {{ $role === 'admin' ? 'Administrador' : 'Técnico' }}
            </span>
        </div>
    </div>

    {{-- Módulos principales (todos los roles) --}}
    <div>
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Módulos del Sistema</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">

            {{-- Inventario TIC --}}
            <a href="{{ route('dispositivos.index') }}"
               class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-[#39A900] transition-all duration-200 p-5 flex items-center gap-4">
                <div class="w-14 h-14 bg-[#39A900]/10 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#39A900] transition-colors">
                    <i class="fas fa-laptop text-[#39A900] text-xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-gray-800 text-sm uppercase tracking-tight">Inventario TIC</p>
                    <p class="text-gray-400 text-[11px] font-bold mt-0.5">Gestión de equipos y dispositivos</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-2xl font-black text-gray-700">{{ $stats['dispositivos'] }}</p>
                    <p class="text-[10px] font-bold text-gray-400">equipos</p>
                </div>
            </a>

            {{-- Reportes --}}
            <a href="{{ route('reportes.index') }}"
               class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-400 transition-all duration-200 p-5 flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-500 transition-colors">
                    <i class="fas fa-chart-pie text-blue-500 text-xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-gray-800 text-sm uppercase tracking-tight">Reportes</p>
                    <p class="text-gray-400 text-[11px] font-bold mt-0.5">Estadísticas y análisis del inventario</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <i class="fas fa-arrow-right text-gray-200 text-lg group-hover:text-blue-400 transition-colors"></i>
                </div>
            </a>

            {{-- Energía Regulada --}}
            <a href="{{ route('equipos-energia.index') }}"
               class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-yellow-400 transition-all duration-200 p-5 flex items-center gap-4">
                <div class="w-14 h-14 bg-yellow-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-yellow-400 transition-colors">
                    <i class="fas fa-bolt text-yellow-500 text-xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-gray-800 text-sm uppercase tracking-tight">Energía Regulada</p>
                    <p class="text-gray-400 text-[11px] font-bold mt-0.5">UPS, reguladores y protección eléctrica</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-2xl font-black text-gray-700">{{ $stats['energia'] }}</p>
                    <p class="text-[10px] font-bold text-gray-400">equipos</p>
                </div>
            </a>

            {{-- ISO 27001 --}}
            <a href="{{ route('areas-seguras.index') }}"
               class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-purple-400 transition-all duration-200 p-5 flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-purple-500 transition-colors">
                    <i class="fas fa-shield-alt text-purple-500 text-xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-gray-800 text-sm uppercase tracking-tight">Áreas Seguras</p>
                    <p class="text-gray-400 text-[11px] font-bold mt-0.5">Control ISO 27001:2022</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-2xl font-black text-gray-700">{{ $stats['areas'] }}</p>
                    <p class="text-[10px] font-bold text-gray-400">áreas</p>
                </div>
            </a>

            {{-- Mantenimientos en proceso --}}
            <a href="{{ route('mantenimientos.index') }}"
               class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-orange-400 transition-all duration-200 p-5 flex items-center gap-4">
                <div class="w-14 h-14 bg-orange-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-orange-400 transition-colors">
                    <i class="fas fa-tools text-orange-400 text-xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-gray-800 text-sm uppercase tracking-tight">Mantenimientos</p>
                    <p class="text-gray-400 text-[11px] font-bold mt-0.5">Registro de mantenimientos preventivos y correctivos</p>
                </div>
                <div class="text-right flex-shrink-0">
                    @if($stats['mantenimientos'] > 0)
                        <span class="inline-block px-2.5 py-1 bg-orange-100 text-orange-600 text-[11px] font-black rounded-full">
                            {{ $stats['mantenimientos'] }} en proceso
                        </span>
                    @else
                        <i class="fas fa-check-circle text-green-300 text-lg"></i>
                    @endif
                </div>
            </a>

            {{-- SGSPI Público --}}
            <a href="{{ route('sgspi.index') }}" target="_blank"
               class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-teal-400 transition-all duration-200 p-5 flex items-center gap-4">
                <div class="w-14 h-14 bg-teal-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-teal-500 transition-colors">
                    <i class="fas fa-user-shield text-teal-500 text-xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-gray-800 text-sm uppercase tracking-tight">SGSPI — Sensibilización</p>
                    <p class="text-gray-400 text-[11px] font-bold mt-0.5">Juegos y actividades de seguridad de la información</p>
                </div>
                <div class="flex-shrink-0">
                    <span class="text-[9px] font-black bg-teal-50 text-teal-500 border border-teal-200 px-2 py-0.5 rounded-full uppercase tracking-widest">Público</span>
                </div>
            </a>

        </div>
    </div>

    {{-- Módulos exclusivos admin --}}
    @if($role === 'admin')
    <div>
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">
            <i class="fas fa-shield-alt text-[#39A900] mr-1"></i> Administración
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Usuarios --}}
            <a href="{{ route('usuarios.index') }}"
               class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-[#39A900] transition-all duration-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-[#39A900]/10 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#39A900] transition-colors">
                    <i class="fas fa-users-cog text-[#39A900] text-lg group-hover:text-white transition-colors"></i>
                </div>
                <div>
                    <p class="font-black text-gray-800 text-sm uppercase tracking-tight">Usuarios</p>
                    <p class="text-gray-400 text-[11px] font-bold mt-0.5">Gestión de accesos</p>
                </div>
            </a>

            {{-- SGSPI Resultados --}}
            <a href="{{ route('sgspi.admin.resultados') }}"
               class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-teal-400 transition-all duration-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-teal-500 transition-colors">
                    <i class="fas fa-poll text-teal-500 text-lg group-hover:text-white transition-colors"></i>
                </div>
                <div>
                    <p class="font-black text-gray-800 text-sm uppercase tracking-tight">SGSPI Resultados</p>
                    <p class="text-gray-400 text-[11px] font-bold mt-0.5">Calificaciones del juego</p>
                </div>
            </a>

            {{-- SGSPI Configuración --}}
            <a href="{{ route('sgspi.admin.config') }}"
               class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-teal-400 transition-all duration-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-teal-500 transition-colors">
                    <i class="fas fa-sliders-h text-teal-500 text-lg group-hover:text-white transition-colors"></i>
                </div>
                <div>
                    <p class="font-black text-gray-800 text-sm uppercase tracking-tight">SGSPI Configuración</p>
                    <p class="text-gray-400 text-[11px] font-bold mt-0.5">Celdas, preguntas y tablero</p>
                </div>
            </a>

            {{-- Exportar reportes --}}
            <a href="{{ route('reportes.exportar') }}"
               class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-green-400 transition-all duration-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-green-500 transition-colors">
                    <i class="fas fa-file-excel text-green-500 text-lg group-hover:text-white transition-colors"></i>
                </div>
                <div>
                    <p class="font-black text-gray-800 text-sm uppercase tracking-tight">Exportar Excel</p>
                    <p class="text-gray-400 text-[11px] font-bold mt-0.5">Inventario completo</p>
                </div>
            </a>

        </div>
    </div>
    @endif

</div>
@endsection
