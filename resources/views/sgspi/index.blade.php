@extends('layouts.public')
@section('title', 'SGSPI — Actividades de Sensibilización')

@section('content')
<div class="max-w-3xl mx-auto space-y-10">

    {{-- Acceso rápido QR --}}
    <div class="text-center pt-4">
        <a href="{{ route('sgspi.instrucciones') }}"
           class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-600 font-black px-4 py-2 rounded-xl text-xs uppercase tracking-widest shadow-sm hover:border-[#39A900] hover:text-[#39A900] transition">
            <i class="fas fa-qrcode text-[#39A900]"></i> Instrucciones e impresión del QR
        </a>
    </div>

    {{-- Hero --}}
    <div class="text-center py-6">
        <div class="inline-flex items-center justify-center w-20 h-20 sena-bg rounded-3xl shadow-xl mb-5">
            <i class="fas fa-shield-alt text-white text-4xl"></i>
        </div>
        <h1 class="text-4xl font-black text-gray-800 uppercase italic tracking-tighter">
            Módulo de <span class="sena-text">Sensibilización</span>
        </h1>
        <p class="text-gray-500 font-bold mt-2 text-sm">SGSPI — Sistema de Gestión de Seguridad de la Información</p>
        <p class="text-gray-400 text-xs mt-1 max-w-lg mx-auto">Aprende sobre seguridad de la información a través de actividades interactivas.</p>
    </div>

    {{-- Módulo Juegos --}}
    <div>
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 sena-bg rounded-xl flex items-center justify-center">
                <i class="fas fa-gamepad text-white text-sm"></i>
            </div>
            <h2 class="text-lg font-black text-gray-700 uppercase tracking-tight">Juegos Interactivos</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-5">

            {{-- Buscaminas --}}
            <a href="{{ route('sgspi.buscaminas') }}"
               class="group bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex gap-5 items-center hover:shadow-lg hover:border-[#39A900] transition-all duration-200">
                <div class="w-16 h-16 bg-[#39A900]/10 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#39A900] transition-colors">
                    <i class="fas fa-bomb text-[#39A900] text-2xl group-hover:text-white transition-colors"></i>
                </div>
                <div>
                    <p class="font-black text-gray-800 text-base uppercase tracking-tight">Buscaminas SGSPI</p>
                    <p class="text-gray-400 text-xs font-bold mt-1">Descubre celdas y responde preguntas sobre seguridad de la información.</p>
                    <div class="flex items-center gap-2 mt-3">
                        <span class="text-[10px] font-black bg-green-50 text-[#39A900] px-2 py-0.5 rounded-full uppercase">25 celdas</span>
                        <span class="text-[10px] font-black bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full uppercase">20 preguntas</span>
                        <span class="text-[10px] font-black bg-yellow-50 text-yellow-600 px-2 py-0.5 rounded-full uppercase">200 pts máx.</span>
                    </div>
                </div>
            </a>

            {{-- Próximamente --}}
            <div class="bg-gray-50 rounded-3xl border border-dashed border-gray-200 p-6 flex gap-5 items-center opacity-60">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-puzzle-piece text-gray-400 text-2xl"></i>
                </div>
                <div>
                    <p class="font-black text-gray-500 text-base uppercase tracking-tight">Próximamente</p>
                    <p class="text-gray-400 text-xs font-bold mt-1">Nuevas actividades de sensibilización en camino.</p>
                </div>
            </div>

        </div>
    </div>

    {{-- Temas cubiertos --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Temas cubiertos</p>
        <div class="flex flex-wrap gap-2">
            @foreach(['🔐 Contraseñas','📧 Phishing','🦠 Malware','☁️ Copias de Seguridad','🔑 Privacidad','💾 USB y Dispositivos','✉️ Correo Electrónico'] as $t)
                <span class="px-3 py-1.5 bg-gray-50 border border-gray-200 text-gray-600 text-[11px] font-bold rounded-xl">{{ $t }}</span>
            @endforeach
        </div>
    </div>

</div>
@endsection
