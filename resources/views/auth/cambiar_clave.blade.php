@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">

        <div class="sena-bg p-8 text-center text-white">
            <div class="flex justify-center mb-3">
                <div class="bg-white/20 rounded-2xl p-3">
                    <i class="fas fa-key text-2xl"></i>
                </div>
            </div>
            <h1 class="text-2xl font-black uppercase tracking-tight">Cambiar Contraseña</h1>
            <p class="text-white/70 text-xs font-bold uppercase tracking-widest mt-1">{{ Auth::user()->name }}</p>
        </div>

        @if(session('success'))
            <div class="mx-8 mt-6 bg-green-50 border border-green-200 text-green-700 rounded-2xl px-5 py-4 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500"></i>
                <span class="text-xs font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('perfil.cambiar-clave') }}" method="POST" class="p-8 space-y-5">
            @csrf

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Contraseña Actual</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-4 text-gray-300"></i>
                    <input type="password" name="clave_actual"
                        class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition @error('clave_actual') ring-2 ring-red-400 @enderror"
                        placeholder="••••••••" required autofocus>
                </div>
                @error('clave_actual')
                    <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Nueva Contraseña</label>
                <div class="relative">
                    <i class="fas fa-shield-halved absolute left-4 top-4 text-gray-300"></i>
                    <input type="password" name="nueva_clave"
                        class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition @error('nueva_clave') ring-2 ring-red-400 @enderror"
                        placeholder="Mínimo 8 caracteres" required>
                </div>
                @error('nueva_clave')
                    <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Confirmar Nueva Contraseña</label>
                <div class="relative">
                    <i class="fas fa-shield-halved absolute left-4 top-4 text-gray-300"></i>
                    <input type="password" name="nueva_clave_confirmation"
                        class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition"
                        placeholder="Repite la nueva contraseña" required>
                </div>
            </div>

            <div class="pt-2 flex gap-3">
                <a href="{{ route('dispositivos.index') }}"
                    class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-600 font-black py-4 rounded-2xl transition text-xs uppercase tracking-widest">
                    Cancelar
                </a>
                <button type="submit"
                    class="flex-1 sena-bg text-white font-black py-4 rounded-2xl shadow-lg hover:scale-[1.02] transition-transform active:scale-95 uppercase tracking-widest text-xs">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
