@extends('layouts.public')
@section('title', 'Detector de Phishing — SGSPI')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-md">

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">

            {{-- Header --}}
            <div class="bg-gradient-to-br from-[#1e3a5f] to-[#0f2236] p-8 text-center text-white">
                <div class="flex justify-center mb-4">
                    <div class="bg-white/15 rounded-2xl p-4 relative">
                        <i class="fas fa-fish text-3xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full">!</span>
                    </div>
                </div>
                <h1 class="text-2xl font-black uppercase tracking-tight">Detector de Phishing</h1>
                <p class="text-white/60 text-xs font-bold uppercase tracking-widest mt-1">4 Niveles · 20 Escenarios</p>
                <div class="flex justify-center gap-3 mt-4">
                    <span class="bg-green-500/20 text-green-300 text-[10px] font-black px-3 py-1 rounded-full uppercase">+10 pts acierto</span>
                    <span class="bg-yellow-500/20 text-yellow-300 text-[10px] font-black px-3 py-1 rounded-full uppercase">+5 pts bonus</span>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('sgspi.phishing.registrar') }}" method="POST" class="p-8 space-y-5">

                @csrf

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl p-3 text-xs font-bold">
                        {{ session('error') }}
                    </div>
                @endif

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Nombre Completo *</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-4 text-gray-300"></i>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#1e3a5f] transition @error('nombre') ring-2 ring-red-400 @enderror"
                            placeholder="Tu nombre y apellido" required autofocus>
                    </div>
                    @error('nombre')<p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Número de Documento *</label>
                    <div class="relative">
                        <i class="fas fa-id-card absolute left-4 top-4 text-gray-300"></i>
                        <input type="text" name="documento" value="{{ old('documento') }}"
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#1e3a5f] transition @error('documento') ring-2 ring-red-400 @enderror"
                            placeholder="Cédula o identificación" required>
                    </div>
                    @error('documento')<p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Área / Dependencia *</label>
                    <div class="relative">
                        <i class="fas fa-building absolute left-4 top-4 text-gray-300"></i>
                        <input type="text" name="area" value="{{ old('area') }}"
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#1e3a5f] transition @error('area') ring-2 ring-red-400 @enderror"
                            placeholder="Ej: Coordinación Académica" required>
                    </div>
                    @error('area')<p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>@enderror
                </div>

                {{-- Niveles --}}
                <div class="bg-gray-50 rounded-2xl p-4 space-y-2.5">
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Niveles del juego</p>
                    @foreach([
                        ['1','Fácil','green','Errores obvios y dominios falsos claros'],
                        ['2','Medio','blue','Señales sutiles y urgencia artificial'],
                        ['3','Difícil','orange','Dominios casi idénticos y contexto engañoso'],
                        ['4','Avanzado','red','Spear phishing, BEC y adjuntos maliciosos'],
                    ] as [$n,$lbl,$c,$desc])
                    <div class="flex items-start gap-3">
                        <span class="text-[10px] font-black bg-{{ $c }}-100 text-{{ $c }}-700 px-2 py-0.5 rounded-full mt-0.5 shrink-0">N{{ $n }}</span>
                        <div>
                            <span class="text-xs font-black text-gray-700">{{ $lbl }}</span>
                            <span class="text-[11px] text-gray-400 ml-1">— {{ $desc }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-[#1e3a5f] to-[#0f2236] text-white font-black py-4 rounded-2xl shadow-lg hover:scale-[1.02] transition-transform active:scale-95 uppercase tracking-widest text-sm">
                    <i class="fas fa-shield-alt mr-2"></i> ¡Comenzar Detección!
                </button>
            </form>
        </div>

        <p class="text-center text-[11px] text-gray-400 font-bold mt-4">
            <a href="{{ route('sgspi.index') }}" class="hover:text-[#39A900] transition">
                <i class="fas fa-arrow-left mr-1"></i> Volver al módulo SGSPI
            </a>
        </p>
    </div>
</div>
@endsection
