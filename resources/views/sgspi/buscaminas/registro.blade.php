@extends('layouts.public')
@section('title', 'Registro — Buscaminas SGSPI')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">

            {{-- Header --}}
            <div class="sena-bg p-8 text-center text-white">
                <div class="flex justify-center mb-3">
                    <div class="bg-white/20 rounded-2xl p-4">
                        <i class="fas fa-bomb text-3xl"></i>
                    </div>
                </div>
                <h1 class="text-2xl font-black uppercase tracking-tight">Buscaminas SGSPI</h1>
                <p class="text-white/70 text-xs font-bold uppercase tracking-widest mt-1">Ingresa tus datos para comenzar</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('sgspi.registrar') }}" method="POST" class="p-8 space-y-5">
                @csrf

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Nombre Completo *</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-4 text-gray-300"></i>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition @error('nombre') ring-2 ring-red-400 @enderror"
                            placeholder="Tu nombre y apellido" required autofocus>
                    </div>
                    @error('nombre')
                        <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Número de Documento *</label>
                    <div class="relative">
                        <i class="fas fa-id-card absolute left-4 top-4 text-gray-300"></i>
                        <input type="text" name="documento" value="{{ old('documento') }}"
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition @error('documento') ring-2 ring-red-400 @enderror"
                            placeholder="Cédula o identificación" required>
                    </div>
                    @error('documento')
                        <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Área / Dependencia *</label>
                    <div class="relative">
                        <i class="fas fa-building absolute left-4 top-4 text-gray-300"></i>
                        <input type="text" name="area" value="{{ old('area') }}"
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition @error('area') ring-2 ring-red-400 @enderror"
                            placeholder="Ej: Coordinación Académica" required>
                    </div>
                    @error('area')
                        <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Reglas del juego --}}
                <div class="bg-gray-50 rounded-2xl p-4 text-[11px] text-gray-500 space-y-1.5">
                    <p class="font-black text-gray-700 text-[10px] uppercase tracking-widest mb-2">¿Cómo se juega?</p>
                    <p><i class="fas fa-hand-pointer text-[#39A900] mr-1.5"></i>Haz clic en cualquier celda del tablero para revelarla.</p>
                    <p><i class="fas fa-question-circle text-blue-400 mr-1.5"></i>Cada celda puede contener una pregunta de seguridad.</p>
                    <p><i class="fas fa-check-circle text-green-500 mr-1.5"></i>Respuesta correcta: <strong>+10 puntos</strong>.</p>
                    <p><i class="fas fa-shield-alt text-purple-400 mr-1.5"></i>Zona segura: celda sin pregunta.</p>
                </div>

                <button type="submit"
                    class="w-full sena-bg text-white font-black py-4 rounded-2xl shadow-lg hover:scale-[1.02] transition-transform active:scale-95 uppercase tracking-widest text-sm">
                    <i class="fas fa-play mr-2"></i> ¡Comenzar Juego!
                </button>
            </form>
        </div>

        <p class="text-center text-[11px] text-gray-400 font-bold mt-4">
            <a href="{{ route('sgspi.index') }}" class="hover:text-[#39A900] transition">
                <i class="fas fa-arrow-left mr-1"></i> Volver al inicio
            </a>
        </p>
    </div>
</div>
@endsection
